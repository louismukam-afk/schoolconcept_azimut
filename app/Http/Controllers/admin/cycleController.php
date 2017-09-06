<?php namespace gesecole\Http\Controllers\admin;

use gesecole\cycle;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class cycleController extends Controller {

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


	public function index(Request $req)
	{
        $titre='Cycles';
        $cycles=cycle::paginate(5);
	    $values= array('titre'=>$titre,'cycles'=>$cycles);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }

		return view('admin\cycles',$values);
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
        $c=new cycle();
        $c->nom=$req->input('nom');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('admin\cycleController@index');
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
        $c=cycle::find($id);
        $c->nom=$req->input('nom');
        $c->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $c=cycle::find($id);
        $niv=niveau::whereIdCycle($id);
        $niv->delete();
        $c->delete();
        session(['success'=>true]);
        return redirect()->action('admin\cycleController@index');
	}

}
