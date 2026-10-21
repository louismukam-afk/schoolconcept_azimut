<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\HndPv;
function checkHndPv($condition, $message) { if (!$condition) throw new RuntimeException($message); }
foreach (array(array(null,''),array(0,'mark-red'),array(39.99,'mark-red'),array(40,'mark-yellow'),array(49.99,'mark-yellow'),array(50,'mark-green'),array(100,'mark-green')) as $case) {
    checkHndPv(HndPv::markClass($case[0])===$case[1], 'Mark color boundary: '.var_export($case[0],true));
}
checkHndPv(HndPv::marks(array(1=>20, 7=>5, 3=>40), 1, false)['total'] === 65.0, 'CA, practical work and exam contributions added without reweighting');
checkHndPv(HndPv::marks(array(2=>15, 4=>40), 2, false)['total'] === 55.0, 'Second semester sequences');
checkHndPv(HndPv::marks(array(1=>20, 3=>45, 5=>30), 1, true)['retained'] === 65.0, 'A resit does not lower a normal result');
checkHndPv(HndPv::marks(array(1=>15, 3=>20, 5=>40), 1, true)['retained'] === 55.0, 'Better resit retained');
checkHndPv(HndPv::marks(array(1=>20), 1, false)['total'] === null, 'Missing exam not treated as a zero or a passing grade');
checkHndPv(HndPv::marks(array(1=>0, 3=>0), 1, false)['total'] === 0.0, 'Zero marks preserved');
$students = array((object)array('id'=>1));
$subjects = array((object)array('id_classe_matiere'=>10, 'id_matiere'=>2, 'semestre'=>1, 'credit'=>3, 'sessions'=>array(100)),
    (object)array('id_classe_matiere'=>11, 'id_matiere'=>3, 'semestre'=>2, 'credit'=>2, 'sessions'=>array(101)));
