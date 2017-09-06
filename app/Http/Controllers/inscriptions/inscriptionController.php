<?php namespace gesecole\Http\Controllers\inscriptions;

use gesecole\classe;
use gesecole\eleve;
use gesecole\inscription;
use gesecole\payement;
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
        return view('inscriptions\index');
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

        return view('inscriptions\add_inscription',$values);
    }

    public function find(Request $req)
    {
        $pattern=$req->input('search');
        $e=DB::table('eleve')->where('nom', 'LIKE', '%'.$pattern.'%')
            ->orWhere('prenom', 'LIKE', '%'.$pattern.'%')
            ->orWhere('matricule', 'LIKE', '%'.$pattern.'%')
            ->orderBy('nom')->orderBy('prenom')
            ->paginate(30);
        //$e=DB::table('eleve')->whereRaw("nom LIKE '%?%' or prenom LIKE '%?%' or matricule LIKE '%?%'",[$pattern,$pattern,$pattern])->get();
        //$e=eleve::whereRaw("nom LIKE '%?%' or prenom LIKE '%?%' or matricule LIKE '%?%'",[$pattern,$pattern,$pattern]);
        $values= array('titre'=>trans('inscription.title_search_register'));
        $values['eleves']=$e;
        session(['search_result'=>true]);

        return view('inscriptions\add_inscription',$values);
    }


	public function create($id_eleve)
	{
        $c=classe::all();
        $e=eleve::find($id_eleve);
        $values= array('titre'=>trans('inscription.title_search_register'));
        $values['classes']=$c;
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

        return view('inscriptions\add_inscription',$values);
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
           'montant'=>'required|numeric'
       ]) ;
       $id_eleve=$req->input('id_eleve');
        $id_classe=$req->input('classe');
        $montant=$req->input('montant');
       $annee_scolaire=session('year');
       $today=date('Y-m-d');
       $capacite_classe=classe::find($id_classe)->capacite;
       $nb_eleve_classe=inscription::whereRaw('id_classe=? and annee_scolaire=?',[$id_classe,$annee_scolaire])->get()->count();
       //echo $nb_eleve_classe.'   '.$capacite_classe;
       if($nb_eleve_classe>=$capacite_classe){
            session(['custom_error'=>trans('inscription.full_classe')]);
            return redirect()->action('inscriptions\inscriptionController@create',['id_eleve'=>$id_eleve]);
       }
       $test=inscription::whereRaw("id_eleve=? and annee_scolaire=?",[$id_eleve,$annee_scolaire])->get()->first();

       if($test!=null){
           session(['custom_error'=>trans('inscription.deja_inscrit')]);
           return redirect()->action('inscriptions\inscriptionController@create',['id_eleve'=>$id_eleve]);
       }
       $i=new inscription();
       $i->id_eleve=$id_eleve;
       $i->id_classe=$id_classe;
       $i->annee_scolaire=$annee_scolaire;
       $i->date_inscription=$today;
       $i->montant=$montant;
       if($i->save()){
           $p=new payement();
           $p->id_eleve=$id_eleve;
           $p->annee_scolaire=$annee_scolaire;
           $p->date_payement=$today;
           $p->montant=$montant;
           $p->motif='Inscription';
           $p->save();
           $numero=sprintf('%08d',$p->id);
           $p->numero=$numero;
           $p->save();
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
		return view('inscriptions\recu_inscription',['titre'=>'Recu']);
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

	public function print_receipe($id){
	    $p=payement::find($id);
        $i=inscription::whereAnneeScolaire(session('year'))->where('id_eleve',$p->id_eleve,false)->first();
	    $pa=parametres::all();
	    $pa=$pa->where('nom','nom_etablissement',false)->first();
	    $nom_e=$pa->valeur;
        $values['payement']=$p;
        $values['inscription']=$i;
        $values['nom_etablissement']=$nom_e;

        return view('inscriptions\imprimer_recu',$values);

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
		//
	}

}
