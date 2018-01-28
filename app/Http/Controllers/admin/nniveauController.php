<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\NNiveau;
use Illuminate\Http\Request;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;

class nniveauController extends Controller {

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
        $titre=trans('admin.nniveau');
        $nn=NNiveau::paginate(20);
        $values= array('titre'=>$titre,'nniveaux'=>$nn);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }

        return view('admin\nniveaux',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request,[
		   'nom'=>'required',
        ]);
        $nn= new NNiveau();
        $nn->nom=$request->input('nom');
        $nn->save();
        session(['success'=>true]);
        return redirect()->action('admin\nniveauController@index');
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
	public function update(Request $request)
	{
        $this->validate($request,[
            'id'=>'required|numeric',
            'nom'=>'required',
        ]);
        $nn= NNiveau::find($request->input('id'));
        $nn->nom=$request->input('nom');
        $nn->save();
        session(['success'=>true]);
        return redirect()->action('admin\nniveauController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		NNiveau::destroy($id);
        session(['success'=>true]);
        return redirect()->action('admin\nniveauController@index');
	}

}
