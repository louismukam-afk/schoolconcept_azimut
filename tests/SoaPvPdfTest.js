'use strict';
const assert = require('assert');
const fs = require('fs');
const path = require('path');
fs.mkdirSync(path.join(__dirname, '../.codex_tmp'), {recursive: true});
const buildDocument = require('../public/js/soa_pv_pdf.js');
assert.strictEqual(buildDocument.cleanText('ContrÃ´le De Gestion'), 'Contrôle De Gestion');
assert.strictEqual(buildDocument.cleanText('ComptabilitÃƒÂ©'), 'Comptabilité');
assert.strictEqual(buildDocument.cleanText('Économie'), 'Économie');
const pdfMake = require('../public/js/datatables/pdfmake-0.1.36/pdfmake.min.js');
pdfMake.vfs = require('../public/js/datatables/pdfmake-0.1.36/vfs_fonts.js').pdfMake.vfs;
const subjects = ['Comptabilité', 'Économie', 'Gestion', 'Statistiques', 'Droit'];
const row = name => ['1', 'EXAMPLE', name].concat(subjects.flatMap(() => ['20', '45', '65'])).concat(['13.00', '2.60', '30', 'Admis', '1']);
const page = {subjects, rows: [row('ALPHA'), row('BETA')], validation: subjects.flatMap(() => ['100 %', '100 %', '100 %']), title: 'Exemple — semestre 1'};
const logo = 'data:image/png;base64,' + fs.readFileSync(path.join(__dirname, '../public/img/logo.png')).toString('base64');
const universityLogoPath = path.join(__dirname, '../public/img/soa.jpeg');
const universityLogo = fs.existsSync(universityLogoPath) ? 'data:image/jpeg;base64,' + fs.readFileSync(universityLogoPath).toString('base64') : logo;
const header = {
    left: {name: 'INSTITUT SUPERIEUR AZIMUT DE COMMERCE, DE TECHNOLOGIE ET DE SANTE', logo},
    right: {name: 'UNIVERSITE DE YAOUNDE 2-SOA', logo: universityLogo},
    center: 'Arrêté de création n° 14/0027/MINESUP du 11 Mars 2014\nAutorisation d’ouverture n° 14/01316/MINESUP du 11 Mars 2014',
    title: 'RESULTATS DU SEMESTRE 1 NORMALE — 2025-2026',
    meta: ['CYCLE : LICENCE PROFESSIONNELLE', 'NIVEAU : III', 'SPECIALITE : GESTION', 'TUTELLE : SOA'],
    description: 'Résultats des examens du semestre\nSemester exam results',
    context: ['Spécialité : GESTION — Niveau III', 'Filière : GESTION / Path : MANAGEMENT'],
    footer: {statistics: ['Classement par moyenne générale décroissante, ordre alphabétique conservé.', 'Admis : 75 %', 'Échec : 25 %'],
        signature: 'Responsable des Affaires Academiques', lines: []}
};
(async function () {
    for (const format of ['A4', 'A3']) {
        const doc = buildDocument([page, page], 'Procès-verbal de Soa — 2025-2026', format);
        assert.strictEqual(doc.pageSize, format);
        assert.strictEqual(doc.pageOrientation, 'landscape');
        assert.strictEqual(doc.content[2].table.body[2][2].text, 'ALPHA');
        assert.strictEqual(doc.content[2].table.body[3][2].text, 'BETA');
        assert.strictEqual(doc.content[2].table.body[2][3].noWrap, true);
        assert.strictEqual(doc.content[2].table.headerRows, 2);
        const buffer = await new Promise(resolve => pdfMake.createPdf(doc).getBuffer(resolve));
        assert.strictEqual(buffer.slice(0, 4).toString(), '%PDF');
        const pdf = buffer.toString('latin1');
        const dimensions = format === 'A4' ? '841.89 595.28' : '1190.55 841.89';
        assert(pdf.includes('/MediaBox [0 0 ' + dimensions + ']'), 'Landscape dimensions: ' + format);
        assert.strictEqual(buildDocument.countPages(buffer), 1, 'Merged table must fit on a single page');
        fs.writeFileSync(path.join(__dirname, '../.codex_tmp/soa_pdf_' + format + '_test.pdf'), buffer);
        console.log(format + ': PDF generated, landscape dimensions, grouped headers and alphabetical row order verified.');
    }
    const fixturePath = process.argv[2];
    const fixture = fixturePath ? JSON.parse(fs.readFileSync(fixturePath, 'utf8')) : {
        ...page, rows: Array.from({length: 100}, (_, i) => [(i + 1).toString(), 'EXAMPLE', 'ETUDIANT EXEMPLE ' + i].concat(row('').slice(3)))
    };
    for (const format of ['A4', 'A3']) {
        const result = await buildDocument.generateSinglePage(pdfMake, [fixture], header, format);
        assert.strictEqual(buildDocument.countPages(result.buffer), 1, 'Complete PV on one page: ' + format);
        const fitted = buildDocument([fixture], header, format, result.scale);
        assert.strictEqual(fitted.content[0].stack[0].columns[0].stack[0].image, logo, 'School logo retained');
        assert.strictEqual(fitted.content[0].stack[0].columns[2].stack[0].image, universityLogo, 'University logo retained');
        assert(JSON.stringify(fitted.content[0]).includes('TUTELLE : SOA'), 'Academic metadata retained');
        assert(JSON.stringify(fitted.footer(1, 1)).includes('Responsable des Affaires Academiques'), 'Signature retained');
        assert(JSON.stringify(fitted.footer(1, 1)).includes('Admis : 75 %'), 'Footer statistics retained');
        assert((result.buffer.toString('latin1').match(/\/Subtype\s*\/Image/g) || []).length >= 2, 'Logos embedded in actual PDF');
        assert.strictEqual(fitted.content[2].table.body.length, fixture.rows.length + 3, 'Every student and validation row retained');
        assert.deepStrictEqual(fitted.content[2].table.body[2].map(cell=>cell.text), fixture.rows[0].map(buildDocument.cleanText));
        assert.deepStrictEqual(fitted.content[2].table.body[fixture.rows.length + 1].map(cell=>cell.text), fixture.rows[fixture.rows.length - 1].map(buildDocument.cleanText));
        fs.writeFileSync(path.join(__dirname, '../.codex_tmp/soa_full_' + format + '_test.pdf'), result.buffer);
        console.log(format + ': complete PV, ' + fixture.subjects.length + ' subjects, ' + fixture.rows.length + ' students, exactly one page; all rows retained (scale ' + result.scale.toFixed(3) + ').');
    }
})().catch(error => { console.error(error); process.exitCode = 1; });
