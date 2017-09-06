<?php namespace gesecole\Http\Controllers\admin;

use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\matiere;
use gesecole\parametres;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class programmeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('admin.dashboard')]);
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
        $titre=trans('admin.selectionner_classe');
        $m=classe::orderBy('nom')->get();
        $values= array('titre'=>$titre,'classes'=>$m);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;

        }
        return view('admin\selection_classe_programme',$values);
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
	    $c=classe::find($id);
        $titre=trans('admin.programme');
        $p=DB::select('SELECT cm.id,cm.id_matiere,nom_anglais,m.nom as nom_matiere,coef,max FROM classe_matiere cm, matiere m ,classe c WHERE cm.id_classe=c.id AND cm.id_matiere=m.id And c.id=?',[$id]);
        $m=DB::select('select m.id,m.nom,m.nom_anglais FROM matiere m WHERE  m.id NOT IN (SELECT cm.id_matiere FROM classe_matiere cm WHERE cm.id_classe=?)',[$id]);

        $values= array('titre'=>$titre,'programmes'=>$p);
        $values['classe']=$c;
        $values['matieres']=$m;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;

        }
        return view('admin\addProgram',$values);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
	    $this->validate($request,[
	       'id'=>'required|numeric',

        ]);
	    $id=$request->input('id');
	    //$matiereList=implode(',',$request->input('matiere'));
        //$coefList=implode(',',$request->input('coef'));
       // $maxList=implode(',',$request->input('max'));
        $matiereList=$request->input('matiere');
        $coefList=$request->input('coef');
        $maxList=$request->input('max');
        $i=0;
        foreach ($request->input('matiere') as $mat){
            $cm=new classeMatiere();
            $cm->id_classe=$id;
            $cm->id_matiere=$mat;
            $cm->coef=$coefList[$i];
            $cm->max=$maxList[$i];
            $cm->save();

            $i++;
        }

       // DB::insert('INSERT INTO classe_matiere (id_classe,id_matiere,coef,max) VALUES(?,?,?,?) ',[$id,$matiereList,$coefList,$maxList]);
        session(['success'=>true]);
        return redirect()->action('admin\programmeController@create',[$id]);
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
	public function destroy(Request $request,$id)
	{
	    $id_classe=$request->input('id');
		$cm=classeMatiere::find($id);
		$cm->delete();
        session(['success'=>true]);
        return redirect()->action('admin\programmeController@create',[$id_classe]);
	}

}
