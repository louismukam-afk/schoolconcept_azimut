$id=$request->input('id');
$this->validate($request,[
"cycle"=>"required|numeric",
"nniveau"=>"required|numeric",
]);
$proces_v=[];
$id_cycle=$request->input('cycle');
$nniveau=$request->input('nniveau');
$values= array('titre'=>trans('admin.releve_notes'));
$id_eleves=array();
$semestre=array(1,2);
$sn=array(3,4);
$cc=array(1,2);
$rattrap=array(5,6);
$sequences_pv=array(1,2,3,4,5,6);


$e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle,$nniveau]);
$j=0;
$matieres=array();
foreach ($e as $el){
$id_eleves[$j]=$el->id;
$j++;
}
$n_rattrap = note::whereAnneeScolaire(session('year'))
->whereIn('sequence',$rattrap)
->whereIn('id_eleve',$id_eleves)
->get();
$n_sn = note::whereAnneeScolaire(session('year'))
->whereIn('sequence',$sn)
->whereIn('id_eleve',$id_eleves)
->get();

$n_cc = note::whereAnneeScolaire(session('year'))
->whereIn('sequence',$cc)
->whereIn('id_eleve',$id_eleves)
->get();
$notes_sequences_pv=note::whereAnneeScolaire(\session('year'))
->whereIn('id_eleve',$id_eleves)
->whereIn('sequence',$sequences_pv)
->get();


$matieres_pv=$notes_sequences_pv->fetch('id_matiere')->toArray();
$sCoef=0;
$cm = classeMatiere::whereIdClasse($id)->where('id_nniveau','=',$nniveau)->where('id_cycle','=',$id_cycle)->get();
foreach ($cm as $entry){
$sCoef+=$entry->coef;
}

$matiere_list=$cm->fetch('id_matiere')->toArray();
//$m=matiere::whereIn('id',$matiere_list)->whereIn('id',$matieres_pv)->get();
$m=matiere::whereIn('matiere.id',$matiere_list)
->Join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
//->join('codages','codages.id','=','classe_matiere.id_code'   )
->where('id_classe','=',$id)
->where('id_cycle','=',$id_cycle)
->where('classe_matiere.id_nniveau','=',$nniveau)
->select(['matiere.*','id_code','semestre','type','classe_matiere.id AS id_classe_matiere'])

->get();

$k=0;
$gm=groupeMatieres::whereIdClasse($id)->where('id_nniveau','=',$nniveau)->get();
$m_cps=$gm->fetch('id_matiere_mere')->toArray();

$calc=$gm->fetch('id_classe_matiere')->toArray();
$m_cpst= classeMatiere::whereIn('id',$calc)->get()->fetch('id_matiere')->toArray();

$composes= array();
$composantes= array();
$cpstList= array();
$minus_credit_cpst=false;

