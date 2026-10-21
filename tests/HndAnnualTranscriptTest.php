<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\HndAnnualTranscript as Hnd;
function checkHnd($value, $message) { if (!$value) throw new RuntimeException($message); }
foreach (array(array(80,'A',4),array(79,'B+',3.5),array(70,'B+',3.5),array(60,'B',3),
    array(55,'C+',2.5),array(50,'C',2),array(49,'D+',1.5),array(45,'D+',1.5),array(44,'D',1),array(40,'D',1),array(39,'F',0)) as $case) {
    $grade = Hnd::grade($case[0]); checkHnd($grade[0] === $case[1] && $grade[1] == $case[2], 'HND grade boundary');
}
$rows = array();
foreach (array(array(3,79),array(2,65),array(2,86),array(2,58),array(5,64),array(2,64),array(2,50),array(2,53),array(3,52),array(2,84),array(2,65),array(1.5,68),array(1.5,66)) as $i=>$item) {
    $rows[] = (object) array('code'=>'ACC '.(1111+$i), 'nom'=>'GENERAL MATHEMATICS I', 'credit'=>$item[0], 'note'=>$item[1], 'type'=>$i<4 ? 0 : ($i<11 ? 2 : 1));
}
$semester = Hnd::summarize($rows);
checkHnd($semester->credits == 30 && $semester->weighted == 87.5 && round($semester->mgp, 2) == 2.92, 'Sample first semester GPA');
checkHnd($semester->classification === 'SECOND CLASS (Lower Division)', 'English degree class');
$legacyRows = array(
    (object) array('nom'=>'GENERAL ECONOMICS I','code'=>'','type'=>0,'credit'=>2,'note'=>29),
    (object) array('nom'=>'DAILY OPERATIONS I','code'=>'','type'=>0,'credit'=>4,'note'=>53),
    (object) array('nom'=>'GENERAL MATHEMATICS I','code'=>'','type'=>0,'credit'=>2,'note'=>33),
    (object) array('nom'=>'FINANCIAL MATHEMATICS I','code'=>'','type'=>0,'credit'=>2,'note'=>49),
);
$presented = Hnd::presentationRows($legacyRows, 1, 'ACCOUNTANCY');
$byName = array(); foreach ($presented as $row) $byName[$row->nom] = $row;
checkHnd($byName['GENERAL ECONOMICS I']->type === 1 && $byName['GENERAL ECONOMICS I']->code === 'ACC 1172', 'Common courses from reference');
checkHnd($byName['DAILY OPERATIONS I']->type === 2 && $byName['DAILY OPERATIONS I']->code === 'ACC 1131', 'Professional courses from reference');
checkHnd($presented[0]->code === 'ACC 1111', 'Official ordering by course code');
checkHnd($legacyRows[0]->type === 0 && $legacyRows[0]->code === '', 'Historical snapshot not mutated');
$before = Hnd::summarize($legacyRows); $after = Hnd::summarize($presented);
checkHnd($before->credits === $after->credits && $before->weighted === $after->weighted && $before->mgp === $after->mgp, 'Presentation preserves real credits, marks and GPA');
$configured = array((object) array('nom'=>'DAILY OPERATIONS I','type'=>1,'code'=>'CUSTOM 1','credit'=>4,'note'=>53));
$kept = Hnd::presentationRows($configured, 1, 'ACCOUNTANCY');
checkHnd($kept[0]->type === 1 && $kept[0]->code === 'CUSTOM 1', 'Explicit configured categories and codes preserved');
checkHnd(Hnd::presentationRows($legacyRows, 1, 'COMPUTER SCIENCE') === $legacyRows, 'No Accountancy defaults for other departments');
$secondRows = Hnd::presentationRows(array((object) array('nom'=>'RESEARCH METHODOLOGY','code'=>'','type'=>0,'credit'=>2,'note'=>75)), 2, 'ACCOUNTANCY');
checkHnd($secondRows[0]->type === 2 && $secondRows[0]->code === 'ACC 1251', 'Second semester reference');
$low = Hnd::summarize(array((object) array('credit'=>3, 'note'=>48)));
checkHnd($low->rows[0]->grade === 'D+' && $low->weighted == 4.5, 'HND service used by GPA calculation');
$container = new Illuminate\Container\Container(); Illuminate\Container\Container::setInstance($container);
$url = new Illuminate\Routing\UrlGenerator(new Illuminate\Routing\RouteCollection(), Illuminate\Http\Request::create('http://localhost'));
$container->instance('url', $url); $container->instance('Illuminate\Contracts\Routing\UrlGenerator', $url);
$compiler = new Illuminate\View\Compilers\BladeCompiler(new Illuminate\Filesystem\Filesystem(), sys_get_temp_dir());
$template = file_get_contents(__DIR__.'/../resources/views/eleves/print_releves_hnd.blade.php');
$template = preg_replace('/^@extends\([^\n]+\)\r?\n@section\(\x27sub-content\x27\)\r?\n/', '', $template);
$template = preg_split('/@endsection/', $template, 2)[0];
$PV = json_decode(json_encode(array((object) array('eleve'=>(object) array('nom'=>'STUDENT','prenom'=>'EXAMPLE','matricule'=>'HND-EXAMPLE','date_naiss'=>'2006-07-07','lieu_naiss'=>'YAOUNDE','sexe'=>'F'), 'semestres'=>(object) array('1'=>$semester,'2'=>$semester),'annuel'=>Hnd::summarize(array_merge($rows,$rows))))));
$annee='2025-2026'; $periode_debut='2025-09-01'; $periode_fin='2026-08-31'; $date_signature='2026-10-20';
$classe=(object) array('nom'=>'COMPTABILITE','nom_anglais'=>'ACCOUNTANCY');
$niveau=(object) array('nom'=>'FINANCE','nom_anglais'=>'BUSINESS AND FINANCE'); $nniveau=(object) array('nom'=>'HND I');
ob_start(); eval('?>'.$compiler->compileString($template)); $html=ob_get_clean();
foreach (array('ANNUAL TRANSCRIPT','ACCOUNTANCY','FIRST SEMESTER GPA','SECOND SEMESTER GPA','2.92','20/10/2026','WEIGHTED','Cycle: HND') as $expected) checkHnd(strpos($html,$expected)!==false, 'Missing HND field: '.$expected);
echo "HND grades, sample GPA, English labels and Blade rendering: OK\n";
