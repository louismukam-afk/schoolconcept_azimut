<?php namespace gesecole\Http\Controllers\admin;

use gesecole\classe;
use gesecole\dispense;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\matiere;
use gesecole\NNiveau;
use gesecole\personnel;
use gesecole\taux;
use Illuminate\Support\Facades\Session;
use gesecole\parametres;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class tauxController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('admin.dashboard')]);
        $this->renewSchoolYear();
    }
	public function index()
	{
        $titre=trans('admin.taux');
        $values= array('titre'=>$titre);
        return view('admin/taux',$values);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
        $titre=trans('admin.taux');
        $t=taux::where('id_personnel','=',$id)->orderBy('id_classe')->orderBy('id_matiere')->orderBy('id_nniveau')->get();
        $p=personnel::find($id);

        $d=dispense::whereAnneeScolaire(\session('year'))->where('id_personnel','=',$id)->distinct()->get(['id_classe','id_matiere','id_nniveau']);

        foreach ($t as $taux)
        {
            foreach ($d as $key=>$dis)
            {


                if(($taux->id_matiere==$dis->id_matiere) && ($taux->id_classe==$dis->id_classe) && ($taux->id_nniveau==$dis->id_nniveau))
                {
                    unset($d[$key]);
                    break;
                }
            }
        }


        $c=classe::all();
        $m=matiere::all();
        $nn=NNiveau::all();


        $values= array('titre'=>$titre);
        $values['taux']=$t;
        $values['matieres']=$m;
        $values['classes']=$c;
        $values['nniveaux']=$nn;
        $values['dispenses']=$d;
        $values['personnel']=$p;



        if(session('success')){
            Session::forget('success');
            $values['success']=true;

        }
        return view('admin/taux_enseignant',$values);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request,[
		   'id_personnel'=>'required|numeric',
        ]);
        $id=$request->input('id_personnel');
        $matiereList=$request->input('matiere');
        $tauxList=$request->input('taux');
        $classeList=$request->input('classe');
        $nniveauList=$request->input('nniveau');

        $i=0;
        foreach ($matiereList as $mat){
            if(($mat==0) &&($nniveauList[$i]==0) && ($classeList[$i]==0)){
                $t=taux::firstOrNew(['id_personnel'=>$id,'id_matiere'=>0,'id_classe'=>0,'id_nniveau'=>0]);
                $t->taux=$tauxList[$i];
                $t->save();
            }else
            {
                $t=new taux();
                $t->id_personnel=$id;
                $t->id_classe=$classeList[$i];
                $t->id_matiere=$mat;
                $t->id_nniveau=$nniveauList[$i];
                $t->taux=$tauxList[$i];
                $t->save();
            }


            $i++;
        }



        \session(['success'=>true]);
        return redirect()->action('admin\tauxController@create',['id'=>$id]);
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
		$t=taux::find($id);
		$t->delete();
        \session(['success'=>true]);
        return redirect()->action('admin\tauxController@create',[$t->id_personnel]);
	}

}
