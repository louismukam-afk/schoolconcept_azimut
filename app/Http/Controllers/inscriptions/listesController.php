<?php namespace gesecole\Http\Controllers\inscriptions;

use gesecole\eleve;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\classe;
use Illuminate\Support\Facades\DB;
use gesecole\inscription;
use gesecole\parametres;
use Illuminate\Http\Request;

class listesController extends Controller {

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
        $c=classe::orderBy('nom')->get();
        $values= array('titre'=>trans('inscription.listes'));
        $values['classes']=$c;


        return view('inscriptions\selection_liste',$values);
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
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request,$id)
	{
	    $p=parametres::whereNom('nom_etablissement')->first();
	    $e_name=$p->valeur;
	    $values['e_name']=$e_name;
	    if($request->input('option')==null) {
            if ($id == 0) {
                $values['titre'] = trans('inscription.listes');

                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe FROM eleve e,inscription,classe c WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id  ORDER BY nom_classe,nom,prenom ', [session('year')]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.all_eleves');
                return view('inscriptions\listes', $values);
            } else {
                $values['titre'] = trans('inscription.listes');
                $classe_name = classe::find($id)->nom;
                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe FROM eleve e,inscription,classe c WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id and c.id=? ORDER BY nom,prenom ', [session('year'), $id]);
                $values['eleves'] = $e;
               @$values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $e[0]->nom_classe;
                return view('inscriptions\listes', $values);
            }
        }else if($request->input('option')==1){
            if ($id == 0) {
                $values['titre'] = trans('inscription.listes');

                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,s.tranche1 AS tranche ,(sol.solde-s.tranche1) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.motif<>\'Inscription\' THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND (sol.solde-s.tranche1)<0 ORDER BY nom_classe,nom,prenom', [session('year'),session('year')]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.all_eleves');
                $values['tranche']=true;
                return view('inscriptions\listes', $values);
            } else {
                $values['titre'] = trans('inscription.listes');
                $classe_name = classe::find($id)->nom;
                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,s.tranche1 AS tranche ,(sol.solde-s.tranche1) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.motif<>\'Inscription\' THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND (sol.solde-s.tranche1)<0 AND c.id=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name;
                $values['tranche']=true;
                return view('inscriptions\listes', $values);
            }
        }else if($request->input('option')==2){
            if ($id == 0) {
                $values['titre'] = trans('inscription.listes');

                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2) AS tranche ,(sol.solde-(s.tranche1+s.tranche2)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.motif<>\'Inscription\' THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND (sol.solde-(s.tranche1+s.tranche2))<0 ORDER BY nom_classe,nom,prenom', [session('year'),session('year')]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.all_eleves');
                $values['tranche']=true;
                return view('inscriptions\listes', $values);
            } else {
                $values['titre'] = trans('inscription.listes');
                $classe_name = classe::find($id)->nom;
                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2) AS tranche ,(sol.solde-(s.tranche1+s.tranche2)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.motif<>\'Inscription\' THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND (sol.solde-(s.tranche1+s.tranche2))<0 AND c.id=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name;
                $values['tranche']=true;
                return view('inscriptions\listes', $values);
            }
        }else if($request->input('option')==3){
            if ($id == 0) {
                $values['titre'] = trans('inscription.listes');

                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.motif<>\'Inscription\' THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3))<0 ORDER BY nom_classe,nom,prenom', [session('year'),session('year')]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.all_eleves');
                $values['tranche']=true;
                return view('inscriptions\listes', $values);
            } else {
                $values['titre'] = trans('inscription.listes');
                $classe_name = classe::find($id)->nom;
                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.motif<>\'Inscription\' THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3))<0 AND c.id=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name;
                $values['tranche']=true;
                return view('inscriptions\listes', $values);
            }
        }else if($request->input('option')==4){
            if ($id == 0) {
                $values['titre'] = trans('inscription.listes');

                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3+s.tranche4) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.motif<>\'Inscription\' THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4))<0 ORDER BY nom_classe,nom,prenom', [session('year'),session('year')]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.all_eleves');
                $values['tranche']=true;
                return view('inscriptions\listes', $values);
            } else {
                $values['titre'] = trans('inscription.listes');
                $classe_name = classe::find($id)->nom;
                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3+s.tranche4) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.motif<>\'Inscription\' THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4))<0 AND c.id=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id]);
                $values['eleves'] = $e;
                $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name;
                $values['tranche']=true;
                return view('inscriptions\listes', $values);
            }
        }
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
		//
	}

}