foreach($e as $eleve){

$l=0;
$c=0;
$cst=0;
$sNote=0;
foreach ($m as $matiere){
$cps=false;
$cpst=false;

if($this->isIn($matiere->id,$m_cps)){
$cps=true;
$cpstList=$gm->where('id_matiere_mere',$matiere->id,false)->fetch('id_classe_matiere')->toArray();


}
$nom=\App::getLocale()=='en'?$matiere->nom_anglais:$matiere->nom;
if($this->isIn($matiere->id,$m_cpst))
$cpst=true;

$credit=$cm->where('id_matiere',$matiere->id)
->first()
->coef;
if ($cpst)
if(!$minus_credit_cpst) {
$sCoef -= $credit;
}

$note_cc1=$n_cc->where('id_matiere',$matiere->id,false)
->where('id_eleve',$eleve->id,false)
->where('sequence',1,false)
->first();

$note_cc2=$n_cc->where('id_matiere',$matiere->id,false)
->where('id_eleve',$eleve->id,false)
->where('sequence',2,false)
->first();

$note_sn1=$n_sn->where('id_matiere',$matiere->id,false)
->where('id_eleve',$eleve->id,false)
->where('sequence',3,false)
->first();
$note_sn2=$n_sn->where('id_matiere',$matiere->id,false)
->where('id_eleve',$eleve->id,false)
->where('sequence',4,false)
->first();
$note_rattrap1=$n_rattrap->where('id_matiere',$matiere->id,false)
->where('id_eleve',$eleve->id,false)
->where('sequence',5,false)
->first();
$note_rattrap2=$n_rattrap->where('id_matiere',$matiere->id,false)
->where('id_eleve',$eleve->id,false)
->where('sequence',6,false)
->first();
if($note_cc1!=null || $note_sn1!=null ){
@$note1=$note_cc1->note+$note_sn1->note;
if($note_rattrap1!=null){
if($note1>=50){
$noteS1=$note1;
}else{
$noteS1=($note_cc1?$note_cc1->note:0)+$note_rattrap1->note;
}
}else{
$noteS1=$note1;
}
}else{
if($note_rattrap1!=null){

$noteS1=$note_rattrap1->note;

}else{
$noteS1='-';
}

}
if($note_cc2!=null || $note_sn2!=null ){
@$note1=$note_cc2->note+$note_sn2->note;
if($note_rattrap2!=null){
if($note1>=50){
$noteS2=$note1;
}else{
$noteS2=($note_cc2?$note_cc2->note:0)+$note_rattrap2->note;
}
}else{
$noteS2=$note1;
}
}else{
if($note_rattrap2!=null){

$noteS2=$note_rattrap2->note;

}else{
$noteS2='-';
}

}
$note=0;
if(is_numeric($noteS1) && is_numeric($noteS2)){
$note=($noteS1+$noteS2)/2;

}elseif (!is_numeric($noteS2)){
$note=$noteS1;
}elseif (!is_numeric($noteS1)){
$note=$noteS2;
}

if(is_numeric($note)){
if((!$cpst) && (!$cps))
$sNote+=($note*$credit);

}



if((!$cpst) && (!$cps)){
$matieres[$l]=new pv1_matiere($matiere->id,$note,$credit,$nom,$matiere->type,$matiere->semestre,$matiere->id_code);

}

if($cpst){
$composantes[$cst]= new composante($matiere->id,$note,$matiere->id_classe_matiere,$nom,$credit);
$cst++;

}

if($cps){
$composes[$c]= new compose($matiere->id,$note,$credit,$cpstList,$nom,$credit,$matiere->type,$matiere->semestre,$matiere->id_code);
$c++;

}
$l++;

}
$minus_credit_cpst=true;
foreach($composes as $co){
$co_note=0;
$co_n=0;
$s_co_coef=0;
$lnoms='';
foreach ($composantes as $compt){
if($this->isIn($compt->id_classe_matiere,$co->cpsList)){
$co_note+=$compt->note;
$lnoms.=$compt->nom.' ,';
$s_co_coef+=$compt->credit;
$co_n++;
}


}

if($s_co_coef>0){
//$co_note=round($co_note/$s_co_coef,2);
//                    dump($co_note);
//                    die();
$co_note=$co_note/$co_n;


}

/* if($co_n>0){
$co_note=$co_note/$co_n;
}*/


// if(is_numeric($co->note))
// $co_note=round(($co_note+$co->note)/2,2);



if(sizeof($lnoms)>=1)
$lnoms='('.substr($lnoms,0,sizeof($lnoms)-2).')';

$sNote+=($co_note*$co->credit);
// $sNote+=$co_note;

$matieres[$l]=new pv1_matiere($co->id_matiere,$co_note,$s_co_coef,$co->nom.' '.$lnoms,$co->type,$co->semestre,$co->id_code);
$l++;

}


if($sCoef==0) $sCoef=1;
$proces_v[$k]=new PV1($eleve->id,$matieres,$sNote/$sCoef);
//           dump($proces_v);
//          die();

$k++;
}
$classe=classe::find($id);
$values['eleves'] = $e;
$values['PV'] = $proces_v;
$values['matieres'] = $m;
$values['nb_matieres'] = $m->count();
$values['classe'] = $classe;
$values['cycle']= cycle::find($id_cycle);
$values['nniveau']=NNiveau::find($nniveau);
$values['niveau']= niveau::find($classe->id_niveau);
$values['semestre'] = $semestre;