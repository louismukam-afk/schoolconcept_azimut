<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\ProgrammeReferenceImport as Import;
function extendedCheck($condition, $message) { if (!$condition) throw new RuntimeException($message); }
$catalogue=Import::catalogue('programme_references_extended_20261022.json');
extendedCheck($catalogue['version']===2 && count($catalogue['profiles'])===48,'All specialty/level profiles');
$sources=array(); $counts=array(0=>0,1=>0,2=>0);
foreach($catalogue['source_inventory'] as $source) $sources[$source['source']]=true;
extendedCheck(count($sources)===60,'All sixty input files accounted for');
foreach($catalogue['profiles'] as $profile) {
    foreach($profile['subjects'] as $subject) {
        extendedCheck(!preg_match('/echantillon|SEM1 23|RESULT LEV/i',$subject['source']),'No grades or example template imported');
        if($subject['type']!==null) $counts[$subject['type']]++;
        if($subject['category_basis']==='approved_module_code_convention') {
            $module=preg_replace('/\D/','',$subject['module']);
            $group=(int)substr($module,-1);
            extendedCheck($subject['type']===($group<=2?0:($group<=6?2:1)),'Approved module category rule');
            extendedCheck(strpos(preg_replace('/\D/','',$subject['code']),$module)===0,'Category belongs to actual parent module');
        }
        if($subject['category_basis']==='unclassified_short_code') extendedCheck($subject['type']===null,'Unknown exam category is preserved');
    }
}
foreach($counts as $count) extendedCheck($count>100,'All three categories represented');
$row=(object)array('id'=>1,'id_classe'=>10,'id_cycle'=>20,'id_nniveau'=>30,'semestre'=>1,'type'=>1,'id_code'=>'','nom'=>'NETWORKS','nom_anglais'=>'');
$class=(object)array('id'=>10,'code'=>'NWS','nom'=>'NETWORKS AND SECURITY');
$cycle=(object)array('id'=>20,'nom'=>'HND'); $level=(object)array('id'=>30,'id_cycle'=>20,'nom'=>'HIGHER NATIONAL DIPLOMA 1');
$profile=array('id'=>'nws1','cycle'=>'HND','level'=>1,'class_codes'=>array('NWS'),'class_names'=>array('NETWORKS AND SECURITY'),
    'subjects'=>array(array('semester'=>1,'name'=>'NETWORKS','code'=>'NWS 1131','type'=>2,'source'=>'reference.xlsx','category_basis'=>'approved_module_code_convention')));
$plan=function($rows,$mode,$p=null,$l=null,$version=2)use($row,$class,$cycle,$level,$profile){return Import::plan($rows,array($class),array($cycle),array($l?:$level),array(),array('version'=>$version,'mode'=>$mode,'profiles'=>array($p?:$profile)));};
$codes=$plan(array($row),'codes')['rows'][0];
extendedCheck($codes['status']==='update' && $codes['new_code']==='NWS 1131' && $codes['new_type']===1,'Code migration preserves manual category');
extendedCheck($plan(array($row),'categories')['rows'][0]['status']==='existing_category_conflict','Category migration protects conflicting explicit category');
$fund=clone $row; $fund->type=0;
$types=$plan(array($fund),'categories')['rows'][0];
extendedCheck($types['status']==='update' && $types['new_code']==='' && $types['new_type']===2,'Category migration changes only category');
$unknown=$profile; $unknown['subjects'][0]['type']=null;
extendedCheck($plan(array($fund),'categories',$unknown)['rows'][0]['status']==='category_unclassified','Short code without category is reported');
$duplicate=clone $row; $duplicate->id=2;
$duplicates=$plan(array($fund,$duplicate),'categories')['rows'];
extendedCheck($duplicates[0]['status']==='ambiguous_programme' && $duplicates[1]['status']==='existing_category_conflict','Manual conflicts cannot hide a duplicate programme row');
$third=clone $fund; $third->semestre=5;
$thirdProfile=$profile; $thirdProfile['level']=3;
$thirdLevel=clone $level; $thirdLevel->nom='HIGHER NATIONAL DIPLOMA III';
extendedCheck($plan(array($third),'codes',$thirdProfile,$thirdLevel)['rows'][0]['status']==='update','Level III / semester 5 supported by extended catalogue');
extendedCheck($plan(array($third),'codes',$thirdProfile,$thirdLevel,1)['rows'][0]['status']==='missing_semester','First catalogue semester policy unchanged');
echo 'Extended references: sixty files, three categories, separate migrations, duplicates and third year OK'.PHP_EOL;
