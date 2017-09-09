<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\personnel;
use gesecole\salaire;
use Illuminate\Http\Request;

class salaireController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $titre=trans('admin.salaire');
        $m=salaire::paginate(15);
        $p=personnel::paginate(15);

        $values= array('titre'=>$titre,'salaire'=>$m);

        $values['personnel']=$p;

        return view('personnel\salaire',$values);
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
            'montant'=>'required|numeric',
            'id_personnel'=>'required|numeric'
    ]);
        $c=new salaire();
        $c->montant=$req->input('montant');
        $c->id_personnel=$req->input('id_personnel');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('personnel\salaireController@index');

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
            'montant'=>'required|numeric',
            'id_personnel'=>'required|numeric'
        ]);
        $c=salaire::find($id);
        $c->montant=$req->input('montant');
        $c->id_personnel=$req->input('id_personnel');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('personnel\salaireController@index');

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