function hndNote($sequence, $mark, $date, $session = 100) { return (object)array('id_eleve'=>1, 'id_matiere'=>2, 'id_classe_matiere'=>10, 'id_session'=>$session, 'sequence'=>$sequence, 'note'=>$mark, 'updated_at'=>$date); }
$notes = array(hndNote(1,20,'2025-09-01'),hndNote(3,30,'2026-01-31 23:59:59'),hndNote(3,70,'2026-01-31',999));
$pv = HndPv::build($students,$subjects,$notes,'semester_1','2026-01-01','2026-01-31');
checkHndPv(count($pv['subjects'])===1 && $pv['credits']===3.0, 'Only selected semester contributes to credit total');
checkHndPv(count($pv['rows'])===1 && $pv['rows'][0]['marks'][0]['total']===50.0, 'Inclusive end date, out-of-period CA retained and wrong exam session excluded');
checkHndPv($pv['rows'][0]['decision']==='V' && $pv['rows'][0]['credits']===3.0, '50/100 validates credits');
checkHndPv(count(HndPv::build($students,$subjects,$notes,'semester_1','2026-02-01','2026-02-28')['rows'])===0, 'Students without exam marks in period excluded');
checkHndPv(HndPv::build($students,$subjects,array(),'semester_1')['rows'][0]['decision']==='NV', 'Missing marks do not validate credits');
checkHndPv(HndPv::build($students,$subjects,array(),'semester_1')['subjects']===array(), 'No courses or credits without semester notes');
foreach (array('semester_1'=>array(1,3,7), 'resit_1'=>array(5), 'semester_2'=>array(2,4,8), 'resit_2'=>array(6)) as $examKey=>$sequences) {
    foreach (range(1,8) as $sequence) {
        $subject = clone $subjects[0];
        $subject->semestre = 0;
        $result = HndPv::build($students,array($subject),array(hndNote($sequence,0,'2026-01-31')),$examKey);
        $expected = in_array($sequence,$sequences) ? 1 : 0;
        checkHndPv(count($result['subjects'])===$expected, $examKey.': subject membership follows sequence '.$sequence.' including recorded zero');
        checkHndPv($result['credits']===($expected ? 3.0 : 0), 'Credit denominator follows the filtered courses');
    }
}
$resitNotes = array(hndNote(1,15,'2026-01-31'),hndNote(3,20,'2026-01-31'),hndNote(5,40,'2026-01-31'));
$resitPv = HndPv::build($students,$subjects,$resitNotes,'resit_1');
checkHndPv(count($resitPv['subjects'])===1 && $resitPv['rows'][0]['marks'][0]['retained']===55.0, 'Resit membership uses sequence 5 while retaining CA and normal exam for calculation');
$wrongSemester = clone $subjects[0]; $wrongSemester->semestre = 2;
checkHndPv(count(HndPv::build($students,array($wrongSemester),$notes,'semester_1')['subjects'])===1, 'Recorded semester sequences take precedence over legacy programme semester');
foreach (array(hndNote(3,null,'2026-01-31'), hndNote(3,'','2026-01-31'), hndNote(3,50,'2026-01-31',999)) as $irrelevant) {
    checkHndPv(HndPv::build($students,$subjects,array($irrelevant),'semester_1')['subjects']===array(), 'Empty marks and notes from another exam session do not add courses');
}
$otherStudent = hndNote(3,50,'2026-01-31'); $otherStudent->id_eleve=999;
checkHndPv(HndPv::build($students,$subjects,array($otherStudent),'semester_1')['subjects']===array(), 'Notes of students outside the selected roster do not add courses');
foreach (HndPv::exams() as $key=>$exam) checkHndPv(in_array($exam['sequence'],array(3,4,5,6,9)), 'Semester, resit and annual exam choices');
$moduleSubject = (object)array('id_classe_matiere'=>12,'id_matiere'=>3,'semestre'=>1,'credit'=>1,'sessions'=>array(102));
$moduleNote = hndNote(3,90,'2026-01-31',102); $moduleNote->id_matiere=3; $moduleNote->id_classe_matiere=12;
$module = (object)array('id_module'=>20,'code'=>'UE20','nom'=>'Module test','nom_anglais'=>'Test module');
$modulePv = HndPv::build($students,array($subjects[0],$moduleSubject),array(hndNote(1,20,'2026-01-31'),hndNote(3,30,'2026-01-31'),$moduleNote),'semester_1','','',array(10=>$module,12=>$module));
checkHndPv(count($modulePv['modules'])===1 && $modulePv['modules'][0]['positions']===array(0,1), 'Configured module links to displayed courses');
checkHndPv($modulePv['rows'][0]['modules'][0]===60.0 && $modulePv['rows'][0]['average']===60.0, 'Module and overall averages weighted by credits');
checkHndPv($modulePv['rows'][0]['average20']===12.0, 'Validated weighted average converted to /20');
$rankStudents=array(); $rankNotes=array();
foreach (array(70,70,60,45,null) as $index=>$score) {
    $rankStudents[]=(object)array('id'=>$index+1);
    if($score!==null) { $note=hndNote(3,$score,'2026-01-31'); $note->id_eleve=$index+1; $rankNotes[]=$note; }
}
$rankPv=HndPv::build($rankStudents,array($subjects[0]),$rankNotes,'semester_1');
checkHndPv(array_column($rankPv['rows'],'rank')===array(1,1,3,4,null), 'Competition ranks with ties; incomplete students not ranked');
checkHndPv(array_column($rankPv['rows'],'average20')===array(14.0,14.0,12.0,null,null), 'Average displayed only after all credits validated');
checkHndPv($rankPv['rows'][3]['average']===45.0 && $rankPv['rows'][3]['decision']==='NV', 'Unvalidated average remains available only for internal ranking');
$moduleNote->sequence=1;
$incomplete = HndPv::build($students,array($subjects[0],$moduleSubject),array(hndNote(3,30,'2026-01-31'),$moduleNote),'semester_1','','',array(10=>$module,12=>$module));
checkHndPv($incomplete['rows'][0]['modules'][0]===null && $incomplete['rows'][0]['average']===null, 'Incomplete modules and overall averages are not invented');
checkHndPv(HndPv::layout('a4',26,4)['width'] < HndPv::layout('a3',26,4)['width'], 'Paper size changes available width');
checkHndPv(HndPv::layout('a4',26,4)['font'] < HndPv::layout('a3',26,4)['font'], 'Dense A4 and A3 adapt their type size');
checkHndPv(HndPv::layout('a4',26,4)['subjects_per_block']===4 && HndPv::layout('a3',26,4)['subjects_per_block']===6, 'Original four-column course groups remain readable in both paper formats');
$compiler = new Illuminate\View\Compilers\BladeCompiler(new Illuminate\Filesystem\Filesystem(),sys_get_temp_dir());
foreach (array('selection','sessions','print','annual') as $name) {
    $compiled=$compiler->compileString(file_get_contents(__DIR__.'/../resources/views/eleves/hnd_pv/'.$name.'.blade.php'));
    $file=tempnam(sys_get_temp_dir(),'hndpv'); file_put_contents($file,$compiled);
    exec('"'.PHP_BINARY.'" -l '.escapeshellarg($file),$output,$status); unlink($file);
    checkHndPv($status===0,'Compiled HND view: '.$name);
}
echo "HND PV semesters, resits, credits, dates, missing marks and views: OK\n";
