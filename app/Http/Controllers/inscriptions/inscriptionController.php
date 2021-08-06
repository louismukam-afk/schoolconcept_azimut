<?php namespace gesecole\Http\Controllers\inscriptions;

use gesecole\classe;
use gesecole\cycle;
use gesecole\eleve;
use gesecole\Http\Controllers\startController;
use gesecole\inscription;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\payement;
use gesecole\Vars;
use Illuminate\Support\Facades\DB;
use gesecole\Http\Requests;
use Illuminate\Support\Facades\Session;
use gesecole\Http\Controllers\Controller;
use gesecole\parametres;
use Illuminate\Http\Request;

class inscriptionController extends Controller {

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
        $nb_eleve=inscription::whereAnneeScolaire(\session('year'))->get()->count();

        return view('inscriptions/index',["nb_eleves"=>$nb_eleve]);
	}



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
    public function search()
    {
        $c=classe::all();
        $values= array('titre'=>trans('inscription.title_search_register'));
        $values['classes']=$c;
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('inscription_edition')){
            $values['inscription_edition']=session('inscription_edition');
            Session::forget('inscription_edition');
        }


        return view('inscriptions/add_inscription',$values);
    }

    public function find(Request $req)
    {
        $pattern=$req->input('search');
        $e=DB::table('eleve')->whereRaw('lower(nom) LIKE ?',['%'.strtolower($pattern).'%'] )

            ->orWhereRaw('lower(prenom) LIKE ?',['%'.strtolower($pattern).'%'])
            ->orWhereRaw('lower(matricule) LIKE ?',['%'.strtolower($pattern).'%'])
            ->orderBy('nom')->orderBy('prenom')
            ->paginate(30);
        //$e=DB::table('eleve')->whereRaw("nom LIKE '%?%' or prenom LIKE '%?%' or matricule LIKE '%?%'",[$pattern,$pattern,$pattern])->get();
        //$e=eleve::whereRaw("nom LIKE '%?%' or prenom LIKE '%?%' or matricule LIKE '%?%'",[$pattern,$pattern,$pattern]);
        $values= array('titre'=>trans('inscription.title_search_register'));
        $values['eleves']=$e;
        //session(['search_result'=>true]);

        return view('inscriptions/eleve_operations',$values);
    }


	public function create($id_eleve)
	{
        $c=classe::with('niveau')->orderBy('id_niveau')
            ->orderBy('nom')
            ->get();
        $cy=cycle::all();
        $e=eleve::find($id_eleve);
        $nniveau = NNiveau::orderBy('nom')->get();
        $values= array('titre'=>trans('admin.inscription'));
        $values['classes']=$c;
        $values['cycles']=$cy;
        $values['nniveaux']=$nniveau;
        $values['e']=$e;
        $values['new_record']=true;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        if(session('custom_error')){
            $values['custom_error']=session('custom_error');
            Session::forget('custom_error');

        }

        return view('inscriptions/add_inscription',$values);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
       $this->validate($req,[
           'id_eleve'=>'required',
           'classe'=>'required',
           'cycle'=>'required|numeric',
           'montant'=>'required|numeric',
           'nniveau'=>'required|numeric',
       ]) ;
       $id_eleve=$req->input('id_eleve');
        $id_classe=$req->input('classe');
        $id_cycle=$req->input('cycle');
        $id_nniveau=$req->input('nniveau');
        $montant=$req->input('montant');
       $annee_scolaire=session('year');
       $today=date('Y-m-d');
       $capacite_classe=classe::find($id_classe)->capacite;
       $nb_eleve_classe=inscription::whereRaw('id_classe=? and id_nniveau=? and annee_scolaire=?',[$id_classe,$id_nniveau,$annee_scolaire])->get()->count();
       //echo $nb_eleve_classe.'   '.$capacite_classe;
       if($nb_eleve_classe>=$capacite_classe){
            session(['custom_error'=>trans('inscription.full_classe')]);
            return redirect()->action('inscriptions\inscriptionController@create',['id_eleve'=>$id_eleve]);
       }
       $test=inscription::whereRaw("id_eleve=?  and annee_scolaire=?",[$id_eleve,$annee_scolaire])->get()->first();

       if($test!=null){
           session(['custom_error'=>trans('inscription.deja_inscrit')]);
           return redirect()->action('inscriptions\inscriptionController@create',['id_eleve'=>$id_eleve]);
       }
       $i=new inscription();
       $i->id_eleve=$id_eleve;
       $i->id_classe=$id_classe;
       $i->annee_scolaire=$annee_scolaire;
       $i->id_cycle=$id_cycle;
       $i->id_nniveau=$id_nniveau;
       $i->date_inscription=$today;
       $i->montant=$montant;

        $current_user = \Auth::user();
        $i->id_creator = $current_user->id;
        $i->edition_details = 'classe =' . $id_classe . ', cycle = ' . $id_cycle . ', nniveau = ' . $id_nniveau . ', montant = ' . $montant;
        $i->id_last_editor = $current_user->id;

       if($i->save()) {
           $p = new payement();
           $p->id_eleve = $id_eleve;
           $p->annee_scolaire = $annee_scolaire;
           $p->date_payement = $today;
           $p->montant = $montant;
           $p->id_inscription = $i->id;
           $p->motif = 'Inscription';
           $p->type = Vars::INSCRIPTION_TYPE;

           $current_user = \Auth::user();
           $p->edition_details = 'montant = ' . $montant;
           $p->id_creator = $current_user->id;
           $p->id_last_editor = $current_user->id;

           $p->save();
           $numero = sprintf('%08d', $p->id);
           $p->numero = $numero;
           $p->save();

           $m = $req->input('matricule');
           if ($m != null) {
               $e = eleve::find($id_eleve);
               $e->matricule=$m;
               $e->save();
           } else{

               $e = eleve::find($id_eleve);

                if(strlen($e->matricule) <= 0){
                   $c = classe::find($id_classe);
                   $param = parametres::all();
                   $pa = $param->where('nom', 'initiales_etablissement', false)->first();

                   $front = session('year');
                   $front = substr($front, 2, 2);
                   $number = sprintf('%04d', $e->id);
                   $e->matricule = $front . $pa->valeur . '-' . $c->code . $number;
                   $e->save();
                }
              
           }


           session(['success'=>$p->id]);

           return redirect()->action('inscriptions\inscriptionController@search') ;
       }else{
           session(['custom_error'=>trans('inscription.full_classe')]);
           return redirect()->action('inscriptions\inscriptionController@create',['id_eleve'=>$id_eleve]);
       }



	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		return view('inscriptions/recu_inscription',['titre'=>'Recu']);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id_eleve)
	{
        $c=classe::with('niveau')->orderBy('id_niveau')
            ->orderBy('nom')
            ->get();
        $cy=cycle::all();
        $e=eleve::find($id_eleve);
        $nniveau=NNiveau::all();
        $i=inscription::whereAnneeScolaire(\session('year'))
            ->where('id_eleve','=',$id_eleve)
            ->first();
        $values= array('titre'=>trans('inscription.title_search_register'));
        $values['classes']=$c;
        $values['cycles']=$cy;
        $values['inscription']=$i;
        $values['e']=$e;
        $values['nniveaux']=$nniveau;
        $values['old_record']=true;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        if(session('custom_error')){
            $values['custom_error']=session('custom_error');
            Session::forget('custom_error');

        }

        return view('inscriptions/add_inscription',$values);
	}

	public function print_receipe($id){
	    $p=payement::find($id);
        $i=inscription::whereAnneeScolaire(session('year'))->where('id_eleve',$p->id_eleve,false)->first();
        $cy=cycle::find($i->id_cycle)->nom;
        $c=classe::find($i->id_classe);
        $n=niveau::find($c->id_niveau);
        $nn=NNiveau::find($i->id_nniveau);
	    $pa=parametres::all();
	    $pa=$pa->where('nom','nom_etablissement',false)->first();
	    $nom_e=$pa->valeur;
        $values['cycle']=$cy;
        $values['classe']=$c;
        $values['niveau']=$n;
        $values['nniveau']=$nn;
        $values['payement']=$p;
        $values['inscription']=$i;
        $values['nom_etablissement']=$nom_e;

        return view('inscriptions/imprimer_recu',$values);

    }

    public function certificate($id){
	    $e=eleve::find($id);
        $i=inscription::whereAnneeScolaire(session('year'))->where('id_eleve','=',$id)->first();
        $values['inscription']=$i;
        if($i!=null){
            $cy=cycle::find($i->id_cycle);
            $c=classe::find($i->id_classe);
            $n=niveau::find($c->id_niveau);
            $nn=nniveau::find($i->id_nniveau);
            $pa=parametres::all();
            $pa1=$pa->where('nom','nom_etablissement',false)->first();
            $pa2=$pa->where('nom','nom_directeur',false)->first();
            $nom_e=$pa1->valeur;
            $nom_d=$pa2->valeur;
            $values['cycle']=$cy;
            $values['classe']=$c;
            $values['eleve']=$e;
            $values['niveau']=$n;
            $values['nniveau']=$nn;
            $values['nom_etablissement']=$nom_e;
            $values['nom_directeur']=$nom_d;

        }


        return view('inscriptions/print_certificate',$values);

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
            'id_eleve'=>'required',
            'classe'=>'required',
            'cycle'=>'required|numeric',
            'nniveau'=>'required|numeric',
        ]) ;
        $update=true;
        $id_eleve=$req->input('id_eleve');
        $id_classe=$req->input('classe');
        $id_cycle=$req->input('cycle');
        $id_nniveau=$req->input('nniveau');
        $annee_scolaire=session('year');
        $capacite_classe=classe::find($id_classe)->capacite;
        $nb_eleve_classe=inscription::whereRaw('id_classe=? and id_nniveau=? and annee_scolaire=?',[$id_classe,$id_nniveau,$annee_scolaire])->get()->count();
        //echo $nb_eleve_classe.'   '.$capacite_classe;
        if($nb_eleve_classe>=$capacite_classe){
            session(['custom_error'=>trans('inscription.full_classe')]);
            return redirect()->action('inscriptions\inscriptionController@create',['id_eleve'=>$id_eleve]);
        }

        $i=inscription::whereAnneeScolaire(\session('year'))
            ->where('id_eleve','=',$id_eleve)
            ->first();
        $i->id_classe=$id_classe;
        $i->id_cycle=$id_cycle;
        $i->id_nniveau=$id_nniveau;

        $current_user = \Auth::user();
        $i->id_last_editor = $current_user->id;

        if($i->save()) {


            $m = $req->input('matricule');
            if ($m != null) {
                $e = eleve::find($id_eleve);
                $e->matricule=$m;
                $e->save();
            } else{
                $e = eleve::find($id_eleve);
                $c = classe::find($id_classe);
                $param = parametres::all();
                $pa = $param->where('nom', 'initiales_etablissement', false)->first();

                $front = session('year');
                $front = substr($front, 2, 2);
                $number = sprintf('%04d', $e->id);
                $e->matricule = $front . $pa->valeur . '-' . $c->code . $number;
                $e->save();
            }


            session(['success'=>true]);
            session(['inscription_edition'=>true]);

            return redirect()->action('inscriptions\listesController@index') ;
        }else{
            session(['custom_error'=>trans('inscription.full_classe')]);
            return redirect()->action('inscriptions\inscriptionController@edit',['id_eleve'=>$id_eleve]);
        }
	}

	public function index_scan_carte(){
        $values= array('titre'=> trans('admin.scan_carte'));

        return view('inscriptions.scan_carte', $values);

    }

    public function process_scan(Request $request){
	    $scanned = $request->input('scanned');
	    //$id_eleve = openssl_decrypt($scanned, startController::SIMPLE_CRYPT_ALGO, startController::PASSPHRASE, 0, startController::VECTOR);
        $eleve = eleve::find(intval($scanned));
        if($eleve) {
            return response()->json(intval($scanned));
        } else {
            return response()->json(0);

        }

    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id_eleve)
	{
		$i=inscription::whereAnneeScolaire(\session('year'))
            ->where('id_eleve','=',$id_eleve)->first();
		$i->delete();
        return redirect()->action('inscriptions\listesController@index') ;

	}

}
