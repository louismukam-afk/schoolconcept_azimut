<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use gesecole\Jour;
use Illuminate\Http\Request;

class jourController extends Controller {

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
        $titre = "Gestion des jours";

        $jours = Jour::orderBy('id')->get();

        $values = array(
            'titre' => $titre,
            'jours' => $jours
        );

        if(session('success')){
            Session::forget('success');
            $values['success'] = true;
        }

        if(session('error')){
            $values['error'] = session('error');
            Session::forget('error');
        }

        return view('admin.jours',$values);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nom' => 'required'
        ]);

        $j = new Jour();
        $j->nom = $request->input('nom');
        $j->observation = $request->input('observation');
        $j->save();

        session(['success'=>true]);

        return redirect()->action('admin\jourController@index');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'nom' => 'required'
        ]);

        $j = Jour::findOrFail($request->input('id'));
        $j->nom = $request->input('nom');
        $j->observation = $request->input('observation');
        $j->save();

        session(['success'=>true]);

        return redirect()->action('admin\jourController@index');
    }

    public function destroy($id)
    {
        try {
            Jour::destroy($id);
            session(['success'=>true]);
        } catch (QueryException $e) {
            return redirect()->to(URL::previous())
                ->withErrors(['constraint_violation' => 'Impossible de supprimer ce jour']);
        }

        return redirect()->to(URL::previous());
    }
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store1()
	{
		//
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
	public function update1($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy1($id)
	{
		//
	}

}
