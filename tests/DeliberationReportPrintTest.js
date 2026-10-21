const assert = require('assert');
const fs = require('fs');
const vm = require('vm');
const template = fs.readFileSync(__dirname + '/../resources/views/eleves/rapport_deliberations.blade.php', 'utf8');
const scripts = [...template.matchAll(/<script>([\s\S]*?)<\/script>/g)];
const roots = [];
const events = {};
const report = { cloneNode: () => ({ content: 'Report tables and marks' }) };
const body = {
    appendChild(node) { node.parentNode = body; roots.push(node); },
    removeChild(node) { roots.splice(roots.indexOf(node), 1); }
};
let printed = 0;
const document = {
    body,
    getElementById(id) {
        if (id === 'deliberation-report') return report;
        return roots.find(node => node.id === id) || null;
    },
    addEventListener() {}
};
const window = {
    addEventListener(name, callback) { events[name] = callback; },
    print() {
        events.beforeprint();
        assert.strictEqual(roots.length, 1, 'Repeated beforeprint must not duplicate the report');
        assert.strictEqual(roots[0].id, 'deliberation-print-root');
        assert.strictEqual(roots[0].content, 'Report tables and marks');
        printed++;
        events.afterprint();
    }
};
for (const script of scripts) vm.runInNewContext(script[1], { window, document });
window.printDeliberationReport();
assert.strictEqual(printed, 1);
assert.strictEqual(roots.length, 0, 'Print or cancellation cleans up the temporary report');
events.beforeprint();
assert.strictEqual(roots.length, 1, 'Keyboard printing prepares the report too');
events.afterprint();
assert.strictEqual(roots.length, 0);
console.log('Report print button, keyboard printing, duplicate prevention and cleanup: OK');
