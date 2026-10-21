<?php
require __DIR__.'/../vendor/autoload.php';
use gesecole\Services\SoaPvSummary;
function checkSoa($condition, $message) { if (!$condition) throw new RuntimeException($message); }
$subject=function($id,$note,$credits) { return (object) array('id_matiere'=>$id,'note'=>$note,'credit'=>$credits); };
$pv=function($id,$mean,$subjects) { return (object) array('id_eleve'=>$id,'mgp'=>$mean,'matieres'=>$subjects); };
$transcripts=array(
    $pv(10,60,array($subject(1,60,3),$subject(2,60,2))),
    $pv(20,80,array($subject(1,80,3),$subject(2,80,2))),
    $pv(30,80,array($subject(1,80,3),$subject(2,80,2))),
    $pv(40,40,array($subject(1,50,3),$subject(2,25,2))),
    $pv(50,0,array($subject(1,'-',3))),
);
$summary=SoaPvSummary::build($transcripts);
checkSoa(array_keys($summary)===array(10,20,30,40,50),'Original student order preserved');
checkSoa($summary[10]->rank===3 && $summary[20]->rank===1 && $summary[30]->rank===1 && $summary[40]->rank===4,'Competition ranks and ties');
checkSoa($summary[10]->average===12.0 && $summary[10]->validated==5 && $summary[10]->admitted,'Average and validated credits');
checkSoa($summary[40]->validated==3 && !$summary[40]->admitted,'Credit validation and admission');
checkSoa($summary[50]->average===null && $summary[50]->rank===null,'Missing marks are not ranked');
$boundary=SoaPvSummary::build(array($pv(1,50,array($subject(1,50,3))),$pv(2,49.99,array($subject(1,49.99,3)))));
checkSoa($boundary[1]->admitted && !$boundary[2]->admitted && $boundary[2]->validated==0,'Admission boundary before rounding');
$duplicate=SoaPvSummary::build(array($pv(1,60,array($subject(1,60,3),$subject(1,60,3)))));
checkSoa($duplicate[1]->validated==3,'No double counting of validated credits');
echo "Soa averages, validated credits, admission, ties and unchanged row order: OK\n";
