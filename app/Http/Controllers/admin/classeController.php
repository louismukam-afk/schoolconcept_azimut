<?php namespace gesecole\Http\Controllers\admin;

use gesecole\classe;
use gesecole\cycle;
use gesecole\Http\Controllers\Controller;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;
use gesecole\niveau;
use Illuminate\Http\Request;

class classeController extends Controller {

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
        $c=classe::paginate(15);
        $n=niveau::all();
        $cy=cycle::all();
        $values= array('titre'=>trans('admin.specialites'),'niveaux'=>$n,'classes'=>$c,'cycles'=>$cy);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        return view('admin\classes',$values);
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
            'nom'=>'required',
            'nom_anglais'=>'required',
            'code'=>'required',
            'niveau'=>'required|numeric',
            'capacite'=>'required|numeric',
            'cycle'=>'required|numeric'
        ]);

        $niveau=$req->input('niveau');
        $niveau=intval($niveau);
        $capacite=$req->input('capacite');
        $nom=$req->input('nom');
        $cycle=$req->input('cycle');
        $nom_anglais=$req->input('nom_anglais');
        $code=$req->input('code');

        $c=new classe();
        $c->nom=$nom;
        $c->nom_anglais=$nom_anglais;
        $c->code=$code;
        $c->id_cycle=$cycle;
        $c->capacite=$capacite;
        $n=niveau::find($niveau);
        $n->classes()->save($c);
        session(['success'=>true]);
        return redirect()->action('admin\classeController@index');
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
            'code'=>'required',
            'niveau'=>'required|numeric',
            'cycle'=>'required|numeric',
            'capacite'=>'required|numeric'
        ]);
        $id=$req->input('id');
        $niveau=$req->input('niveau');
        $capacite=$req->input('capacite');
        $nom=$req->input('nom');
        $nom_anglais=$req->input('nom_anglais');
        $code=$req->input('code');
        $cycle=$req->input('cycle');

        $c=classe::find($id);
        $c->nom=$nom;
        $c->nom_anglais=$nom_anglais;
        $c->code=$code;
        $c->id_cycle=$cycle;
        $c->capacite=$capacite;
        $c->id_niveau=$niveau;

        $c->save();
        session(['success'=>true]);
        return redirect()->action('admin\classeController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

        $c=classe::find($id);
        $c->delete();
        session(['success'=>true]);
        return redirect()->action('admin\classeController@index');
	}

}
