<?php namespace gesecole\Http\Controllers\admin;

use gesecole\cycle;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class niveauController extends Controller {

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
        $n=niveau::paginate(15);
        $values= array('titre'=>trans('admin.level'),'niveaux'=>$n);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        return view('admin/niveau',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{


	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
        $this->validate($req,[
            'nom'=>'required',
            'nom_anglais'=>'required',
        ]);

        $nom=$req->input('nom');
        $nom_anglais=$req->input('nom_anglais');
        $n=new niveau();
        $n->nom=$nom;
        $n->nom_anglais=$nom_anglais;
        $n->save();
        session(['success'=>true]);
        return redirect()->action('admin\niveauController@index');
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
            'id'=>'required|numeric',
            'nom'=>'required',
            'nom_anglais'=>'required',
        ]);

        $id=$req->input('id');
        $nom=$req->input('nom');
        $nom_anglais=$req->input('nom_anglais');
        $n=niveau::find($id);
        $n->nom=$nom;
        $n->nom_anglais=$nom_anglais;
        $n->save();
        session(['success'=>true]);
        return redirect()->action('admin\niveauController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $c=niveau::find($id);
        $c->delete();
        session(['success'=>true]);
        return redirect()->action('admin\niveauController@index');
	}

}
