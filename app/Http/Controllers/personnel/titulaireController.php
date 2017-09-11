<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\classe;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\personnel;
use gesecole\titulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class titulaireController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function __construct()
    {
        session(['big_title'=>trans('admin.gestion_personnel')]);
        $year=session('year');
        if($year==null){
            $p=parametres::WhereNom('annee_scolaire')->get()->first();

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
        $titre=trans('admin.titulaire');
        $titulaire=titulaire::all();
        $classe=classe::all();
        $p=personnel::all();

        $st=DB::select( 'SELECT DISTINCT c.id ,c.nom FROM classe c WHERE c.id NOT IN(SELECT id_classe FROM titulaire WHERE annee_scolaire=?)',[session('year')]);
        $sc=DB::select( 'SELECT DISTINCT p.id ,p.nom FROM personnel p WHERE p.id NOT IN(SELECT id_personnel FROM titulaire WHERE annee_scolaire=?)',[session('year')]);

        $value['titre']=$titre;
        $value['classes']=$classe;
        $value['personnel']=$p;
        $value['titulaire']=$titulaire;
        $value['st']=$st;
        $value['sc']=$sc;


        return view('personnel\titulaire',$value);
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

            'id_personnel'=>'required',
            'id_classe'=>'required'

            ]);
        $c=new titulaire();
        $c->annee_scolaire=session('year');
        $c->id_classe=$req->input('id_classe');
        $c->id_personnel=$req->input('id_personnel');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('personnel\titulaireController@index');

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
        $fp=titulaire::find($id);
      //  $id_p=$fp->id_personnel;
        $fp->delete();
        session(['success'=>true]);
        return redirect()->action('personnel\titulaireController@index');

	}

}
