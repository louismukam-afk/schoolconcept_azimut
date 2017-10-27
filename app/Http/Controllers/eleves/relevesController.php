<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\eleve;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use gesecole\parametres;
use gesecole\classe;
use gesecole\niveau;
use gesecole\cycle;
use Illuminate\Support\Facades\DB;
use gesecole\note;
use gesecole\classeMatiere;
use gesecole\matiere;
use Illuminate\Http\Request;

class relevesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('eleve.big_title')]);
        $year=session('year');
        if($year==null){
            $p=parametres::whereNom('annee_scolaire')->get()->first();

            if(count($p)<=0){
                $p=new parametres();
                $p->nom='annee_scolaire';
                $p->valeur='2017-2018';
                $p->save();
                session(['year'=>$p->valeur]);
            }else{
                session(['year'=>$p->valeur]);
            }
        }
    }

	public function index()
	{
        $values= array('titre'=>trans('admin.releve_notes'));
        $c=classe::orderBy('nom')->get();
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $cy=cycle::all();
        $n=niveau::all();

        $values['niveaux']=$n;
        $values['classes']=$c;
        $values['cycles']=$cy;

        return view('eleves\classe_releves',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request,$id)
	{
	    $this->validate($request,[
	       "cycle"=>"required|numeric",
        ]);

	    $id_cycle=$request->input('cycle');
        $values= array('titre'=>trans('admin.releve_notes'));
        $id_eleves=array();
            $semestre=array(1,2);
            $sn=array(3,4);
            $cc=array(1,2);
            $rattrap=array(5,6);
            $sequences_pv=array(1,2,3,4,5,6);


            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle]);
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
            $cm = classeMatiere::whereIdClasse($id)->get();
            foreach ($cm as $entry){
                $sCoef+=$entry->coef;
            }
            $matiere_list=$cm->fetch('id_matiere')->toArray();
            //$m=matiere::whereIn('id',$matiere_list)->whereIn('id',$matieres_pv)->get();
        $m=matiere::whereIn('id',$matiere_list)->get();

            $k=0;
            foreach($e as $eleve){

                $l=0;
                $sNote=0;
                foreach ($m as $matiere){


                    $credit=$cm->where('id_matiere',$matiere->id)
                        ->first()
                        ->coef;
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
                                $noteS1=$note_rattrap1->note;
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
                                $noteS2=$note_rattrap2->note;
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
                        $sNote+=($this->getGrade($note)[1])*$credit;
                    }


                    $matieres[$l]=new pv_matiere($matiere->id,$note,$credit);


                    $l++;
                }
                //dump($matieres);
                $proces_v[$k]=new PV($eleve->id,$matieres,$sNote/$sCoef);
                $k++;
            }
            $classe=classe::find($id);
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['classe'] = $classe;
            $values['cycle']= cycle::find($classe->id_cycle);
            $values['niveau']= niveau::find($classe->id_niveau);
            $values['semestre'] = $semestre;

            return view('eleves\print_releves', $values);


	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


    function getGrade($note){
        if($note>=80){
            $grade[0]='A';
            $grade[1]=4;
            return $grade;
        }
        if($note>=75){
            $grade[0]='A-';
            $grade[1]=3.7;
            return $grade;
        }
        if($note>=70){
            $grade[0]='B+';
            $grade[1]=3.3;
            return $grade;
        }
        if($note>=65){
            $grade[0]='B';
            $grade[1]=3;
            return $grade;
        }
        if($note>=60){
            $grade[0]='B-';
            $grade[1]=2.7;
            return $grade;
        }
        if($note>=55){
            $grade[0]='C+';
            $grade[1]=2.3;
            return $grade;
        }
        if($note>=50){
            $grade[0]='C';
            $grade[1]=2;
            return $grade;
        }

        if($note>=45){
            $grade[0]='C-';
            $grade[1]=1.7;
            return $grade;
        }
        if($note>=40){
            $grade[0]='D+';
            $grade[1]=1.3;
            return $grade;
        }
        if($note>=35){
            $grade[0]='D';
            $grade[1]=1;
            return $grade;
        }
        if($note>=30){
            $grade[0]='E';
            $grade[1]=0;
            return $grade;
        }
        $grade[0]='F';
        $grade[1]=0;
        return $grade;

    }
}
class PV{
    public $eleve;
    public $matieres;
    public $mgp;
    public  function __construct($id_e,$ma,$mg)
    {
        $this->eleve=eleve::find($id_e);
        $this->matieres=$ma;
        $this->mgp=$mg;

    }
}
class pv_matiere{
    public $id_matiere;
    public $note;
    public $credit;

    public  function __construct($id_m,$not,$cr)
    {
        $this->id_matiere=$id_m;
        $this->note=$not;
        $this->credit=$cr;
    }

}