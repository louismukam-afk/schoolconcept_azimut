<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\HndPv;
use gesecole\Services\AdmissionList;
function checkAdmission($ok, $message) { if (!$ok) throw new RuntimeException($message); }
$students = array();
foreach (range(1, 4) as $id) $students[] = (object) array('id'=>$id,'nom'=>'Etudiant '.$id,'prenom'=>'','matricule'=>'TEST'.$id);
$subjects = array();
foreach (range(1, 2) as $id) $subjects[] = (object) array('id_classe_matiere'=>$id,'id_matiere'=>$id,'sessions'=>array($id),'credit'=>3);
$notes = array();
foreach (array(1=>array(60,60),2=>array(90,40),3=>array(60,60),4=>array(70,null)) as $id=>$marks) {
    foreach ($marks as $index=>$mark) if ($mark !== null) $notes[] = (object) array('id_eleve'=>$id,'id_matiere'=>$index+1,'id_classe_matiere'=>$index+1,'id_session'=>$index+1,'sequence'=>3,'note'=>$mark,'updated_at'=>'2026-01-01');
}
$normal = HndPv::build($students,$subjects,$notes,'semester_1');
$admitted = AdmissionList::admitted($normal);
checkAdmission(array_map(function($r){return $r['student']->id;},$admitted)===array(1,3),'Partial credits and missing marks excluded despite high average');
checkAdmission($admitted[0]['rank']===$admitted[1]['rank'],'Existing tied ranks preserved');
$notes[]=(object)array('id_eleve'=>2,'id_matiere'=>2,'id_classe_matiere'=>2,'id_session'=>2,'sequence'=>5,'note'=>60,'updated_at'=>'2026-01-02');
$resit=HndPv::build($students,$subjects,$notes,'resit_1','','',array(),true);
checkAdmission(count(AdmissionList::admitted($resit))===3,'Resit retains normal passes across the entire semester');
checkAdmission(count(AdmissionList::admitted(HndPv::build($students,$subjects,$notes,'semester_1')))===2,'Normal session ignores later resit scores');
checkAdmission(AdmissionList::admitted(array('credits'=>0,'rows'=>array()))===array(),'Empty programme is not an admission');
echo "Admission lists: complete validation, missing marks, ties, normal/resit separation: OK\n";
