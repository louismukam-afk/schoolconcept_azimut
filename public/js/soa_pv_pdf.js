(function (root) {
    'use strict';

    function cleanText(value) {
        var text = String(value == null ? '' : value);
        var cp1252 = {'€':128,'‚':130,'ƒ':131,'„':132,'…':133,'†':134,'‡':135,'ˆ':136,'‰':137,'Š':138,'‹':139,'Œ':140,'Ž':142,'‘':145,'’':146,'“':147,'”':148,'•':149,'–':150,'—':151,'˜':152,'™':153,'š':154,'›':155,'œ':156,'ž':158,'Ÿ':159};
        for (var pass = 0; pass < 2 && /Ã|Â|â€/.test(text); pass++) {
            try {
                var encoded = Array.from(text).map(function (letter) {
                    var code = letter.charCodeAt(0);
                    if (code > 255) code = cp1252[letter];
                    if (code === undefined) throw new Error('Non-reversible text');
                    return '%' + code.toString(16).padStart(2, '0');
                }).join('');
                text = decodeURIComponent(encoded);
            } catch (error) { break; }
        }
        return text;
    }

    function mergeTables(pages) {
        var combined = {subjects: [], rows: [], validation: [], title: ''};
        if (!pages.length) return combined;
        combined.title = pages[0].title.replace(/\s*—\s*Tableau\s+\d+\/\d+\s*$/, '');
        pages.forEach(function (page) {
            combined.subjects = combined.subjects.concat(page.subjects);
            combined.validation = combined.validation.concat(page.validation);
        });
        pages[0].rows.forEach(function (firstRow, rowIndex) {
            var row = firstRow.slice(0, 3);
            pages.forEach(function (page) {
                var source = page.rows[rowIndex];
                if (!source || source.slice(0, 3).join('\u0000') !== firstRow.slice(0, 3).join('\u0000')) {
                    throw new Error('Les tableaux ne contiennent pas les mêmes étudiants dans le même ordre.');
                }
                row = row.concat(source.slice(3, -5));
            });
            combined.rows.push(row.concat(firstRow.slice(-5)));
        });
        return combined;
    }

    function buildDocument(pages, heading, format, scale) {
        format = format === 'A3' ? 'A3' : 'A4';
        scale = scale || 1;
        var content = [];
        var structured = heading && typeof heading === 'object';
        [mergeTables(pages)].forEach(function (page) {
            var first = [], second = [];
            ['N°', 'Matricule', 'Nom et prénoms'].forEach(function (label) {
                first.push({text: label, rowSpan: 2, bold: true}); second.push({});
            });
            page.subjects.forEach(function (name) {
                first.push({text: cleanText(name), colSpan: 3, bold: true}, {}, {});
                second.push({text: 'CC', bold: true}, {text: 'SN', bold: true}, {text: 'Total /100', bold: true});
            });
            ['Moyenne /20', 'MGP', 'Crédits validés', 'Décision', 'Rang'].forEach(function (label) {
                first.push({text: label, rowSpan: 2, bold: true}); second.push({});
            });
            var weights = [1.2, 5, 9];
            page.subjects.forEach(function () { weights.push(1.3, 1.3, 1.5); });
            weights = weights.concat([3, 1.8, 2.5, 3.4, 1.5]);
            var totalWeight = weights.reduce(function (sum, value) { return sum + value; }, 0);
            var available = (format === 'A3' ? 1190.55 : 841.89) - 24 - weights.length * 1.4 - (weights.length + 1) * 0.35;
            var widths = weights.map(function (weight) { return available * weight / totalWeight; });
            var body = [first, second].concat(page.rows.map(function (row) {
                return row.map(function (value, column) {
                    var text = cleanText(value), numeric = /^[+-]?\d+(?:[.,]\d+)?$/.test(text);
                    var cell = {text: text, alignment: column === 2 ? 'left' : 'center', bold: column >= row.length - 5};
                    if (numeric) {
                        cell.noWrap = true;
                        cell.fontSize = Math.min((format === 'A3' ? 8 : 6) * scale, widths[column] / (Math.max(1, text.length) * 0.58));
                    }
                    return cell;
                });
            }));
            if (page.validation.length) {
                body.push([{text: '% validation', colSpan: 3, bold: true}, {}, {}]
                    .concat(page.validation).concat([{text: '', colSpan: 5}, {}, {}, {}, {}]));
            }
            content.push(structured ? buildHeader(heading, format) : {text: heading, alignment: 'center', bold: true, fontSize: 9 * scale,
                margin: [0, 0, 0, 4 * scale]});
            content.push({text: cleanText(page.title), bold: true, margin: [0, 0, 0, 4 * scale]});
            content.push({table: {headerRows: 2, widths: widths, body: body},
                layout: {paddingLeft: function () { return 0.7; }, paddingRight: function () { return 0.7; },
                    paddingTop: function () { return 0.7 * scale; }, paddingBottom: function () { return 0.7 * scale; },
                    hLineWidth: function () { return 0.35; }, vLineWidth: function () { return 0.35; },
                    fillColor: function (row) { return row < 2 ? '#e7edf3' : (row % 2 === 0 ? '#f9f9f9' : null); }}});
        });
        if (!structured) content.push({text: 'Responsable des Affaires Académiques', alignment: 'right', margin: [0, 4 * scale, 0, 0]});
        return {pageSize: format, pageOrientation: 'landscape', pageMargins: [12, 12, 12, structured ? 86 : 16],
            defaultStyle: {fontSize: (format === 'A3' ? 8 : 6) * scale, alignment: 'center'}, content: content,
            footer: function (current, total) {
                if (!structured) return {text: current + ' / ' + total, alignment: 'right', margin: [0, 0, 12, 0], fontSize: 5};
                var footer = heading.footer || {};
                return {margin: [12, 4, 12, 0], stack: [
                    {text: (footer.statistics || []).join('\n'), fontSize: 6, alignment: 'left'},
                    {text: footer.signature || '', bold: true, fontSize: 9, alignment: 'right', margin: [0, 8, 0, 16]},
                    {columns: [{text: (footer.lines || []).join('\n'), fontSize: 6},
                        {text: current + ' / ' + total, alignment: 'right', fontSize: 6, width: 35}]}
                ]};
            }};
    }

    function buildHeader(header, format) {
        function schoolColumn(school) {
            var stack = [];
            if (school.logo) stack.push({image: school.logo, fit: [72, 40], alignment: 'center', margin: [0, 0, 0, 3]});
            stack.push({text: school.name || '', bold: true, fontSize: 8, alignment: 'center'});
            return {width: '28%', stack: stack};
        }
        var width = (format === 'A3' ? 1190.55 : 841.89) - 24;
        var stack = [
            {columns: [schoolColumn(header.left || {}),
                {width: '44%', text: header.center || '', fontSize: 7, alignment: 'center', margin: [5, 8, 5, 0]},
                schoolColumn(header.right || {})]},
            {canvas: [{type: 'line', x1: 0, y1: 0, x2: width, y2: 0, lineWidth: 1, lineColor: '#b30000'}], margin: [0, 6, 0, 4]},
            {text: header.title || '', bold: true, fontSize: 11, alignment: 'center', margin: [0, 0, 0, 4]}
        ];
        if (header.meta && header.meta.length) stack.push({table: {widths: header.meta.map(function () { return '*'; }),
            body: [header.meta.map(function (text) { return {text: text, fontSize: 7, alignment: 'center'}; })]}, margin: [0, 0, 0, 5]});
        if (header.description) stack.push({text: header.description, alignment: 'center', bold: true, fontSize: 9, margin: [0, 0, 0, 4]});
        if (header.context && header.context.length) stack.push({columns: header.context.map(function (text) { return {text: text, fontSize: 7}; }), margin: [0, 0, 0, 5]});
        return {stack: stack};
    }

    function countPages(buffer) {
        var text = '';
        for (var offset = 0; offset < buffer.length; offset += 16384) {
            text += String.fromCharCode.apply(null, buffer.subarray(offset, offset + 16384));
        }
        return (text.match(/\/Type\s*\/Page\b/g) || []).length;
    }

    async function generateSinglePage(pdfMake, pages, heading, format) {
        var scale = 1, failed = 1, best = null;
        for (var attempt = 0; attempt < 16; attempt++) {
            var doc = buildDocument(pages, heading, format, scale);
            var buffer = await new Promise(function (resolve) { pdfMake.createPdf(doc).getBuffer(resolve); });
            var count = countPages(buffer);
            if (count === 1) { best = {buffer: buffer, scale: scale, pageCount: count}; break; }
            if (!count) throw new Error('Le PDF généré est invalide.');
            failed = scale; scale *= 0.8;
        }
        if (!best) throw new Error('Impossible d’ajuster ce PV sur une page.');
        // Rechercher une taille plus lisible entre le dernier essai trop grand et celui qui tient.
        for (var refine = 0; best.scale < 1 && refine < 3; refine++) {
            scale = (best.scale + failed) / 2;
            var candidate = await new Promise(function (resolve) { pdfMake.createPdf(buildDocument(pages, heading, format, scale)).getBuffer(resolve); });
            if (countPages(candidate) === 1) best = {buffer: candidate, scale: scale, pageCount: 1};
            else failed = scale;
        }
        return best;
    }

    function cellText(cell) { return cleanText(cell.textContent.replace(/\s+/g, ' ').trim()); }

    async function captureHeader() {
        var header = document.querySelector('.pv-tutelle-header');
        function text(selector) { var node = document.querySelector(selector); return node ? cleanText(node.innerText.trim()) : ''; }
        async function logo(selector) {
            var image = document.querySelector(selector);
            if (!image) return null;
            if (!image.complete) await new Promise(function (resolve, reject) {
                image.addEventListener('load', resolve, {once: true});
                image.addEventListener('error', function () { reject(new Error('Impossible de charger un logo du PV.')); }, {once: true});
            });
            if (!image.naturalWidth) throw new Error('Un logo du PV est introuvable.');
            var canvas = document.createElement('canvas');
            canvas.width = image.naturalWidth; canvas.height = image.naturalHeight;
            canvas.getContext('2d').drawImage(image, 0, 0);
            return canvas.toDataURL('image/png');
        }
        if (!header) throw new Error('En-tête du procès-verbal introuvable.');
        var logos = await Promise.all([logo('.pv-tutelle-left img'), logo('.pv-tutelle-right img')]);
        return {
            left: {name: text('.pv-tutelle-left .pv-tutelle-school'), logo: logos[0]},
            right: {name: text('.pv-tutelle-right .pv-tutelle-school'), logo: logos[1]},
            center: text('.pv-tutelle-center'), title: text('.pv-tutelle-header h3'),
            meta: Array.prototype.map.call(header.querySelectorAll('.pv-meta-item'), cellText),
            description: text('.titre'), context: [text('.classe .specialite'), text('.classe .filiere')].filter(Boolean),
            footer: {statistics: Array.prototype.map.call(document.querySelectorAll('.soa-pv-statistics > *'), cellText),
                signature: text('.soa-pv-signature'), lines: [text('.soa-pv-document-footer')].filter(Boolean)}
        };
    }

    root.exportSoaPvPdf = async function (format, preview) {
        var previewWindow = preview ? root.open('', '_blank') : null;
        var status = document.getElementById('soa-pdf-status');
        if (status) status.textContent = 'Ajustement du PV sur une page…';
        try {
        var pages = [];
        document.querySelectorAll('.soa-pv-print-page').forEach(function (page) {
            var table = page.querySelector('table'), subjects = [], rows = [], validation = [];
            table.querySelectorAll('thead tr:first-child th[colspan="3"]').forEach(function (cell) { subjects.push(cellText(cell)); });
            table.querySelectorAll('tbody tr').forEach(function (row) {
                rows.push(Array.prototype.map.call(row.cells, cellText));
            });
            table.querySelectorAll('tfoot td:not([colspan])').forEach(function (cell) { validation.push(cellText(cell)); });
            pages.push({subjects: subjects, rows: rows, validation: validation, title: cellText(page.querySelector('p'))});
        });
        var heading = await captureHeader();
        var result = await generateSinglePage(root.pdfMake, pages, heading, format);
        var url = root.URL.createObjectURL(new Blob([result.buffer], {type: 'application/pdf'}));
        if (previewWindow) previewWindow.location.href = url;
        else {
            var link = document.createElement('a'); link.href = url;
            link.download = 'PV_Soa_' + (format === 'A3' ? 'A3' : 'A4') + '.pdf';
            document.body.appendChild(link); link.click(); link.remove();
        }
        root.setTimeout(function () { root.URL.revokeObjectURL(url); }, 60000);
        if (status) status.textContent = 'PDF prêt : 1 page ' + (format === 'A3' ? 'A3' : 'A4') + ' paysage.';
        } catch (error) {
            if (previewWindow) previewWindow.close();
            if (status) status.textContent = error.message || 'Échec de la génération du PDF.';
        }
    };
    buildDocument.generateSinglePage = generateSinglePage;
    buildDocument.countPages = countPages;
    buildDocument.cleanText = cleanText;
    if (typeof module !== 'undefined' && module.exports) module.exports = buildDocument;
}(typeof window !== 'undefined' ? window : this));
