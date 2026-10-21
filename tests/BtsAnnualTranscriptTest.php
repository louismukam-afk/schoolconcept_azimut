<?php
// Vérification autonome : php tests/BtsAnnualTranscriptTest.php
require __DIR__.'/../vendor/autoload.php';
set_error_handler(function ($severity, $message, $file, $line) { throw new RuntimeException($message.' at '.$file.':'.$line); });

use gesecole\Services\BtsAnnualTranscript as Transcript;

function checkBts($condition, $message) {
    if (!$condition) throw new RuntimeException($message);
}
checkBts(Transcript::displayText('CoÃ»ts complets') === 'Coûts complets', 'Accents anciens corrigés à l’affichage');
checkBts(Transcript::displayText('Mathématiques') === 'Mathématiques', 'Accents corrects conservés');
checkBts(Transcript::levelLabel('BREVET DE TECHNICIENS SUPERIEURS 1') === 'I', 'Niveau compact');
foreach (array(array(80,'A',4),array(79,'B+',3.5),array(70,'B+',3.5),array(69,'B',3),
    array(60,'B',3),array(55,'C+',2.5),array(50,'C',2),array(45,'C-',1.5),array(40,'D+',1),array(0,'F',0)) as $case) {
    $grade = Transcript::grade($case[0]);
    checkBts($grade[0] === $case[1] && $grade[1] == $case[2], 'Seuil de grade incorrect');
}
$makeRow = function ($id, $note, $credit) {
    return (object) array('id_matiere'=>$id, 'id_classe_matiere'=>$id, 'note'=>$note, 'credit'=>$credit);
};
$summary = Transcript::summarize(array($makeRow(1, 78, 2), $makeRow(2, 62, 3), $makeRow(3, null, 2)));
checkBts($summary->credits == 7 && $summary->gradedCredits == 5, 'Crédits sans note');
checkBts(abs($summary->mgp - 3.2) < .00001 && $summary->weighted == 16, 'MGP pondérée par crédits');
checkBts($summary->validated == 5 && $summary->rows[2]->point === null, 'Note absente distincte de zéro');
checkBts(Transcript::summarize(array($makeRow(1, null, 3)))->mgp === null, 'Semestre vide');
$grouped = Transcript::groupRows(array($makeRow(1, null, 3), $makeRow(2, 80, 1), $makeRow(3, 60, 2)),
    array('meres'=>array(1=>true), 'composantes'=>array(2=>1,3=>1)));
checkBts(count($grouped) === 1 && abs($grouped[0]->note - 66.67) < .001, 'UE composée et double comptage');
$annual = Transcript::summarize(array($makeRow(1,80,2),$makeRow(2,50,3)));
checkBts(abs($annual->mgp - 2.8) < .00001, 'Moyenne annuelle');
$reflection = new ReflectionClass('gesecole\Http\Controllers\eleves\bts_releveSessionController');
$controller = $reflection->newInstanceWithoutConstructor();
$method = $reflection->getMethod('calculateSemestreNote'); $method->setAccessible(true);
$subject = (object) array('cc1'=>30,'sn1'=>70,'tpe1'=>0,'cc2'=>30,'sn2'=>70,'tpe2'=>0);
checkBts($method->invoke($controller, array(1=>20,3=>45,2=>10,4=>20,6=>50), 1, $subject) == 65, 'Note semestre 1');
checkBts($method->invoke($controller, array(1=>20,3=>45,2=>10,4=>20,6=>50), 2, $subject) == 60, 'Rattrapage semestre 2');
checkBts($method->invoke($controller, array(), 1, $subject) === null, 'Absence de note');
$legacySubject = function ($id, $semester, $note) {
    return (object) array('id_matiere'=>$id, 'id_classe_matiere'=>$id, 'id_code'=>'UE'.$id,
        'altName'=>'Enseignement', 'semestre'=>$semester, 'note'=>$note, 'credit'=>3, 'type'=>0);
};
$legacy = Transcript::fromLegacy(array((object) array('eleve'=>(object) array('id'=>1),
    'matieres'=>array($legacySubject(1,1,62), $legacySubject(2,2,75)))));
$legacySemesters = (array) $legacy[0]->semestres;
checkBts(count($legacySemesters[1]->rows) === 1 && $legacySemesters[1]->rows[0]->note === 62.0, 'Notes sauvegardées conservées');
checkBts(count($legacySemesters[2]->rows) === 1 && $legacySemesters[2]->rows[0]->note === 75.0, 'Répartition des anciens semestres');

// Compiler et rendre le modèle sans démarrer les contrôleurs de l’application.
$container = new Illuminate\Container\Container();
Illuminate\Container\Container::setInstance($container);
$container->instance('url', new Illuminate\Routing\UrlGenerator(new Illuminate\Routing\RouteCollection(), Illuminate\Http\Request::create('http://localhost')));
$container->instance('Illuminate\Contracts\Routing\UrlGenerator', $container->make('url'));
$compiler = new Illuminate\View\Compilers\BladeCompiler(new Illuminate\Filesystem\Filesystem(), sys_get_temp_dir());
$template = file_get_contents(__DIR__.'/../resources/views/eleves/print_releves_bts_annuel.blade.php');
// Rendre le contenu du relevé seul ; le gabarit commun nécessite une session utilisateur.
$template = preg_replace('/^@extends\([^\n]+\)\r?\n@section\(\x27sub-content\x27\)\r?\n/', '', $template);
$template = preg_split('/@endsection/', $template, 2)[0];
$row = (object) array('code'=>'GSI1111','nom'=>'Mathématiques générales I','type'=>0,'credit'=>3,'note'=>62);
$sem = Transcript::summarize(array($row));
$PV = json_decode(json_encode(array((object) array('eleve'=>(object) array('nom'=>'EXEMPLE','prenom'=>'ETUDIANT','date_naiss'=>'2006-07-07','lieu_naiss'=>'YAOUNDE','matricule'=>'TEST','sexe'=>'M'), 'semestres'=>(object) array('1'=>$sem,'2'=>$sem),'annuel'=>Transcript::summarize(array($row,$row))))));
$annee = '2024-2025'; $periode_debut='2024-09-01'; $periode_fin='2025-08-31'; $date_signature='2025-09-15';
$classe=(object) array('nom'=>'Gestion des systèmes d’information'); $cycle=(object) array('nom'=>'BTS');
$nniveau=(object) array('nom'=>'I'); $niveau=(object) array('nom'=>'GESTION');
ob_start(); eval('?>'.$compiler->compileString($template)); $html = ob_get_clean();
checkBts(strpos($html, '15/09/2025') !== false && strpos($html, 'SEMESTRE 2') !== false && strpos($html, '62.00') !== false, 'Rendu du relevé sauvegardé');
$date_signature = null;
ob_start(); eval('?>'.$compiler->compileString($template)); $unsignedHtml = ob_get_clean();
checkBts(strpos($unsignedHtml, '01/01/1970') === false && strpos($unsignedHtml, '...........................') !== false, 'Signature absente non inventée');
foreach (array('classe_releves_bts_annuel', 'print_releves_bts_annuel') as $view) {
    $compiled = $compiler->compileString(file_get_contents(__DIR__.'/../resources/views/eleves/'.$view.'.blade.php'));
    checkBts(strpos($compiled, '@foreach') === false, 'Compilation Blade');
}
echo "BTS : grades, MGP, UE composées, semestres, rattrapage et rendu Blade validés.\n";
