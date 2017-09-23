<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\cycle;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use gesecole\matiere;
use gesecole\note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $cy=cycle::all();
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $values['classes']=$c;
        $values['cycles']=$cy;

        return view('eleves\classe_note',$values);
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
        $m=DB::select('SELECT m.id,m.nom FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? ORDER BY m.nom',[$id,$id_cycle]);
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $values['matieres']=$m;
        $values['id']=$id;
        $values['id_cycle']=$id_cycle;

        return view('eleves\matiere_note',$values);
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

}
