<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\fonction;
use gesecole\fonction_personnel;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\parametres;
use gesecole\personnel;
use Illuminate\Http\Request;

class fonction_personnelController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response

    public function __construct()
    {
        session(['big_title'=>trans('admin.dashboard')]);
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
    } */
	public function index($id)
	{
        $titre=trans('admin.fonction_personnel');
        $m=fonction_personnel::all();
        $p=personnel::find($id);
        $n=fonction::all();

        $values= array('titre'=>$titre,'fonction_personnel'=>$m,'personnel'=>$p,'fonction'=>$n);

      //  $values['personnel']=$p;
      //  $values['fonction']=$n;

        return view('personnel\fonction_personnel',$values);
	}

    public function index2()
    {
        $titre=trans('admin.fonction_personnel');

        $p=personnel::all();
        $values= array('titre'=>$titre,'personnel'=>$p);

        //  $values['personnel']=$p;
        //  $values['fonction']=$n;

        return view('personnel\fonction_pers',$values);
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
            'id_fonction'=>'required',
            'id_personnel'=>'required',
            'annee_scolaire'=>'required',

        ]);
        $c=new fonction_personnel();
        $c->annee_scolaire=$req->input('annee_scolaire');
        $c->id_fonction=$req->input('id_fonction');
        $c->id_personnel=$req->input('id_personnel');
        $c->save();
        session('year');
        session(['success'=>true]);
        return redirect()->action('personnel\fonction_personnelController@index');

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
            'id_fonction' => 'required|numeric',
            'id_personnel' => 'required|numeric',
        ]);

        $c = fonction_personnel::find($id);
        $c->id_fonction = $req->input('id_fonction');
        $c->id_personnel = $req->input('id_personnel');
        $c->annee_scolaire = session('year');
        $c->save();
        session(['success' => true]);
        return redirect()->action('personnel\fonction_personnelController@index');
    }


        /*    $this->validate($req,[
              'annee_scolaire'=>'required',
              'id_fonction'=>'required|numeric',
              'id_personnel'=>'required|numeric'
          ]);
          $niveau=$req->input('id_fonction');
          $niveau=intval($niveau);
          $capacite=$req->input('capacite');
          $nom=$req->input('nom');
          $c=new classe();
          $c->nom=$nom;
          $c->capacite=$capacite;
          $n=niveau::find($niveau);
          $n->classes()->save($c);
          session(['success'=>true]);
          return redirect()->action('admin\classeController@index');

      }*/

      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return Response
       */
	public function destroy($id)
	{
        $fp=fonction_personnel::find($id);
        $id_p=$fp->id_personnel;
        $fp->delete();
        session(['success'=>true]);
        return redirect()->action('personnel\fonction_personnelController@index',['id'=>$id_p]);

	}

}
