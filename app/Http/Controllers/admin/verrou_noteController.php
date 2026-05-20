<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\verrouNote;
use Illuminate\Support\Facades\Session;
use gesecole\parametres;
use Illuminate\Http\Request;

class verrou_noteController extends Controller {

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
        $titre=trans('admin.verrou_note');
        $v=verrouNote::whereAnneeScolaire(\session('year'))->get();
        $values= array('titre'=>$titre,'verrous'=>$v);
        if(session('success')){
            $values['success']=true;
            Session::forget('success');
        }


        return view('admin/verrouNote',$values);
	}

    public function activate($sequence)
    {
       $v= verrouNote::whereAnneeScolaire(\session('year'))
           ->where('sequence','=',$sequence)
            ->get();
       foreach ($v as $verrous){
           $verrous->delete();
       }
       \session(['success'=>true]);
        return redirect()->action('admin\verrou_noteController@index');

    }
    public function deactivate($sequence)
    {
        $v=new verrouNote();
        $v->annee_scolaire=\session('year');
        $v->sequence=$sequence;
        $v->save();

        \session(['success'=>true]);
        return redirect()->action('admin\verrou_noteController@index');
    }

    public function deliberer($sequence)
    {
        $v= verrouNote::whereAnneeScolaire(\session('year'))
            ->where('sequence','=',$sequence)
            ->get();
        foreach ($v as $verrous){
            $verrous->delete();
        }
        \session(['success'=>true]);
        return redirect()->action('admin\verrou_noteController@index');

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
	public function store()
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

	}

}
