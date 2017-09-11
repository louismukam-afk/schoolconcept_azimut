<?php namespace gesecole\Http\Controllers\inscriptions;

use gesecole\eleve;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\parametres;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use gesecole\parents;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class enregistrementController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {

        session(['big_title'=>trans('menu.gestion_inscriptions')]);
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
        $values= array('titre'=>trans('inscription.title_search'));
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');

        }

        return view('inscriptions\search_edit_eleve',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

        $values= array('titre'=>'Enregistrement');
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');

        }
        if(session('error_parent')){
            Session::forget('error_parent');
            $values['error_parent']=trans('inscription.minimum_parent');
        }
        if(session('error_eleve')){
            Session::forget('error_eleve');
            $values['error_parent']=trans('inscription.deja_enreg');
        }
        return view('inscriptions\add_eleve',$values);
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
            'date_naiss'=>'required|date',
            'lieu_naiss'=>'required',
            'sexe'=>'required',
            'nationalite'=>'required',
            'quartier'=>'required',
            'transport'=>'required',
            'parents_ens'=>'required',

        ]);
        $e=new eleve();
        $p0=new parents();
        $p1=new parents();
        $p2=new parents();
        //élève

        $e->nom=$req->input('nom');
        $e->prenom=$req->input('prenom');
        $e->date_naiss=$req->input('date_naiss');
        $e->lieu_naiss=$req->input('lieu_naiss');
        $e->sexe=$req->input('sexe');
        $e->nationalite=$req->input('nationalite');
        $e->quartier=$req->input('quartier');
        $e->transport=$req->input('transport');
        $e->parent_ens=$req->input('parents_ens');
        $e->lien_tuteur=$req->input('lien_tuteur');
        $eTest=eleve::whereNom($e->nom)->where('prenom','=',$e->prenom)->where('date_naiss','=',$e->date_naiss)->where('sexe','=',$e->sexe)->get()->first();
        //père//////////////////////////
        $p1->nom=$req->input('nom_pere');
        $p1->profession=$req->input('profession_pere');
        $p1->ville=$req->input('ville_pere');
        $p1->quartier=$req->input('quartier_pere');
        $p1->tel1=$req->input('tel1_pere');
        $p1->tel2=$req->input('tel2_pere');
        $p1->type=1;
        //mere//////////////////////////
        $p0->nom=$req->input('nom_mere');
        $p0->profession=$req->input('profession_mere');
        $p0->ville=$req->input('ville_mere');
        $p0->quartier=$req->input('quartier_mere');
        $p0->tel1=$req->input('tel1_mere');
        $p0->tel2=$req->input('tel2_mere');
        $p0->type=0;
        //tuteur////////////////////////
        $p2->nom=$req->input('nom_tuteur');
        $p2->profession=$req->input('profession_tuteur');
        $p2->ville=$req->input('ville_tuteur');
        $p2->quartier=$req->input('quartier_tuteur');
        $p2->tel1=$req->input('tel1_tuteur');
        $p2->tel2=$req->input('tel2_tuteur');
        $p2->type=2;

        if(($p0->nom==null) && ($p1->nom==null) && ($p2->nom==null) ){
            session(['error_parent'=>true]);

        }elseif ($eTest!=null){
            session(['error_eleve'=>true]);
        }
        else{

                $e->save();
                if ($p0->nom != null) {
                    $p0->id_eleve = $e->id;
                    $p0->save();
                }
                if ($p1->nom != null) {
                    $p1->id_eleve = $e->id;
                    $p1->save();
                }
                if ($p2->nom != null) {
                    $p2->id_eleve = $e->id;
                    $p2->save();
                }

                $param=parametres::all();
                $p=$param->where('nom','initiales_etablissement',false)->first();

                $front=session('year');
                $front=substr($front,2,2);
                $number=sprintf('%05d',$e->id);
                $e->matricule=$front.$p->valeur.$number;
                $e->save();
                session(['success' => $e->id]);

        }
        return redirect()->action('inscriptions\enregistrementController@create');
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $e=eleve::find($id);
        $p=$e->parents;
        $values['eleve']=$e;
        $values['parents']=$p;
        return $values;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$e=eleve::find($id);
		$p=$e->parents;
        $values= array('titre'=>trans('inscription.edit_eleve'),'eleve'=>$e);
        $values['eleve']=$e;
        $values['parents']=$p;

        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        return view('inscriptions\edit_eleve',$values);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{ $this->validate($req,[
        'nom'=>'required',
        'date_naiss'=>'required|date',
        'lieu_naiss'=>'required',
        'sexe'=>'required',
        'nationalite'=>'required',
        'quartier'=>'required',
        'transport'=>'required',
        'parents_ens'=>'required',

    ]);
        $e=eleve::find($id);
        $e->nom=$req->input('nom');
        $e->date_naiss=$req->input('date_naiss');
        $e->lieu_naiss=$req->input('lieu_naiss');
        $e->sexe=$req->input('sexe');
        $e->nationalite=$req->input('nationalite');
        $e->quartier=$req->input('quartier');
        $e->transport=$req->input('transport');
        $e->parent_ens=$req->input('parents_ens');
        $e->lien_tuteur=$req->input('lien_tuteur');
        $e->parent_ens=$req->input('parents_ens');
        $e->prenom=$req->input('prenom');
        $e->save();
        session(['success'=>true]);
        return redirect()->action('inscriptions\inscriptionController@search') ;
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
