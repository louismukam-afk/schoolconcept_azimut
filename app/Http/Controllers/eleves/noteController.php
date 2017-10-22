<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\cycle;
use gesecole\dispense;
use gesecole\eleve;
use gesecole\niveau;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\verrouNote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use gesecole\matiere;
use gesecole\note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use gesecole\parametres;


class noteController extends Controller {

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
        $values= array('titre'=>'Notes');
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

        return view('eleves\classe_note',$values);
	}
    public function index2()
    {
        $values= array('titre'=>'Notes');


        return view('eleves\notes_index',$values);
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
    public function matiere(Request $request,$id)
    {
        $id_cycle=$request->input('cycle');
        $values= array('titre'=>'Notes');
        $m=DB::select('SELECT m.* FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? ORDER BY m.nom',[$id,$id_cycle]);
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('erreur')){
            $values['erreur']=session('erreur');
            Session::forget('erreur');
        }
        $values['matieres']=$m;
        $values['id']=$id;
        $values['id_cycle']=$id_cycle;

        return view('eleves\matiere_note',$values);
    }
    public function matiere_show(Request $request,$id)
    {
        $id_cycle=$request->input('cycle');
        $values= array('titre'=>'Notes');
        $m=DB::select('SELECT m.* FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? ORDER BY m.nom',[$id,$id_cycle]);
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('erreur')){
            $values['erreur']=session('erreur');
            Session::forget('erreur');
        }
        $values['matieres']=$m;
        $values['id']=$id;
        $values['id_cycle']=$id_cycle;

        return view('eleves\matiere_note_show',$values);
    }

	public function create(Request $request,$id)
	{
	    $this->validate($request,[
	        'sequence'=>'required|numeric',
            'cycle'=>'required|numeric',
        ]);


        $values= array('titre'=>'Notes');
        $sequence=$request->input('sequence');
        $id_m=$request->input('id');
        $id_cycle=$request->input('cycle');

        $v=verrouNote::whereAnneeScolaire(\session('year'))
            ->where('sequence','=',$sequence)
            ->get();

        if($v->count()>0){
            session(['erreur'=>trans('admin.mod_locked')]);
            return redirect()->to('/eleves/notes/matieres/'.$id.'?cycle='.$id_cycle);
        }

        $user=Auth::user();
        if($user->role==18.5){
            $d=dispense::whereAnneeScolaire(\session('year'))
                ->where('id_personnel','=',$user->id_personnel)
                ->where('id_matiere','=',$id_m)
                ->where('id_classe','=',$id)
                ->first();
            if($d==null){
                $mat=matiere::find($id_m);
                session(['erreur'=>trans('admin.no_modification',['matiere'=>$mat->nom.'('.$mat->nom_anglais.')'])]);
                return redirect()->to('/eleves/notes/matieres/'.$id.'?cycle='.$id_cycle);
            }
        }

        $mat=matiere::find($id_m);
        $cm=classeMatiere::whereIdClasse($id)->where('id_matiere','=',$id_m)->where('id_cycle','=',$id_cycle)->first();

		$e=DB::select('SELECT e.id,e.nom,e.prenom FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? ORDER BY e.nom,e.prenom',[session('year'),$id,$id_cycle]);
		$n=note::whereAnneeScolaire(session('year'))
            ->where('id_matiere','=',$id_m)
            ->where('sequence','=',$sequence)
            ->get();

		$values['eleves']=$e;
        $values['notes']=$n;
        $values['max']=$cm->max;
        $values['matiere']=$mat;
        $values['id_cycle']=$id_cycle;
        $values['id_classe']=$id;

        $values['sequence']=$sequence;
        return view('eleves\eleves_notes',$values);
	}

    public function print_note(Request $request,$id)
    {
        $this->validate($request,[
            'sequence'=>'required|numeric',
            'cycle'=>'required|numeric',
        ]);


        $values= array('titre'=>'Notes');
        $sequence=$request->input('sequence');
        $id_m=$request->input('id');
        $id_cycle=$request->input('cycle');

        if($sequence==1 || $sequence==2) {
            $mat = matiere::find($id_m);
            $cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->first();

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle]);
            $j=0;
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve',$id_eleves)
                ->get();


            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);

            $values['sequence'] = $sequence;

            return view('eleves\print_note_cc', $values);
        }

        if($sequence==3 || $sequence==4) {
            if($sequence==3)
                $cc=1;
            else
                $cc=2;
            $mat = matiere::find($id_m);
            //$cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->first();
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle]);
            $j=0;
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve',$id_eleves)
                ->get();

            $tns=note::whereAnneeScolaire(\session('year'))->where('sequence','=',$cc)->where('id_matiere','=',$id_m)->whereIn('id_eleve',$id_eleves)->first();
            if($tns==null){
                session(['erreur'=>trans('admin.no_cc',['matiere'=>$mat->nom.'('.$mat->nom_anglais.')'])]);
                return redirect()->to('/eleves/notes/matieres/show/'.$id.'?cycle='.$id_cycle);
            }
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;
            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            if($sequence==3)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves\print_note_sn', $values);
        }
        if($sequence==5 || $sequence==6) {
            if($sequence==5) {
                $cc = 1;
                $sn = 3;
            }
            else {
                $cc = 2;
                $sn=4;
            }
            $mat = matiere::find($id_m);
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle]);
            $j=0;
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $id_eleves2=$n->fetch('id_eleve')->toArray();
            $e=eleve::whereIn('id',$id_eleves2)->orderBy('nom')->orderBy('prenom')->get();


            $tns=note::whereAnneeScolaire(\session('year'))->where('sequence','=',$cc)->where('id_matiere','=',$id_m)->whereIn('id_eleve',$id_eleves)->first();
            $tnr=note::whereAnneeScolaire(\session('year'))->where('sequence','=',$sn)->where('id_matiere','=',$id_m)->whereIn('id_eleve',$id_eleves)->first();

            if($tns==null || $tnr==null){
                session(['erreur'=>trans('admin.no_note',['matiere'=>$mat->nom.'('.$mat->nom_anglais.')'])]);
                return redirect()->to('/eleves/notes/matieres/show/'.$id.'?cycle='.$id_cycle);
            }

            $values['eleves'] = $e;
            $values['notes'] = $n;
            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            if($sequence==5)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves\print_note_rat', $values);
        }
        if($sequence==7 || $sequence==8) {
            if($sequence==7){
                $semestre=1;
                $sn=3;
                $cc=1;
                $rattrap=5;
                $sequences_pv=array(1,3,5);
            }else{
                $semestre=2;
                $sn=4;
                $cc=2;
                $rattrap=6;
                $sequences_pv=array(2,4,6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle]);
            $j=0;
            $matieres=array();
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $rattrap)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $sn)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $cc)
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
            $m=matiere::whereIn('id',$matiere_list)->whereIn('id',$matieres_pv)->get();

            $k=0;
            foreach($e as $eleve){

                $l=0;
                $sNote=0;
                foreach ($m as $matiere){


                    $credit=$cm->where('id_matiere',$matiere->id)
                        ->first()
                        ->coef;
                    $note_cc=$n_cc->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->first();
                    $note_sn=$n_sn->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->first();
                    $note_rattrap=$n_rattrap->where('id_matiere',$matiere->id,false)
                            ->where('id_eleve',$eleve->id,false)
                            ->first();
                    if($note_cc!=null || $note_sn!=null ){
                        @$note1=$note_cc->note+$note_sn->note;
                        if($note_rattrap!=null){
                            if($note1>=50){
                                $note=$note1;
                            }else{
                                $note=$note_rattrap->note;
                            }
                        }else{
                            $note=$note1;
                        }
                    }else{
                        if($note_rattrap!=null){

                                $note=$note_rattrap->note;

                        }else{
                            $note='-';
                        }

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
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['classe'] = classe::find($id);
            $values['semestre'] = $semestre;

            return view('eleves\print_pv', $values);

        }
        if($sequence==9) {
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
            $m=matiere::whereIn('id',$matiere_list)->whereIn('id',$matieres_pv)->get();

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
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['classe'] = classe::find($id);
            $values['semestre'] = $semestre;

            return view('eleves\print_bilan', $values);
        }
    }

    /**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request,[
            'id_matiere'=>'required|numeric',
            'sequence'=>'required|numeric',
            'id_classe'=>'required|numeric',

        ]);
        $id_cycle=$request->input('cycle');
        $id_matiere=$request->input('id_matiere');
        $sequence=$request->input('sequence');
        $id_classe=$request->input('id_classe');

        $eleveList=$request->input('eleve');
        $noteList=$request->input('note');
        $i=0;
        if($eleveList==null){
            session(['success'=>true]);

            return redirect()->to('/eleves/notes/matieres/'.$id_classe.'?cycle='.$id_cycle);
        }
        foreach ($eleveList as $e){
            $n=note::firstOrNew(['annee_scolaire'=>session('year'),'id_matiere'=>$id_matiere,'sequence'=>$sequence,'id_eleve'=>$e]);

                $n->id_matiere=$id_matiere;
                $n->sequence=$sequence;
                $n->id_eleve=$e;
                $n->annee_scolaire=session('year');
                $n->note=$noteList[$i];
                $n->save();

            $i++;
        }

        session(['success'=>true]);

        return redirect()->to('/eleves/notes/matieres/'.$id_classe.'?cycle='.$id_cycle);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
        $values= array('titre'=>'Notes');
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

        return view('eleves\classe_note_show',$values);
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
    public $id_eleve;
    public $matieres;
    public $mgp;
    public  function __construct($id_e,$ma,$mg)
    {
        $this->id_eleve=$id_e;
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
