<?php namespace gesecole\Http\Controllers;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\parametres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class startController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('start');
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
            'initiales'=>'required|max:2|min:2',
            'adresse'=>'required',
            'numero'=>'required',
            'annee_scolaire'=>'required|max:9|min:9',
        ]);

		$nom_e=parametres::firstOrNew(['nom'=>'nom_etablissement']);
        $annee=parametres::firstOrNew(['nom'=>'annee_scolaire']);
        $initiales=parametres::firstOrNew(['nom'=>'initiales_etablissement']);
        $numero=parametres::firstOrNew(['nom'=>'telephone']);
        $adresse=parametres::firstOrNew(['nom'=>'adresse']);

        $nom_e->valeur=$request->input('nom');
        $nom_e->save();

        $annee->valeur=$request->input('annee_scolaire');
        $annee->save();

        $initiales->valeur=$request->input('initiales');
        $initiales->save();

        $numero->valeur=$request->input('numero');
        $numero->save();

        $adresse->valeur=$request->input('adresse');
        $adresse->save();

        $web=parametres::firstOrNew(['nom'=>'web']);
        $email=parametres::firstOrNew(['nom'=>'email']);

        $web->valeur=$request->input('web');
        $web->save();

        $img=$request->file('logo');
        if ($img!=null)
            Image::make($img->getRealPath())->save('img/logo.png');

        $email->valeur=$request->input('email');
        $email->save();
        session(['start_part_2'=>true]);

        return redirect()->action('startController@index');
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
