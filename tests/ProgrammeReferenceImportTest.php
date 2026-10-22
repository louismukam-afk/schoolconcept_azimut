<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\ProgrammeReferenceImport as Import;
function referenceCheck($value, $label) { if (!$value) throw new RuntimeException($label); }
$profile = array('id'=>'acc1','cycle'=>'HND','level'=>1,'class_codes'=>array('ACC'),
    'class_names'=>array('ACCOUNTANCY'),'subjects'=>array(array('semester'=>1,'name'=>'GENERAL COMPUTING I','code'=>'ACC 1112','type'=>2,'source'=>'reference.docx')));
$row = (object) array('id'=>1,'id_classe'=>10,'id_cycle'=>20,'id_nniveau'=>30,'semestre'=>1,'type'=>0,'id_code'=>'',
    'nom'=>'GENERAL COMPUTING I','nom_anglais'=>'');
$class = (object) array('id'=>10,'code'=>'-ACC','nom'=>'-ACCOUNTANCY');
$cycle = (object) array('id'=>20,'nom'=>'HND');
$level = (object) array('id'=>30,'id_cycle'=>20,'nom'=>'HIGHER NATIONAL DIPLOMA 1');
$plan = function ($rows, $p = null, $c = null, $l = null, $codes = array()) use ($profile, $class, $cycle, $level) {
    return Import::plan($rows,array($c ?: $class),array($cycle),array($l ?: $level),$codes,array('profiles'=>array($p ?: $profile)));
};
$result=$plan(array($row));
referenceCheck($result['rows'][0]['status']==='update' && $result['rows'][0]['new_code']==='ACC 1112' && $result['rows'][0]['new_type']===2,'Codes and explicit categories');
$same=clone $row; $same->id_code='ACC 1112'; $same->type=2;
referenceCheck($plan(array($same))['rows'][0]['status']==='unchanged','Idempotent');
$manual=clone $row; $manual->id_code='CUSTOM 1';
referenceCheck($plan(array($manual))['rows'][0]['status']==='existing_code_conflict','Preserve incompatible code');
$manual->id_code='ACC111';
referenceCheck($plan(array($manual))['rows'][0]['new_code']==='ACC 1112','Expand parent module code to exact subject code');
$manual->id_code=''; $manual->type=1;
referenceCheck($plan(array($manual))['rows'][0]['status']==='existing_category_conflict','Preserve explicit conflicting category');
$excel=$profile; $excel['subjects'][0]['type']=null;
referenceCheck($plan(array($manual),$excel)['rows'][0]['new_type']===1,'Excel preserves existing category');
$wrong=clone $class; $wrong->code='CGE';
referenceCheck(count($plan(array($row),null,$wrong)['rows'])===0,'Never cross specialties');
$wrongLevel=clone $level; $wrongLevel->nom='HIGHER NATIONAL DIPLOMA 2';
referenceCheck(count($plan(array($row),null,null,$wrongLevel)['rows'])===0,'Never cross levels');
$wrongCycle=clone $level; $wrongCycle->id_cycle=99;
referenceCheck(count($plan(array($row),null,null,$wrongCycle)['rows'])===0,'Never cross cycles');
$wrongSemester=clone $row; $wrongSemester->semestre=2;
referenceCheck($plan(array($wrongSemester))['rows'][0]['status']==='unmatched_subject','Never cross semesters');
$secondProfile=$profile; $secondProfile['level']=2;
$third=clone $row; $third->semestre=3;
referenceCheck($plan(array($third),$secondProfile,null,$wrongLevel)['rows'][0]['status']==='update','Global semester 3 is first semester of level II');
$duplicate=clone $row; $duplicate->id=2;
referenceCheck($plan(array($row,$duplicate))['summary']['ambiguous_programme']===2,'Duplicate programme rows are excluded');
$conflict=$profile; $conflict['subjects'][]=$conflict['subjects'][0]; $conflict['subjects'][1]['code']='ACC 1199';
referenceCheck($plan(array($row),$conflict)['rows'][0]['status']==='source_conflict','Contradictory document codes excluded');
$conflict=$profile; $conflict['subjects'][]=$conflict['subjects'][0]; $conflict['subjects'][1]['name']='OTHER SUBJECT';
referenceCheck($plan(array($row),$conflict)['rows'][0]['status']==='source_conflict','Same code on different source subjects excluded');
referenceCheck(Import::normalize('MathÃ©matiques GÃ©nÃ©rales I')===Import::normalize('Mathématiques générales I'),'Match legacy encoding without changing stored names');
referenceCheck(Import::normalize('ANALYSIS I')!==Import::normalize('ANALYSIS II'),'Keep semester suffixes');
$foreign=clone $row; $foreign->id_code='123'; $foreign->type=2;
referenceCheck($plan(array($foreign),null,null,null,array((object)array('id'=>123,'nom'=>'ACC 1112')))['rows'][0]['status']==='unchanged','Existing codage foreign key recognized');
$catalogue=Import::catalogue();
referenceCheck(count($catalogue['profiles'])===10,'All ten specialty/level references included');
foreach($catalogue['profiles'] as $p) {
    foreach($p['subjects'] as $subject) {
        if(substr($subject['source'],-5)==='.xlsx') referenceCheck($subject['type']===null,'No inferred spreadsheet category');
        else referenceCheck(in_array($subject['type'],array(0,1,2),true),'Word category taken from explicit heading');
        referenceCheck(isset($subject['code'],$subject['name'],$subject['semester']),'Sanitized programme metadata');
    }
}
echo 'Programme references: scope, categories, codes, ambiguity, encoding and idempotence OK'.PHP_EOL;
