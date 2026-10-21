<?php namespace gesecole\Http\Controllers\admin;

use gesecole\fonction;
use gesecole\fonction_personnel;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use Illuminate\Http\Request;

class fonctionController extends Controller {

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
	public function index(Request $req)
	{
        $titre='Fonction';
        $fonction=fonction::paginate(15);
        $values= array('titre'=>$titre,'fonction'=>$fonction);

        return view('admin/fonctions',$values);
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
            'nom'=>'required'
        ]);
        $c=new fonction();
        $c->nom=$req->input('nom');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('admin\fonctionController@index');

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
            'nom'=>'required'
        ]);
        $c=fonction::find($id);
        $c->nom=$req->input('nom');
        $c->save();
        return redirect()->action('admin\fonctionController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $c=fonction::find($id);
        $fp=fonction_personnel::whereIdFonction($id);
        foreach ($fp as $fps){
            $fps->delete();

        }
        $c->delete();
        session(['success'=>true]);
        return redirect()->action('admin\fonctionController@index');
	}

}
