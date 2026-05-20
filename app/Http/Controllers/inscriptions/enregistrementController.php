<?php namespace gesecole\Http\Controllers\inscriptions;

use gesecole\acces_eleve;
use gesecole\bourse;
use gesecole\eleve;
use gesecole\Http\Controllers\Controller;
use gesecole\inscription;
use gesecole\parametres;
use gesecole\payement;
use gesecole\Photo;
use gesecole\scolarite;
use gesecole\Vars;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use gesecole\parents;
use gesecole\Services\EnregistrementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
        $this->renewSchoolYear();
    }


	public function index()
	{
        $values= array('titre'=>trans('inscription.title_search'));
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');

        }

        return view('inscriptions.search_edit_eleve',$values);
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
        return view('inscriptions.add_eleve',$values);
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
        $e->status_matrimonial=$req->input('status_matrimonial');
        $e->annee_sortie=$req->input('annee_sortie');
        $e->diplome_obtenu=$req->input('diplome_obtenu');
        $e->premiere_langue=$req->input('premiere_langue');
        $e->deuxieme_langue=$req->input('deuxieme_langue');
        $e->autre_langue=$req->input('autre_langue');
        $e->profession=$req->input('profession');
        $e->telephone_wathsapp_eleve=$req->input('telephone_wathsapp_eleve');
        $e->moyenne_generale=$req->input('moyenne_generale');
        $e->serie=$req->input('serie');
        $e->localisation_ecole=$req->input('localisation_ecole');
        $e->source_financement=$req->input('source_financement');
        $e->canal_decouvert=$req->input('canal_decouvert');
        $e->site_internet=$req->input('site_internet');
        $e->abonnement=$req->input('abonnement');
        $tp=$req->input('tel1_pere');
        $tm=$req->input('tel1_mere');
        $tt=$req->input('tel1_tuteur');
       // $ta=$request->input('max');
         $e->telephone_wathsapp_pere=$tp;
         $e->telephone_wathsapp_mere=$tm;
         $e->telephone_wathsapp_tuteur=$tt;

        // $e->telephone_wathsapp_pere=$req->input('tel1_pere');
        // $e->telephone_wathsapp_mere=$req->input('tel1_mere');
        // $e->telephone_wathsapp_tuteur=$req->input('tel1_tuteur');
        $e->tel=$req->input('tel');

        $e->abonnement_valide=$req->input('abonnement_valide');
        $e->chaine=$req->input('chaine');
        $e->numero_conseille=$req->input('numero_conseille');
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
            //Input::flash();
            return redirect()->action('inscriptions\enregistrementController@create')->withInput();


        }elseif ($eTest!=null){
            session(['error_eleve'=>true]);
        }
        else{

            $current_user = \Auth::user();
            $e->id_creator = $current_user->id;
            $e->id_last_editor = $current_user->id;

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
                $ext=$photo->getClientOriginalExtension();
                $savePath = photosController::getPhotosSaveDirPath() . '/' .$e->id.'-'.session('year').'.'.$ext;
                //Image::make($photo->getRealPath())->encode('jpg',80)->resize(300,300)->save($savePath);
                Image::make($photo->getRealPath())->resize(300,300)->save($savePath);

                $ph=new Photo();
                $ph->eleve_id=$e->id;
                $ph->annee_scolaire=\session('year');
                $ph->url = $savePath;
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
        $ins = inscription::with('classe', 'nniveau')
            ->where('id_eleve', '=', $e->id)
            ->orderBy('annee_scolaire')
            ->get();
        $current_inscription = inscription::where('id_eleve', '=', $e->id)
            ->where('annee_scolaire', '=', \session('year'))
            ->first();
		$photo=Photo::whereEleveId($id)->where('annee_scolaire','=',session('year'))->first();
        $values= array('titre'=>trans('inscription.edit_eleve'));
        if($photo!=null)
        {
            $pUrl=$photo->url;
            $values['photoUrl']=$pUrl;
        }
        $values['eleve']=$e;
        $values['parents']=$p;
        $values['inscription']=$current_inscription;
        $values['inscriptions']=$ins;

        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }


        return view('inscriptions.edit_eleve',$values);
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

        $e->telephone_wathsapp_eleve=$req->input('telephone_wathsapp_eleve');
        $e->moyenne_generale=$req->input('moyenne_generale');
        $e->serie=$req->input('serie');
        $e->localisation_ecole=$req->input('localisation_ecole');
        $e->source_financement=$req->input('source_financement');
        $e->canal_decouvert=$req->input('canal_decouvert');
        $e->site_internet=$req->input('site_internet');
        $e->abonnement=$req->input('abonnement');
        $e->telephone_wathsapp_pere=$req->input('telephone_wathsapp_pere');
        $e->telephone_wathsapp_mere=$req->input('telephone_wathsapp_mere');
        $e->telephone_wathsapp_tuteur=$req->input('telephone_wathsapp_tuteur');
       // $e->telephone_wathsapp_autre=$req->input('telephone_wathsapp_autre');
        $e->abonnement_valide=$req->input('abonnement_valide');
        $e->chaine=$req->input('chaine');
        $e->numero_conseille=$req->input('numero_conseille');

        $current_user = \Auth::user();
        $e->id_last_editor = $current_user->id;
        $e->save();

        $photo=$req->file('photo');
        if($photo!=null){

            $ext=$photo->getClientOriginalExtension();
            $savePath = photosController::getPhotosSaveDirPath() . '/' .$e->id.'-'.session('year').'.'.$ext;
            //Image::make($photo->getRealPath())->encode('jpg',80)->resize(300,300)->save($savePath);
            Image::make($photo->getRealPath())->resize(300,300)->save($savePath);

            $ph=Photo::firstOrNew(['eleve_id'=>$id,'annee_scolaire'=>\session('year')]);
            $ph->url = $savePath;
            $ph->save();


        }
        session(['success'=>true]);
        return redirect()->action('inscriptions\enregistrementController@edit',[$id]) ;
	}

	public function status_eleve(Request $request) {
	    $id = $request->input('id');
	    $show_access = $request->input('show_access');
        $values= array('titre'=>trans('admin.status_eleve'));

        $id=intval($id);
        $eleve = eleve::find($id);

	    if($eleve) {
	        if($show_access == 1) {
                $values['show_access'] = $show_access;
            }
            $acces = acces_eleve::where('annee_scolaire', '=', \session('year'))
                ->where('id_eleve', '=', $id)
                ->first();
            $values['eleve'] = $eleve;
            $values['acces'] = $acces;
            $values['access_decision'] = EnregistrementService::getStudentAccessStatus($id);


            $inscription = inscription::with('classe.niveau', 'nniveau.cycle')
                ->where('id_eleve', '=', $id)
                ->where('annee_scolaire', '=', \session('year'))
                ->first();

	        if ($inscription) {
	            $values['inscription'] = $inscription;
                $solde = payement::where('id_eleve', '=', $id)
                    ->where('type', '=', 0)
                    ->where('id_inscription', '=', 0)
                    ->where('annee_scolaire', '=', \session('year'))
                    ->sum('montant');

                $values['solde'] = $solde;


                $scolarite = scolarite::where('id_cycle', '=', $inscription->nniveau->cycle->id)
                    ->where('id_niveau', '=', $inscription->classe->niveau->id)
                    ->where('type_scolarite','=',$inscription->type_scolarite)
                    ->first();
                $bourse = bourse::where('id_eleve', '=', $id)
                    ->where('annee_scolaire', '=', session('year'))
                    ->first();
                if ($bourse) {
                    $values['montant_bourse'] = $bourse->montant;
                }

                $values['scolarite'] = $scolarite;
            }
        }

	    return view('inscriptions.status_eleve', $values);
    }


    public function set_status(Request $request) {
	    $status = $request->input('status');
	    $id = $request->input('id');
	    $date_debut = $request->input('date_debut');

	    $date_fin = $request->input('date_fin');
	    $montant = $request->input('montant');
	    $eleve = eleve::find($id);

	    if($eleve) {

            $current_user = \Auth::user();

            $acces = acces_eleve::where('annee_scolaire', '=', \session('year'))
                ->where('id_eleve', '=', $id)
                ->first();

            if($acces == null) {
                $acces = new acces_eleve();
                $acces->annee_scolaire = \session('year');
                $acces->id_eleve = $id;
                $acces->id_creator = $current_user->id;

                if($status == 1) {
                    $acces->edition_details = 'decision = '. Vars::DECISION_ALLOW;
                } else {
                    $acces->edition_details = 'decision = '. Vars::DECISION_DENY;
                }
            }
            $acces->id_last_editor = $current_user->id;

            if ($date_debut) {
                $acces->date_debut = $date_debut;
            }
            if ($date_fin) {
                $acces->date_fin = $date_fin;
            }

            if ($montant) {
                $acces->montant = $montant;
            }

            if($status == 1) {
                $acces->decision = Vars::DECISION_ALLOW;
            } else {
                $acces->decision = Vars::DECISION_DENY;
            }

            $acces->save();
        }

	    return redirect()->to(URL::previous());

    }

    public function set_multi_status(Request $request) {
        $status = $request->input('status');
        $ids = $request->input('ids');
        $current_user = \Auth::user();
        $date_debut = $request->input('date_debut');
	    $date_fin = $request->input('date_fin');
	    $montant = $request->input('montant');

        foreach ($ids as $id) {
            $eleve = eleve::find($id);
            if($eleve) {

                $acces = acces_eleve::where('annee_scolaire', '=', \session('year'))
                    ->where('id_eleve', '=', $id)
                    ->first();

                if($acces == null) {
                    $acces = new acces_eleve();
                    $acces->annee_scolaire = \session('year');
                    $acces->id_eleve = $id;
                    $acces->id_creator = $current_user->id;
                    if($status == 1) {
                        $acces->edition_details = 'decision = '. Vars::DECISION_ALLOW;
                    } else {
                        $acces->edition_details = 'decision = '. Vars::DECISION_DENY;

                    }

                }
                $acces->id_last_editor = $current_user->id;
                if ($date_debut) {
                    $acces->date_debut = $date_debut;
                }
                if ($date_fin) {
                    $acces->date_fin = $date_fin;
                }
                if ($montant) {
                    $acces->montant = $montant;
                }

                if($status == 1) {
                    $acces->decision = Vars::DECISION_ALLOW;
                } else {
                    $acces->decision = Vars::DECISION_DENY;
                }

                $acces->save();
            }

        }



        return response()->json('OK');

    }

    public function get_status(Request $request) {
        $id = $request->input('id');

            $acces = acces_eleve::where('annee_scolaire', '=', \session('year'))
                ->where('id_eleve', '=', $id)
                ->first();


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
