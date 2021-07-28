<?php namespace gesecole\Http\Controllers\inscriptions;

use gesecole\eleve;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\parametres;
use gesecole\Photo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use gesecole\parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Validator;
use Intervention\Image\Facades\Image;

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

        return view('inscriptions/search_edit_eleve',$values);
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
        return view('inscriptions/add_eleve',$values);
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
            'photo'=>'image|max:2048',

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
        $e->lien_tuteur=$req->input('lien_tuteur');
        $e->email=$req->input('email');
        $e->tel=$req->input('tel');
        $e->region_origine=$req->input('region_origine');
        $e->departement_origine=$req->input('departement_origine');
        $e->diplome_preinscription=$req->input('diplome_preinscription');
        $e->annee_obtention=$req->input('annee_obtention');
        $e->pays_obtention=$req->input('pays_obtention');
        $e->dernier_etablissement=$req->input('dernier_etablissement');
        $e->annee_sortie=$req->input('annee_sortie');
        $e->diplome_obtenu=$req->input('diplome_obtenu');
        $e->premiere_langue=$req->input('premiere_langue');
        $e->deuxieme_langue=$req->input('deuxieme_langue');
        $e->autre_langue=$req->input('autre_langue');
        $e->profession=$req->input('profession');
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
            Input::flash();
            return redirect()->action('inscriptions\enregistrementController@create')->withInput();


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
            $photo=$req->file('photo');
            if($photo!=null){


                Image::make($photo->getRealPath())->encode('jpg',80)->resize(300,300)->save('img/photos/'.$e->id.'-'.session('year').'.jpg');
                $ph=new Photo();
                $ph->eleve_id=$e->id;
                $ph->annee_scolaire=\session('year');
                $ph->url='img/photos/'.$e->id.'-'.session('year').'.jpg';
                $ph->save();


            }


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
		$photo=Photo::whereEleveId($id)->where('annee_scolaire','=',session('year'))->first();
        $values= array('titre'=>trans('inscription.edit_eleve'));
        if($photo!=null)
        {
            $pUrl=$photo->url;
            $values['photoUrl']=$pUrl;
        }
        $values['eleve']=$e;
        $values['parents']=$p;

        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        return view('inscriptions/edit_eleve',$values);
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
        'photo'=>'image|max:2048',

    ]);
        $e=eleve::find($id);
        $e->nom=$req->input('nom');
        $e->date_naiss=$req->input('date_naiss');
        $e->lieu_naiss=$req->input('lieu_naiss');
        $e->sexe=$req->input('sexe');
        $e->nationalite=$req->input('nationalite');
        $e->quartier=$req->input('quartier');
        $e->lien_tuteur=$req->input('lien_tuteur');
        $e->parent_ens=$req->input('parents_ens');
        $e->prenom=$req->input('prenom');
        $e->email=$req->input('email');
        $e->tel=$req->input('tel');
        $e->region_origine=$req->input('region_origine');
        $e->departement_origine=$req->input('departement_origine');
        $e->diplome_preinscription=$req->input('diplome_preinscription');
        $e->annee_obtention=$req->input('annee_obtention');
        $e->pays_obtention=$req->input('pays_obtention');
        $e->dernier_etablissement=$req->input('dernier_etablissement');
        $e->annee_sortie=$req->input('annee_sortie');
        $e->diplome_obtenu=$req->input('diplome_obtenu');
        $e->premiere_langue=$req->input('premiere_langue');
        $e->deuxieme_langue=$req->input('deuxieme_langue');
        $e->autre_langue=$req->input('autre_langue');
        $e->profession=$req->input('profession');
        $e->save();

        $photo=$req->file('photo');
        if($photo!=null){


            Image::make($photo->getRealPath())->encode('jpg',80)->resize(300,300)->save('img/photos/'.$e->id.'-'.session('year').'.jpg');
            $ph=Photo::firstOrNew(['eleve_id'=>$id,'annee_scolaire'=>\session('year')]);
            $ph->url='img/photos/'.$e->id.'-'.session('year').'.jpg';
            $ph->save();


        }
        session(['success'=>true]);
        return redirect()->action('inscriptions\enregistrementController@edit',[$id]) ;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try {
            eleve::destroy([$id]);
        } catch (QueryException $fe) {
            return  redirect()->to(URL::previous())->withErrors(['constraint_violation' => trans('admin.cannot_delete')]);
        }
            return redirect()->to(\URL::previous());
	}

}
