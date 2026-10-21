<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\HndPv;
function annualCheck($condition,$message) { if(!$condition)throw new RuntimeException($message); }
$subjects=array((object)array('id_classe_matiere'=>10,'id_matiere'=>100,'semestre'=>1,'credit'=>3,'sessions'=>array(1000)),
    (object)array('id_classe_matiere'=>20,'id_matiere'=>200,'semestre'=>2,'credit'=>2,'sessions'=>array(2000)));
$students=array(); foreach(range(1,5) as $id)$students[]=(object)array('id'=>$id);
function annualNotes($id,$subject,$map,$date='2026-01-31') {
    $rows=array(); foreach($map as $seq=>$value)$rows[]=(object)array('id_eleve'=>$id,'id_matiere'=>$subject->id_matiere,
        'id_classe_matiere'=>$subject->id_classe_matiere,'id_session'=>$subject->sessions[0],'sequence'=>$seq,'note'=>$value,'updated_at'=>$date);
    return $rows;
}
$notes=array();
foreach(array(1,3) as $id)$notes=array_merge($notes,annualNotes($id,$subjects[0],array(1=>20,3=>30,5=>40)),annualNotes($id,$subjects[1],array(2=>10,4=>60,6=>20)));
$notes=array_merge($notes,annualNotes(2,$subjects[0],array(1=>20,3=>30)),annualNotes(2,$subjects[1],array(2=>10,4=>30,6=>20)),
    annualNotes(4,$subjects[0],array(1=>20,3=>40)),annualNotes(5,$subjects[0],array(1=>5,5=>55)),annualNotes(5,$subjects[1],array(2=>10,6=>50)));
$pv=HndPv::build($students,$subjects,$notes,'annual');
annualCheck(count($pv['subjects'])===2 && $pv['credits']===5.0 && $pv['complete_year'],'Both semesters and annual planned credits');
annualCheck($pv['subjects'][0]->annual_semester===1 && $pv['subjects'][1]->annual_semester===2,'Course semester labels retained');
annualCheck(array_column($pv['rows'][0]['marks'],'retained')===array(60.0,70.0),'Best resit for S1 and best normal exam for S2');
annualCheck($pv['rows'][0]['average']===64.0 && $pv['rows'][0]['average20']===12.8 && $pv['rows'][0]['decision']==='ADMIS','Annual credit-weighted average and admission');
annualCheck($pv['rows'][1]['average']===46.0 && $pv['rows'][1]['average20']===null && $pv['rows'][1]['credits']===3.0 && $pv['rows'][1]['decision']==='ECHOUE','One failed course fails annual decision and hides average /20');
annualCheck($pv['rows'][3]['average']===null && $pv['rows'][3]['decision']==='ECHOUE','Missing course cannot be silently ignored');
annualCheck($pv['rows'][4]['average20']===12.0,'Resit-only courses retained in annual PV');
annualCheck(array_column($pv['rows'],'rank')===array(1,4,1,null,3),'Annual ranks and ties across both semesters');
$single=HndPv::build(array($students[0]),$subjects,annualNotes(1,$subjects[0],array(3=>70)),'annual');
annualCheck(!$single['complete_year'] && $single['rows'][0]['decision']==='ECHOUE' && $single['rows'][0]['average']===null,'Missing entire semester prevents false annual admission');
$zero=clone $subjects[1]; $zero->credit=0;
$zeroPv=HndPv::build(array($students[0]),array($subjects[0],$zero),array_merge(annualNotes(1,$subjects[0],array(3=>70)),annualNotes(1,$zero,array(4=>40))),'annual');
annualCheck($zeroPv['rows'][0]['decision']==='ECHOUE','Failed zero-credit course still prevents admission');
$period=HndPv::build($students,$subjects,$notes,'annual','2026-01-31','2026-01-31');
annualCheck($period['rows'][0]['average']===64.0,'Annual period includes normal and resit notes on boundary dates');
annualCheck(HndPv::layout('a4',26,0,true)['font']<HndPv::layout('a3',26,0,true)['font'],'Annual density adapts to selected paper');
echo "Annual HND PV: semester union, best normal/resit, weighted average, credits, decisions, ranks and missing marks: OK\n";
