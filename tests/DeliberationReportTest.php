<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\DeliberationReport;

function checkReport($condition, $message) { if (!$condition) throw new RuntimeException($message); }
function reportRow($overrides = array()) {
    return (object) array_merge(array(
        'id' => 1, 'id_cycle' => 1, 'id_nniveau' => 1, 'id_filiere' => 1, 'id_classe' => 1,
        'id_classe_matiere' => 10, 'id_eleve' => 1, 'semestre' => 1,
        'cycle_nom' => 'BTS', 'niveau_nom' => '1', 'filiere_nom' => 'Gestion', 'classe_nom' => 'Comptabilité',
        'matiere_nom' => 'Calcul', 'edition_details' => 'deliberation criteres ue_note vers 10/20',
        'nom' => 'Exemple', 'prenom' => 'Test', 'matricule' => 'TEST', 'sequence' => 3, 'updated_at' => '2026-10-21 10:00:00'
    ), $overrides);
}
$report = DeliberationReport::build(array(
    reportRow(), reportRow(array('id' => 2, 'id_eleve' => 2)),
    reportRow(array('id' => 3, 'id_classe_matiere' => 11, 'edition_details' => 'deliberation criteres credit vers 10/20')),
    reportRow(array('id' => 4, 'id_nniveau' => 2, 'niveau_nom' => '2', 'id_classe_matiere' => 12)),
    reportRow(array('id' => 5, 'id_classe' => 2, 'classe_nom' => 'Commerce', 'id_classe_matiere' => 13, 'edition_details' => 'deliberation rattrapage vers 10/20')),
    reportRow(array('id' => 6, 'edition_details' => 'update: note=50'))
));
checkReport(count($report['groups']) === 3, 'Separate groups for each specialty and level');
checkReport($report['count'] === 5 && $report['students'] === 2 && $report['subjects'] === 4, 'Only deliberation traces counted, students deduplicated across groups');
$first = reset($report['groups']);
checkReport(count($first['students']) === 2 && $first['count'] === 3, 'Group totals deduplicate students across types');
checkReport(count($first['types']['ue_note']['subjects']['10-1']['rows']) === 2, 'Students grouped beneath deliberated subject and type');
foreach (DeliberationReport::types() as $key => $label) {
    if ($key === 'individuelle') continue;
    checkReport(DeliberationReport::trace('deliberation criteres '.$key.' vers 10.25/20')['target'] === 10.25, 'All criteria types recognised: '.$key);
}
checkReport(DeliberationReport::trace('deliberation semestre vers 10,5/20')['target'] === 10.5, 'Individual legacy traces and decimal commas');
$filtered = DeliberationReport::build(array(reportRow(), reportRow(array('edition_details' => 'deliberation criteres credit vers 10/20'))), 'credit');
checkReport($filtered['count'] === 1 && count(reset($filtered['groups'])['types']) === 1, 'Type filter excludes unrelated deliberations');
checkReport(DeliberationReport::build(array())['count'] === 0, 'Empty reports');
checkReport(DeliberationReport::trace('deliberation criteres credit vers 10/20')['before'] === null, 'Legacy traces do not invent an old mark');
foreach (array(0, 8.75, 10) as $before) {
    foreach (array('deliberation criteres ue_note vers 10/20', 'deliberation rattrapage vers 10/20') as $trace) {
        $saved = DeliberationReport::withBefore($trace, $before);
        checkReport(DeliberationReport::trace($saved)['before'] === (float) $before, 'Before marks preserved, including zero');
        $beforeReport = DeliberationReport::build(array(reportRow(array('edition_details' => $saved))));
        $beforeGroup = reset($beforeReport['groups']);
        $beforeType = reset($beforeGroup['types']);
        checkReport($beforeType['subjects']['10-1']['rows'][0]->before20 === (float) $before, 'Before mark available in report rows');
    }
}
checkReport(DeliberationReport::withBefore('deliberation semestre vers 10/20', null) === 'deliberation semestre vers 10/20', 'Unknown before mark stays absent');
foreach (array('fin_semestre_1' => 3, 'fin_semestre_2' => 4, 'rattrapage_1' => 5, 'rattrapage_2' => 6) as $examKey => $sequence) {
    checkReport(DeliberationReport::examSequences($examKey) === array($sequence), 'Exact exam filter: '.$examKey);
    $examReport = DeliberationReport::build(array(reportRow(array('sequence' => $sequence))));
    $examGroup = reset($examReport['groups']);
    $row = $examGroup['types']['ue_note']['subjects']['10-1']['rows'][0];
    checkReport($row->exam_label === DeliberationReport::exams()[$examKey], 'Detailed report distinguishes exam and semester');
}

$compiler = new Illuminate\View\Compilers\BladeCompiler(new Illuminate\Filesystem\Filesystem(), sys_get_temp_dir());
foreach (array('rapport_deliberations', 'partials/cycle_niveau_filiere_filter') as $view) {
    $compiled = $compiler->compileString(file_get_contents(__DIR__.'/../resources/views/eleves/'.$view.'.blade.php'));
    $path = tempnam(sys_get_temp_dir(), 'report');
    file_put_contents($path, $compiled);
    exec('"'.PHP_BINARY.'" -l '.escapeshellarg($path), $output, $status);
    unlink($path);
    checkReport($status === 0, 'Compiled view syntax: '.$view);
}
echo "Report traces, grouping, distinct counts, type filtering and Blade syntax: OK\n";
