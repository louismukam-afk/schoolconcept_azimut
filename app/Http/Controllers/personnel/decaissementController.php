<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\decaissement;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use Illuminate\Http\Request;

class decaissementController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
            'id_personnel'=>'required|numeric',
            'motif'=>'required',
            'montant=>required|numeric',
            'total'=>'required|numeric',



        ]);

        $c=new decaissement();
        $c->id_personnel=$req->input('id_personnel');
        $c->motif=$req->input('motif');
        $c->montant=$req->input('montant');
        $c->total=$req->input('total');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('personnel\decaissementController@index');
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
