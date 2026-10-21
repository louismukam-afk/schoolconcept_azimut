<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\ExamSessionNote;
function checkExamNote($condition,$message){if(!$condition)throw new RuntimeException($message);}
$config=(object)array('cc1'=>20,'tpe1'=>10,'sn1'=>70,'ratrappage1'=>70,'cc2'=>30,'tpe2'=>0,'sn2'=>70,'ratrappage2'=>0);
$first=ExamSessionNote::phases($config,1);
checkExamNote(array_keys($first)===array(1,7,3,5),'First-semester configured phases');
checkExamNote(array_keys(ExamSessionNote::phases($config,4))===array(2,4,6),'Second semester and resit fallback');
checkExamNote(count(ExamSessionNote::phases($config,0))===7,'Annual subject phases');
checkExamNote(ExamSessionNote::phases($config,2)[6]->weight===70.0 && ExamSessionNote::phases($config,2)[6]->usesNormalWeight,'Unconfigured resit uses normal exam weighting');
checkExamNote(!$first[5]->usesNormalWeight,'Explicit resit weighting preserved');
$config->ratrappage1=60;
checkExamNote(ExamSessionNote::phases($config,1)[5]->weight===60.0,'Explicit resit percentage takes precedence');
checkExamNote(ExamSessionNote::weightedMark(15,20)===15.0,'20 percent weighting');
checkExamNote(ExamSessionNote::weightedMark(15,70)===52.5,'70 percent weighting');
checkExamNote(ExamSessionNote::weightedMark(0,30)===0.0,'Zero is a valid mark');
foreach(array(-1,21,'invalid') as $mark){$rejected=false;try{ExamSessionNote::weightedMark($mark,30);}catch(InvalidArgumentException $e){$rejected=true;}checkExamNote($rejected,'Invalid mark rejected');}
echo "Configured phases, semesters, weights and note boundaries: OK\n";
