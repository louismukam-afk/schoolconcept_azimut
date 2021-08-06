<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use gesecole\personnel;
use gesecole\salaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class salaireController extends Controller {

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
        $titre=trans('admin.salaire');
        $m=salaire::orderBy('montant','desc')->get();
        $arr=[];
        foreach ($m as $s){
            array_push($arr,$s->id_personnel);
        }
        $p=personnel::orderBy('nom')->get();
        $p1=personnel::whereNotIn('id',$arr)->orderBy('nom')->get();

        $values= array('titre'=>$titre,'salaire'=>$m);

        $values['personnel']=$p;
        $values['personnel1']=$p1;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;

        }

        return view('admin/salaire',$values);
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

        $current_user = \Auth::user();
        $c->id_creator = $current_user->id;
        $c->edition_details = 'montant : ' . $c->montant;
        $c->id_last_editor = $current_user->id;

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
	public function update(Request $req)
	{
        $this->validate($req,[
            'id'=>'numeric|required',
            'montant'=>'numeric|required',
            'type'=>'required|numeric',

        ]);
        $id = $req->input('id');
        $c=salaire::find($id);
        $c->montant=$req->input('montant');
        $c->type=$req->input('type');

        $current_user = \Auth::user();
        $c->id_last_editor = $current_user->id;

        $c->save();
        session(['success'=>true]);
        return redirect()->to(URL::previous());

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
