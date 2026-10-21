const assert = require('assert');
const {execFileSync} = require('child_process');
const vm = require('vm');

function render(previous, specialtyMode) {
    const encoded = Buffer.from(JSON.stringify(previous)).toString('base64');
    return execFileSync('php', ['-r', `
        require 'vendor/autoload.php';
        class AnnualFieldFixture {
            public $id; public $id_niveau; public $nom;
            function __construct($id, $level) { $this->id=$id; $this->id_niveau=$level; $this->nom='Field '.$id; }
            function getNom() { return $this->nom; }
        }
        $cycles=array((object)array('id'=>1,'nom'=>'BTS'),(object)array('id'=>2,'nom'=>'HND'));
        $nniveaux=array((object)array('id'=>11,'id_cycle'=>1,'nom'=>'I'),(object)array('id'=>22,'id_cycle'=>2,'nom'=>'II'));
        $niveaux=array(new AnnualFieldFixture(101,11),new AnnualFieldFixture(202,22));
        $values=json_decode(base64_decode('${encoded}'),true);
        $selectionSubmitFilters=true;
        $selectionCycleValue=$values['cycle']; $selectionLevelValue=$values['level'];
        $selectionFiliereValue=$values['field']; $selectionSpecialtyValue=$values['specialty'];
        $selectionSpecialtySelectId='${specialtyMode ? 'annual_class' : ''}';
        $compiler=new Illuminate\\View\\Compilers\\BladeCompiler(new Illuminate\\Filesystem\\Filesystem(),sys_get_temp_dir());
        eval('?>'.$compiler->compileString(file_get_contents('resources/views/eleves/partials/cycle_niveau_filiere_filter.blade.php')));
    `], {cwd: __dirname + '/..', encoding: 'utf8'});
}

function setup(previous = {cycle: 0, level: 0, field: 0, specialty: 0}, specialtyMode = true) {
    function select(options) {
        let value = '';
        return {
            options: [{value: '', style: {}}].concat(options.map(([id, data]) => ({
                value: String(id), style: {}, disabled: false,
                getAttribute: key => data[key] === undefined ? null : String(data[key])
            }))), disabled: false, events: {},
            get value() { return value; },
            set value(next) { value = this.options.some(option => option.value === String(next)) ? String(next) : ''; },
            addEventListener(name, callback) { this.events[name] = callback; },
            change(next) { this.value = next; this.events.change(); }
        };
    }
    const nodes = {
        cycle_filter: select([[1, {}], [2, {}]]),
        nniveau_filter: select([[11, {'data-cycle': 1}], [22, {'data-cycle': 2}]]),
        filiere_filter: select([[101, {'data-nniveau': 11}], [202, {'data-nniveau': 22}]]),
        specialite_empty: {style: {}}
    };
    nodes.nniveau_filter.disabled = nodes.filiere_filter.disabled = true;
    if (specialtyMode) {
        nodes.annual_class = select([[1001, {'data-filiere': 101}], [2002, {'data-filiere': 202}]]);
        nodes.annual_class.disabled = true;
    }
    const blocks = [101, 202].map(id => ({style: {}, getAttribute: key => key === 'data-filiere' ? String(id) : null}));
    const list = {style: {}};
    const callbacks = [];
    const document = {
        getElementById: id => nodes[id] || null,
        querySelector: () => specialtyMode ? null : list,
        getElementsByClassName: name => name === 'niveau-block' ? blocks : [],
        addEventListener: (name, callback) => callbacks.push(callback)
    };
    const html = render(previous, specialtyMode);
    vm.runInNewContext(html.match(/<script>([\s\S]*?)<\/script>/)[1], {window: {}, document});
    callbacks.forEach(callback => callback());
    return {nodes, blocks, list};
}

const {nodes} = setup();
assert(nodes.nniveau_filter.disabled && nodes.filiere_filter.disabled && nodes.annual_class.disabled);
nodes.cycle_filter.change('1');
assert(!nodes.nniveau_filter.disabled && nodes.nniveau_filter.options[2].disabled);
assert(nodes.filiere_filter.disabled && nodes.annual_class.disabled);
nodes.nniveau_filter.change('11');
assert(!nodes.filiere_filter.disabled && nodes.filiere_filter.options[2].disabled);
nodes.filiere_filter.change('101');
assert(!nodes.annual_class.disabled && nodes.annual_class.options[2].disabled);
nodes.annual_class.value = '1001';
nodes.cycle_filter.change('2');
assert.strictEqual(nodes.annual_class.value, '');
assert(nodes.filiere_filter.disabled && nodes.annual_class.disabled);
nodes.nniveau_filter.change('22'); nodes.filiere_filter.change('202');
assert(nodes.annual_class.options[1].disabled && !nodes.annual_class.options[2].disabled);
const restored = setup({cycle: 2, level: 22, field: 202, specialty: 2002}).nodes;
assert.strictEqual(restored.annual_class.value, '2002');
const stale = setup({cycle: 2, level: 22, field: 202, specialty: 1001}).nodes;
assert.strictEqual(stale.annual_class.value, '');
const pv = setup({cycle: 1, level: 11, field: 101, specialty: 0}, false);
assert.strictEqual(pv.blocks[0].style.display, '');
assert.strictEqual(pv.blocks[1].style.display, 'none');
console.log('Annual BTS/HND cascade, reset, validation recovery and existing PV filters: OK');
