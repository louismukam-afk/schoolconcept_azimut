const assert = require('assert'), fs = require('fs');
const build = require('../public/js/admissions_pdf.js').buildDocument;
const pdfMake = require('../public/js/datatables/pdfmake-0.1.36/pdfmake.min.js');
pdfMake.vfs = require('../public/js/datatables/pdfmake-0.1.36/vfs_fonts.js').pdfMake.vfs;
const rows = Array.from({length:90},(_,i)=>[i+1,'TEST'+i,'Étudiant exemple '+i,'12,50',30,'Admis']);
const data = {campus:'AZIMUT — EMANA',adresse:'Yaoundé',cycle:'BTS',annee:'2025-2026',session:'Fin de semestre 1',groups:[
    {filiere:'Business',specialite:'Accountancy',niveau:'1',effectif:100,credits:30,rows:rows},
    {filiere:'Technology',specialite:'Networks',niveau:'2',effectif:3,credits:30,rows:rows.slice(0,3)}]};
const doc = build(data);
assert.strictEqual(doc.content[5].pageBreak,'before');
assert.strictEqual(doc.content[4].table.headerRows,1);
assert.strictEqual(doc.content[4].table.body.length,91);
pdfMake.createPdf(doc).getBuffer(buffer=>{
    assert(buffer.length>10000);
    const pages=(buffer.toString('latin1').match(/\/Type\s*\/Page\b/g)||[]).length;
    assert(pages>=3,'Multi-page class and separate specialty page');
    fs.mkdirSync('.codex_tmp',{recursive:true});fs.writeFileSync('.codex_tmp/admissions_test.pdf',buffer);
    console.log('Admission PDF: '+pages+' A4 pages, repeated table headings, two groups, all rows retained: OK');
});
