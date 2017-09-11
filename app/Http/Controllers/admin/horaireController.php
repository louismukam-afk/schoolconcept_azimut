<?php namespace gesecole\Http\Controllers\admin;

use gesecole\horaire;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use gesecole\parametres;
use Illuminate\Http\Request;

class horaireController extends Controller {

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
        $titre=trans('admin.horaire');
        $h=horaire::all();

        $values= array('titre'=>$titre,'horaire'=>$h);

        if(session('success')){
            Session::forget('success');
            $values['success']=true;

        }

        return view('admin\horaire',$values);
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
            'intervalle'=>'required',
            'type'=>'required|numeric',
        ]);
        $h=new horaire();
        $h->intervalle=$request->input('intervalle');
        $h->type=$request->input('type');
        $h->save();
        session(['success'=>true]);
        return redirect()->action('admin\horaireController@index');
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
        'intervalle'=>'required',
              'id'=>'required|numeric',
              'type'=>'required|numeric',
    ]);
        $h=horaire::find($request->input('id'));
        $h->intervalle=$request->input('intervalle');
        $h->type=$request->input('type');
        $h->save();
        session(['success'=>true]);
        return redirect()->action('admin\horaireController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        horaire::destroy($id);
        session(['success'=>true]);
        return redirect()->action('admin\horaireController@index');
	}

}
