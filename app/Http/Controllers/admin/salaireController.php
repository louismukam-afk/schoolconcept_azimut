<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use gesecole\personnel;
use gesecole\salaire;
use Illuminate\Http\Request;

class salaireController extends Controller {

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
        $titre=trans('admin.salaire');
        $m=salaire::all();
        $arr=[];
        foreach ($m as $s){
            array_push($arr,$s->id_personnel);
        }
        $p=personnel::all();
        $p1=personnel::whereNotIn('id',$arr)->get();

        $values= array('titre'=>$titre,'salaire'=>$m);

        $values['personnel']=$p;
        $values['personnel1']=$p1;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;

        }

        return view('admin\salaire',$values);
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
	public function store(Request $req)
	{
        $this->validate($req,[
            'montant'=>'numeric',
            'id_personnel'=>'required|numeric',
            'type'=>'required|numeric',
    ]);
        $c=new salaire();
        $c->montant=$req->input('montant');
        $c->type=$req->input('type');
        $c->id_personnel=$req->input('id_personnel');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('admin\salaireController@index');

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
	public function update(Request $req,$id)
	{
        $this->validate($req,[
            'montant'=>'numeric',
            'id_personnel'=>'required|numeric',
            'type'=>'required|numeric',

        ]);
        $c=salaire::find($id);
        $c->montant=$req->input('montant');
        $c->type=$req->input('type');
        $c->id_personnel=$req->input('id_personnel');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('admin\salaireController@index');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		salaire::destroy($id);
        session(['success'=>true]);
        return redirect()->action('admin\salaireController@index');
	}

}
