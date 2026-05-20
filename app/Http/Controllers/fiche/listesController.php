<?php namespace gesecole\Http\Controllers\fiche;

use gesecole\cycle;
use gesecole\eleve;
use gesecole\Frais;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\classe;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\payement;
use gesecole\scolarite;
use Illuminate\Support\Facades\App;
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
        $this->renewSchoolYear();
    }
     public function home()
    {
        return view('inscriptions/index_fiche');
    }

    public function index()
    {
        $c=classe::orderBy('nom')->get();
        $c2=classe::with('niveau')->orderBy('id_niveau')
            ->orderBy('nom')
            ->get();
        $f = Frais::orderBy('nom')->get();
        $values= array('titre'=>trans('inscription.listes'));
        $values['classes']=$c;
        $values['frais']=$f;
        $values['classes_list']=$c2;
        $cy=cycle::all();
        $n=niveau::all();
        $nn=NNiveau::all();
        $values['cycles']=$cy;
        $values['niveaux']=$n;
        $values['nniveaux']=$nn;


        return view('fiche/selection_listes',$values);
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
    public function show(Request $request)
    {
        $this->validate($request,[
            'id'=>'required',
        ]);

        $id=$request->input('id');
        $type=$request->input('type');
        $option=$request->input('option');

        $nniveau=$request->input('nniveau');
        $p=parametres::whereNom('nom_etablissement')->first();
        $e_name=$p->valeur;
        $values['e_name']=$e_name;
        $cycle=$request->input('cycle');
        if($nniveau) {
            $cycle = NNiveau::find($nniveau)->id_cycle;
        }


        if($option==null) {
            if ($id == 0) {
                $values['titre'] = trans('inscription.listes');

                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,c.code as code_classe,nn.nom as nniveau FROM eleve e,inscription,classe c,n_niveaus nn WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id and inscription.id_nniveau=nn.id ORDER BY code_classe,nom,prenom ', [session('year')]);
                $values['eleves'] = $e;
                $values['show_classe'] = true;
                $values['titre_liste'] = trans('inscription.all_eleves');
                return view('fiche/listes', $values);
            } else {
                $values['titre'] = trans('inscription.listes');
                $classe = classe::find($id);
                $classe_name = $classe->getNom();
                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe  FROM eleve e,inscription,classe c WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id and c.id=? AND inscription.id_cycle=? AND inscription.id_nniveau=? ORDER BY nom,prenom ', [session('year'), $id,$cycle,$nniveau]);
                $values['eleves'] = $e;
                @$values['titre_liste'] = trans('inscription.eleves_de') . ' ' . ($classe_name).' '.NNiveau::find($nniveau)->nom;
                return view('fiche/listes', $values);
            }
        }else
        {
            if($option==1){
                $tranches = "s.tranche1";
                $tranche = "s.tranche1";
                $motif=trans('admin.tranche1');
                /*
                if ($id == 0) {
                    $values['titre'] = trans('inscription.listes');

                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,c.code as code_classe,sol.solde,s.tranche1 AS tranche ,(sol.solde-s.tranche1) AS reste,nn.nom as nniveau   FROM n_niveaus nn,eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0  THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=?  GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND inscription.id_nniveau=nn.id AND (sol.solde-s.tranche1)<0 ORDER BY code_classe,nom,prenom', [session('year'),session('year')]);
                    $values['eleves'] = $e;
                    $values['show_classe'] = true;
                    $values['titre_liste'] = trans('inscription.all_eleves');
                    $values['tranche']=true;
                    return view('inscriptions/listes', $values);
                } else {
                    $values['titre'] = trans('inscription.listes');
                    $classe_name = classe::find($id)->nom;
                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,s.tranche1 AS tranche ,(sol.solde-s.tranche1) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-s.tranche1)<0 AND c.id=? AND inscription.id_cycle=? AND inscription.id_nniveau=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id,$cycle,$nniveau]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name.' '.NNiveau::find($nniveau)->nom;
                    $values['tranche']=true;
                    return view('inscriptions/listes', $values);
                }*/
            }else if($option==2){
                $tranches = "(s.tranche1+s.tranche2)";
                $tranche = "s.tranche2";
                $motif=trans('admin.tranche2');
                /*
                if ($id == 0) {
                    $values['titre'] = trans('inscription.listes');
                    $values['show_classe'] = true;
                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,c.code as code_classe,sol.solde,(s.tranche1+s.tranche2) AS tranche ,(sol.solde-(s.tranche1+s.tranche2)) AS reste,nn.nom as nniveau  FROM n_niveaus nn, eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-(s.tranche1+s.tranche2))<0 AND inscription.id_nniveau=nn.id ORDER BY code_classe,nom,prenom', [session('year'),session('year')]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.all_eleves');
                    $values['tranche']=true;
                    return view('inscriptions/listes', $values);
                } else {
                    $values['titre'] = trans('inscription.listes');
                    $classe_name = classe::find($id)->nom;
                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2) AS tranche ,(sol.solde-(s.tranche1+s.tranche2)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-(s.tranche1+s.tranche2))<0 AND c.id=? AND inscription.id_cycle=? AND inscription.id_nniveau=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id,$cycle,$nniveau]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name.' '.NNiveau::find($nniveau)->nom;
                    $values['tranche']=true;
                    return view('inscriptions/listes', $values);
                }*/
            }else if($option==3){
                $tranches = "(s.tranche1+s.tranche2+s.tranche3)";
                $tranche = "s.tranche3";
                $motif=trans('admin.tranche3');
                /*
                if ($id == 0) {
                    $values['titre'] = trans('inscription.listes');

                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,c.code as code_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3)) AS reste,nn.nom as nniveau   FROM n_niveaus nn, eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id  AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3))<0 AND inscription.id_nniveau=nn.id ORDER BY code_classe,nom,prenom ', [session('year'),session('year')]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.all_eleves');
                    $values['tranche']=true;
                    $values['show_classe'] = true;
                    return view('inscriptions/listes', $values);
                } else {
                    $values['titre'] = trans('inscription.listes');
                    $classe_name = classe::find($id)->nom;
                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3))<0 AND c.id=? AND inscription.id_cycle=? AND inscription.id_nniveau=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id,$cycle,$nniveau]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name.' '.NNiveau::find($nniveau)->nom;
                    $values['tranche']=true;
                    return view('inscriptions/listes', $values);
                }*/
            }else if($option==4){
                $tranches = "(s.tranche1+s.tranche2+s.tranche3+s.tranche4)";
                $tranche = "s.tranche4";
                $motif=trans('admin.tranche4');
                /*
                if ($id == 0) {
                    $values['titre'] = trans('inscription.listes');

                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,c.code as code_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3+s.tranche4) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4)) AS reste  ,nn.nom as nniveau FROM n_niveaus nn, eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4))<0 AND inscription.id_nniveau=nn.id ORDER BY code_classe,nom,prenom', [session('year'),session('year')]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.all_eleves');
                    $values['tranche']=true;
                    $values['show_classe'] = true;
                    return view('inscriptions/listes', $values);
                } else {
                    $values['titre'] = trans('inscription.listes');
                    $classe_name = classe::find($id)->nom;
                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3+s.tranche4) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4))<0 AND c.id=? AND inscription.id_cycle=? AND inscription.id_nniveau=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id,$cycle,$nniveau]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name.' '.NNiveau::find($nniveau)->nom;
                    $values['tranche']=true;
                    return view('inscriptions/listes', $values);
                }*/
            }else if($option==5){
                $tranches = "(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5)";
                $tranche = "(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5)";

                $motif=trans('admin.tranche5');
                /*
                if ($id == 0) {
                    $values['titre'] = trans('inscription.listes');

                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,c.code as code_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5)) AS reste ,nn.nom as nniveau  FROM n_niveaus nn, eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND  (sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5))<0 AND inscription.id_nniveau=nn.id ORDER BY code_classe,nom,prenom', [session('year'),session('year')]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.all_eleves');
                    $values['tranche']=true;
                    $values['show_classe'] = true;
                    return view('inscriptions/listes', $values);
                } else {
                    $values['titre'] = trans('inscription.listes');
                    $classe_name = classe::find($id)->nom;
                    $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5) AS tranche ,(sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5)) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5))<0 AND c.id=? AND inscription.id_cycle=? AND inscription.id_nniveau=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id,$cycle,$nniveau]);
                    $values['eleves'] = $e;
                    $values['titre_liste'] = trans('inscription.eleves_de') . ' ' . $classe_name.' '.NNiveau::find($nniveau)->nom;
                    $values['tranche']=true;
                    return view('inscriptions/listes', $values);
                }*/
            }
            else
            {
                $tranches = "(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5)";
                $tranche = "(s.tranche1+s.tranche2+s.tranche3+s.tranche4+s.tranche5)";

                $motif=trans('admin.scolarite');


            }


            if($type==1)
            {
                $opComp='>=';
                $values['paid'] = true;

                $t0=trans('admin.paid_eleve').' : '.$motif;
            }
            else
            {
                $opComp='<';
                $t0=trans('admin.unpaid_eleve').' : '.$motif;


            }

            if ($id == 0) {
                $values['titre'] = trans('inscription.listes');

                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,c.code as code_classe,sol.solde,' . $tranche . ' AS tranche ,(' . $tranches . ' - sol.solde) AS reste  ,nn.nom as nniveau FROM n_niveaus nn, eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-' . $tranches . ')'.$opComp.'0 AND inscription.id_nniveau=nn.id ORDER BY code_classe,nom,prenom', [session('year'),session('year')]);
                $values['eleves'] = $e;
                $values['titre_liste'] = $t0.' - '.trans('inscription.all_eleves');
                if($type != null) {
                    $values['type']=$type;
                }
                $values['show_classe'] = true;
                return view('fiche/listes', $values);
            } else {
                $values['titre'] = trans('inscription.listes');
                $classe = classe::find($id);
                $classe_name = $classe->getNom();
                $e = DB::select('SELECT e.matricule,e.id, e.nom ,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe,sol.solde,' . $tranche . ' AS tranche ,(' . $tranches . ' - sol.solde) AS reste   FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN p.type = 0 AND p.id_inscription=0 THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and annee_scolaire=? and inscription.id_classe=c.id AND sol.id=e.id AND c.id_niveau=n.id AND s.id_niveau=n.id AND s.id_cycle=inscription.id_cycle AND (sol.solde-' . $tranches . ')'.$opComp.'0 AND c.id=? AND inscription.id_cycle=? AND inscription.id_nniveau=? ORDER BY nom_classe,nom,prenom', [session('year'),session('year'),$id,$cycle,$nniveau]);
                $values['eleves'] = $e;
                $values['titre_liste'] = $t0.' - '. $classe_name.' '.NNiveau::find($nniveau)->nom;
                if($type != null) {
                    $values['type']=$type;
                }
                return view('fiche/listes', $values);
            }


        }

    }


    public function showFrais(Request $request)
    {
        $this->validate($request,[
            'classe'=>'required',
            //'nniveau'=>'required',
            'type'=>'required',
            'frais'=>'required',
        ]);



        $classe=$request->input('classe');
        $type=$request->input('type');
        $nniveau = $request->input('nniveau');
        $frais=$request->input('frais');
        $f=Frais::find($frais);

        if($classe != 0) {
            if($nniveau == null || strlen($nniveau) <= 0) {
                return redirect()->to(\URL::previous())->withErrors(['no_nniveau' => trans('admin.must_specify', ['value' => trans('admin.nniveau')])]);
            }
        }

        $values['titre'] = trans('inscription.listes');

        $added='';
        if($type==1){
            if ($classe==0)
            {
                if($frais != 0)

                    $e = DB::select('SELECT e.*, nn.nom as nniveau,c.nom as nom_classe, c.code AS code_classe,f.montant as total, sol.solde as tranche,(f.montant-sol.solde) as reste  FROM eleve e,inscription,classe c,frais f, n_niveaus nn, (SELECT SUM(CASE WHEN (p.type=?) THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and inscription.annee_scolaire=? AND sol.id=e.id and inscription.id_classe=c.id AND inscription.id_nniveau = nn.id   AND f.id=? AND (f.montant-sol.solde)<=0 ORDER BY nom_classe,nom,prenom ', [$frais,session('year'),session('year'),$frais]);
                else
                    $e = DB::select('SELECT e.*, nn.nom as nniveau, c.nom as nom_classe, c.code AS code_classe, s.inscription as total, sol.solde as tranche,(s.inscription-sol.solde) as reste  FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN (p.id_inscription<>0) THEN p.montant ELSE 0 END) as solde,e.id FROM n_niveaus nn, eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve  AND sol.id=e.id and inscription.id_classe=c.id and inscription.annee_scolaire=? and s.annee_scolaire=? AND c.id_niveau=n.id AND s.id_niveau=n.id AND inscription.id_nniveau = nn.id  AND (s.inscription-sol.solde)<=0  ORDER BY nom_classe,nom,prenom ', [session('year'),session('year'),session('year')]);



            }
            else
            {
                if($frais!=0)

                    $e = DB::select('SELECT e.*, nn.nom as nniveau, c.nom as nom_classe, c.code AS code_classe, f.montant as total, sol.solde as tranche,(f.montant-sol.solde) as reste  FROM eleve e,inscription,classe c,frais f, n_niveaus nn, (SELECT SUM(CASE WHEN (p.type=?) THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and inscription.annee_scolaire=? AND sol.id=e.id and inscription.id_classe=c.id AND inscription.id_nniveau = nn.id AND nn.id = ?  AND f.id=? AND (f.montant-sol.solde)<=0 AND c.id=? ORDER BY nom_classe,nom,prenom ', [$frais,session('year'),session('year'), $nniveau,$frais,$classe]);
                else
                    $e = DB::select('SELECT e.*, nn.nom as nniveau, c.nom as nom_classe, c.code AS code_classe, s.inscription as total, sol.solde as tranche,(s.inscription-sol.solde) as reste  FROM eleve e,inscription,classe c,niveau n,scolarite s, n_niveaus nn, (SELECT SUM(CASE WHEN (p.id_inscription<>0) THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and inscription.annee_scolaire=? and s.annee_scolaire=? AND sol.id=e.id and inscription.id_classe=c.id AND c.id=? AND c.id_niveau=n.id AND s.id_niveau=n.id AND inscription.id_nniveau = nn.id AND nn.id = ? AND (s.inscription-sol.solde)<=0  ORDER BY nom_classe,nom,prenom ', [session('year'),session('year'),session('year'),$classe, $nniveau]);

                $added=classe::find($classe)->nom;
            }

            $values['paid'] = true;

            if($frais!=0)
                $values['titre_liste'] = trans('admin.paid_eleve').' : '.$f->nom.' - '.$added;
            else
                $values['titre_liste'] = trans('admin.paid_eleve').' : '.trans('admin.inscription').' - '.$added;

        }else
        {
            if ($classe==0)
            {
                if($frais!=0)

                    $e = DB::select('SELECT e.*, nn.nom as nniveau,c.nom as nom_classe, c.code AS code_classe, f.montant as total, sol.solde as tranche,(f.montant-sol.solde) as reste  FROM eleve e,inscription,classe c,frais f, n_niveaus nn, (SELECT SUM(CASE WHEN (p.type=?) THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and inscription.annee_scolaire=? AND sol.id=e.id and inscription.id_classe=c.id AND inscription.id_nniveau = nn.id   AND f.id=? AND (f.montant-sol.solde) > 0 ORDER BY nom_classe,nom,prenom ', [$frais,session('year'),session('year'),$frais]);
                else
                    $e = DB::select('SELECT e.*, nn.nom as nniveau, c.nom as nom_classe, c.code AS code_classe, s.inscription as total, sol.solde as tranche,(s.inscription-sol.solde) as reste  FROM eleve e,inscription,classe c,niveau n,scolarite s, (SELECT SUM(CASE WHEN (p.id_inscription<>0) THEN p.montant ELSE 0 END) as solde,e.id FROM n_niveaus nn, eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve  AND sol.id=e.id and inscription.id_classe=c.id and inscription.annee_scolaire=? and s.annee_scolaire=? AND c.id_niveau=n.id AND s.id_niveau=n.id AND inscription.id_nniveau = nn.id  AND (s.inscription-sol.solde) > 0  ORDER BY nom_classe,nom,prenom ', [session('year'),session('year'),session('year')]);


            }
            else
            {
                if($frais!=0)

                    $e = DB::select('SELECT e.*, nn.nom as nniveau, c.nom as nom_classe, c.code AS code_classe, f.montant as total, sol.solde as tranche,(f.montant-sol.solde) as reste  FROM eleve e,inscription,classe c,frais f, n_niveaus nn, (SELECT SUM(CASE WHEN (p.type=?) THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and inscription.annee_scolaire=? AND sol.id=e.id and inscription.id_classe=c.id AND inscription.id_nniveau = nn.id AND nn.id = ?  AND f.id=? AND (f.montant-sol.solde) > 0 AND c.id=? ORDER BY nom_classe,nom,prenom ', [$frais,session('year'),session('year'), $nniveau,$frais,$classe]);
                else
                    $e = DB::select('SELECT e.*, nn.nom as nniveau, c.nom as nom_classe, c.code AS code_classe, s.inscription as total, sol.solde as tranche,(s.inscription-sol.solde) as reste  FROM eleve e,inscription,classe c,niveau n,scolarite s, n_niveaus nn, (SELECT SUM(CASE WHEN (p.id_inscription<>0) THEN p.montant ELSE 0 END) as solde,e.id FROM eleve e LEFT JOIN payement p ON p.id_eleve=e.id WHERE  annee_scolaire=? GROUP BY e.id) AS sol WHERE e.id=inscription.id_eleve and inscription.annee_scolaire=? and s.annee_scolaire=? AND sol.id=e.id and inscription.id_classe=c.id AND c.id=? AND c.id_niveau=n.id AND s.id_niveau=n.id AND inscription.id_nniveau = nn.id AND nn.id = ? AND (s.inscription-sol.solde) > 0  ORDER BY nom_classe,nom,prenom ', [session('year'),session('year'),session('year'),$classe, $nniveau]);

                $added=classe::find($classe)->nom;


            }

            if($frais!=0)
                $values['titre_liste'] = trans('admin.unpaid_eleve').' : '.$f->nom.' - '.$added;
            else
                $values['titre_liste'] = trans('admin.unpaid_eleve').' : '.trans('admin.inscription').' - '.$added;

        }

        if($type != null) {
            $values['type']=$type;
        }
        $values['show_classe'] = true;
        $values['eleves'] = $e;
        return view('inscriptions.listes', $values);





    }

    public function fiche_scolarite(Request $request){
        $this->validate($request,[
            'id'=> 'required|numeric',
            'nniveau'=> 'required|numeric',
        ]);
        $id_nniveau = $request->input('nniveau');
        $id_classe = $request->input('id');
        $nniveau = NNiveau::find($id_nniveau);
        $classe = classe::find($id_classe);
        $id_cycle = $nniveau->id_cycle;
        $frais_report = Frais::where('show_on_report', '=', true)
            ->orderBy('ordre')
            ->get();
        $frais_array = $frais_report->fetch('id')->toArray();
        $scolarite = scolarite::where('id_cycle', '=', $id_cycle)
            ->where('id_niveau', '=', $classe->id_niveau)
            ->first();

        $eleves_inscrits = eleve::join('inscription', 'inscription.id_eleve', '=', 'eleve.id')
            ->where('annee_scolaire', '=', session('year'))
            ->where('id_cycle', '=', $id_cycle)
            ->where('id_classe', '=', $id_classe)
            ->where('id_nniveau', '=', $id_nniveau)
            ->select(['eleve.id', 'matricule', 'nom', 'prenom', 'inscription.id as id_inscription'])
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        foreach ($eleves_inscrits as $e) {
            $inscription = payement::where('id_eleve', '=', $e->id)
                ->where('id_inscription', '=', $e->id_inscription)
                ->where('annee_scolaire', '=', session('year'))
                ->first();
            $frais = payement::where('id_eleve', '=', $e->id)
                ->where('annee_scolaire', '=', session('year'))
                ->whereIn('type', $frais_array)
                ->get();
            $payements = payement::where('id_eleve', '=', $e->id)
                ->where('annee_scolaire', '=', session('year'))
                ->where('type', '=', 0)
                ->orderBy('date_payement', 'desc')
                ->get();

            $e->montant_inscription = $inscription->montant;
            $e->payements_frais = $frais;
            $e->payements = $payements;

        }

        return view('inscriptions.fiche_scolarite')
            ->with('eleves', $eleves_inscrits)
            ->with('titre_liste', trans('admin.fiche_scolarite_eleve') . ' / ' . trans('admin.year') . ' ' . session('year')  . ' / ' . trans('admin.classe') . ' ' . $classe->code . ' - ' . $nniveau->nom)
            ->with('frais', $frais_report)
            ->with('scolarite', $scolarite)
            ->with('titre', trans('admin.fiche_scolarite_eleve'))
            ->with('page_title', trans('admin.fiche_scolarite_eleve'));
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
