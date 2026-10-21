<?php
require __DIR__.'/../vendor/autoload.php';

use gesecole\Http\Controllers\eleves\noteController;

function checkDeliberation($condition, $message)
{
    if (!$condition) throw new RuntimeException($message);
}

$reflection = new ReflectionClass('gesecole\Http\Controllers\eleves\noteController');
$controller = $reflection->newInstanceWithoutConstructor();
function deliberationCall($name, $args)
{
    global $reflection, $controller;
    $method = $reflection->getMethod($name);
    $method->setAccessible(true);
    return $method->invokeArgs($controller, $args);
}

checkDeliberation(deliberationCall('deliberationValueInInterval', array(8, 8, 10, true, false)), 'Inclusive lower bound');
checkDeliberation(!deliberationCall('deliberationValueInInterval', array(10, 8, 10, true, false)), 'Exclusive upper bound');
checkDeliberation(!deliberationCall('deliberationValueInInterval', array(8, 8, 10, false, true)), 'Exclusive lower bound');
checkDeliberation(deliberationCall('deliberationSemesterPosition', array(5)) === 1, 'Odd semester');
checkDeliberation(deliberationCall('deliberationSemesterPosition', array(6)) === 2, 'Even semester');

$first = (object) array('id_classe_matiere' => 101, 'id_matiere' => 11, 'semestre' => 3, 'credit' => 2, 'module_nom' => 'UE');
$second = (object) array('id_classe_matiere' => 102, 'id_matiere' => 12, 'semestre' => 3, 'credit' => 1, 'module_nom' => 'UE');
$result = array('eleve' => (object) array('id' => 1), 'matieres' => array(
    101 => array('s1' => array('note20' => 8, 'credit' => 2, 'valide' => false)),
    102 => array('s1' => array('note20' => 11, 'credit' => 1, 'valide' => true))
));
$summary = deliberationCall('deliberationModuleSummary', array($result, array($first, $second)));
checkDeliberation($summary['average'] === 9.0, 'UE average weighted by credits');
$adjustment = deliberationCall('deliberationModuleAdjustment', array($summary, 10));
checkDeliberation(count($adjustment) === 1 && $adjustment[0]['matiere']->id_matiere === 11 && $adjustment[0]['target20'] === 9.5, 'UE adjusts the failed subject and preserves the validated subject');
checkDeliberation(!deliberationCall('deliberationModuleSubjectEligible', array(array('note20' => 10, 'valide' => false))), 'Exactly 10/20 or 50/100 is excluded');
checkDeliberation(deliberationCall('deliberationModuleSubjectEligible', array(array('note20' => 9.99, 'valide' => false))), 'Below 10/20 or 50/100 is eligible');
checkDeliberation(!deliberationCall('deliberationModuleSubjectEligible', array(array('note20' => 9, 'valide' => true))), 'Validated status is protected even below the threshold');
checkDeliberation(!deliberationCall('deliberationModuleSubjectEligible', array(null)), 'Missing marks are excluded');
checkDeliberation(deliberationCall('deliberationModuleAdjustment', array($summary, 8)) === array(), 'No decrease when UE already exceeds its target');

$third = (object) array('id_classe_matiere' => 103, 'id_matiere' => 13, 'semestre' => 3, 'credit' => 1, 'module_nom' => 'UE');
$multipleResult = $result;
$multipleResult['matieres'][103]['s1'] = array('note20' => 1, 'credit' => 1, 'valide' => false);
$multipleSummary = deliberationCall('deliberationModuleSummary', array($multipleResult, array($first, $second, $third)));
$multipleAdjustments = deliberationCall('deliberationModuleAdjustment', array($multipleSummary, 17));
checkDeliberation(count($multipleAdjustments) === 2, 'Several failed subjects can be adjusted to reach the UE target');
$weightedTotal = 28;
foreach ($multipleAdjustments as $choice) {
    checkDeliberation($choice['matiere']->id_matiere !== 12 && $choice['note20'] < 10 && $choice['target20'] <= 20, 'Only failed subjects modified, respecting the /20 maximum');
    $weightedTotal += ($choice['target20'] - $choice['note20']) * $choice['matiere']->credit;
}
checkDeliberation(abs($weightedTotal / 4 - 17) < 0.000001, 'Combined adjustments reach the weighted target');
checkDeliberation(deliberationCall('deliberationModuleAdjustment', array($multipleSummary, 20)) === array(), 'Unreachable target never changes validated subjects');
$validatedResult = $result;
$validatedResult['matieres'][101]['s1'] = array('note20' => 10, 'credit' => 2, 'valide' => true);
$validatedSummary = deliberationCall('deliberationModuleSummary', array($validatedResult, array($first, $second)));
checkDeliberation(deliberationCall('deliberationModuleAdjustment', array($validatedSummary, 15)) === array(), 'An entirely validated UE has no eligible subjects');
$rows = deliberationCall('deliberationCreditRows', array($result, array($first, $second), 3, 1, 'credit', 'Credits', ''));
checkDeliberation(count($rows) === 1 && $rows[0]['matiere']->id_matiere === 11 && $rows[0]['target20'] === 10.0, 'Only failed UV raised to earn missing credits');
foreach (array('credit', 'matiere_credit') as $creditType) {
    foreach (array(
        array('note20' => 10, 'valide' => false),
        array('note20' => 12, 'valide' => false),
        array('note20' => 9, 'valide' => true),
        array('valide' => false)
    ) as $protectedData) {
        $creditResult = $result;
        $creditResult['matieres'][101]['s1'] = $protectedData;
        $creditRows = deliberationCall('deliberationCreditRows', array($creditResult, array($first, $second), 3, 1, $creditType, 'Credits', ''));
        checkDeliberation(count($creditRows) === 0, $creditType.': validated, passing and missing marks excluded');
    }
    $creditResult['matieres'][101]['s1'] = array('note20' => 9.99, 'credit' => 2, 'valide' => false);
    $creditRows = deliberationCall('deliberationCreditRows', array($creditResult, array($first, $second), 3, 1, $creditType, 'Credits', ''));
    checkDeliberation(count($creditRows) === 1 && $creditRows[0]['target20'] === 10.0, $creditType.': below threshold raised to 10/20');
    checkDeliberation(deliberationCall('deliberationCreditRows', array($creditResult, array($first, $second), 1, 1, $creditType, 'Credits', '')) === array(), $creditType.': no adjustment when credit target already reached');
}
checkDeliberation(deliberationCall('deliberationMatiereMatchesSemester', array($first, 3)), 'Programme semester selection');
checkDeliberation(!deliberationCall('deliberationMatiereMatchesSemester', array($first, 1)), 'Different programme semester excluded');

$compiler = new Illuminate\View\Compilers\BladeCompiler(new Illuminate\Filesystem\Filesystem(), sys_get_temp_dir());
foreach (array('deliberations', 'classe_note_show', 'partials/cycle_niveau_filiere_filter') as $view) {
    $compiled = $compiler->compileString(file_get_contents(__DIR__.'/../resources/views/eleves/'.$view.'.blade.php'));
    $path = tempnam(sys_get_temp_dir(), 'delib');
    file_put_contents($path, $compiled);
    exec('"'.PHP_BINARY.'" -l '.escapeshellarg($path), $output, $status);
    unlink($path);
    checkDeliberation($status === 0, 'Compiled Blade syntax: '.$view);
}
echo "Deliberation intervals, semesters, UE averages, credit targets and Blade compilation: OK\n";
