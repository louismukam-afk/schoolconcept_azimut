<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\classe;
use gesecole\dispense;
use gesecole\fonction_personnel;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\personnel;
use gesecole\Session;
use gesecole\matiere;
use gesecole\parametres;
use Illuminate\Http\Request;

class dispenseController extends Controller {


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
	public function index($id)
	{
        $titre=trans('admin.dispense');
        $m=dispense::whereIdPersonnel($id)->get();
        $p=personnel::find($id);
        $n=classe::all();
        $e=matiere::all();
        $values= array('titre'=>$titre,'dispense'=>$m,'classes'=>$n,'matieres'=>$e);
        $values['personnel']=$p;

        return view('personnel\dispense',$values);
	}
    public function index1()
    {
        $titre=trans('admin.titre_table_personnel');

        $p=personnel::all();
        $values= array('titre'=>$titre,'personnel'=>$p);


        return view('personnel\dispenses',$values);
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
	public function store(Request  $req)
	{
        $this->validate($req,[
            'id_matiere'=>'required',
            'id_personnel'=>'required',
            'id_classe'=>'required',

        ]);
        $c=new dispense();
        $c->annee_scolaire=session('year');
        $c->id_matiere=$req->input('id_matiere');
        $c->id_personnel=$req->input('id_personnel');
        $c->id_classe=$req->input('id_classe');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('personnel\dispenseController@index',['id'=>$c->id_personnel]);
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
        $this->validate($req, [
            'id_matiere' => 'required|numeric',
            'id_personnel' => 'required|numeric',
            'id_classe' => 'required|numeric',
            'annee_scolaire' => 'required'
        ]);

        $c = dispense::find($id);
        $c->id_matiere = $req->input('id_matiere');
        $c->id_personnel = $req->input('id_personnel');
        $c->id_classe = $req->input('id_classe');
        $c->annee_scolaire = $req->input('annee_scolaire');
        $c->save();
        session(['success' => true]);
        return redirect()->action('personnel\dispenseController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
      $fp=dispense::find($id);
        $id_p=$fp->id_personnel;
        $fp->delete();
        session(['success'=>true]);
        return redirect()->action('personnel\dispenseController@index',['id'=>$id_p]);
	}

}
