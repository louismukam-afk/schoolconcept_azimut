<?php namespace gesecole\Http\Controllers\eleves;

use database\seeds\eleveTableSeeder;
use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\cycle;
use gesecole\dispense;
use gesecole\eleve;
use gesecole\Functions;
use gesecole\groupeMatieres;
use gesecole\inscription;
use gesecole\niveau;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\NNiveau;
use gesecole\Session_exam;
use gesecole\verrouNote;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use gesecole\matiere;
use gesecole\note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate \Support\Facades\Log;
use gesecole\parametres;


class noteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function __construct()
    {
        session(['big_title'=>trans('eleve.big_title')]);
        $this->renewSchoolYear();
    }

	public function index()
	{
        $values= array('titre'=>'Notes');
        $c=classe::orderBy('nom')->get();
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $cy=cycle::all();
        $n=niveau::all();
        $nn=NNiveau::all();
        $values['niveaux']=$n;
        $values['classes']=$c;
        $values['nniveaux']=$nn;
        $values['cycles']=$cy;

        return view('eleves/classe_note',$values);
	}
    public function index2()
    {
        $values= array('titre'=>'Notes');


        return view('eleves/notes_index',$values);
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
    public function matiere(Request $request)
    {
        $this->validate($request, [
            "nniveau" => "required|numeric",
        ]);

        $id_classe = $request->input('id');      // id de la classe
        $id_cycle  = $request->input('cycle');
        $nniveau   = $request->input('nniveau');

        $values = ['titre' => 'Notes'];

        // ✅ On récupère les matières AVEC l’id de classe_matiere
        $matieres = \DB::select('
        SELECT 
            m.*, 
            cm.id AS id_classe_matiere
        FROM matiere m
        INNER JOIN classe_matiere cm 
            ON m.id = cm.id_matiere
        WHERE cm.id_classe = ?
          AND cm.id_cycle  = ?
          AND cm.id_nniveau = ?
        ORDER BY m.nom
    ', [$id_classe, $id_cycle, $nniveau]);

        if (session('success')) {
            $values['success'] = session('success');
            \Session::forget('success');
        }
        if (session('erreur')) {
            $values['erreur'] = session('erreur');
            \Session::forget('erreur');
        }

        $values['matieres']   = $matieres;
        $values['id']         = $id_classe;
        $values['id_cycle']   = $id_cycle;
        $values['id_nniveau'] = $nniveau;

        return view('eleves/matiere_note', $values);
    }


    public function matiere1(Request $request)
    {
         $this->validate($request,[
            "nniveau"=>"required|numeric",
        ]);
        $id=$request->input('id');
        $id_cycle=$request->input('cycle');
        $nniveau=$request->input('nniveau');
        $values= array('titre'=>'Notes');
        $m=DB::select('SELECT m.* FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? AND cm.id_nniveau=? ORDER BY m.nom',[$id,$id_cycle,$nniveau]);
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('erreur')){
            $values['erreur']=session('erreur');
            Session::forget('erreur');
        }
        $values['matieres']=$m;
        $values['id']=$id;
        $values['id_cycle']=$id_cycle;
        $values['id_nniveau']=$nniveau;

        return view('eleves/matiere_note',$values);
    }

    private function getPourcentage($sessionExam, $sequence)
    {
        // sécurité
        if (!$sessionExam || !$sequence) return 0;

        // forcer en entier (au cas où $sequence = '3' en string)
        $s = intval($sequence);

        // mapping sequence -> liste de noms de colonnes possibles (du plus probable au moins probable)
        $candidates = [
            1 => ['cc1', 'pourcentage_cc1', 'cc_1', 'cc_01'],
            2 => ['cc2', 'pourcentage_cc2', 'cc_2', 'cc_02'],
            3 => ['sn1', 'pourcentage_sn1', 'sn_1', 'sn_01'],
            4 => ['sn2', 'pourcentage_sn2', 'sn_2', 'sn_02'],
            5 => ['ratrappage1', 'rattrapage1', 'pourcentage_r1', 'rattrapage_1'],
            6 => ['ratrappage2', 'rattrapage2', 'pourcentage_r2', 'rattrapage_2'],
            7 => ['tpe1', 'pourcentage_tpe1', 'tpe_1'],
            8 => ['tpe2', 'pourcentage_tpe2', 'tpe_2'],
        ];

        if (!isset($candidates[$s])) return 0;

        // Cherche la première colonne qui existe dans l'objet $sessionExam
        foreach ($candidates[$s] as $col) {
            // supporte objets Eloquent (propriétés) et tableaux associatifs
            if (is_array($sessionExam) && array_key_exists($col, $sessionExam)) {
                $val = $sessionExam[$col];
                return is_numeric($val) ? floatval($val) : 0;
            }

            if (is_object($sessionExam) && (isset($sessionExam->{$col}) || property_exists($sessionExam, $col))) {
                $val = $sessionExam->{$col};
                return is_numeric($val) ? floatval($val) : 0;
            }
        }

        // fallback : 0 si aucune colonne trouvée
        return 0;
    }


    private function getPourcentageBySequence($session, $seq)
    {
        switch ($seq) {
            case 1: return $session->cc1;
            case 2: return $session->cc2;
            case 3: return $session->sn1;
            case 4: return $session->sn2;
            case 5: return $session->ratrappage1;
            case 6: return $session->ratrappage2;
            case 7: return $session->tpe1;
            case 8: return $session->tpe2;
            default: return 0;
        }
    }
    public function matiere_show(Request $request)
    {
        $this->validate($request, [
            "nniveau" => "required|numeric",
        ]);

        $id        = (int) $request->input('id');        // id_classe
        $id_cycle  = (int) $request->input('cycle');
        $nniveau   = (int) $request->input('nniveau');
        $annee     = session('year');                   // année académique active

        $values = ['titre' => 'Notes'];

        // Récupération des matières rattachées à la classe + cycle + niveau
        // ET qui ont une session d’examen configurée pour l’année en cours.
        $matieres = DB::table('matiere as m')
            ->join('classe_matiere as cm', 'm.id', '=', 'cm.id_matiere')
            ->leftJoin('session_exams as se', function ($join) use ($annee) {
                $join->on('se.id_classe_matiere', '=', 'cm.id')
                    ->where('se.annee_academique', '=', $annee);
            })
            ->where('cm.id_classe', '=', $id)
            ->where('cm.id_cycle', '=', $id_cycle)
            ->where('cm.id_nniveau', '=', $nniveau)
            ->orderBy('m.nom')
            ->select(
                'm.*',
                'cm.id as id_classe_matiere',
                'cm.coef',
                'cm.semestre',
                'cm.type',
                'se.id as id_session_exam'
            )
            ->get();

        // Gestion des messages de session
        if (session('success')) {
            $values['success'] = session('success');
            Session::forget('success');
        }
        if (session('erreur')) {
            $values['erreur'] = session('erreur');
            Session::forget('erreur');
        }

       // Si aucune matière n’a de session configurée pour cette année
       /* if ($matieres->isEmpty()) {
            $values['erreur'] = "Aucun programme ou aucune session d'examen n'est configuré pour cette classe / niveau / année ($annee).";
        }*/
        // ⚠️ $matieres est un array (DB::select), pas une Collection.
// On vérifie simplement s'il est vide.
        if (empty($matieres)) {

            $values['erreur'] =
                "Aucun programme (classe_matiere) ou aucune session d'examen n'est configuré ".
                "pour cette classe / niveau / année ($annee).";
        }


        $values['matieres']    = $matieres;
        $values['id']          = $id;
        $values['id_cycle']    = $id_cycle;
        $values['id_nniveau']  = $nniveau;
        $values['annee']       = $annee;

        return view('eleves/matiere_note_show', $values);
    }
    public function matiere_show_ancien(Request $request)
    {
         $this->validate($request,[
            "nniveau"=>"required|numeric",
        ]);
        $id=$request->input('id');
        $id_cycle=$request->input('cycle');
        $nniveau=$request->input('nniveau');
        $values= array('titre'=>'Notes');
        $m=DB::select('SELECT m.* FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? AND cm.id_nniveau=? ORDER BY m.nom',[$id,$id_cycle,$nniveau]);
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('erreur')){
            $values['erreur']=session('erreur');
            Session::forget('erreur');
        }
        $values['matieres']=$m;
        $values['id']=$id;
        $values['id_cycle']=$id_cycle;
        $values['id_nniveau']=$nniveau;

        return view('eleves/matiere_note_show',$values);
    }



    public function matiere_deliberer(Request $request)
    {
        $this->validate($request,[
            "nniveau"=>"required|numeric",
        ]);
        $id=$request->input('id');
        $id_cycle=$request->input('cycle');
        $nniveau=$request->input('nniveau');
        $values= array('titre'=>'Notes');
        $m=DB::select('SELECT m.* FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? AND cm.id_nniveau=? ORDER BY m.nom',[$id,$id_cycle,$nniveau]);
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('erreur')){
            $values['erreur']=session('erreur');
            Session::forget('erreur');
        }
        $values['matieres']=$m;
        $values['id']=$id;
        $values['id_cycle']=$id_cycle;
        $values['id_nniveau']=$nniveau;

        return view('eleves/matiere_note_deliberer',$values);
    }
    public function matiere_show1(Request $request)
    {
        $this->validate($request,[
            "nniveau"=>"required|numeric",
        ]);
        $id=$request->input('id');
        $id_cycle=$request->input('cycle');
        $nniveau=$request->input('nniveau');
        $values= array('titre'=>'Notes');
        $m=DB::select('SELECT m.* FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? AND cm.id_nniveau=? ORDER BY m.nom',[$id,$id_cycle,$nniveau]);
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('erreur')){
            $values['erreur']=session('erreur');
            Session::forget('erreur');
        }
        $values['matieres']=$m;
        $values['id']=$id;
        $values['id_cycle']=$id_cycle;
        $values['id_nniveau']=$nniveau;

        return view('eleves/matiere_note_show',$values);
    }

    public function matiere_fiche(Request $request)
    {
        $this->validate($request,[
            "nniveau"=>"required|numeric",
        ]);
        $id=$request->input('id');
        $id_cycle=$request->input('cycle');
        $nniveau=$request->input('nniveau');
        $values= array('titre'=>'Notes');
        $m=DB::select('SELECT m.* FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? AND cm.id_nniveau=? ORDER BY m.nom',[$id,$id_cycle,$nniveau]);
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('erreur')){
            $values['erreur']=session('erreur');
            Session::forget('erreur');
        }
        $values['matieres']=$m;
        $values['id']=$id;
        $values['id_cycle']=$id_cycle;
        $values['id_nniveau']=$nniveau;

        return view('eleves/matiere_note_fiche',$values);
    }

    public function create(Request $request, $id)
    {
        $this->validate($request, [
            'sequence' => 'required|numeric',
            'cycle'    => 'required|numeric',
            'nniveau'  => 'required|numeric',
        ]);

        $values   = ['titre' => 'Notes'];
        $sequence = (int)$request->input('sequence');
        $nniveau  = (int)$request->input('nniveau');
        $id_m     = (int)$request->input('id');        // id de la matière
        $id_cycle = (int)$request->input('cycle');

        // 👉 On travaille sur l’année active (comme avant)
        $annee = session('year');

        // 🔒 Vérrouillage pour cette année / séquence
        $v = verrouNote::whereAnneeScolaire($annee)
            ->where('sequence', '=', $sequence)
            ->get();

        if ($v->count() > 0) {
            session(['erreur' => trans('admin.mod_locked')]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau)
            );
        }

        $user = \Auth::user();

        // Vérifier que le prof dispense bien cette matière cette année
        if ($user->isTeacher()) {
            $d = dispense::whereAnneeScolaire($annee)
                ->where('id_personnel', '=', $user->id_personnel)
                ->where('id_matiere', '=', $id_m)
                ->where('id_classe', '=', $id)
                ->where('id_nniveau', '=', $nniveau)
                ->first();

            if ($d == null) {
                $mat = matiere::find($id_m);
                session([
                    'erreur' => trans('admin.no_modification', [
                        'matiere' => $mat->nom . '(' . $mat->nom_anglais . ')'
                    ])
                ]);
                return redirect()->to(
                    url('/eleves/notes/matieres?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau)
                );
            }
        }

        // Matière + classe_matiere
        $mat = matiere::find($id_m);
        $cm  = classeMatiere::whereIdClasse($id)
            ->where('id_matiere', '=', $id_m)
            ->where('id_cycle', '=', $id_cycle)
            ->where('id_nniveau', '=', $nniveau)
            ->first();

        if (!$cm) {
            session(['erreur' => "Aucun programme (classe_matiere) trouvé pour cette matière."]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau)
            );
        }

        // 🔹 Session d’examen pour cette classe_matiere + année
        $sessionExam = Session_exam::where('id_classe_matiere', $cm->id)
            ->where('annee_academique', $annee)
            ->first();


        if (!$sessionExam) {
            session(['erreur' => "Aucune session n'est configurée pour cette matière et cette année ($annee)."]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau)
            );
        }


        // 🔹 Pourcentage de la séquence choisie (CC1, SN1, TP, etc.)
        $pourcentage = 0;
        switch ($sequence) {
            case 1: $pourcentage = $sessionExam->cc1;          break;
            case 2: $pourcentage = $sessionExam->cc2;          break;
            case 3: $pourcentage = $sessionExam->sn1;          break;
            case 4: $pourcentage = $sessionExam->sn2;          break;
            case 5: $pourcentage = $sessionExam->ratrappage1;  break;
            case 6: $pourcentage = $sessionExam->ratrappage2;  break;
            case 7: $pourcentage = $sessionExam->tpe1;         break;
            case 8: $pourcentage = $sessionExam->tpe2;         break;
            default: $pourcentage = 0;
        }

        if (!$pourcentage || $pourcentage <= 0) {
            session(['erreur' => "La session sélectionnée ne prévoit pas ce type de note (séquence $sequence)."]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau)
            );
        }

        // 🔹 Libellé du type de note (pour la vue)
        switch ($sequence) {
            case 1: $libelleType = 'CC 1'; break;
            case 2: $libelleType = 'CC 2'; break;
            case 3: $libelleType = trans('admin.session_normale').' 1'; break;
            case 4: $libelleType = trans('admin.session_normale').' 2'; break;
            case 5: $libelleType = trans('admin.rattrapage').' 1'; break;
            case 6: $libelleType = trans('admin.rattrapage').' 2'; break;
            case 7: $libelleType = 'TP 1'; break;
            case 8: $libelleType = 'TP 2'; break;
            default: $libelleType = '---';
        }

        // 🔹 Liste des élèves inscrits pour CETTE année / classe / cycle / niveau
        $e = \DB::select(
            'SELECT e.id,e.nom,e.prenom
         FROM eleve e, inscription i
         WHERE i.annee_scolaire = ?
           AND i.id_classe = ?
           AND i.id_eleve = e.id
           AND i.id_cycle = ?
           AND i.id_nniveau = ?
         ORDER BY e.nom, e.prenom',
            [$annee, $id, $id_cycle, $nniveau]
        );


        // 🔹 Notes existantes pour cette année / séquence / matière
        $n = note::whereAnneeScolaire($annee)
            ->where('id_matiere', '=', $id_m)
            ->where('sequence', '=', $sequence)
            ->get();
        $values['annee_scolaire']  = $annee;
        $values['libelleType']     = $libelleType;
        $values['eleves']          = $e;
        $values['notes']           = $n;
        $values['max']             = $cm->max;
        $values['matiere']         = $mat;
        $values['id_cycle']        = $id_cycle;
        $values['id_classe']       = $id;
        $values['id_nniveau']      = $nniveau;
        $values['sequence']        = $sequence;
        $values['session_exam']    = $sessionExam;
        $values['pourcentage']     = $pourcentage;

        return view('eleves/eleves_notes', $values);
    }
    public function print_note_tpe(Request $request, $id)
    {
        $this->validate($request,[
            'sequence'=>'required|numeric',
            'cycle'=>'required|numeric',
            'nniveau'=>'required|numeric',
        ]);

        $sequence = (int)$request->input('sequence'); // 7 ou 8
        $id_m     = (int)$request->input('id');
        $nniveau  = (int)$request->input('nniveau');
        $id_cycle = (int)$request->input('cycle');
        $annee    = session('year');

        $values = ['titre' => 'Notes'];

        $cm = classeMatiere::whereIdClasse($id)
            ->where('id_matiere', '=', $id_m)
            ->where('id_cycle',   '=', $id_cycle)
            ->where('id_nniveau', '=', $nniveau)
            ->first();

        if (!$cm) {
            session(['erreur' => "Aucun programme (classe_matiere) trouvé pour cette matière."]);
            return redirect()->to(
                url('/eleves/notes/matieres/show?id='.$id.'&cycle='.$id_cycle.'&nniveau='.$nniveau)
            );
        }

        $sessionExam = Session_exam::where('id_classe_matiere', $cm->id)
            ->where('annee_academique', $annee)
            ->first();

        if (!$sessionExam) {
            session(['erreur' => "Aucune session n'est configurée pour cette matière et cette année ($annee)."]);
            return redirect()->to(
                url('/eleves/notes/matieres/show?id='.$id.'&cycle='.$id_cycle.'&nniveau='.$nniveau)
            );
        }

        $pc_tpe = ($sequence == 7) ? $sessionExam->tpe1 : $sessionExam->tpe2;

        $e = DB::select(
            'SELECT e.*
         FROM eleve e, inscription i
         WHERE i.annee_scolaire = ?
           AND i.id_classe = ?
           AND i.id_eleve = e.id
           AND i.id_cycle = ?
           AND i.id_nniveau = ?
         ORDER BY e.nom, e.prenom',
            [$annee, $id, $id_cycle, $nniveau]
        );

        $id_eleves = array_map(function ($el) { return $el->id; }, $e);

        $n = note::whereAnneeScolaire($annee)
            ->where('id_matiere', '=', $id_m)
            ->where('sequence',   '=', $sequence)
            ->whereIn('id_eleve', $id_eleves)
            ->get();

        $values['eleves']      = $e;
        $values['notes']       = $n;
        $values['matiere']     = matiere::find($id_m);
        $values['classe']      = classe::find($id);
        $values['nniveau']     = NNiveau::find($nniveau);
        $values['id_cycle']    = $id_cycle;
        $values['sequence']    = $sequence;
        $values['pc_tpe']      = $pc_tpe;

        return view('eleves/print_note_tpe', $values);
    }

// Conversion note stockée → note sur 20
    private function computeNoteSur20($noteStockee, $pourcentage)
    {
        if ($pourcentage <= 0) return null;
        return round(($noteStockee * 20) / $pourcentage, 2);
    }

// Conversion note sur 20 → note à stocker
    private function computeNoteStockee($noteSur20, $pourcentage)
    {
        if ($pourcentage <= 0) return null;
        return round(($noteSur20 * $pourcentage) / 20, 4);
    }

// Récupère pourcentage d’une séquence
    private function getPourcentage1($session, $seq)
    {
        if (!$session) {
            return 0;
        }

        switch ($seq) {
            case 1: return $session->cc1;
            case 2: return $session->cc2;
            case 3: return $session->sn1;
            case 4: return $session->sn2;
            case 5: return $session->ratrappage1;
            case 6: return $session->ratrappage2;
            case 7: return $session->tpe1;
            case 8: return $session->tpe2;
            default: return 0;
        }
    }


// Liste des UV d’un module
    private function getUV($classeId, $cycleId, $nniveauId, $moduleId, $semestre)
    {
        return DB::table('groupe_matieres as gm')
            ->join('classe_matiere as cm', 'gm.id_classe_matiere', '=', 'cm.id')
            ->join('matiere as m', 'cm.id_matiere', '=', 'm.id')
            ->where('gm.id_matiere_mere', $moduleId)
            ->where('gm.id_classe', $classeId)
            ->where('gm.id_nniveau', $nniveauId)
            ->where('gm.semestre', $semestre)
            ->select('m.*','cm.id as id_classe_matiere','cm.coef','cm.max','cm.type')
            ->get();
    }

// Calcul note finale module (moyenne pondérée)
    private function computeModuleNote($uvNotes)
    {
        $sum = 0; $coef = 0;
        foreach ($uvNotes as $uv) {
            if (is_numeric($uv['note_finale'])) {
                $sum += $uv['note_finale'] * $uv['coef'];
                $coef += $uv['coef'];
            }
        }
        if ($coef <= 0) return null;
        return round($sum / $coef, 2);
    }
    private function getEleves($classeId, $cycleId, $nniveauId, $annee)
    {
        return DB::select("
        SELECT e.* 
        FROM eleve e
        JOIN inscription i ON i.id_eleve = e.id
        WHERE i.annee_scolaire = ?
        AND i.id_classe = ?
        AND i.id_cycle = ?
        AND i.id_nniveau = ?
        ORDER BY e.nom, e.prenom
    ", [$annee, $classeId, $cycleId, $nniveauId]);
    }

    private function getNotes($annee, $matiereId, $sequence, $elevesIds)
    {
        return note::whereAnneeScolaire($annee)
            ->where('id_matiere',$matiereId)
            ->where('sequence',$sequence)
            ->whereIn('id_eleve',$elevesIds)
            ->get();
    }

    private function getClasseMatiere($classeId, $matiereId, $cycleId, $nniveauId)
    {
        return classeMatiere::where('id_classe',$classeId)
            ->where('id_matiere',$matiereId)
            ->where('id_cycle',$cycleId)
            ->where('id_nniveau',$nniveauId)
            ->first();
    }

    private function getModules($classeId, $cycleId, $nniveauId, $semestre)
    {
        return DB::table('matiere as m')
            ->join('groupe_matieres as gm','gm.id_matiere_mere','=','m.id')
            ->where('gm.id_classe',$classeId)
            ->where('gm.id_nniveau',$nniveauId)
            ->where('gm.semestre',$semestre)
            ->select('m.*','gm.id_matiere_mere')
            ->groupBy('gm.id_matiere_mere')
            ->get();
    }
   /* public function print_note(Request $request, $id_classe)
    {
        // ----------------------------------------------------
        // 1) VALIDATION
        // ----------------------------------------------------
        $this->validate($request, [
            'sequence' => 'required|numeric',
            'cycle'    => 'required|numeric',
            'nniveau'  => 'required|numeric',
        ]);

        $sequence = intval($request->input('sequence'));
        $id_matiere = intval($request->input('id'));
        $id_cycle   = intval($request->input('cycle'));
        $nniveau    = intval($request->input('nniveau'));
        $annee      = session('year');

        // ----------------------------------------------------
        // 2) RECUPERATION MATIERE - CLASSE_MATIERE
        // ----------------------------------------------------
        $matiere = matiere::find($id_matiere);
        if (!$matiere) {
            abort(404, "Matière introuvable");
        }

        $cm = classeMatiere::where("id_classe", $id_classe)
            ->where("id_matiere", $id_matiere)
            ->where("id_cycle", $id_cycle)
            ->where("id_nniveau", $nniveau)
            ->first();

        if (!$cm) {
            session(['erreur' => "Aucun programme trouvé pour cette matière."]);
            return redirect()->back();
        }

        // ----------------------------------------------------
        // 3) RÉCUPÉRATION DE LA SESSION_EXAM
        // ----------------------------------------------------
        $session = Session_exam::where("id_classe_matiere", $cm->id)
            ->where("annee_academique", $annee)
            ->first();

        if (!$session) {
            session(['erreur' => "Aucune session configurée pour cette matière."]);
            return redirect()->back();
        }

        // ----------------------------------------------------
        // 4) OBTENTION DU POURCENTAGE DE LA SÉQUENCE
        // ----------------------------------------------------
        $pourcentage = $this->getPourcentageBySequence($session, $sequence);

        // ----------------------------------------------------
        // 5) RÉCUPÉRATION LISTE ÉLÈVES
        // ----------------------------------------------------
        $eleves = DB::select("
        SELECT e.id, e.nom, e.prenom, e.matricule
        FROM eleve e
        JOIN inscription i ON i.id_eleve = e.id
        WHERE i.annee_scolaire = ?
        AND i.id_classe = ?
        AND i.id_cycle = ?
        AND i.id_nniveau = ?
        ORDER BY e.nom, e.prenom
    ", [$annee, $id_classe, $id_cycle, $nniveau]);

        // Ids élèves
        $ids_eleves = array();
        foreach ($eleves as $e) {
            $ids_eleves[] = $e->id;
        }

        // ----------------------------------------------------
        // 6) RECUPERATION DES NOTES DE LA SÉQUENCE
        // ----------------------------------------------------
        $notes = note::where("annee_scolaire", $annee)
            ->where("id_matiere", $id_matiere)
            ->where("sequence", $sequence)
            ->whereIn("id_eleve", $ids_eleves)
            ->get();

        // ----------------------------------------------------
        // 7) CALCUL NOTE BRUTE /20
        // ----------------------------------------------------
        foreach ($notes as $n) {
            if ($pourcentage > 0) {
                $n->brute = round(($n->note * 20) / $pourcentage, 2);
            } else {
                $n->brute = round($n->note, 2);
            }
        }

        // ----------------------------------------------------
        // 8) DÉTERMINATION DU TYPE DE PV À AFFICHER
        // ----------------------------------------------------
        if ($sequence == 1 || $sequence == 2) {
            // ======== CONTROLE CONTINU CC1 / CC2 ========
            return $this->renderCC($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
        }

        if ($sequence == 3 || $sequence == 4) {
            // ======== SESSION NORMALE SN1 / SN2 ========
            return $this->renderSN($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
        }

        if ($sequence == 5 || $sequence == 6) {
            // ======== RATTRAPAGE R1 / R2 ========
            return $this->renderRattrapage($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
        }

        if ($sequence == 7 || $sequence == 8) {
            // ======== PV DE SEMESTRE (CC + SN + TPE) ========
            return $this->renderPVSemestre($eleves, $id_classe, $id_cycle, $nniveau, $sequence);
        }

        if ($sequence == 9) {
            // ======== BILAN ANNUEL ========
            return $this->renderPVAnnuel($eleves, $id_classe, $id_cycle, $nniveau);
        }

        abort(404, "Séquence inconnue");
    }*/


    public function print_note(Request $request, $id_classe)
    {
        // 1) Validation
        $this->validate($request, array(
            'sequence' => 'required|numeric',
            'cycle'    => 'required|numeric',
            'nniveau'  => 'required|numeric',
        ));

        $sequence   = (int)$request->input('sequence');
        $id_matiere = $request->has('id') ? (int)$request->input('id') : null;
        $id_cycle   = (int)$request->input('cycle');
        $nniveau    = (int)$request->input('nniveau');
        $annee      = session('year');

        // ----------------------------------------------------
        // CAS 1 : PAS de matière => PV GLOBAL (par semestre ou annuel)
        // ----------------------------------------------------




        if ($id_matiere === null) {



            if (in_array($sequence, [1, 2])) {

                /* ===============================
                 * 1. Récupération des élèves
                 * =============================== */
                $eleves = DB::select(
                    "SELECT e.id, e.nom, e.prenom, e.matricule
         FROM eleve e
         JOIN inscription i ON i.id_eleve = e.id
         WHERE i.annee_scolaire = ?
           AND i.id_classe      = ?
           AND i.id_cycle       = ?
           AND i.id_nniveau     = ?
         ORDER BY e.nom, e.prenom",
                    [$annee, $id_classe, $id_cycle, $nniveau]
                );
                $ids_eleves = []; foreach ($eleves as $e) { $ids_eleves[] = $e->id; }

//                $ids_eleves = array_map(fn($e) => $e->id, $eleves);

    /* ===============================
     * 2. Sessions CC configurées
     * =============================== */
                $sessions = DB::table('session_exams as se')
                    ->join('classe_matiere as cm', 'cm.id', '=', 'se.id_classe_matiere')
                    ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
                    ->where('cm.id_classe', $id_classe)
                    ->where('cm.id_cycle', $id_cycle)
                    ->where('cm.id_nniveau', $nniveau)
                    ->where('se.annee_academique', $annee)
                    ->where("se.cc{$sequence}", '>', 0)
                    ->select(
                        'se.id as id_session',
                        'm.nom',
                        'cm.coef as credit',
                        DB::raw("se.cc{$sequence} as pourcentage_cc")
                    )
                    ->orderBy('m.nom')
                    ->get();


                // Récupérer tous les IDs de session $session_ids = [];
                 foreach ($sessions as $s) {
                     $session_ids[] = $s->id_session;
                 }
// Récupérer tous les IDs de session $session_ids = [];
// foreach ($sessions as $s) { $session_ids[] = $s->id_session; }
//    $session_ids = $sessions->pluck('id_session')->toArray();

    /* ===============================
     * 3. Notes
     * =============================== */
    $notes = note::where('annee_scolaire', $annee)
        ->where('sequence', $sequence) // 🔴 LIGNE MANQUANTE
        ->whereIn('id_session', $session_ids)
        ->whereIn('id_eleve', $ids_eleves)
        ->get();
    /*dump($notes);*/

    /* ===============================
     * 4. Mapping notes
     * =============================== */
    $notes_map = [];
    foreach ($notes as $n) {
        $notes_map[$n->id_eleve][$n->id_session] = round((float)$n->note, 2);
    }
/*    dump($notes_map);*/

    /* ===============================
     * 5. Calculs par élève
     * =============================== */
    $results = [];

    foreach ($eleves as $eleve) {

        $total_notes  = 0;
        $total_credits = 0;
        $somme_notes = 0;
        $nb_matieres = 0;


        $data = [
            'eleve'            => $eleve,
            'cc'               => [],
            'moyenne'          => 0,
            'mgp'              => 0,
            'credit_prevus'    => 0,
            'credit_valides'   => 0,
            'non_valides'      => [],
        ];

        foreach ($sessions as $matiere) {

            $credit = $matiere->credit;
            $total_credits += $credit;
            $data['credit_prevus'] += $credit;

            // Note ou 0 si absente
         /*   if (isset($notes_map[$eleve->id]) && isset($notes_map[$eleve->id][$matiere->id_session])) {
                $note = $notes_map[$eleve->id][$matiere->id_session];
            } else {
                $note = 0;
            }*/
            if (isset($notes_map[$eleve->id]) && isset($notes_map[$eleve->id][$matiere->id_session])) {

                $note_stockee = $notes_map[$eleve->id][$matiere->id_session]; // ex: 18
                $pourcentage  = $matiere->pourcentage_cc;                     // ex: 30

                if ($pourcentage > 0) {
                    // 🔑 RETOUR À LA NOTE /20
                    $note = round(($note_stockee * 20) / $pourcentage, 2);
                } else {
                    $note = 0;
                }

            } else {
                $note = 0;
            }





//            $note = $notes_map[$eleve->id][$matiere->id_session] ?? 0;
            $data['cc'][] = $note > 0 ? $note : '-';

            // Calcul moyenne (même si note absente)
//            $total_notes += $note * $credit;
            $somme_notes += $note;
            $nb_matieres++; // chaque matière avec session configurée compte

            // Validation
            if ($note >= 10) {
                $data['credit_valides'] += $credit;
            } else {
                $data['non_valides'][] = $matiere->nom;
            }
        }

        $data['moyenne'] = $total_credits > 0
            ? round($somme_notes / $nb_matieres, 2)
            : 0;

        $data['mgp'] = round($data['moyenne'] / 5, 2);

        $results[] = $data;
    }
             /* dump($data['cc']);
                dump($sessions);
                die();*/
    /* ===============================
     * 6. Classement
     * =============================== */
//    usort($results, fn($a, $b) => $b['moyenne'] <=> $a['moyenne']);
usort($results, function($a, $b) { if ($a['moyenne'] == $b['moyenne'])
    return 0;
return ($a['moyenne'] < $b['moyenne']) ? 1 : -1; });
    foreach ($results as $k => &$r) {
        $r['rang'] = $k + 1;
    }

    /* ===============================
     * 7. Objets & vue
     * =============================== */
    return view('eleves.print_pv_cc', [
        'matieres'       => $sessions,
        'results'        => $results,
        'id_classe_obj'  => classe::find($id_classe),
        'id_cycle_obj'   => cycle::find($id_cycle),
        'nniveau_obj'    => NNiveau::find($nniveau),
        'sequence'       => $sequence,
        'annee'          => $annee,
        'titre'          => trans('admin.PV'),
    ]);
}





            // PV GLOBAL DE SEMESTRE (SN1 / SN2)
            if ($sequence == 3 || $sequence == 4) {
                $semestre = ($sequence == 3) ? 1 : 2;

                $data = $this->computePVSemestreGlobal(
                    $id_classe,
                    $id_cycle,
                    $nniveau,
                    $annee,
                    $semestre
                );

                // Compléter les infos attendues par print_pv.blade.php
                $data['semestre'] = $semestre;
                $data['classe']   = classe::find($id_classe);
                $data['nniveau']  = NNiveau::find($nniveau);
                $data['id_cycle'] = $id_cycle;
                $data['annee']    = $annee;
                $data['titre']    = trans('admin.PV').' '.trans('admin.semestre').' '.$semestre;

                return view('eleves.print_pv', $data);
            }
            // PV ANNUEL (séquence 9)
            // PV ANNUEL (séquence 9)
            if ($sequence == 9) {

                // 1. Calcul des PV par semestre
                $S1 = $this->computePVSemestreGlobal($id_classe, $id_cycle, $nniveau, $annee, 1);
                $S2 = $this->computePVSemestreGlobal($id_classe, $id_cycle, $nniveau, $annee, 2);

                // 2. Matières ayant AU MOINS une configuration de session sur l’année
                $matieres = DB::table('classe_matiere as cm')
                    ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
                    ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
                    ->where('cm.id_classe', $id_classe)
                    ->where('cm.id_cycle', $id_cycle)
                    ->where('cm.id_nniveau', $nniveau)
                    ->where('se.annee_academique', $annee)
                    ->select(
                        'm.*',
                        'cm.coef as credit',
                        'se.cc1','se.sn1','se.tpe1','se.ratrappage1',
                        'se.cc2','se.sn2','se.tpe2','se.ratrappage2'
                    )
                    ->distinct()
                    ->orderBy('m.nom')
                    ->get();

                // 3. Index PV par élève
                $PVmapS1 = array();
                foreach ($S1['PV'] as $pv) {
                    $PVmapS1[$pv->id_eleve] = $pv;
                }

                $PVmapS2 = array();
                foreach ($S2['PV'] as $pv) {
                    $PVmapS2[$pv->id_eleve] = $pv;
                }

                return view('eleves.print_bilan', array(
                    'eleves'    => $S1['eleves'],
                    'matieres'  => $matieres,
                    'PV_s1_map' => $PVmapS1,
                    'PV_s2_map' => $PVmapS2,
                    'classe'    => classe::find($id_classe),
                    'nniveau'   => NNiveau::find($nniveau),
                    'annee'     => $annee,
                    'titre'     => trans('admin.bilan_annuel')
                ));
            }

            /*    if ($sequence == 9) {
                     // Calcul PV semestre 1 et semestre 2
                     $S1 = $this->computePVSemestreGlobal($id_classe, $id_cycle, $nniveau, $annee, 1);
                     $S2 = $this->computePVSemestreGlobal($id_classe, $id_cycle, $nniveau, $annee, 2);

                     // --- IMPORTANT ---
                     // On ne prend ici que les matières pour lesquelles il existe une configuration
                     // de session_exams pour l'année courante (S1 ou S2). Ainsi, les PV contiendront
                     // des notes pour ces matières.
                     $matieres_all = DB::table('classe_matiere as cm')
                         ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
                         ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
                         ->where('cm.id_classe', $id_classe)
                         ->where('cm.id_cycle', $id_cycle)
                         ->where('cm.id_nniveau', $nniveau)
                         ->where('se.annee_academique', $annee)
                         ->select('m.*', 'cm.id as id_classe_matiere', 'cm.coef as credit')
                         ->distinct()
                         ->orderBy('m.nom')
                         ->get();

                     // Indexer les PV par élève pour S1 et S2 (accès rapide en vue)
                     $PVmapS1 = array();
                     if (isset($S1['PV']) && is_array($S1['PV'])) {
                         foreach ($S1['PV'] as $pv) {
                             if (isset($pv->id_eleve)) $PVmapS1[$pv->id_eleve] = $pv;
                         }
                     }
                     $PVmapS2 = array();
                     if (isset($S2['PV']) && is_array($S2['PV'])) {
                         foreach ($S2['PV'] as $pv) {
                             if (isset($pv->id_eleve)) $PVmapS2[$pv->id_eleve] = $pv;
                         }
                     }

                     // DEBUG temporaire (décommenter si nécessaire)
                     // dd('S1 PV count: '.count($S1['PV']).' | S2 PV count: '.count($S2['PV']).' | matieres_all: '.count($matieres_all));

                     // Préparer données pour la vue (compatible avec la vue que je t'ai fourni)
                     $data = [
                         'eleves'    => $S1['eleves'],   // mêmes élèves
                         'matieres'  => $matieres_all,   // uniquement matières avec session_exams pour l'année
                         'PV_s1_map' => $PVmapS1,
                         'PV_s2_map' => $PVmapS2,
                         'S1'        => $S1,
                         'S2'        => $S2,
                         'classe'    => classe::find($id_classe),
                         'nniveau'   => NNiveau::find($nniveau),
                         'id_cycle'  => $id_cycle,
                         'annee'     => $annee,
                         'titre'     => trans('admin.bilan_annuel'),
                     ];

                     return view('eleves.print_bilan', $data);
                 }*/
        }

        // ----------------------------------------------------
        // CAS 2 : AVEC matière => PV PAR MATIÈRE
        // ----------------------------------------------------

        // 2) Récupération matière
        $matiere = matiere::find($id_matiere);
        if (!$matiere) {
            abort(404, "Matière introuvable");
        }

        // 3) Programme classe_matiere
        $cm = classeMatiere::where("id_classe", $id_classe)
            ->where("id_matiere", $id_matiere)
            ->where("id_cycle",  $id_cycle)
            ->where("id_nniveau", $nniveau)
            ->first();

        if (!$cm) {
            session(array('erreur' => "Aucun programme trouvé pour cette matière."));
            return redirect()->back();
        }

        // 4) Session_exam pour cette matière (pour les CC / SN / RAT, pas pour globale)
        $session = Session_exam::where("id_classe_matiere", $cm->id)
            ->where("annee_academique", $annee)
            ->first();

        // pour CC / SN / RAT : on exige une session
        if (!$session && in_array($sequence, array(1,2,3,4,5,6))) {
            session(array('erreur' => "Aucune session configurée pour cette matière."));
            return redirect()->back();
        }

        // 5) Liste des élèves
        $eleves = DB::select(
            "SELECT e.id, e.nom, e.prenom, e.matricule
         FROM eleve e
         JOIN inscription i ON i.id_eleve = e.id
         WHERE i.annee_scolaire = ?
           AND i.id_classe      = ?
           AND i.id_cycle       = ?
           AND i.id_nniveau     = ?
         ORDER BY e.nom, e.prenom",
            array($annee, $id_classe, $id_cycle, $nniveau)
        );

        $ids_eleves = array();
        foreach ($eleves as $e) {
            $ids_eleves[] = $e->id;
        }

        // 6) Récupération des notes brutes de la séquence (échelle 0–100)
        $notes = note::where("annee_scolaire", $annee)
            ->where("id_matiere", $id_matiere)
            ->where("sequence", $sequence)
            ->whereIn("id_eleve", $ids_eleves)
            ->get();

        // 7) Dispatch selon le type de PV souhaité
        if ($sequence == 1 || $sequence == 2) {
            // CONTROLE CONTINU CC1 / CC2
            return $this->renderCC($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
        }

        if ($sequence == 3 || $sequence == 4) {
            // SESSION NORMALE SN1 / SN2 (PV par matière)
            return $this->renderSN($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
        }

        if ($sequence == 5 || $sequence == 6) {
            // RATTRAPAGE R1 / R2 (PV par matière)
            return $this->renderRattrapage($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
        }

        if ($sequence == 7 || $sequence == 8) {
            // PV DE SEMESTRE pour UNE matière (CC+SN+TPE)
            $data = $this->renderPVSemestre(
                $id_classe,
                $id_matiere,
                $sequence,
                $id_cycle,
                $nniveau,
                $annee,
                false // withModules
            );

            // on complète pour la vue par matière
            $data['classe']  = classe::find($id_classe);
            $data['nniveau'] = NNiveau::find($nniveau);
            $data['matiere'] = $matiere;
            $data['id_cycle']= $id_cycle;
            $data['annee']   = $annee;
            $data['titre']   = "PV Semestre ".$data['semestre']." — ".$matiere->nom;

            return view('eleves.print_pv', $data);
        }

        abort(404, "Séquence inconnue");
    }

    public function print_note1234(Request $request, $id_classe)
    {
        // 1) Validation
        $this->validate($request, array(
            'sequence' => 'required|numeric',
            'cycle' => 'required|numeric',
            'nniveau' => 'required|numeric',
        ));

        $sequence = (int)$request->input('sequence');
        $id_matiere = $request->has('id') ? (int)$request->input('id') : null;
        $id_cycle = (int)$request->input('cycle');
        $nniveau = (int)$request->input('nniveau');
        $annee = session('year');

        // ----------------------------------------------------
        // CAS 1 : PAS de matière => PV GLOBAL (par semestre ou annuel)
        // ----------------------------------------------------
        if ($id_matiere === null) {

            // PV GLOBAL DE SEMESTRE (SN1 / SN2)
            if ($sequence == 3 || $sequence == 4) {
                $semestre = ($sequence == 3) ? 1 : 2;

                $data = $this->computePVSemestreGlobal(
                    $id_classe,
                    $id_cycle,
                    $nniveau,
                    $annee,
                    $semestre
                );

                // Compléter les infos attendues par print_pv.blade.php
                $data['semestre'] = $semestre;
                $data['classe'] = classe::find($id_classe);
                $data['nniveau'] = NNiveau::find($nniveau);
                $data['id_cycle'] = $id_cycle;
                $data['annee'] = $annee;
                $data['titre'] = trans('admin.PV') . ' ' . trans('admin.semestre') . ' ' . $semestre;

                return view('eleves.print_pv', $data);
            }



            // ----------------------------------------------------
            // CAS 2 : AVEC matière => PV PAR MATIÈRE
            // ----------------------------------------------------

            // 2) Récupération matière
            $matiere = matiere::find($id_matiere);
            if (!$matiere) {
                abort(404, "Matière introuvable");
            }

            // 3) Programme classe_matiere
            $cm = classeMatiere::where("id_classe", $id_classe)
                ->where("id_matiere", $id_matiere)
                ->where("id_cycle", $id_cycle)
                ->where("id_nniveau", $nniveau)
                ->first();

            if (!$cm) {
                session(array('erreur' => "Aucun programme trouvé pour cette matière."));
                return redirect()->back();
            }

            // 4) Session_exam pour cette matière (pour les CC / SN / RAT, pas pour globale)
            $session = Session_exam::where("id_classe_matiere", $cm->id)
                ->where("annee_academique", $annee)
                ->first();

            // pour CC / SN / RAT : on exige une session
            if (!$session && in_array($sequence, array(1, 2, 3, 4, 5, 6))) {
                session(array('erreur' => "Aucune session configurée pour cette matière."));
                return redirect()->back();
            }

            // 5) Liste des élèves
            $eleves = DB::select(
                "SELECT e.id, e.nom, e.prenom, e.matricule
         FROM eleve e
         JOIN inscription i ON i.id_eleve = e.id
         WHERE i.annee_scolaire = ?
           AND i.id_classe      = ?
           AND i.id_cycle       = ?
           AND i.id_nniveau     = ?
         ORDER BY e.nom, e.prenom",
                array($annee, $id_classe, $id_cycle, $nniveau)
            );

            $ids_eleves = array();
            foreach ($eleves as $e) {
                $ids_eleves[] = $e->id;
            }

            // 6) Récupération des notes brutes de la séquence (échelle 0–100)
            $notes = note::where("annee_scolaire", $annee)
                ->where("id_matiere", $id_matiere)
                ->where("sequence", $sequence)
                ->whereIn("id_eleve", $ids_eleves)
                ->get();

            // 7) Dispatch selon le type de PV souhaité
            if ($sequence == 1 || $sequence == 2) {
                // CONTROLE CONTINU CC1 / CC2
                return $this->renderCC($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
            }

            if ($sequence == 3 || $sequence == 4) {
                // SESSION NORMALE SN1 / SN2 (PV par matière)
                return $this->renderSN($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
            }

            if ($sequence == 5 || $sequence == 6) {
                // RATTRAPAGE R1 / R2 (PV par matière)
                return $this->renderRattrapage($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence);
            }

            if ($sequence == 7 || $sequence == 8) {
                // PV DE SEMESTRE pour UNE matière (CC+SN+TPE)
                $data = $this->renderPVSemestre(
                    $id_classe,
                    $id_matiere,
                    $sequence,
                    $id_cycle,
                    $nniveau,
                    $annee,
                    false // withModules
                );

                // on complète pour la vue par matière
                $data['classe'] = classe::find($id_classe);
                $data['nniveau'] = NNiveau::find($nniveau);
                $data['matiere'] = $matiere;
                $data['id_cycle'] = $id_cycle;
                $data['annee'] = $annee;
                $data['titre'] = "PV Semestre " . $data['semestre'] . " — " . $matiere->nom;

                return view('eleves.print_pv', $data);
            }

            if ($sequence == 9) {
              //  $annee = $annee; session('year') déjà en variable plus haut

                // 1) Calcul PV semestre 1 et semestre 2 (utilise ta fonction existante)
                $S1 = $this->computePVSemestreGlobal($id_classe, $id_cycle, $nniveau, $annee, 1);
                $S2 = $this->computePVSemestreGlobal($id_classe, $id_cycle, $nniveau, $annee, 2);

                // 2) Récupérer TOUTES les matières liées à la classe (pour afficher même si pas de session ni de note)
                $matieres_all = DB::table('classe_matiere as cm')
                    ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
                    ->where('cm.id_classe', $id_classe)
                    ->where('cm.id_cycle', $id_cycle)
                    ->where('cm.id_nniveau', $nniveau)
                    ->select('m.*', 'cm.id as id_classe_matiere', 'cm.coef as credit')
                    ->orderBy('m.nom')
                    ->get();

                // 3) Indexer PV par élève pour accès rapide (par semestre)
                $PVmapS1 = array(); // id_eleve => PVsem
                foreach ($S1['PV'] as $pv) {
                    $PVmapS1[$pv->id_eleve] = $pv;
                }
                $PVmapS2 = array();
                foreach ($S2['PV'] as $pv) {
                    $PVmapS2[$pv->id_eleve] = $pv;
                }

                // 4) Préparer données pour la vue
                $data = [
                    'eleves' => $S1['eleves'],        // mêmes élèves attendus
                    'matieres' => $matieres_all,        // toutes les matières de la classe
                    'PV_s1_map' => $PVmapS1,             // indexé par id_eleve
                    'PV_s2_map' => $PVmapS2,
                    'S1' => $S1,
                    'S2' => $S2,
                    'classe' => classe::find($id_classe),
                    'nniveau' => NNiveau::find($nniveau),
                    'id_cycle' => $id_cycle,
                    'annee' => $annee,
                    'titre' => trans('admin.bilan_annuel'),
                ];

                return view('eleves.print_bilan', $data);
            }


            abort(404, "Séquence inconnue");
        }
    }

    private function computePVSemestreGlobal($classeId, $id_cycle, $nniveau, $annee, $semestre)
    {
        // 1) élèves
        $eleves = DB::select("
        SELECT e.* FROM eleve e
        JOIN inscription i ON i.id_eleve = e.id
        WHERE i.annee_scolaire = ?
          AND i.id_classe      = ?
          AND i.id_cycle       = ?
          AND i.id_nniveau     = ?
        ORDER BY e.nom, e.prenom
    ", array($annee, $classeId, $id_cycle, $nniveau));
        $elevesIds = array_map(function($e){ return $e->id; }, $eleves);

        // 2) sélectionner les matières qui ont une session_exams configurée POUR CE SEMESTRE
        // On vérifie que la ligne session_exams existe et qu'une des colonnes pour le semestre est > 0
        if ($semestre == 1) {
            $matieres = DB::table('classe_matiere as cm')
                ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
                ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
                ->where('cm.id_classe', $classeId)
                ->where('cm.id_cycle',  $id_cycle)
                ->where('cm.id_nniveau',$nniveau)
                ->where('se.annee_academique', $annee)
                ->where(function($q){
                    $q->where('se.cc1','>','0')
                        ->orWhere('se.sn1','>','0')
                        ->orWhere('se.tpe1','>','0')
                        ->orWhere('se.ratrappage1','>','0');
                })
                ->select('m.*', 'cm.id as id_classe_matiere', 'cm.coef as credit', 'se.id as id_session')
                ->orderBy('m.nom')
                ->get();
        } else {
            $matieres = DB::table('classe_matiere as cm')
                ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
                ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
                ->where('cm.id_classe', $classeId)
                ->where('cm.id_cycle',  $id_cycle)
                ->where('cm.id_nniveau',$nniveau)
                ->where('se.annee_academique', $annee)
                ->where(function($q){
                    $q->where('se.cc2','>','0')
                        ->orWhere('se.sn2','>','0')
                        ->orWhere('se.tpe2','>','0')
                        ->orWhere('se.ratrappage2','>','0');
                })
                ->select('m.*', 'cm.id as id_classe_matiere', 'cm.coef as credit', 'se.id as id_session')
                ->orderBy('m.nom')
                ->get();
        }

        $nb_matieres = count($matieres);

        // 3) init PV map
        $PV_map = array();
        $sumNote100 = array();
        $sumCoef = array();
        foreach ($eleves as $e) {
            $PV_map[$e->id] = new PVsem($e->id, array(), 0);
            $sumNote100[$e->id] = 0;
            $sumCoef[$e->id] = 0;
        }

        // 4) pour chaque matière, utiliser renderPVSemestre pour obtenir PV par élève
        foreach ($matieres as $m) {
            $res = $this->renderPVSemestre(
                $classeId,
                $m->id,
                ($semestre == 1 ? 3 : 4), // point d'entrée SN1/SN2 pour le semestre
                $id_cycle,
                $nniveau,
                $annee,
                false
            );
//           dd($res['PV'][0]->matieres);

            foreach ($res['PV'] as $pvMat) {
                $id_eleve = $pvMat->id_eleve;
                if (!isset($PV_map[$id_eleve])) {
                    $PV_map[$id_eleve] = new PVsem($id_eleve, array(), 0);
                    $sumNote100[$id_eleve] = 0;
                    $sumCoef[$id_eleve] = 0;
                }

                // pvMat->matieres contient 1 élément (pour cette matière)
                foreach ($pvMat->matieres as $pm) {
                    $PV_map[$id_eleve]->matieres[] = $pm;

                    // si note finale /20 existe, on convertit en 0-100 pour faire la moyenne pondérée MGP
                    if (isset($pm->note) && $pm->note !== null) {
                        $note20 = floatval($pm->note);
                        $note100 = $note20 * 5.0; // 20 -> 100
                        $coef = isset($m->credit) ? floatval($m->credit) : 1.0;
                        $sumNote100[$id_eleve] += ($note100 * $coef);
                        $sumCoef[$id_eleve] += $coef;
                    } else {
                        // Si pas de note mais matière doit compter dans le mgp (tu as demandé ça),
                        // on doit compter coef (et note 0) - donc add coef au dénominateur
                        $coef = isset($m->credit) ? floatval($m->credit) : 1.0;
                        $sumCoef[$id_eleve] += $coef;
                        // sumNote100 += 0
                    }
                }
            }
        }


        // 5) calcul mgp global (0-100) par élève
        foreach ($PV_map as $id_eleve => $pv) {
            if ($sumCoef[$id_eleve] > 0) {
                $pv->mgp = round($sumNote100[$id_eleve] / $sumCoef[$id_eleve], 2);
            } else {
                $pv->mgp = 0;
            }
        }

        return array(
            'eleves' => $eleves,
            'matieres' => $matieres,
            'PV' => array_values($PV_map),
            'nb_matieres' => $nb_matieres,
            'semestre' => $semestre,
        );
    }


    private function computePVSemestreGlobalquidonne($classeId, $id_cycle, $nniveau, $annee, $semestre)
    {
        // 1) Élèves inscrits
        $eleves = DB::select("
        SELECT e.*
        FROM eleve e
        JOIN inscription i ON i.id_eleve = e.id
        WHERE i.annee_scolaire = ?
          AND i.id_classe      = ?
          AND i.id_cycle       = ?
          AND i.id_nniveau     = ?
        ORDER BY e.nom, e.prenom
    ", array($annee, $classeId, $id_cycle, $nniveau));

        $elevesIds = array();
        foreach ($eleves as $e) {
            $elevesIds[] = $e->id;
        }

        // 2) Matières programmées POUR CE SEMESTRE et AYANT une Session_exam configurée
        if ($semestre == 1) {
            $condSem = "(se.sn1 > 0 OR se.cc1 > 0 OR se.tpe1 > 0 OR se.ratrappage1 > 0)";
        } else {
            $condSem = "(se.sn2 > 0 OR se.cc2 > 0 OR se.tpe2 > 0 OR se.ratrappage2 > 0)";
        }

        $matieres = DB::table('classe_matiere as cm')
            ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
            ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->where('cm.id_classe', $classeId)
            ->where('cm.id_cycle',  $id_cycle)
            ->where('cm.id_nniveau',$nniveau)
            ->where('se.annee_academique', $annee)
            ->whereRaw($condSem)
            ->select('m.*', 'cm.id as id_classe_matiere', 'cm.coef as credit')
            ->orderBy('m.nom')
            ->get();

        $nb_matieres = count($matieres);

        // 3) Initialisation PV_map par élève, et sommes / compte
        $PV_map = array();           // id_eleve => PVsem (avec matieres[])
        $sumTotals = array();        // id_eleve => somme des total20
        $countMat = array();         // id_eleve => nombre de matières (ici on comptera toutes les matières configurées)

        foreach ($eleves as $e) {
            $PV_map[$e->id] = new PVsem($e->id, array(), 0);
            $sumTotals[$e->id] = 0.0;
            $countMat[$e->id] = 0;
        }

        // 4) Pour chaque matière configurée, on calcule le PV semestriel et on fusionne
        $seqSN = ($semestre == 1) ? 3 : 4; // point d’entrée SN1 / SN2

        foreach ($matieres as $m) {

            $res = $this->renderPVSemestre(
                $classeId,
                $m->id,
                $seqSN,
                $id_cycle,
                $nniveau,
                $annee,
                false
            );

            // Construire un index id_eleve => PVsem pour cette matière (résultat de renderPVSemestre)
            $resPVMap = array();
            if (isset($res['PV']) && is_array($res['PV'])) {
                foreach ($res['PV'] as $pvItem) {
                    if (isset($pvItem->id_eleve)) {
                        $resPVMap[$pvItem->id_eleve] = $pvItem;
                    }
                }
            }

            // Pour chaque élève, on rattache la pv_matiereSem (s'il existe) sinon on crée un placeholder
            foreach ($eleves as $e) {
                $id_eleve = $e->id;

                // Si pour cette matière on a un PV pour l'élève
                if (isset($resPVMap[$id_eleve])) {
                    $pvForThisMat = $resPVMap[$id_eleve]; // c'est un PVsem contenant ->matieres (généralement 1 element)
                    // Attacher les pv_matiereSem (s'il y en a)
                    if (isset($pvForThisMat->matieres) && is_array($pvForThisMat->matieres) && count($pvForThisMat->matieres) > 0) {
                        foreach ($pvForThisMat->matieres as $pm) {
                            $PV_map[$id_eleve]->matieres[] = $pm;

                            // Récupérer total20 si présent
                            $total20 = null;
                            if (is_object($pm) && (isset($pm->total20) || property_exists($pm, 'total20'))) {
                                $total20 = $pm->total20;
                            } elseif (is_array($pm) && array_key_exists('total20', $pm)) {
                                $total20 = $pm['total20'];
                            }

                            if ($total20 !== null && $total20 !== '') {
                                $sumTotals[$id_eleve] += (float)$total20;
                            } else {
                                // Pas de total20 — on ajoute 0 (explicit) : comportement demandé
                                $sumTotals[$id_eleve] += 0.0;
                            }

                            // On compte toujours la matière (même si pas de note)
                            $countMat[$id_eleve] += 1;
                        }
                    } else {
                        // Cas improbable : PVsem existe mais pas de matieres -> on considère la matière comptée avec 0
                        $countMat[$id_eleve] += 1;
                        $sumTotals[$id_eleve] += 0.0;
                    }
                } else {
                    // Pas de PV pour cet élève/matière : on doit tout de même compter la matière (et ajouter 0)
                    $countMat[$id_eleve] += 1;
                    $sumTotals[$id_eleve] += 0.0;
                    // On peut aussi ajouter un placeholder pv_matiereSem si tu veux l'afficher, mais pas nécessaire
                }
            }
        }

        // 5) Calcul de la moyenne semestrielle (mgp) par élève : somme des total20 / nombre de matières configurées
        foreach ($PV_map as $id_eleve => $pvObj) {
            if (isset($countMat[$id_eleve]) && $countMat[$id_eleve] > 0) {
                $avg = $sumTotals[$id_eleve] / $countMat[$id_eleve];
                // mgp en /20 (arrondi)
                $pvObj->mgp = round($avg, 2);
            } else {
                // si aucune matière configurée (cas improbable), mgp = 0
                $pvObj->mgp = 0;
            }
        }

        // 6) Retour
        return array(
            'eleves'      => $eleves,
            'matieres'    => $matieres,
            'PV'          => array_values($PV_map),
            'nb_matieres' => $nb_matieres,
            'semestre'    => $semestre,
        );
    }


    private function computePVSemestreGlobal111($classeId, $id_cycle, $nniveau, $annee, $semestre)
    {
        // Élèves
        $eleves = DB::select("
        SELECT e.*
        FROM eleve e
        JOIN inscription i ON i.id_eleve = e.id
        WHERE i.annee_scolaire = ?
          AND i.id_classe      = ?
          AND i.id_cycle       = ?
          AND i.id_nniveau     = ?
        ORDER BY e.nom, e.prenom",
            array($annee, $classeId, $id_cycle, $nniveau)
        );

        $elevesIds = array_map(function($e){ return $e->id; }, $eleves);

        // Matières programmées POUR CE SEMESTRE et AYANT une Session_exam
        $seqSN = ($semestre == 1) ? 3 : 4;
// colonnes à tester selon semestre
        if ($semestre == 1) {
            $condSem = "(se.sn1 > 0 OR se.cc1 > 0 OR se.tpe1 > 0 OR se.ratrappage1 > 0)";
        } else {
            $condSem = "(se.sn2 > 0 OR se.cc2 > 0 OR se.tpe2 > 0 OR se.ratrappage2 > 0)";
        }
        $matieres = DB::table('classe_matiere as cm')
            ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
            ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->where('cm.id_classe', $classeId)
            ->where('cm.id_cycle',  $id_cycle)
            ->where('cm.id_nniveau',$nniveau)
            ->where('se.annee_academique', $annee)
            // n'inclure que les sessions configurées pour le semestre demandé
            ->whereRaw($condSem)
            ->select('m.*', 'cm.id as id_classe_matiere', 'cm.coef as credit')
            ->orderBy('m.nom')
            ->get();
     /*   $matieres = DB::table('classe_matiere as cm')
            ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
            ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->where('cm.id_classe', $classeId)
            ->where('cm.id_cycle',  $id_cycle)
            ->where('cm.id_nniveau',$nniveau)
            ->where('se.annee_academique', $annee)
            ->select('m.*', 'cm.id as id_classe_matiere', 'cm.coef as credit')
            ->orderBy('m.nom')
            ->get();*/

        $nb_matieres = count($matieres);

        // Initialisation
        $PV_map = [];        // map id_eleve => PV object that aggregates matieres
        $sumNote100 = [];    // sum(note20*5 * coef)
        $sumCoef = [];       // sum(coef)

        foreach ($eleves as $e) {
            $PV_map[$e->id] = new PVsem($e->id, array(), 0);
            $sumNote100[$e->id] = 0;
            $sumCoef[$e->id] = 0;
        }

        // Pour chaque matière (déjà filtrée sur session_exams)
        foreach ($matieres as $m) {
            // appel renderPVSemestre (sequence SN pour ce semestre)
            $res = $this->renderPVSemestre(
                $classeId,
                $m->id,    // id_matiere
                $seqSN,    // SN1 ou SN2
                $id_cycle,
                $nniveau,
                $annee,
                false
            );

            foreach ($res['PV'] as $pvMat) {
                $id_eleve = $pvMat->id_eleve;
                if (!isset($PV_map[$id_eleve])) {
                    $PV_map[$id_eleve] = new PVsem($id_eleve, array(), 0); // ou new PVsem si tu as renommé
                    $sumNote100[$id_eleve] = 0;
                    $sumCoef[$id_eleve] = 0;
                }

                // pvMat->matieres contient list (ici 1 element)
                foreach ($pvMat->matieres as $pm) {
                    $PV_map[$id_eleve]->matieres[] = $pm;

                    // note20 = pm->note (null ou float)
                    if ($pm->note !== null) {
                        // On convertit en 0-100 pour aggregation interne comme tu faisais (note20 * 5)
                        $note100 = ($pm->note * 5);

                        // Remplacement de l'opérateur '??' par un test isset (compatible PHP 5.x)
                        if (isset($pm->coef) && $pm->coef !== null) {
                            $coef = $pm->coef;
                        } else {
                            // si $m->credit défini, on l'utilise sinon fallback à 1
                            if (isset($m->credit) && $m->credit !== null) {
                                $coef = $m->credit;
                            } else {
                                $coef = 1;
                            }
                        }

                        $sumNote100[$id_eleve] += ($note100 * $coef);
                        $sumCoef[$id_eleve] += $coef;
                    }
                }
            }

        }

        // Calcul du mgp global 0–100 par élève
        foreach ($PV_map as $id_eleve => $pv) {
            if ($sumCoef[$id_eleve] > 0) {
                $pv->mgp = round($sumNote100[$id_eleve] / $sumCoef[$id_eleve], 2);
            } else {
                $pv->mgp = 0;
            }
        }

        // Retour
        return array(
            'eleves'      => $eleves,
            'matieres'    => $matieres,
            'PV'          => array_values($PV_map),
            'nb_matieres' => $nb_matieres,
            'semestre'    => $semestre,
        );
    }


    private function computePVSemestreGlobal1($classeId, $id_cycle, $nniveau, $annee, $semestre)
    {
        // 1) Élèves
        $eleves = DB::select("
        SELECT e.*
        FROM eleve e
        JOIN inscription i ON i.id_eleve = e.id
        WHERE i.annee_scolaire = ?
          AND i.id_classe      = ?
          AND i.id_cycle       = ?
          AND i.id_nniveau     = ?
        ORDER BY e.nom, e.prenom
    ", array($annee, $classeId, $id_cycle, $nniveau));

        $elevesIds = array();
        foreach ($eleves as $e) {
            $elevesIds[] = $e->id;
        }

        // 2) Matières programmées POUR CE SEMESTRE et AYANT une Session_exam
        $seqSN = ($semestre == 1) ? 3 : 4; // point d’entrée SN1 / SN2

        $matieres = DB::table('classe_matiere as cm')
            ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
            ->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->where('cm.id_classe', $classeId)
            ->where('cm.id_cycle',  $id_cycle)
            ->where('cm.id_nniveau',$nniveau)
            ->where('se.annee_academique', $annee)
            ->select('m.*', 'cm.id as id_classe_matiere', 'cm.coef as credit')
            ->orderBy('m.nom')
            ->get();

        $nb_matieres = count($matieres);

        // 3) Initialisation PV globaux par élève
        $PV         = array();
        $sumNote100 = array();
        $sumCoef    = array();

        foreach ($eleves as $e) {
            $PV[$e->id]         = new PV($e->id, array(), 0);
            $sumNote100[$e->id] = 0;
            $sumCoef[$e->id]    = 0;
        }

        // 4) Pour chaque matière, on calcule la note finale de semestre via renderPVSemestre()
        foreach ($matieres as $m) {

            $res = $this->renderPVSemestre(
                $classeId,
                $m->id,       // id_matiere
                $seqSN,       // 3 ou 4
                $id_cycle,
                $nniveau,
                $annee,
                false         // withModules
            );

            // $res['PV'] = array de PV par élève pour CETTE matière
            foreach ($res['PV'] as $pvMat) {

                $id_eleve = $pvMat->id_eleve;

                if (!isset($PV[$id_eleve])) {
                    $PV[$id_eleve]         = new PV($id_eleve, array(), 0);
                    $sumNote100[$id_eleve] = 0;
                    $sumCoef[$id_eleve]    = 0;
                }

                // Ajout de la matière dans la liste
                foreach ($pvMat->matieres as $pm) {
                    $PV[$id_eleve]->matieres[] = $pm;
                }

                // mgp de $pvMat = note finale sur 20 pour CETTE matière
                $note20 = $pvMat->mgp;
                $coef   = $m->credit;

                // On remonte sur échelle 0–100 pour la cohérence MGP globale
                $note100 = $note20 * 5;


                $sumNote100[$id_eleve] += ($note100 * $coef);
                $sumCoef[$id_eleve]    += $coef;
            }
        }

        // 5) Calcul du mgp global 0–100 par élève
        foreach ($PV as $id_eleve => $pv) {
            if ($sumCoef[$id_eleve] > 0) {
                $pv->mgp = round($sumNote100[$id_eleve] / $sumCoef[$id_eleve], 2);
            } else {
                $pv->mgp = 0;
            }
        }
       /* dump($note20);
        dump($note100);
        dump($pv);
        die();*/

        // 6) Retour pour la vue print_pv
        return array(
            'eleves'      => $eleves,
            'matieres'    => $matieres,
            'PV'          => array_values($PV),
            'nb_matieres' => $nb_matieres,
            'semestre'    => $semestre,
        );
    }


    /**
     * PV GLOBAL ANNUEL (fusion Semestre 1 + Semestre 2)
     * On s'appuie sur computePVSemestreGlobal pour S1 et S2.
     */

    private function computePVAnnuelGlobal($classeId, $id_cycle, $nniveau, $annee)
    {
        $S1 = $this->computePVSemestreGlobal($classeId, $id_cycle, $nniveau, $annee, 1);
        $S2 = $this->computePVSemestreGlobal($classeId, $id_cycle, $nniveau, $annee, 2);

        $eleves = $S1['eleves'];
        // pour la liste des matières annuelles on prend l'union des deux listes
        $matMap = [];
        foreach ($S1['matieres'] as $m) { $matMap[$m->id] = $m; }
        foreach ($S2['matieres'] as $m) { $matMap[$m->id] = $m; }
        $matieres = array_values($matMap);
        $nb_mat = count($matieres);

        // Index PV par élève
        $PV1 = []; foreach ($S1['PV'] as $pv) $PV1[$pv->id_eleve] = $pv;
        $PV2 = []; foreach ($S2['PV'] as $pv) $PV2[$pv->id_eleve] = $pv;

        $PV_Annuel = [];
        foreach ($eleves as $e) {
            $id_eleve = $e->id;
            $mgp1 = isset($PV1[$id_eleve]) ? $PV1[$id_eleve]->mgp : 0;
            $mgp2 = isset($PV2[$id_eleve]) ? $PV2[$id_eleve]->mgp : 0;
            $mgpAnnuel = round(($mgp1 + $mgp2) / 2, 2);

            // Fusion des matières (préserve notes disponibles)
            $matList = [];
            // de S1
            if (isset($PV1[$id_eleve])) {
                foreach ($PV1[$id_eleve]->matieres as $pm) $matList[$pm->id_matiere] = $pm;
            }
            // de S2 (remplace si S2 a valeur)
            if (isset($PV2[$id_eleve])) {
                foreach ($PV2[$id_eleve]->matieres as $pm) $matList[$pm->id_matiere] = $pm;
            }
            $PV_Annuel[] = new PVsem($id_eleve, array_values($matList), $mgpAnnuel);
        }

        return [
            'eleves' => $eleves,
            'matieres' => $matieres,
            'PV' => $PV_Annuel,
            'nb_matieres' => $nb_mat,
            'semestre' => 'Annuel',
        ];
    }



    private function computePVAnnuelGlobalquidonne($classeId, $id_cycle, $nniveau, $annee)
    {
        $S1 = $this->computePVSemestreGlobal($classeId, $id_cycle, $nniveau, $annee, 1);
        $S2 = $this->computePVSemestreGlobal($classeId, $id_cycle, $nniveau, $annee, 2);

        $eleves = $S1['eleves'];
        $matieres = $S1['matieres'];
        $nb_mat = $S1['nb_matieres'];

        // Indexer PV par élève
        $PV1 = [];
        foreach ($S1['PV'] as $pv) $PV1[$pv->id_eleve] = $pv;
        $PV2 = [];
        foreach ($S2['PV'] as $pv) $PV2[$pv->id_eleve] = $pv;

        $PV_Annuel = [];

        foreach ($eleves as $e) {
            $id_eleve = $e->id;
            $mgp1 = isset($PV1[$id_eleve]) ? $PV1[$id_eleve]->mgp : 0;
            $mgp2 = isset($PV2[$id_eleve]) ? $PV2[$id_eleve]->mgp : 0;

            // Moyenne annuelle 0–100
            $mgpAnnuel = round(($mgp1 + $mgp2) / 2, 2);

            // Pour les matières annuelles on prend idéalement la fusion des matières S1+S2:
            // ici on prend la liste de matieres de S2 si présente, sinon S1
            $matieresAnnuel = [];
            if (isset($PV2[$id_eleve]) && !empty($PV2[$id_eleve]->matieres)) {
                $matieresAnnuel = $PV2[$id_eleve]->matieres;
            } elseif (isset($PV1[$id_eleve])) {
                $matieresAnnuel = $PV1[$id_eleve]->matieres;
            }

            $PV_Annuel[] = new PV($id_eleve, $matieresAnnuel, $mgpAnnuel);
        }

        return array(
            'eleves'      => $eleves,
            'matieres'    => $matieres,
            'PV'          => $PV_Annuel,
            'nb_matieres' => $nb_mat,
            'semestre'    => 'Annuel',
        );
    }

    private function computePVAnnuelGlobal1($classeId, $id_cycle, $nniveau, $annee)
    {
        // Semestre 1
        $S1 = $this->computePVSemestreGlobal($classeId, $id_cycle, $nniveau, $annee, 1);
        // Semestre 2
        $S2 = $this->computePVSemestreGlobal($classeId, $id_cycle, $nniveau, $annee, 2);

        $eleves   = $S1['eleves'];     // mêmes élèves
        $matieres = $S1['matieres'];   // structure des matières (à adapter si besoin)
        $nb_mat   = $S1['nb_matieres'];

        // Indexer les PV par élève pour S1 et S2
        $PV1 = array();
        foreach ($S1['PV'] as $pv) {
            $PV1[$pv->id_eleve] = $pv;
        }
        $PV2 = array();
        foreach ($S2['PV'] as $pv) {
            $PV2[$pv->id_eleve] = $pv;
        }

        $PV_Annuel = array();

        foreach ($eleves as $e) {

            $id_eleve = $e->id;

            $mgp1 = isset($PV1[$id_eleve]) ? $PV1[$id_eleve]->mgp : 0;
            $mgp2 = isset($PV2[$id_eleve]) ? $PV2[$id_eleve]->mgp : 0;

            // Moyenne annuelle 0–100 = moyenne de mgp S1 et S2
            $mgpAnnuel = round(($mgp1 + $mgp2) / 2, 2);

            // Pour la liste des matières, on peut prendre celles du S2
            // si tu veux fusionner plus finement, il faudra adapter ici.
            $matieresAnnuel = array();
            if (isset($PV2[$id_eleve])) {
                $matieresAnnuel = $PV2[$id_eleve]->matieres;
            } elseif (isset($PV1[$id_eleve])) {
                $matieresAnnuel = $PV1[$id_eleve]->matieres;
            }

            $PV_Annuel[] = new PV(
                $id_eleve,
                $matieresAnnuel,
                $mgpAnnuel
            );
        }

        return array(
            'eleves'      => $eleves,
            'matieres'    => $matieres,
            'PV'          => $PV_Annuel,
            'nb_matieres' => $nb_mat,
            'semestre'    => 'Annuel',
        );
    }

    /*
     * PV PAR SEMESTRE (par matière)
     * Gère : CC, SN, RAT, TPE, modules/UV, pondération Session_exam
     */
    private function renderPVSemestreTTTT(
        $classe_id,
        $matiere_id,
        $sequence,
        $id_cycle,
        $nniveau,
        $annee,
        $withModules = false
    ) {

        /* ===============================
           1) CLASSE_MATIERE & SESSION
           =============================== */
        $cm = classeMatiere::where(array(
            'id_classe'  => $classe_id,
            'id_matiere' => $matiere_id,
            'id_cycle'   => $id_cycle,
            'id_nniveau' => $nniveau,
        ))->first();

        if (!$cm) {
            throw new \Exception("classe_matiere introuvable");
        }

        $session = Session_exam::where(array(
            'id_classe_matiere' => $cm->id,
            'annee_academique'  => $annee,
        ))->first();

        if (!$session) {
            throw new \Exception("session_exam introuvable");
        }

        /* ===============================
           2) ÉLÈVES
           =============================== */
        $eleves = DB::select(
            "SELECT e.* FROM eleve e
         JOIN inscription i ON i.id_eleve = e.id
         WHERE i.annee_scolaire=?
           AND i.id_classe=?
           AND i.id_cycle=?
           AND i.id_nniveau=?
         ORDER BY e.nom, e.prenom",
            array($annee, $classe_id, $id_cycle, $nniveau)
        );

        $elevesIds = array_map(function($e){
            return $e->id;
        }, $eleves);

        /* ===============================
           3) SEMESTRE & SÉQUENCES
           =============================== */
        $semestre = ($sequence == 3 || $sequence == 1 || $sequence == 5 || $sequence == 7) ? 1 : 2;

        if ($semestre == 1) {
            $seq_cc = 1; $seq_sn = 3; $seq_tpe = 7; $seq_rat = 5;
            $p_cc  = (float)$session->cc1;
            $p_sn  = (float)$session->sn1;
            $p_tpe = (float)$session->tpe1;
            $p_rat = (float)$session->rattrapage1;
        } else {
            $seq_cc = 2; $seq_sn = 4; $seq_tpe = 8; $seq_rat = 6;
            $p_cc  = (float)$session->cc2;
            $p_sn  = (float)$session->sn2;
            $p_tpe = (float)$session->tpe2;
            $p_rat = (float)$session->rattrapage2;
        }

        /* ===============================
           4) NOTES
           =============================== */
        $notes = note::whereIn('id_eleve', $elevesIds)
            ->where('id_matiere', $matiere_id)
            ->where('annee_scolaire', $annee)
            ->get();
        dd([
            'matiere_id' => $matiere_id,
            'annee'      => $annee,
            'notes_count'=> $notes->count(),
            'sample'     => $notes->take(5)
        ]);
        die();

        $find = function($seq, $id) use ($notes) {
            return $notes->first(function($n) use ($seq, $id) {

                if (!is_object($n)) return false;
                if (!isset($n->sequence) || !isset($n->id_eleve)) return false;

                return ($n->sequence == $seq && $n->id_eleve == $id);
            });
        };

        $to20 = function($val, $p) {
            if ($val === null || $p <= 0) return null;
            return round(($val * 20) / $p, 2);
        };

        /* ===============================
           5) CONSTRUCTION DU PV
           =============================== */
        $PV = array();

        foreach ($eleves as $e) {

            $cc  = $find($seq_cc,  $e->id);
            $sn  = $find($seq_sn,  $e->id);
            $tpe = $find($seq_tpe, $e->id);
            $rat = $find($seq_rat, $e->id);

            $v_cc  = $cc  ? $to20($cc->note,  $p_cc)  : null;
            $v_sn  = $sn  ? $to20($sn->note,  $p_sn)  : null;
            $v_tpe = $tpe ? $to20($tpe->note, $p_tpe) : null;
            $v_rat = $rat ? $to20($rat->note, $p_rat) : null;

            if ($v_rat !== null) {
                $total20 = $v_rat;
            } else {
                $total20 = 0;
                if ($v_cc  !== null) $total20 += $v_cc  * ($p_cc  / 100);
                if ($v_sn  !== null) $total20 += $v_sn  * ($p_sn  / 100);
                if ($v_tpe !== null) $total20 += $v_tpe * ($p_tpe / 100);
                $total20 = round($total20, 2);
            }

            $pvMat = new pv_matiere(
                $matiere_id,
                $total20,
                $cm->coef,
                $v_cc,
                $v_sn,
                $v_tpe,
                null
            );

            $pvMat->cc      = $v_cc;
            $pvMat->sn      = $v_sn;
            $pvMat->total20 = $total20;
            $pvMat->credit  = $cm->coef;

            $PV[] = new PVsem($e->id, array($pvMat), $total20);
        }

        return array(
            'eleves'   => $eleves,
            'PV'       => $PV,
            'semestre' => $semestre,
            'annee'    => $annee
        );
    }



    private function renderPVSemestre(
        $classe_id,
        $matiere_id,
        $sequence,
        $id_cycle,
        $nniveau,
        $annee,
        $withModules = false
    ) {
        // 1) classe_matiere et session
        $cm = classeMatiere::where('id_classe', $classe_id)
            ->where('id_matiere', $matiere_id)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $nniveau)
            ->first();

        if (!$cm) {
            throw new \Exception("Aucun programme (classe_matiere) pour cette matière");
        }

        $session = Session_exam::where('id_classe_matiere', $cm->id)
            ->where('annee_academique', $annee)
            ->first();

        if (!$session) {
            throw new \Exception("Session_exam introuvable pour classe_matiere=".$cm->id);
        }

        // 2) élèves inscrits
        $eleves = DB::select(
            'SELECT e.* FROM eleve e JOIN inscription i ON i.id_eleve = e.id
         WHERE i.annee_scolaire = ? AND i.id_classe = ? AND i.id_cycle = ? AND i.id_nniveau = ?
         ORDER BY e.nom, e.prenom',
            array($annee, $classe_id, $id_cycle, $nniveau)
        );
        $elevesIds = array_map(function($e){ return $e->id; }, $eleves);

        // 3) map des séquences
        $map = array(
            1 => array('type' => 'cc',  'sem' => 1, 'sn' => 3, 'rat' => 5, 'tpe' => 7),
            2 => array('type' => 'cc',  'sem' => 2, 'sn' => 4, 'rat' => 6, 'tpe' => 8),
            3 => array('type' => 'sn',  'sem' => 1, 'cc' => 1, 'rat' => 5, 'tpe' => 7),
            4 => array('type' => 'sn',  'sem' => 2, 'cc' => 2, 'rat' => 6, 'tpe' => 8),
            5 => array('type' => 'rat', 'sem' => 1, 'cc' => 1, 'sn'  => 3, 'tpe' => 7),
            6 => array('type' => 'rat', 'sem' => 2, 'cc' => 2, 'sn'  => 4, 'tpe' => 8),
            7 => array('type' => 'tpe', 'sem' => 1, 'cc' => 1, 'sn'  => 3, 'rat' => 5),
            8 => array('type' => 'tpe', 'sem' => 2, 'cc' => 2, 'sn'  => 4, 'rat' => 6),
        );

        if (!isset($map[$sequence])) {
            throw new \Exception("Séquence inconnue : ".$sequence);
        }

        $semestre = $map[$sequence]['sem'];

        // si la séquence courante est du type CC/SN/RAT/TPE, forçons la variable correspondante
        $seq_cc  = isset($map[$sequence]['cc'])  ? $map[$sequence]['cc']  : null;
        $seq_sn  = isset($map[$sequence]['sn'])  ? $map[$sequence]['sn']  : null;
        $seq_rat = isset($map[$sequence]['rat']) ? $map[$sequence]['rat'] : null;
        $seq_tpe = isset($map[$sequence]['tpe']) ? $map[$sequence]['tpe'] : null;

        $type = isset($map[$sequence]['type']) ? $map[$sequence]['type'] : null;
        if ($type === 'cc')  { $seq_cc  = $sequence; }
        if ($type === 'sn')  { $seq_sn  = $sequence; }
        if ($type === 'rat') { $seq_rat = $sequence; }
        if ($type === 'tpe') { $seq_tpe = $sequence; }

        // 4) Chargement brut des notes (toutes séquences pertinentes pour la matière & année)
        $notes_all = note::whereIn('id_eleve', $elevesIds)
            ->where('id_matiere', $matiere_id)
            ->where('annee_scolaire', $annee)
            ->get();

        // 5) helper : conversion stock -> valeur sur 20 pour affichage
        $convertTo20 = function($noteStockee, $pourc) {
            if ($noteStockee === null) return null;
            $pourc = floatval($pourc);
            if ($pourc <= 0) {
                // si pas de pourcentage configuré, on suppose que la note est déjà sur 20
                return round(floatval($noteStockee), 2);
            }
            // noteStockee = rawSaisie * (pourc/20)
            // => rawSaisie (/20) = noteStockee * 20 / pourc
            return round((floatval($noteStockee) * 20.0) / $pourc, 2);
        };

        // petit util pour chercher dans une collection / array result
        $findNoteForEleve = function($collection, $id_eleve) {
            foreach ($collection as $n) {
                if (isset($n->id_eleve) && $n->id_eleve == $id_eleve) return $n;
            }
            return null;
        };

        // 6) Pourcentages via session
        $p_cc  = $this->getPourcentage($session, $seq_cc);
        $p_sn  = $this->getPourcentage($session, $seq_sn);
        $p_rat = $this->getPourcentage($session, $seq_rat);
        $p_tpe = $this->getPourcentage($session, $seq_tpe);

        // Préparer listes filtrées (compatibilité)
        $list_cc  = ($seq_cc !== null)  ? $notes_all->filter(function($n) use ($seq_cc) { return isset($n->sequence) && $n->sequence == $seq_cc; })  : collect();
        $list_sn  = ($seq_sn !== null)  ? $notes_all->filter(function($n) use ($seq_sn) { return isset($n->sequence) && $n->sequence == $seq_sn; })  : collect();
        $list_rat = ($seq_rat !== null) ? $notes_all->filter(function($n) use ($seq_rat) { return isset($n->sequence) && $n->sequence == $seq_rat; }) : collect();
        $list_tpe = ($seq_tpe !== null) ? $notes_all->filter(function($n) use ($seq_tpe) { return isset($n->sequence) && $n->sequence == $seq_tpe; }) : collect();

        // 7) Construction PV par élève
        $PV = array();
        foreach ($eleves as $el) {
            $Ncc  = ($seq_cc  ? $findNoteForEleve($list_cc,  $el->id) : null);
            $Nsn  = ($seq_sn  ? $findNoteForEleve($list_sn,  $el->id) : null);
            $Nrat = ($seq_rat ? $findNoteForEleve($list_rat, $el->id) : null);
            $Ntpe = ($seq_tpe ? $findNoteForEleve($list_tpe, $el->id) : null);

            // convert stored -> /20 for display (but do NOT reapply % multiplicateur)
            $v_cc  = $Ncc  ? $convertTo20($Ncc->note,  $p_cc)  : null;
            $v_sn  = $Nsn  ? $convertTo20($Nsn->note,  $p_sn)  : null;
            $v_rat = $Nrat ? $convertTo20($Nrat->note, $p_rat) : null;
            $v_tpe = $Ntpe ? $convertTo20($Ntpe->note, $p_tpe) : null;

            // Calcul du total /20 pour la matière (priorité rattrapage)
            $total20 = 0;
            if ($v_rat !== null) {
                $total20 = $v_rat;
            } else {
                $sum = 0; $countParts = 0;
                if ($v_cc !== null) { $sum += $v_cc; $countParts++; }
                if ($v_sn !== null) { $sum += $v_sn; $countParts++; }
                // TPE est appliqué comme additionnelle si présente
                if ($v_tpe !== null) { $sum += $v_tpe; $countParts++; }
                // Si on veut normaliser quand les composants sont en /20 et on veut faire (cc+sn+tpe)/5,
                // tu peux remplacer la logique ci-dessous ; pour l'instant on affiche la somme convertie.
                $total20 = $sum;
            }

            // Préparer objet pv_matiere (compatible vue)
          /*  $pvMat = new pv_matiere(
                $matiere_id,
                $total20,               // note finale /20
                $cm->coef,              // coefficient
                $v_cc,                  // cc /20 ou null
                ($v_rat !== null ? $v_rat : $v_sn), // pour affichage SN ou RAT
                $v_tpe,
                0
            );*/
            $pvMat = new pv_matiere(
                $matiere_id,
                $total20,            // note finale /20
                $cm->coef,
                $v_cc,               // controle
                ($v_rat !== null ? $v_rat : $v_sn), // normal
                $v_tpe,
                null
            );

// 🔒 NORMALISATION POUR LES VUES
            $pvMat->cc      = $pvMat->controle;
            $pvMat->sn      = $pvMat->normal;
            $pvMat->total20 = $pvMat->note;



            // mgp pour cette matière = total20 (sur 20) ; sera utilisé dans aggregation globale
            $PV[] = new PVsem($el->id, array($pvMat), $total20);
        }

        // Modules si demandé
        $modules = array();
        if ($withModules) {
            $modules = $this->buildModules(
                $classe_id,
                $id_cycle,
                $nniveau,
                $semestre,
                $PV
            );
        }

        return array(
            'eleves'      => $eleves,
            'PV'          => $PV,
            'modules'     => $modules,
            'semestre'    => $semestre,
            'matiere_id'  => $matiere_id,
            'sequence'    => $sequence,
            'id_cycle'    => $id_cycle,
            'nniveau'     => $nniveau,
            'annee'       => $annee,
            'withModules' => $withModules,
        );
    }


    private function renderPVSemestrequidonne(
        $classe_id,
        $matiere_id,
        $sequence,
        $id_cycle,
        $nniveau,
        $annee,
        $withModules = false
    ) {
        // ------------------------------------
        // 1) Récupérer classe_matiere puis Session_exam
        // ------------------------------------
        $cm = classeMatiere::where('id_classe', $classe_id)
            ->where('id_matiere', $matiere_id)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $nniveau)
            ->first();

        if (!$cm) {
            throw new \Exception(
                "Aucun programme (classe_matiere) pour cette matière ".
                "(classe=$classe_id, matiere=$matiere_id, cycle=$id_cycle, nniveau=$nniveau)"
            );
        }

        $session = Session_exam::where('id_classe_matiere', $cm->id)
            ->where('annee_academique', $annee)
            ->first();

        if (!$session) {
            throw new \Exception(
                "Session_exam introuvable pour classe_matiere=".$cm->id." et année ".$annee
            );
        }

        // ------------------------------------
        // 2) Élèves inscrits
        // ------------------------------------
        $eleves = DB::select(
            'SELECT e.*
         FROM eleve e, inscription i
         WHERE i.annee_scolaire = ?
           AND i.id_classe      = ?
           AND i.id_eleve       = e.id
           AND i.id_cycle       = ?
           AND i.id_nniveau     = ?
         ORDER BY e.nom, e.prenom',
            array($annee, $classe_id, $id_cycle, $nniveau)
        );

        $elevesIds = array();
        foreach ($eleves as $e) {
            $elevesIds[] = $e->id;
        }

        // ------------------------------------
        // 3) Définir la famille de séquences
        // ------------------------------------
        $map = array(
            1 => array('type' => 'cc',  'sem' => 1, 'sn' => 3, 'rat' => 5, 'tpe' => 7),
            2 => array('type' => 'cc',  'sem' => 2, 'sn' => 4, 'rat' => 6, 'tpe' => 8),
            3 => array('type' => 'sn',  'sem' => 1, 'cc' => 1, 'rat' => 5, 'tpe' => 7),
            4 => array('type' => 'sn',  'sem' => 2, 'cc' => 2, 'rat' => 6, 'tpe' => 8),
            5 => array('type' => 'rat', 'sem' => 1, 'cc' => 1, 'sn'  => 3, 'tpe' => 7),
            6 => array('type' => 'rat', 'sem' => 2, 'cc' => 2, 'sn'  => 4, 'tpe' => 8),
            7 => array('type' => 'tpe', 'sem' => 1, 'cc' => 1, 'sn'  => 3, 'rat' => 5),
            8 => array('type' => 'tpe', 'sem' => 2, 'cc' => 2, 'sn'  => 4, 'rat' => 6),
        );

        if (!isset($map[$sequence])) {
            throw new \Exception("Séquence inconnue : ".$sequence);
        }

        $semestre = $map[$sequence]['sem'];

        $seq_cc  = isset($map[$sequence]['cc'])  ? $map[$sequence]['cc']  : null;
        $seq_sn  = isset($map[$sequence]['sn'])  ? $map[$sequence]['sn']  : null;
        $seq_rat = isset($map[$sequence]['rat']) ? $map[$sequence]['rat'] : null;
        $seq_tpe = isset($map[$sequence]['tpe']) ? $map[$sequence]['tpe'] : null;

        // si la séquence demandée est elle-même du type correspondant, on l'affecte (sécurise string/int)
        $s = is_string($sequence) ? intval($sequence) : $sequence;
        if ($s === 1 || $s === 2) { $seq_cc  = $s; }
        if ($s === 3 || $s === 4) { $seq_sn  = $s; }
        if ($s === 5 || $s === 6) { $seq_rat = $s; }
        if ($s === 7 || $s === 8) { $seq_tpe = $s; }

        // ------------------------------------
        // 4) Chargement brut des notes (toutes séquences pertinentes)
        // ------------------------------------
        $notes_all = note::whereIn('id_eleve', $elevesIds)
            ->where('id_matiere', $matiere_id)
            ->where('annee_scolaire', $annee)
            ->get();

        // ------------------------------------
        // Helper : conversion inverse -> remettre sur /20 selon pourcentage
        // ------------------------------------
        $convertTo20 = function ($noteStockee, $pourcentage) {
            if ($noteStockee === null) return null;

            if ($pourcentage > 0) {
                // noteStockee = raw (/20) * (pourcentage / 20)  ==> raw (/20) = noteStockee * 20 / pourcentage
                return round(($noteStockee * 20.0) / $pourcentage, 2);
            }

            // fallback : si note <=20 on suppose déjà sur 20 ; si >20 on ramène /20 (diviser par 5)
            if (is_numeric($noteStockee)) {
                if ($noteStockee <= 20) {
                    return round($noteStockee, 2);
                } else {
                    return round($noteStockee / 5.0, 2);
                }
            }

            return null;
        };

        // helper find note for student in collection (compatibilité)
        $findNoteForEleve = function ($collection, $id_eleve) {
            foreach ($collection as $n) {
                if ($n->id_eleve == $id_eleve) return $n;
            }
            return null;
        };

        // ------------------------------------
        // 5) Pourcentages session (cc/sn/rat/tpe)
        // ------------------------------------
        $p_cc  = $seq_cc  ? $this->getPourcentage($session, $seq_cc)  : 0;
        $p_sn  = $seq_sn  ? $this->getPourcentage($session, $seq_sn)  : 0;
        $p_rat = $seq_rat ? $this->getPourcentage($session, $seq_rat) : 0;
        $p_tpe = $seq_tpe ? $this->getPourcentage($session, $seq_tpe) : 0;

        // ------------------------------------
        // 6) Traitement élève par élève
        // ------------------------------------
        $PV = array();

        foreach ($eleves as $el) {

            // extraire notes brutes enregistrées (stockées déjà multipliées par leur pourcentage)
            $Ncc  = $seq_cc  ? $findNoteForEleve($notes_all->filter(function($n) use ($seq_cc){ return isset($n->sequence) && intval($n->sequence) === intval($seq_cc); })->all(),  $el->id) : null;
            $Nsn  = $seq_sn  ? $findNoteForEleve($notes_all->filter(function($n) use ($seq_sn){ return isset($n->sequence) && intval($n->sequence) === intval($seq_sn); })->all(),  $el->id) : null;
            $Nrat = $seq_rat ? $findNoteForEleve($notes_all->filter(function($n) use ($seq_rat){ return isset($n->sequence) && intval($n->sequence) === intval($seq_rat); })->all(), $el->id) : null;
            $Ntpe = $seq_tpe ? $findNoteForEleve($notes_all->filter(function($n) use ($seq_tpe){ return isset($n->sequence) && intval($n->sequence) === intval($seq_tpe); })->all(), $el->id) : null;

            // reconvertir chaque composante en /20 (ou null si absente)
            $v_cc  = $Ncc  ? $convertTo20($Ncc->note,  $p_cc)  : null;
            $v_sn  = $Nsn  ? $convertTo20($Nsn->note,  $p_sn)  : null;
            $v_rat = $Nrat ? $convertTo20($Nrat->note, $p_rat) : null;
            $v_tpe = $Ntpe ? $convertTo20($Ntpe->note, $p_tpe) : null;

            // ----------------------------
            // Calcul du TOTAL /20 (pondéré puis normalisé)
            // ----------------------------
            $total20 = null;

            // Si RAT présent : on considère qu'il remplace le total (déjà en /20)
            if ($v_rat !== null) {
                $total20 = round($v_rat, 2);
            } else {
                // somme pondérée (pondération en %)
                $sumWeighted = 0.0;
                $sumWeight = 0.0;

                if ($v_cc !== null && $p_cc > 0) {
                    $sumWeighted += $v_cc * ($p_cc / 100.0);
                    $sumWeight += ($p_cc / 100.0);
                }

                if ($v_sn !== null && $p_sn > 0) {
                    $sumWeighted += $v_sn * ($p_sn / 100.0);
                    $sumWeight += ($p_sn / 100.0);
                }

                if ($v_tpe !== null && $p_tpe > 0) {
                    $sumWeighted += $v_tpe * ($p_tpe / 100.0);
                    $sumWeight += ($p_tpe / 100.0);
                }

                // si on a des poids, on normalise pour obtenir une note sur 20
                if ($sumWeight > 0) {
                    $total20 = round(($sumWeighted / $sumWeight), 2);
                } else {
                    // pas de pourcentage configuré -> si on a des composantes, on peut sommer ou prendre la moyenne simple
                    // Par défaut : si au moins une composante existe, on prend la moyenne arithmétique des composantes disponibles
                    $parts = array();
                    if ($v_cc !== null) $parts[] = $v_cc;
                    if ($v_sn !== null) $parts[] = $v_sn;
                    if ($v_tpe !== null) $parts[] = $v_tpe;
                    if (count($parts) > 0) {
                        $total20 = round(array_sum($parts) / count($parts), 2);
                    } else {
                        $total20 = null; // aucune note
                    }
                }
            }

            // ----------------------------
            // Conserver aussi "final" non-pondéré si besoin (on mettra le total20 comme valeur principale)
            // ----------------------------
            $final = $total20 !== null ? $total20 : 0;

            // Construction objet matière pour PV : pv_matiereSem(id_matiere, note, coef, cc, sn, tpe, type, total20)
            $pvMat = new pv_matiereSem(
                $matiere_id,            // id_matiere
                $final,                 // note (on met le total20 ici pour cohérence)
                $cm->coef,              // crédit / coefficient
                $v_cc,                  // valeur CC (/20 ou null)
                ($v_rat !== null ? $v_rat : $v_sn), // SN affichage (ou RAT)
                $v_tpe,                 // valeur TPE (/20 ou null)
                0,                      // type
                $total20                // total20 (note finale normalisée /20)
            );

            // PVsem: id_eleve, matieres[], mgp (note finale /20 pour la matière)
            $PV[] = new PVsem($el->id, array($pvMat), ($total20 === null ? 0 : $total20));
        }

        // ------------------------------------
        // 7) Gestion Modules & UV (inchangé)
        // ------------------------------------
        $modules = array();
        if ($withModules) {
            $modules = $this->buildModules(
                $classe_id,
                $id_cycle,
                $nniveau,
                $semestre,
                $PV
            );
        }

        // ------------------------------------
        // 8) Retour structuré
        // ------------------------------------
        return array(
            'eleves'       => $eleves,
            'PV'           => $PV,
            'modules'      => $modules,
            'semestre'     => $semestre,
            'matiere_id'   => $matiere_id,
            'sequence'     => $sequence,
            'id_cycle'     => $id_cycle,
            'nniveau'      => $nniveau,
            'annee'        => $annee,
            'withModules'  => $withModules,
        );
    }

    private function renderPVSemestre123(
        $classe_id,
        $matiere_id,
        $sequence,
        $id_cycle,
        $nniveau,
        $annee,
        $withModules = false
    ) {
        // ------------------------------------
        // 1) Récupérer classe_matiere puis Session_exam
        // ------------------------------------
        $cm = classeMatiere::where('id_classe', $classe_id)
            ->where('id_matiere', $matiere_id)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $nniveau)
            ->first();

        if (!$cm) {
            throw new \Exception(
                "Aucun programme (classe_matiere) pour cette matière ".
                "(classe=$classe_id, matiere=$matiere_id, cycle=$id_cycle, nniveau=$nniveau)"
            );
        }

        $session = Session_exam::where('id_classe_matiere', $cm->id)
            ->where('annee_academique', $annee)
            ->first();

        if (!$session) {
            throw new \Exception(
                "Session_exam introuvable pour classe_matiere=".$cm->id." et année ".$annee
            );
        }

        // ------------------------------------
        // 2) Élèves inscrits
        // ------------------------------------
        $eleves = DB::select(
            'SELECT e.*
         FROM eleve e, inscription i
         WHERE i.annee_scolaire = ?
           AND i.id_classe      = ?
           AND i.id_eleve       = e.id
           AND i.id_cycle       = ?
           AND i.id_nniveau     = ?
         ORDER BY e.nom, e.prenom',
            array($annee, $classe_id, $id_cycle, $nniveau)
        );

        $elevesIds = array();
        foreach ($eleves as $e) {
            $elevesIds[] = $e->id;
        }

        // ------------------------------------
        // 3) Définir la famille de séquences
        // ------------------------------------
        $map = array(
            1 => array('type' => 'cc',  'sem' => 1, 'sn' => 3, 'rat' => 5, 'tpe' => 7),
            2 => array('type' => 'cc',  'sem' => 2, 'sn' => 4, 'rat' => 6, 'tpe' => 8),
            3 => array('type' => 'sn',  'sem' => 1, 'cc' => 1, 'rat' => 5, 'tpe' => 7),
            4 => array('type' => 'sn',  'sem' => 2, 'cc' => 2, 'rat' => 6, 'tpe' => 8),
            5 => array('type' => 'rat', 'sem' => 1, 'cc' => 1, 'sn'  => 3, 'tpe' => 7),
            6 => array('type' => 'rat', 'sem' => 2, 'cc' => 2, 'sn'  => 4, 'tpe' => 8),
            7 => array('type' => 'tpe', 'sem' => 1, 'cc' => 1, 'sn'  => 3, 'rat' => 5),
            8 => array('type' => 'tpe', 'sem' => 2, 'cc' => 2, 'sn'  => 4, 'rat' => 6),
        );

        if (!isset($map[$sequence])) {
            throw new \Exception("Séquence inconnue : ".$sequence);
        }

        $semestre = $map[$sequence]['sem'];

        $seq_cc  = isset($map[$sequence]['cc'])  ? $map[$sequence]['cc']  : null;
        $seq_sn  = isset($map[$sequence]['sn'])  ? $map[$sequence]['sn']  : null;
        $seq_rat = isset($map[$sequence]['rat']) ? $map[$sequence]['rat'] : null;
        $seq_tpe = isset($map[$sequence]['tpe']) ? $map[$sequence]['tpe'] : null;

        // si la séquence demandée est elle-même du type correspondant, on l'affecte
        $s = is_string($sequence) ? intval($sequence) : $sequence;
        if ($s === 1 || $s === 2) { $seq_cc  = $s; }
        if ($s === 3 || $s === 4) { $seq_sn  = $s; }
        if ($s === 5 || $s === 6) { $seq_rat = $s; }
        if ($s === 7 || $s === 8) { $seq_tpe = $s; }

        // ------------------------------------
        // 4) Chargement brut des notes
        // ------------------------------------
        $notes_all = note::whereIn('id_eleve', $elevesIds)
            ->where('id_matiere', $matiere_id)
            ->where('annee_scolaire', $annee)
            ->get();

        // helper locale : conversion inverse -> /20
        $convertTo20 = function ($noteStockee, $pourcentage) {
            // si pas de note, on retourne null pour laisser apparaître '-' en vue si besoin
            if ($noteStockee === null) return null;

            // Si on a un pourcentage configuré (>0) : on reconstitue la note sur 20
            if ($pourcentage > 0) {
                // noteStockee = raw * (pourcentage / 20)
                // => raw (/20) = noteStockee * 20 / pourcentage
                return round(($noteStockee * 20.0) / $pourcentage, 2);
            }

            // Pas de pourcentage configuré : fallback intelligent
            // - si la note stockée est déjà sur 20 (<= 20) : on renvoie telle quelle
            // - si la note stockée est sur 0-100 (par exemple saisie hors règle), on ramène /20
            if (is_numeric($noteStockee)) {
                if ($noteStockee <= 20) {
                    return round($noteStockee, 2);
                } else {
                    // cas fréquent : note sur 0-100 -> diviser par 5 pour avoir sur 20
                    return round($noteStockee / 5.0, 2);
                }
            }

            return null;
        };

        // Petit helper pour retrouver la note d’un élève dans une collection
        $findNoteForEleve = function ($collection, $id_eleve) {
            foreach ($collection as $n) {
                if ($n->id_eleve == $id_eleve) {
                    return $n;
                }
            }
            return null;
        };

        // ------------------------------------
        // 5) Pourcentages (pondération) depuis le session_exam
        // ------------------------------------
        // Utiliser la méthode getPourcentage() existante qui a été adaptée à ton schéma
        $p_cc  = $seq_cc  ? $this->getPourcentage($session, $seq_cc)  : 0;
        $p_sn  = $seq_sn  ? $this->getPourcentage($session, $seq_sn)  : 0;
        $p_rat = $seq_rat ? $this->getPourcentage($session, $seq_rat) : 0;
        $p_tpe = $seq_tpe ? $this->getPourcentage($session, $seq_tpe) : 0;

        // ------------------------------------
        // 6) Traitement élève par élève
        // ------------------------------------
        $PV = array();

        foreach ($eleves as $el) {

            $Ncc  = $seq_cc  ? $findNoteForEleve($notes_all->filter(function($n) use ($seq_cc){ return isset($n->sequence) && intval($n->sequence) === intval($seq_cc); })->all(),  $el->id) : null;
            $Nsn  = $seq_sn  ? $findNoteForEleve($notes_all->filter(function($n) use ($seq_sn){ return isset($n->sequence) && intval($n->sequence) === intval($seq_sn); })->all(),  $el->id) : null;
            $Nrat = $seq_rat ? $findNoteForEleve($notes_all->filter(function($n) use ($seq_rat){ return isset($n->sequence) && intval($n->sequence) === intval($seq_rat); })->all(), $el->id) : null;
            $Ntpe = $seq_tpe ? $findNoteForEleve($notes_all->filter(function($n) use ($seq_tpe){ return isset($n->sequence) && intval($n->sequence) === intval($seq_tpe); })->all(), $el->id) : null;

            // **IMPORTANT** : on **ne** re-multiplie **pas** les notes stockées.
            // On reconvertit simplement la note stockée en note sur 20 selon le pourcentage.
            $v_cc  = $Ncc  ? $convertTo20($Ncc->note,  $p_cc)  : null;
            $v_sn  = $Nsn  ? $convertTo20($Nsn->note,  $p_sn)  : null;
            $v_rat = $Nrat ? $convertTo20($Nrat->note, $p_rat) : null;
            $v_tpe = $Ntpe ? $convertTo20($Ntpe->note, $p_tpe) : null;

            // ----------------------------
            // 6.1 Calcul final matière (sur 20)
            // ----------------------------
            $final = 0;

            // Priorité rattrapage : si RAT existe, on part de ça (RAT est déjà converti en /20)
            if ($v_rat !== null) {
                $final = $v_rat;
            } else {
                // Sinon on prend CC + SN si présents (chacun est en /20 maintenant)
                $sum = 0;
                $countPart = 0;
                if ($v_cc !== null) { $sum += $v_cc; $countPart++; }
                if ($v_sn !== null) { $sum += $v_sn; $countPart++; }
                // On ne divise pas : les composantes sont déjà ramenées sur 20 chacun.
                // Selon ta règle métier, si tu veux normaliser la somme, indique la règle.
                $final = $sum;
            }

            // TPE (optionnel) : on l'ajoute simplement (déjà en /20)
            if ($v_tpe !== null) {
                $final += $v_tpe;
            }

            // Si aucune composante, final = null (pour afficher '-' dans la vue)
            if ($final === 0) {
                // si tu préfères 0 plutôt que null, décommenter la ligne suivante :
                // $final = 0;
            }

            // Construction objet matière pour PV (utilise pv_matiereSem si tu as renommé)
            $pvMat = new pv_matiereSem(
                $matiere_id,            // id_matiere
                $final,                 // note finale (/20 ou somme déroulée)
                $cm->coef,              // crédit / coefficient
                $v_cc,                  // valeur CC (/20 ou null)
                ($v_rat !== null ? $v_rat : $v_sn), // SN affichage (ou RAT)
                $v_tpe,                 // valeur TPE (/20 ou null)
                0                       // type
            );

            $PV[] = new PVsem($el->id, array($pvMat), $final);
        }

        // ------------------------------------
        // 7) Gestion Modules & UV (inchangé)
        // ------------------------------------
        $modules = array();
        if ($withModules) {
            $modules = $this->buildModules(
                $classe_id,
                $id_cycle,
                $nniveau,
                $semestre,
                $PV
            );
        }

        // ------------------------------------
        // 8) Retour structuré
        // ------------------------------------
        return array(
            'eleves'       => $eleves,
            'PV'           => $PV,
            'modules'      => $modules,
            'semestre'     => $semestre,
            'matiere_id'   => $matiere_id,
            'sequence'     => $sequence,
            'id_cycle'     => $id_cycle,
            'nniveau'      => $nniveau,
            'annee'        => $annee,
            'withModules'  => $withModules,
        );
    }




    private function renderPVSemestre1(
        $classe_id,
        $matiere_id,
        $sequence,
        $id_cycle,
        $nniveau,
        $annee,
        $withModules = false
    ) {
        // ------------------------------------
        // 1) Récupérer classe_matiere puis Session_exam
        // ------------------------------------
        $cm = classeMatiere::where('id_classe', $classe_id)
            ->where('id_matiere', $matiere_id)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $nniveau)
            ->first();

        if (!$cm) {
            throw new \Exception(
                "Aucun programme (classe_matiere) pour cette matière ".
                "(classe=$classe_id, matiere=$matiere_id, cycle=$id_cycle, nniveau=$nniveau)"
            );
        }

        // ⚠ On utilise bien id_classe_matiere
        $session = Session_exam::where('id_classe_matiere', $cm->id)
            ->where('annee_academique', $annee)
            ->first();

        if (!$session) {
            throw new \Exception(
                "Session_exam introuvable pour classe_matiere=".$cm->id." et année ".$annee
            );
        }

        // ------------------------------------
        // 2) Élèves inscrits
        // ------------------------------------
        $eleves = DB::select(
            'SELECT e.*
         FROM eleve e, inscription i
         WHERE i.annee_scolaire = ?
           AND i.id_classe      = ?
           AND i.id_eleve       = e.id
           AND i.id_cycle       = ?
           AND i.id_nniveau     = ?
         ORDER BY e.nom, e.prenom',
            array($annee, $classe_id, $id_cycle, $nniveau)
        );

        $elevesIds = array();
        foreach ($eleves as $e) {
            $elevesIds[] = $e->id;
        }

        // ------------------------------------
        // 3) Définir la famille de séquences
        // ------------------------------------
        $map = array(
            1 => array('type' => 'cc',  'sem' => 1, 'sn' => 3, 'rat' => 5, 'tpe' => 7),
            2 => array('type' => 'cc',  'sem' => 2, 'sn' => 4, 'rat' => 6, 'tpe' => 8),
            3 => array('type' => 'sn',  'sem' => 1, 'cc' => 1, 'rat' => 5, 'tpe' => 7),
            4 => array('type' => 'sn',  'sem' => 2, 'cc' => 2, 'rat' => 6, 'tpe' => 8),
            5 => array('type' => 'rat', 'sem' => 1, 'cc' => 1, 'sn'  => 3, 'tpe' => 7),
            6 => array('type' => 'rat', 'sem' => 2, 'cc' => 2, 'sn'  => 4, 'tpe' => 8),
            7 => array('type' => 'tpe', 'sem' => 1, 'cc' => 1, 'sn'  => 3, 'rat' => 5),
            8 => array('type' => 'tpe', 'sem' => 2, 'cc' => 2, 'sn'  => 4, 'rat' => 6),
        );

        if (!isset($map[$sequence])) {
            throw new \Exception("Séquence inconnue : ".$sequence);
        }

        $semestre = $map[$sequence]['sem'];

        $seq_cc  = isset($map[$sequence]['cc'])  ? $map[$sequence]['cc']  : null;
        $seq_sn  = isset($map[$sequence]['sn'])  ? $map[$sequence]['sn']  : null;
        $seq_rat = isset($map[$sequence]['rat']) ? $map[$sequence]['rat'] : null;
        $seq_tpe = isset($map[$sequence]['tpe']) ? $map[$sequence]['tpe'] : null;

        // ------------------------------------
        // 4) Chargement brut des notes
        // ------------------------------------
        $notes_all = note::whereIn('id_eleve', $elevesIds)
            ->where('id_matiere', $matiere_id)
            ->where('annee_scolaire', $annee)
            ->get();

        // Notes par séquence (Collections filtrées)
        $list_cc  = $seq_cc  ? $notes_all->where('sequence', $seq_cc)  : collect();
        $list_sn  = $seq_sn  ? $notes_all->where('sequence', $seq_sn)  : collect();
        $list_rat = $seq_rat ? $notes_all->where('sequence', $seq_rat) : collect();
        $list_tpe = $seq_tpe ? $notes_all->where('sequence', $seq_tpe) : collect();

        // ------------------------------------
        // 5) Pondération Session_exam
        // ------------------------------------
        $p_cc  = $this->getPourcentage($session, $seq_cc);
        $p_sn  = $this->getPourcentage($session, $seq_sn);
        $p_rat = $this->getPourcentage($session, $seq_rat);
        $p_tpe = $this->getPourcentage($session, $seq_tpe);

        // Conversion de la note stockée en note barème 20
        $convert20 = function ($noteStockee, $p) {
            if ($p <= 0) return $noteStockee; // si pas de pourcentage configuré, on laisse tel quel
            return round(($noteStockee * 20) / $p, 2);
        };

        // Petit helper pour retrouver la note d’un élève dans une collection
        $findNoteForEleve = function ($collection, $id_eleve) {
            foreach ($collection as $n) {
                if ($n->id_eleve == $id_eleve) {
                    return $n;
                }
            }
            return null;
        };

        // ------------------------------------
        // 6) Traitement élève par élève
        // ------------------------------------
        $PV = array();

        foreach ($eleves as $el) {

            $Ncc  = $seq_cc  ? $findNoteForEleve($list_cc,  $el->id) : null;
            $Nsn  = $seq_sn  ? $findNoteForEleve($list_sn,  $el->id) : null;
            $Nrat = $seq_rat ? $findNoteForEleve($list_rat, $el->id) : null;
            $Ntpe = $seq_tpe ? $findNoteForEleve($list_tpe, $el->id) : null;

            $v_cc  = $Ncc  ? $convert20($Ncc->note,  $p_cc)  : null;
            $v_sn  = $Nsn  ? $convert20($Nsn->note,  $p_sn)  : null;
            $v_rat = $Nrat ? $convert20($Nrat->note, $p_rat) : null;
            $v_tpe = $Ntpe ? $convert20($Ntpe->note, $p_tpe) : null;

            // ----------------------------
            // 6.1 Calcul final matière
            // ----------------------------
            $final = 0;

            // Priorité rattrapage : si RAT existe, on part de ça
            if ($v_rat !== null) {
                $final = $v_rat;
            } else {
                // Sinon on prend CC + SN si présents
                if ($v_cc !== null) $final += $v_cc;
                if ($v_sn !== null) $final += $v_sn;
            }

            // TPE (optionnel) : on l'ajoute simplement au total
            if ($v_tpe !== null) {
                // Total = CC + SN (+ RAT si présent) + TPE
                $final += $v_tpe;
            }

            // Coef de la matière
            $coef = $cm->coef;

            // Construction objet matière pour PV
            $pvMat = new pv_matiere(
                $matiere_id,   // id_matiere
                $final,        // note finale (somme /20)
                $coef,         // crédit / coefficient
                $v_cc,         // valeur CC (/20 ou null)
                ($v_rat !== null ? $v_rat : $v_sn), // SN ou RAT pour affichage
                $v_tpe,        // valeur TPE (/20 ou null)
                0              // type
            );

            // mg = note finale sur 20 "pondérée" sera gérée ailleurs via coef
            $PV[] = new PV($el->id, array($pvMat), $final);
        }

        // ------------------------------------
        // 7) Gestion Modules & UV
        // ------------------------------------
        $modules = array();
        if ($withModules) {
            $modules = $this->buildModules(
                $classe_id,
                $id_cycle,
                $nniveau,
                $semestre,
                $PV
            );
        }

        // ------------------------------------
        // 8) Retour structuré
        // ------------------------------------
        return array(
            'eleves'       => $eleves,
            'PV'           => $PV,
            'modules'      => $modules,
            'semestre'     => $semestre,
            'matiere_id'   => $matiere_id,
            'sequence'     => $sequence,
            'id_cycle'     => $id_cycle,
            'nniveau'      => $nniveau,
            'annee'        => $annee,
            'withModules'  => $withModules,
        );
    }

    /**
     * PV PAR SEMESTRE
     * Gère : CC, SN, RAT, TPE, Modules, UV, pondération Session_exam
     */

    /**
     * PV ANNUEL — fusion Semestre 1 + Semestre 2
     * Gère : CC / SN / RAT / TPE, Modules / UV
     */
    private function renderPVAnnuel(
        $classe_id,
        $matiere_id,
        $id_cycle,
        $nniveau,
        $annee,
        $withModules = false
    ) {
        // ---------------------------------------------------------------------
        // 1) Récupérer données des deux semestres via renderPVSemestre()
        // ---------------------------------------------------------------------
     /*   $S1 = $this->renderPVSemestre(
            $classe_id,
            $matiere_id,
            sequence: 3,        // SN1 (point d'entrée cohérent pour le semestre)
        id_cycle: $id_cycle,
        nniveau: $nniveau,
        annee: $annee,
        withModules: $withModules
    );

    $S2 = $this->renderPVSemestre(
        $classe_id,
        $matiere_id,
        sequence: 4,        // SN2
        id_cycle: $id_cycle,
        nniveau: $nniveau,
        annee: $annee,
        withModules: $withModules
    );*/
        $S1 = $this->renderPVSemestre(
            $classe_id,
            $matiere_id,
            3,            // SN1
            $id_cycle,
            $nniveau,
            $annee,
            $withModules
        );

        $S2 = $this->renderPVSemestre(
            $classe_id,
            $matiere_id,
            4,            // SN2
            $id_cycle,
            $nniveau,
            $annee,
            $withModules
        );


        $eleves = $S1['eleves'];  // mêmes élèves
    $PV_Annuel = [];
    $modulesAnnuel = [];

    // ---------------------------------------------------------------------
    // 2) Fusion PV Semestre 1 et 2
    // ---------------------------------------------------------------------
    foreach ($eleves as $el) {

        $pv1 = collect($S1['PV'])->firstWhere('id_eleve', $el->id);
        $pv2 = collect($S2['PV'])->firstWhere('id_eleve', $el->id);

        $noteFinale_S1 = $pv1 ? $pv1->mgp : 0;
        $noteFinale_S2 = $pv2 ? $pv2->mgp : 0;

        // Moyenne annuelle = moyenne des deux semestres
        $moyAnnuel = round(($noteFinale_S1 + $noteFinale_S2) / 2, 2);

        $PV_Annuel[] = new PV(
            $el->id,
            array(
                new pv_matiere(
                    $matiere_id,        // id_matiere
                    $moyAnnuel,         // not
                    1,                  // cr
                    $noteFinale_S1,     // cc
                    $noteFinale_S2,     // sn
                    0                   // type
                )
            ),
            $moyAnnuel              // mg
        );

        // PV annuel (un point d’entrée propre)
      /*  $PV_Annuel[] = new PV(
            id_eleve: $el->id,
            ma: [
            new pv_matiere(
                id_matiere: $matiere_id,
                    not: $moyAnnuel,
                    cr: 1,
                    cc: $noteFinale_S1,
                    sn: $noteFinale_S2,
                    type: 0
                )
            ],
            mg: $moyAnnuel
        );*/
    }

    // ---------------------------------------------------------------------
    // 3) Modules annuels = fusion modules S1 + S2
    // ---------------------------------------------------------------------
    if ($withModules) {
        $modulesAnnuel = $this->mergeModulesAnnuel($S1['modules'], $S2['modules']);
    }

    // ---------------------------------------------------------------------
    // 4) Retour structuré
    // ---------------------------------------------------------------------
    return [
        'eleves'        => $eleves,
        'PV'            => $PV_Annuel,
        'modules'       => $modulesAnnuel,
        'semestre'      => 'Annuel',
        'matiere_id'    => $matiere_id,
        'id_cycle'      => $id_cycle,
        'nniveau'       => $nniveau,
        'annee'         => $annee,
        'withModules'   => $withModules,
    ];
}
    /**
     * Fusionne les modules des deux semestres en module annuel
     */
    private function mergeModulesAnnuel($M1, $M2)
    {
        $res = [];

        // Modules Semestre 1
        foreach ($M1 as $id_mere => $uvList) {
            $res[$id_mere] = $uvList;
        }

        // Modules Semestre 2
        foreach ($M2 as $id_mere => $uvList) {
            if (!isset($res[$id_mere])) {
                $res[$id_mere] = $uvList;
                continue;
            }
            // Fusion UV par matière
            foreach ($uvList as $uv) {
                $res[$id_mere][] = $uv;
            }
        }

        return $res;
    }

    private function buildModules($classe, $cycle, $nniveau, $semestre, $PV)
    {
        $gm = DB::table('groupe_matieres')
            ->where('id_classe', $classe)
            ->where('id_nniveau', $nniveau)
            ->where('semestre', $semestre)
            ->get();

        $modules = [];

        foreach ($gm as $g) {

            $uv = classeMatiere::where('id_classe', $classe)
                ->where('id_cycle', $cycle)
                ->where('id_nniveau', $nniveau)
                ->where('id', $g->id_classe_matiere)
                ->first();

            if (!$uv) continue;

            $matiere = matiere::find($uv->id_matiere);

            foreach ($PV as $pv) {
                foreach ($pv->matieres as $m) {
                    if ($m->id_matiere == $uv->id_matiere) {
                        $modules[$g->id_matiere_mere][] = new composante_pv(
                            $m->id_matiere,
                            $m->note,
                            $uv->id,
                            $matiere->nom,
                            $uv->coef,
                            $matiere->id
                        );
                    }
                }
            }
        }

        return $modules;
    }
    private function renderSN($eleves, $notes_sn, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence)
    {
        $annee = session('year');

        // Séquence CC liée
        $seqCC  = ($sequence == 3 ? 1 : 2);
        // Séquence TPE liée : 7 pour S1, 8 pour S2
        $seqTPE = ($sequence == 3 ? 7 : 8);

        // IDs élèves
        $ids_eleves = array();
        foreach ($eleves as $e) {
            $ids_eleves[] = $e->id;
        }

        // Notes de CC
        $notes_cc = note::where('annee_scolaire', $annee)
            ->where('id_matiere', $matiere->id)
            ->where('sequence', $seqCC)
            ->whereIn('id_eleve', $ids_eleves)
            ->get();

        // Notes de TPE (s'il y en a)
        $notes_tpe = note::where('annee_scolaire', $annee)
            ->where('id_matiere', $matiere->id)
            ->where('sequence', $seqTPE)
            ->whereIn('id_eleve', $ids_eleves)
            ->get();

        // Récupération de Session_exam pour cette matière / classe
        $sessionExam = Session_exam::where('id_classe_matiere', $cm->id)
            ->where('annee_academique', $annee)
            ->first();

        // Pourcentages
        $pourcentage_cc  = $this->getPourcentage($sessionExam, $seqCC);
        $pourcentage_sn  = $this->getPourcentage($sessionExam, $sequence);
        $pourcentage_tpe = $this->getPourcentage($sessionExam, $seqTPE);

        // Est-ce qu'on a un TPE pour cette matière ?
        // Ici je décide : si un pourcentage TPE est défini (>0), on considère que la colonne TPE doit s'afficher,
        // même si aucune note n'a encore été saisie (les cases resteront vides).
        $hasTPE = ($pourcentage_tpe > 0);

        return view('eleves/print_note_sn', array(
            'eleves'           => $eleves,
            'notes'            => $notes_sn,     // SN
            'notes_cc'         => $notes_cc,     // CC
            'notes_tpe'        => $notes_tpe,    // TPE (peut être vide)
            'hasTPE'           => $hasTPE,
            'pourcentage_cc'   => $pourcentage_cc,
            'pourcentage_sn'   => $pourcentage_sn,
            'pourcentage_tpe'  => $pourcentage_tpe,
            'matiere'          => $matiere,
            'classe'           => classe::find($id_classe),
            'nniveau'          => NNiveau::find($nniveau),
            'id_cycle'         => $id_cycle,
            'sequence'         => $sequence,
            'semestre'         => ($sequence == 3 ? 1 : 2),
        ));
    }


    private function renderRattrapage($eleves, $notes_rat, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence)
    {
        $annee  = session('year');

        // R1 -> CC1 + SN1 + TPE1(7)
        // R2 -> CC2 + SN2 + TPE2(8)
        $seqCC  = ($sequence == 5 ? 1 : 2);
        $seqSN  = ($sequence == 5 ? 3 : 4);
        $seqTPE = ($sequence == 5 ? 7 : 8);

        // IDs élèves
        $ids_eleves = array();
        foreach ($eleves as $e) {
            $ids_eleves[] = $e->id;
        }

        // Notes CC
        $notes_cc = note::where('annee_scolaire', $annee)
            ->where('id_matiere', $matiere->id)
            ->where('sequence', $seqCC)
            ->whereIn('id_eleve', $ids_eleves)
            ->get();

        // Notes SN (pour affichage éventuel ou calcul)
        $notes_sn = note::where('annee_scolaire', $annee)
            ->where('id_matiere', $matiere->id)
            ->where('sequence', $seqSN)
            ->whereIn('id_eleve', $ids_eleves)
            ->get();

        // Notes TPE
        $notes_tpe = note::where('annee_scolaire', $annee)
            ->where('id_matiere', $matiere->id)
            ->where('sequence', $seqTPE)
            ->whereIn('id_eleve', $ids_eleves)
            ->get();

        // Session_exam
        $sessionExam = Session_exam::where('id_classe_matiere', $cm->id)
            ->where('annee_academique', $annee)
            ->first();

        $pourcentage_cc  = $this->getPourcentage($sessionExam, $seqCC);
        $pourcentage_sn  = $this->getPourcentage($sessionExam, $seqSN);
        $pourcentage_tpe = $this->getPourcentage($sessionExam, $seqTPE);

        $hasTPE = ($pourcentage_tpe > 0);

        return view('eleves/print_note_rat', array(
            'eleves'           => $eleves,
            'notes'            => $notes_rat,   // Rattrapage
            'notes_cc'         => $notes_cc,
            'notes_sn'         => $notes_sn,
            'notes_tpe'        => $notes_tpe,
            'hasTPE'           => $hasTPE,
            'pourcentage_cc'   => $pourcentage_cc,
            'pourcentage_sn'   => $pourcentage_sn,
            'pourcentage_tpe'  => $pourcentage_tpe,
            'matiere'          => $matiere,
            'classe'           => classe::find($id_classe),
            'nniveau'          => NNiveau::find($nniveau),
            'id_cycle'         => $id_cycle,
            'sequence'         => $sequence,
            'semestre'         => ($sequence == 5 ? 1 : 2),
        ));
    }




    private function renderCC($eleves, $notes, $matiere, $cm, $id_classe, $id_cycle, $nniveau, $sequence)
    {
        return view('eleves/print_note_cc', [
            'eleves'   => $eleves,
            'notes'    => $notes,
            'matiere'  => $matiere,
            'classe'   => classe::find($id_classe),
            'nniveau'  => NNiveau::find($nniveau),
            'id_cycle' => $id_cycle,
            'sequence' => $sequence,
            'max'      => $cm->max,
        ]);
    }

    public function print_note22(Request $request,$id)
    {
        $this->validate($request,[
            'sequence'=>'required|numeric',
            'cycle'=>'required|numeric',
            'nniveau'=>'required|numeric',
        ]);

        $id_eleves = [];
        $proces_v = [];
        $values= array('titre'=>'Notes');
        $sequence=$request->input('sequence');
        $id_m=$request->input('id');
        $nniveau=$request->input('nniveau');
        $id_cycle=$request->input('cycle');

        if($sequence==1 || $sequence==2) {
            $mat = matiere::find($id_m);
            $cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->where('id_nniveau','=',$nniveau)->first();

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle,$nniveau]);
            $j=0;
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve',$id_eleves)
                ->get();


            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau']=NNiveau::find($nniveau);
            $values['sequence'] = $sequence;

            return view('eleves/print_note_cc', $values);
        }

        if($sequence==3 || $sequence==4) {
            if($sequence==3)
                $cc=1;
            else
                $cc=2;
            $mat = matiere::find($id_m);
            //$cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->first();
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle,$nniveau]);
            $j=0;
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve',$id_eleves)
                ->get();

            $tns=note::whereAnneeScolaire(\session('year'))->where('sequence','=',$cc)->where('id_matiere','=',$id_m)->whereIn('id_eleve',$id_eleves)->first();
            if($tns==null){
                session(['erreur'=>trans('admin.no_cc',['matiere'=>$mat->nom.'('.$mat->nom_anglais.')'])]);
                return redirect()->to(url('/eleves/notes/matieres/show?id='.$id.'&cycle='.$id_cycle.'&nniveau='.$nniveau));
            }
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;
            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau']=NNiveau::find($nniveau);
            if($sequence==3)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves/print_note_sn', $values);
        }
        if($sequence==5 || $sequence==6) {
            if($sequence==5) {
                $cc = 1;
                $sn = 3;
            }
            else {
                $cc = 2;
                $sn=4;
            }
            $mat = matiere::find($id_m);
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle,$nniveau]);
            $j=0;
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $id_eleves2=$n->fetch('id_eleve')->toArray();
            $e=eleve::whereIn('id',$id_eleves2)->orderBy('nom')->orderBy('prenom')->get();


            $tns=note::whereAnneeScolaire(\session('year'))->where('sequence','=',$cc)->where('id_matiere','=',$id_m)->whereIn('id_eleve',$id_eleves)->first();
            $tnr=note::whereAnneeScolaire(\session('year'))->where('sequence','=',$sn)->where('id_matiere','=',$id_m)->whereIn('id_eleve',$id_eleves)->first();

            if($tns==null || $tnr==null){
                session(['erreur'=>trans('admin.no_note',['matiere'=>$mat->nom.'('.$mat->nom_anglais.')'])]);
                return redirect()->to(url('/eleves/notes/matieres/show?id='.$id.'&cycle='.$id_cycle.'&nniveau='.$nniveau));
            }

            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;

            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau']=NNiveau::find($nniveau);
            if($sequence==5)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves/print_note_rat', $values);
        }
        if($sequence==7 || $sequence==8) {
            if($sequence==7){
                $semestre=1;
                $sn=3;
                $cc=1;
                $rattrap=5;
                $sequences_pv=array(1,3,5);
            }else{
                $semestre=2;
                $sn=4;
                $cc=2;
                $rattrap=6;
                $sequences_pv=array(2,4,6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle,$nniveau]);
            $j=0;
            $matieres=array();
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            /*$n_rattrap = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $rattrap)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $sn)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $notes_sequences_pv=note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve',$id_eleves)
                ->whereIn('sequence',$sequences_pv)
                ->get();

            $matieres_pv=$notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef=0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau','=',$nniveau)->where('id_cycle','=',$id_cycle)->get();
            */
                $notes_sequences_pv=note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve',$id_eleves)
                ->whereIn('sequence',$sequences_pv)
                ->get();
                $matieres_pv=$notes_sequences_pv->fetch('id_matiere')->toArray();


            $m=matiere::/*whereIn('matiere.id',$matiere_list)
            ->*/Join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
            ->whereIn('matiere.id',$matieres_pv)
            ->where('id_classe','=',$id)
            ->where('id_cycle','=',$id_cycle)
            ->where('classe_matiere.id_nniveau','=',$nniveau)
            ->select(['matiere.*','semestre','type','coef','id_code','classe_matiere.id AS id_classe_matiere'])
            ->get();
			


           
            $k=0;

            foreach($e as $eleve){
                    $sCoef=0;

                $l=0;
                $sNote=0;
                foreach ($m as $matiere){


                    $credit=$matiere->coef;

                        $matiere->credit=$credit;

                        $notes_sequences_pv=note::whereAnneeScolaire(\session('year'))
                ->where('id_eleve','=',$eleve->id)
                ->where('id_matiere','=',$matiere->id)
                ->whereIn('sequence',$sequences_pv)
                ->get();

                    $note_cc=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',$cc,false)
                        ->first();
					
                    $note_sn=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',$sn,false)
                        ->first();
                    $note_rattrap=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                            ->where('id_eleve',$eleve->id,false)
                            ->where('sequence',$rattrap,false)
                            ->first();


                    if($note_cc!=null || $note_sn!=null ){
                        @$note1=$note_cc->note+$note_sn->note;
                        if($note_rattrap!=null){
                            if($note1>=50){
                                $note=$note1;
                                if($note_cc)
                                    $controle=$note_cc->note;
                                else
                                    $controle='-';
                                if($note_sn)
                                    $normal=$note_sn->note;
                                else
                                    $normal='-';

                            }else{
                                $note=($note_cc?$note_cc->note:0)+$note_rattrap->note;
                                 if($note_cc)
                                    $controle=$note_cc->note;
                                else
                                    $controle='-';
                                    
                                    $normal=$note_rattrap->note;
                               
                            }
                        }else{
                            $note=$note1;
                                if($note_cc)
                                    $controle=$note_cc->note;
                                else
                                    $controle='-';
                                if($note_sn)
                                    $normal=$note_sn->note;
                                else
                                    $normal='-';
                        }
                    }else{
                        if($note_rattrap!=null){

                                $note=$note_rattrap->note;
                                 $controle='-';
                                    $normal=$note_rattrap->note;
                               

                        }else{
                            $note='-';
                            $controle='-';
                                $normal='-';
                        }

                    }

                    if(is_numeric($note)){
                        $sNote+=$note*$credit;
                        $sCoef+=$credit;
                        $note=round($note);
                    }
                      if(is_numeric($controle)){
                         $controle=round($controle);

                    }
                      if(is_numeric($normal)){
                        $normal=round($normal);

                    }
                   

                   $matieres[$l]=new pv_matiere($matiere->id,$note,$credit,$controle,$normal,$matiere->id_code, $matiere->type);

    



                    $l++;
                }
                //dump($matieres);
                if ($sCoef<=0) {
                    $sCoef=1;
                }
				$s=0;
				$s=$sNote/$sCoef;

                //$proces_v[$k]=new PV($eleve->id,$matieres,$sNote/$sCoef);
                $proces_v[$k]=new PV($eleve->id,$matieres,$s);
            $k++;
            }
			// dump($proces_v);
			// die();
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['nniveau']=NNiveau::find($nniveau);
            $values['classe'] = classe::find($id);
            $values['id_cycle']= $id_cycle;
            $values['semestre'] = $semestre;

            return view('eleves/print_pv', $values);

        }
       /* if($sequence==9) {
                $semestre=array(1,2);
                $sn=array(3,4);
                $cc=array(1,2);
                $rattrap=array(5,6);
                $sequences_pv=array(1,2,3,4,5,6);


            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle,$nniveau]);
            $j=0;
            $matieres=array();
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }

          


            $m=matiere::Join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
                ->where('id_classe','=',$id)
                ->where('id_cycle','=',$id_cycle)
                ->where('classe_matiere.id_nniveau','=',$nniveau)
                ->select(['matiere.*','semestre','type','coef','classe_matiere.id AS id_classe_matiere'])
                ->get();

         

            $lang=App::getLocale();

            $k=0;

           
            foreach($e as $eleve){

                $l=0;
               
                $sNote=0;
                $sCoef=0;

                foreach ($m as $matiere){

                   

                    $credit=$matiere->coef;
                        $matiere->credit=$credit;

                  

                if($lang=='en')
                    $nom=$matiere->nom_anglais;
                else
                    $nom=$matiere->nom;


                   $notes_sequences_pv=note::whereAnneeScolaire(\session('year'))
                ->where('id_eleve','=',$eleve->id)
                ->where('id_matiere','=',$matiere->id)
                ->whereIn('sequence',$sequences_pv)
                ->get();

                    $note_cc1=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',1,false)
                        ->first();
                    $note_cc2=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',2,false)
                        ->first();
                    $note_sn1=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',3,false)
                        ->first();
                    $note_sn2=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',4,false)
                        ->first();
                    $note_rattrap1=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',5,false)
                        ->first();
                    $note_rattrap2=$notes_sequences_pv->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',6,false)
                        ->first();

                    if($note_cc1!=null || $note_sn1!=null ){
                        @$note1=$note_cc1->note+$note_sn1->note;
                        if($note_rattrap1!=null){
                            if($note1>=50){
                                $noteS1=$note1;
                                if($note_cc1)
                                    $controle1=$note_cc1->note;
                                else
                                    $controle1='-';
                                if($note_sn1)
                                    $normal1=$note_sn1->note;
                                else
                                    $normal1='-';
                            }else{
                                $noteS1=($note_cc1?$note_cc1->note:0)+$note_rattrap1->note;

                                if($note_cc1)
                                    $controle1=$note_cc1->note;
                                else
                                    $controle1='-';
                                    
                                    $normal1=$note_rattrap1->note;
                            }
                        }else{
                            $noteS1=$note1;

                             if($note_cc1)
                                    $controle1=$note_cc1->note;
                                else
                                    $controle1='-';
                                if($note_sn1)
                                    $normal1=$note_sn1->note;
                                else
                                    $normal1='-';
                        }
                    }else{
                        if($note_rattrap1!=null){

                            $noteS1=$note_rattrap1->note;

                                 $controle1='-';
                                    $normal1=$note_rattrap1->note;
                               

                        }else{
                            $noteS1='-';
                            $controle1='-';
                                $normal1='-';
                        }

                        
                    }
                    if($note_cc2!=null || $note_sn2!=null ){
                        @$note1=$note_cc2->note+$note_sn2->note;
                        if($note_rattrap2!=null){
                            if($note1>=50){
                                $noteS2=$note1;

                                 if($note_cc2)
                                    $controle2=$note_cc2->note;
                                else
                                    $controle2='-';
                                if($note_sn2)
                                    $normal2=$note_sn2->note;
                                else
                                    $normal2='-';
                            }else{
                                $noteS2=($note_cc2?$note_cc2->note:0)+$note_rattrap2->note;

                                 if($note_cc2)
                                    $controle2=$note_cc2->note;
                                else
                                    $controle2='-';
                                    
                                    $normal2=$note_rattrap2->note;
                            }
                        }else{
                            $noteS2=$note1;

                             if($note_cc2)
                                    $controle2=$note_cc2->note;
                                else
                                    $controle2='-';
                                if($note_sn2)
                                    $normal2=$note_sn2->note;
                                else
                                    $normal2='-';
                        }
                    }else{
                        if($note_rattrap2!=null){

                            $noteS2=$note_rattrap2->note;

                            $controle2='-';
                                    $normal2=$note_rattrap2->note;

                        }else{
                            $noteS2='-';

                             $controle2='-';
                                $normal2='-';
                        }

                    }
                    $note=0;
                    $controle=0;
                    $normal=0;

                    if(is_numeric($noteS1) && is_numeric($noteS2)){
                        $note=($noteS1+$noteS2)/2;
                    }elseif (!is_numeric($noteS2)){
                        $note=$noteS1;
                    }elseif (!is_numeric($noteS1)){
                        $note=$noteS2;
                    }

                     if(is_numeric($controle1) && is_numeric($controle2)){
                        $controle=($controle1+$controle2)/2;
                    }elseif (!is_numeric($controle2)){
                        $controle=$controle1;
                    }elseif (!is_numeric($controle1)){
                        $controle=$controle2;
                    }

                     if(is_numeric($normal1) && is_numeric($normal2)){
                        $normal=($normal1+$normal2)/2;
                    }elseif (!is_numeric($normal2)){
                        $normal=$normal1;
                    }elseif (!is_numeric($normal1)){
                        $normal=$normal2;
                    }



                    if(is_numeric($note)){
                        
                            $sNote+=$note*$credit;
                              $sCoef+=$credit;
                        $note=round($note/5,2);
                    }
                      if(is_numeric($controle)){
                         $controle=round($controle/1.5,2);

                    }
                      if(is_numeric($normal)){
                        $normal=round($normal/3.5,2);

                    
                    }



                 
                        $matieres[$l]=new pv_matiere($matiere->id,$note,$credit,$controle,$normal);
                      




                    $l++;
                }


            

                $proces_v[$k]=new PV($eleve->id,$matieres,$sNote/$sCoef);

                $k++;


            }



            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] =sizeof($m);
            $values['classe'] = classe::find($id);
            $values['id_cycle']=$id_cycle;
            $values['semestre'] = $semestre;
            $values['nniveau']=NNiveau::find($nniveau);

         

            return view('eleves/print_bilan', $values);
        }*/

        if($sequence==9) {
            $semestre=array(1,2);
            $sn=array(3,4);
            $cc=array(1,2);
            $rattrap=array(5,6);
            $sequences_pv=array(1,2,3,4,5,6);


            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle,$nniveau]);
            $j=0;
            $matieres=array();
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }

            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->whereIn('sequence',$rattrap)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->whereIn('sequence',$sn)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->whereIn('sequence',$cc)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $notes_sequences_pv=note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve',$id_eleves)
                ->whereIn('sequence',$sequences_pv)
                ->get();


            $matieres_pv=$notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef=0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau','=',$nniveau)->where('id_cycle','=',$id_cycle)->get();
            foreach ($cm as $entry){
                $sCoef+=$entry->coef;
            }
            $matiere_list=$cm->fetch('id_matiere')->toArray();
            //$m=matiere::whereIn('id',$matiere_list)->whereIn('id',$matieres_pv)->get();
            $m=matiere::whereIn('matiere.id',$matiere_list)
                ->Join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
                ->where('id_classe','=',$id)
                ->where('id_cycle','=',$id_cycle)
                ->where('classe_matiere.id_nniveau','=',$nniveau)
                ->select(['matiere.*','semestre','type','classe_matiere.id AS id_classe_matiere'])
                ->get();
            $lang=App::getLocale();
            $k=0;

            $gm=groupeMatieres::whereIdClasse($id)->where('id_nniveau','=',$nniveau)->get();
            $m_cps=$gm->fetch('id_matiere_mere')->toArray();

            $calc=$gm->fetch('id_classe_matiere')->toArray();
            $m_cpst= classeMatiere::whereIn('id',$calc)->get()->fetch('id_matiere')->toArray();

            $composes= array();
            $composantes= array();
            $liste_mat= array();

            $cpstList= array();
            $minus_credit_cpst=false;
            foreach($e as $eleve){

                $l=0;
                $c=0;
                $cst=0;
                $sNote=0;
                $sCoef=0;
                foreach ($m as $matiere){

                    $cps=false;
                    $cpst=false;

                    $credit=$cm->where('id_matiere',$matiere->id,false)
                        ->first()
                        ->coef;

                    if($this->isIn($matiere->id,$m_cps)){
                        $cps=true;
                        $cpstList=$gm->where('id_matiere_mere',$matiere->id,false)->fetch('id_classe_matiere')->toArray();


                    }

                    if($lang=='en')
                        $nom=$matiere->nom_anglais;
                    else
                        $nom=$matiere->nom;


                    //if($this->isIn($matiere->id,$m_cpst))
                    //  $cpst=true;



                    /*if ($cpst)
                        if(!$minus_credit_cpst) {
                            $sCoef -= $credit;
                        }*/


                    $note_cc1=$n_cc->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',1,false)
                        ->first();
                    $note_cc2=$n_cc->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',2,false)
                        ->first();
                    $note_sn1=$n_sn->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',3,false)
                        ->first();
                    $note_sn2=$n_sn->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',4,false)
                        ->first();
                    $note_rattrap1=$n_rattrap->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',5,false)
                        ->first();
                    $note_rattrap2=$n_rattrap->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',6,false)
                        ->first();
                    $controle1=0;
                    $controle2=0;
                    $normal1=0;
                    $normal2=0;
                    if($note_cc1!=null || $note_sn1!=null ){
                        @$note1=$note_cc1->note+$note_sn1->note;
                        if ($note_cc1!=null) {
                            $controle1= $note_cc1->note;

                        }
                        else{
                            $controle1='-';
                        }

                        if ($note_sn1!=null) {
                            $normal1=$note_sn1->note;
                        }
                        else{
                            $normal1='-';
                        }

                        if($note_rattrap1!=null){
                            if($note1>=50){
                                $noteS1=$note1;
                                $normal1=$note_sn1->note;
                            }else{
                                $noteS1=($note_cc1?$note_cc1->note:0)+$note_rattrap1->note;
                                $normal1=$note_rattrap1->note;


                            }
                        }else{
                            $noteS1=$note1;
                        }
                    }else{
                        if($note_rattrap1!=null){

                            $noteS1=$note_rattrap1->note;
                            $normal1=$note_rattrap1->note;
                            $controle1='-';

                        }else{
                            $noteS1='-';
                        }

                    }
                    if($note_cc2!=null || $note_sn2!=null ){
                        @$note1=$note_cc2->note+$note_sn2->note;

                        if ($note_cc2!=null) {
                            $controle2 = $note_cc2->note;
                        }
                        else{
                            $controle2='-';
                        }
                        if ($note_sn2!=null) {
                            $normal2=$note_sn2->note;
                        }
                        else{
                            $normal2='-';
                        }

                        if($note_rattrap2!=null){
                            if($note1>=50){
                                $noteS2=$note1;
                                $normal2=$note_sn2->note;
                            }else{
                                $noteS2=($note_cc2?$note_cc2->note:0)+$note_rattrap2->note;
                                $normal2=$note_rattrap2->note;
                            }
                        }else{
                            $noteS2=$note1;
                        }
                    }else{
                        if($note_rattrap2!=null){
                            $noteS2=$note_rattrap2->note;
                            $normal2=$note_rattrap2->note;
                            $controle2='-';
                        }else{
                            $noteS2='-';
                        }

                    }
                    $note=0;
                    if(is_numeric($noteS1) && is_numeric($noteS2)){
                        $note=($noteS1+$noteS2)/2;
                        $controle=($controle1+$controle2)/2;
                        $normal=($normal1+$normal2)/2;
                    }elseif (!is_numeric($noteS1)){
                        $note=$noteS2;
                        $controle=$controle2;
                        $normal=$normal2;


                    }elseif (!is_numeric($noteS2)){
                        $note=$noteS1;
                        $controle=$controle1;
                        $normal=$normal1;
                    }



                    if(is_numeric($note)){
                        //ajouts ISSBA
                        if( !$cps){
                            $sNote+=$note*$credit;
                            $sCoef+=$credit;
                        }
                        //if((!$cpst) && (!$cps))

                    }
                    //ajouts ISSBA
                    if( !$cps){
                        //if((!$cpst) && (!$cps)){
                        $matieres[$l]=new pv_matiere($matiere->id,$note,$credit,$controle,$normal,$matiere->nom,$matiere->type);
                        //  $matieres[$l]=new pv_matiere($matiere->id,$note,$credit,$controle,$normal);
                        if(!$minus_credit_cpst) {
                            $liste_mat[$l]=new matiere_groupe($matiere->id,$nom);
                        }
                    }
                    /*if($cpst){
                        $composantes[$cst]= new composante($matiere->id,$note,$matiere->id_classe_matiere,$nom,$credit);
                        $cst++;
                    }*/
                    /*if($cps){
                        $composes[$c]= new compose($matiere->id,$note,$credit,$cpstList,$nom,$credit,$matiere->type,$matiere->semestre);
                        $c++;
                    }*/




                    $l++;
                }


                foreach($composes as $co){
                    $co_note=0;
                    $co_n=0;
                    $s_co_coef=0;
                    $lnoms='';
                    foreach ($composantes as $compt){

                        if($this->isIn($compt->id_classe_matiere,$co->cpsList)){
                            $co_note+=$compt->note*$compt->credit;
                            //$lnoms.=$compt->nom.' ,';
                            $s_co_coef+=$compt->credit;
                            $co_n++;
                        }
                    }

                    /* if($co_n>0){
                         $co_note=$co_note/$co_n;
                     }*/
                    if($s_co_coef>0){
                        $co_note=$co_note/$s_co_coef;
                    }
                    // if(is_numeric($co->note))
                    // $co_note=round(($co_note+$co->note)/2,2);



                    if(sizeof($lnoms)>=1)
                        $lnoms='';//'('.substr($lnoms,0,sizeof($lnoms)-2).')';

                    $sNote+=$co_note*($co->credit);
                    //$matieres[$l]=new pv_matiere($co->id_matiere,$co_note,$s_co_coef);
                    if(!$minus_credit_cpst) {
                        //$liste_mat[$l]=new matiere_groupe($co->id_matiere,$co->nom);
                    }
                    $l++;

                }
                $minus_credit_cpst=true;

                if ($sNote!=null)
                {
                    $proces_v[$k]=new PV($eleve->id,$matieres,$sNote/$sCoef);
                }
                else{
                    $proces_v[$k]=new PV($eleve->id,$matieres,0);

                }


                $k++;


            }



            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            //$values['matieres'] = $m;
            $values['matieres'] = $liste_mat;
            $values['nb_matieres'] =sizeof($liste_mat);
            $values['classe'] = classe::find($id);
            $values['id_cycle']=$id_cycle;
            $values['semestre'] = $semestre;
            $values['nniveau']=NNiveau::find($nniveau);

            return view('eleves/print_bilan', $values);
        }
    }

    /**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_matiere'  => 'required|numeric',
            'sequence'    => 'required|numeric',
            'id_classe'   => 'required|numeric',
            'nniveau'     => 'required|numeric',
            'cycle'       => 'required|numeric',
        ]);

        $id_cycle   = (int) $request->input('cycle');
        $id_nniveau = (int) $request->input('nniveau');
        $id_matiere = (int) $request->input('id_matiere');
        $sequence   = (int) $request->input('sequence');
        $id_classe  = (int) $request->input('id_classe');

        $eleveList  = $request->input('eleve');   // tableau d'id_eleve cochés
        $noteList   = $request->input('note');    // tableau de notes saisies (/20)

        // Année scolaire : on prend ce qui vient du formulaire si présent, sinon session('year')
        $annee      = $request->input('annee_scolaire', session('year'));

        // ⛔ Si aucun élève coché → on sort
        if ($eleveList == null) {
            session(['success' => true]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id_classe . '&cycle=' . $id_cycle . '&nniveau=' . $id_nniveau)
            );
        }

        // 1️⃣ classe_matiere
        $classeMatiere = classeMatiere::where('id_classe', $id_classe)
            ->where('id_matiere', $id_matiere)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $id_nniveau)
            ->first();

        // 2️⃣ session_exam
        $sessionExam = null;
        if ($classeMatiere) {
            $sessionExam = Session_exam::where('id_classe_matiere', $classeMatiere->id)
                ->where('annee_academique', $annee)
                ->first();
        }

        // 3️⃣ Pourcentage correspondant à la séquence
        $pourcentage = 0;
        if ($sessionExam) {
            switch ($sequence) {
                case 1: $pourcentage = (float) $sessionExam->cc1;          break;
                case 2: $pourcentage = (float) $sessionExam->cc2;          break;
                case 3: $pourcentage = (float) $sessionExam->sn1;          break;
                case 4: $pourcentage = (float) $sessionExam->sn2;          break;
                case 5: $pourcentage = (float) $sessionExam->ratrappage1;  break;
                case 6: $pourcentage = (float) $sessionExam->ratrappage2;  break;
                case 7: $pourcentage = (float) $sessionExam->tpe1;         break;
                case 8: $pourcentage = (float) $sessionExam->tpe2;         break;
                default: $pourcentage = 0;
            }
        }

        // 4️⃣ Multiplicateur (fallback 2.5 si pas de session ou pourcentage = 0)
        $mul = 2.5;
        if ($pourcentage > 0) {
            // ex : 30 % → 30 / 20 = 1.5
            $mul = $pourcentage / 20.0;
        }

        $current_user = \Auth::user();

        // 5️⃣ Boucle sur les élèves cochés
        $i = 0;
        foreach ($eleveList as $id_eleve) {

            // Note saisie /20 pour cet élève
            $rawNote = isset($noteList[$i]) ? $noteList[$i] : null;

            // Si l’input est vide, on ne fait rien pour cet élève
            if ($rawNote === null || $rawNote === '' || !is_numeric($rawNote)) {
                $i++;
                continue;
            }

            $rawNote = (float) $rawNote;

            // On cherche s’il existe déjà une note pour cet élève / matière / séquence / année
            $n = note::where('annee_scolaire', $annee)
                ->where('id_matiere', $id_matiere)
                ->where('sequence', $sequence)
                ->where('id_eleve', $id_eleve)
                ->first();

            // Note calculée (pondérée) selon la session
            $noteCalculee = $rawNote * $mul;

            if ($n == null) {
                // 🆕 Création
                $n = new note();
                $n->id_creator      = $current_user->id;
                $n->id_matiere      = $id_matiere;
                $n->sequence        = $sequence;
                $n->id_eleve        = $id_eleve;
                $n->annee_scolaire  = $annee;

                $n->edition_details = 'create: ' . $rawNote . ' (/20) x ' . $mul . ' => ' . $noteCalculee;
            } else {

                // ✅ On ne modifie que si la valeur a réellement changé

                // On reconstitue l’ancienne saisie /20
                $oldRaw = $rawNote; // valeur par défaut

                if ($pourcentage > 0) {
                    // note_stockée = ancienne_saisie * (pourcentage / 20)
                    // => ancienne_saisie = note_stockée * 20 / pourcentage
                    $oldRaw = (float) ($n->note * 20.0 / $pourcentage);
                } else {
                    $oldRaw = (float) $n->note;
                }

                // Si l’enseignant n’a pas changé la valeur → on ne touche pas à la BD
                // (on compare avec une petite tolérance pour les décimales)
                if (abs($rawNote - $oldRaw) < 0.0001) {
                    $i++;
                    continue;
                }

                // L’enseignant a changé la note → on met à jour
                $n->edition_details = 'update: ' . $oldRaw . ' (/20) -> ' . $rawNote . ' (/20) x ' . $mul . ' => ' . $noteCalculee;
            }

            // Champs communs (création ou update)
            $n->note           = $noteCalculee;
            $n->id_classe      = $id_classe;
            $n->id_last_editor = $current_user->id;

            if ($classeMatiere) {
                $n->id_classe_matiere = $classeMatiere->id;
            }
            if ($sessionExam) {
                $n->id_session = $sessionExam->id;
            }

            $n->save();
            $i++;
        }

        session(['success' => true]);

        return redirect()->to(
            url('/eleves/notes/matieres?id=' . $id_classe . '&cycle=' . $id_cycle . '&nniveau=' . $id_nniveau)
        );
    }

    public function storeBON(Request $request)
    {
        $this->validate($request, [
            'id_matiere'      => 'required|numeric',
            'sequence'        => 'required|numeric',
            'id_classe'       => 'required|numeric',
            'nniveau'         => 'required|numeric',
            'cycle'           => 'required|numeric',
            // optionnel si tu envoies l’année depuis le formulaire
            'annee_scolaire'  => 'sometimes',
        ]);

        $id_cycle   = (int)$request->input('cycle');
        $id_nniveau = (int)$request->input('nniveau');
        $id_matiere = (int)$request->input('id_matiere');
        $sequence   = (int)$request->input('sequence');
        $id_classe  = (int)$request->input('id_classe');

        $eleveList  = $request->input('eleve');   // tableau d'id_eleve
        $noteList   = $request->input('note');    // tableau de notes saisies (/20)

        // Année scolaire : soit session('year'), soit valeur envoyée
        $annee = $request->input('annee_scolaire', session('year'));

        // Si aucun élève sélectionné → retour direct
        if ($eleveList == null) {
            session(['success' => true]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id_classe . '&cycle=' . $id_cycle . '&nniveau=' . $id_nniveau)
            );
        }

        // 1️⃣ Récupérer la ligne classe_matiere
        $classeMatiere = classeMatiere::where('id_classe', $id_classe)
            ->where('id_matiere', $id_matiere)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $id_nniveau)
            ->first();

        if (!$classeMatiere) {
            session(['erreur' => "Aucun programme (classe_matiere) trouvé pour cette matière."]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id_classe . '&cycle=' . $id_cycle . '&nniveau=' . $id_nniveau)
            );
        }

        // 2️⃣ Récupérer la session d’examen (pour les %)
        $sessionExam = Session_exam::where('id_classe_matiere', $classeMatiere->id)
            ->where('annee_academique', $annee)
            ->first();

        if (!$sessionExam) {
            session(['erreur' => "Aucune session n'est configurée pour cette matière et cette année ($annee)."]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id_classe . '&cycle=' . $id_cycle . '&nniveau=' . $id_nniveau)
            );
        }

        // 3️⃣ Pourcentage correspondant à la séquence
        $percent = 0;
        switch ($sequence) {
            case 1: $percent = $sessionExam->cc1;          break;
            case 2: $percent = $sessionExam->cc2;          break;
            case 3: $percent = $sessionExam->sn1;          break;
            case 4: $percent = $sessionExam->sn2;          break;
            case 5: $percent = $sessionExam->ratrappage1;  break;
            case 6: $percent = $sessionExam->ratrappage2;  break;
            case 7: $percent = $sessionExam->tpe1;         break;
            case 8: $percent = $sessionExam->tpe2;         break;
            default: $percent = 0;
        }

        if ($percent <= 0) {
            session(['erreur' => "La session ne prévoit pas ce type de note (séquence $sequence)."]);
            return redirect()->to(
                url('/eleves/notes/matieres?id=' . $id_classe . '&cycle=' . $id_cycle . '&nniveau=' . $id_nniveau)
            );
        }

        // multiplicateur pour passer de /20 à la note pondérée
        // ex : 30% => 30/20 = 1.5
        $mul = $percent / 20;

        $current_user = \Auth::user();

        // 4️⃣ Boucle sur les élèves
        $i = 0;
        foreach ($eleveList as $e) {

            // Note saisie /20 pour cet élève
            $rawNote = isset($noteList[$i]) ? $noteList[$i] : null;

            // Si champ vide → on ignore simplement
            if ($rawNote === null || $rawNote === '') {
                $i++;
                continue;
            }

            $rawNote = (float)str_replace(',', '.', $rawNote);

            // Note calculée selon la session
            $noteCalculee = $rawNote * $mul;

            // On cherche une note existante
            $n = note::where('annee_scolaire', $annee)
                ->where('id_matiere', $id_matiere)
                ->where('sequence', $sequence)
                ->where('id_eleve', $e)
                ->first();

            // Si une note existe et que la valeur calculée NE CHANGE PAS,
            // on ne touche pas la ligne (pas de nouvelle édition, pas de re-multiplication inutile)
            if ($n && abs($n->note - $noteCalculee) < 0.0001) {
                $i++;
                continue;
            }

            if (!$n) {
                // Création
                $n = new note();
                $n->id_creator      = $current_user->id;
                $n->annee_scolaire  = $annee;
                $n->id_matiere      = $id_matiere;
                $n->sequence        = $sequence;
                $n->id_eleve        = $e;

                $n->edition_details = 'create: ' . $rawNote . ' (/20) x ' . $mul . ' => ' . $noteCalculee;
            } else {
                // Mise à jour uniquement si valeur changée (on est passé plus haut)
                $old = $n->note;
                $n->edition_details = 'update: old=' . $old . ', new=' . $noteCalculee . ' (saisie=' . $rawNote . '/20)';
            }

            // Colonnes communes
            $n->note            = $noteCalculee;
            $n->id_classe       = $id_classe;
            $n->id_last_editor  = $current_user->id;

            if ($classeMatiere) {
                $n->id_classe_matiere = $classeMatiere->id;
            }
            if ($sessionExam) {
                $n->id_session = $sessionExam->id;
            }

            $n->save();
            $i++;
        }

        session(['success' => true]);

        return redirect()->to(
            url('/eleves/notes/matieres?id=' . $id_classe . '&cycle=' . $id_cycle . '&nniveau=' . $id_nniveau)
        );
    }

    public function deliberer_note(Request $request,$id)
    {
        Log::debug(' ');
        Log::debug(' ');
        Log::debug('########################## NEW DELIBERATION');
        Log::debug('##');
        Log::debug('##');
        Log::debug(' ');

        $this->validate($request, [
            'sequence' => 'required|numeric',
            'cycle' => 'required|numeric',
            'nniveau' => 'required|numeric',

        ]);

        $id_eleves = [];

        $values = array('titre' => 'Notes');
        $sequence = $request->input('sequence');
        $nniveau = $request->input('nniveau');
        $id_cycle = $request->input('cycle');

        // pour les inputs notes

        $id_nniveau=$request->input('nniveau');
        $id_classe=$request->input('id_classe');

        if ($sequence == 7 || $sequence == 8) {
            if ($sequence == 7) {
                $semestre = 1;
                $sn = 3;
                $cc = 1;
                $rattrap = 5;
                $sequences_pv = array(1, 3, 5);
            } else {
                $semestre = 2;
                $sn = 4;
                $cc = 2;
                $rattrap = 6;
                $sequences_pv = array(2, 4, 6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }



            $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve', $id_eleves)
                ->whereIn('sequence', $sequences_pv)
                ->get();
            $g = 0;

            $matieres_pv = $notes_sequences_pv->fetch('id_matiere')->toArray();

            $m = matiere::Join('classe_matiere', 'matiere.id', '=', 'classe_matiere.id_matiere')
                ->whereIn('matiere.id', $matieres_pv)
                ->where('id_classe', '=', $id)
                ->where('id_cycle', '=', $id_cycle)
                ->where('classe_matiere.id_nniveau', '=', $nniveau)
                ->select(['matiere.*', 'semestre', 'type', 'coef', 'classe_matiere.id AS id_classe_matiere'])
                ->get();

            foreach ($e as $eleve) {
                Log::debug('Deliberating student', ['nom' => $eleve->nom . ' ' . $eleve->prenom]);
                foreach ($m as $matiere) {
                    Log::debug('Subject', ['nom' => $matiere->nom ]);

                    $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                        ->where('id_eleve', '=', $eleve->id)
                        ->where('id_matiere', '=', $matiere->id)
                        ->whereIn('sequence', $sequences_pv)
                        ->get();

                    $note_cc = $notes_sequences_pv->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->where('sequence', $cc, false)
                        ->first();

                    $note_sn = $notes_sequences_pv->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->where('sequence', $sn, false)
                        ->first();
                    $note_rattrap = $notes_sequences_pv->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->where('sequence', $rattrap, false)
                        ->first();

                    if ($note_cc != null || $note_sn != null) {
                        @$note1 = $note_cc->note + $note_sn->note;
                        Log::debug('Current notes', ['note_cc' => $note_cc ? $note_cc->note : 'null',  'note_sn' => $note_sn ? $note_sn->note : 'null']);

                        if ($notes_sequences_pv!= null) {
                            if ($note1 < 50 && $note1 >=0)  {
                                Log::debug('Subject can be deliberated');

                                $diff = 50 - $note1;
                                if($note_sn!=null){
                                 $current_note = note::find($note_sn->id);
                                $current_note->note += $diff;
                                $current_note->save();
                               Log::debug('New note', ['note_sn' => $current_note->note]);
                               }

                             /*   $current_note = note::find($note_sn->id);
                                $current_note->note += $diff;
                                $current_note->save();
                                Log::debug('New note', ['note_sn' => $current_note->note]);*/

                            }
                        }
                        $values['semestre'] = $semestre;


                    }

                }
            }
			  return redirect()->to('/eleves/notes/matieres/show?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau);
            //return redirect()->to(URL::previous());

        }  }


    public function deliberer_rattrapage(Request $request,$id)
    {
        Log::debug(' ');
        Log::debug(' ');
        Log::debug('########################## NEW rattrapages');
        Log::debug('##');
        Log::debug('##');
        Log::debug(' ');

        $this->validate($request, [
            'sequence' => 'required|numeric',
            'cycle' => 'required|numeric',
            'nniveau' => 'required|numeric',

        ]);

        $id_eleves = [];

        $values = array('titre' => 'Notes');
        $sequence = $request->input('sequence');
        $nniveau = $request->input('nniveau');
        $id_cycle = $request->input('cycle');

        // pour les inputs notes

        $id_nniveau=$request->input('nniveau');
        $id_classe=$request->input('id_classe');

        if ($sequence == 10 || $sequence == 11) {
            if ($sequence == 10) {
                $semestre = 1;
                $sn = 3;
                $cc = 1;
                $rattrap = 5;
                $sequences_pv = array(1, 3, 5);
            } else {
                $semestre = 2;
                $sn = 4;
                $cc = 2;
                $rattrap = 6;
                $sequences_pv = array(2, 4, 6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }



            $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve', $id_eleves)
                ->whereIn('sequence', $sequences_pv)
                ->get();
            $g = 0;

            $matieres_pv = $notes_sequences_pv->fetch('id_matiere')->toArray();

            $m = matiere::Join('classe_matiere', 'matiere.id', '=', 'classe_matiere.id_matiere')
                ->whereIn('matiere.id', $matieres_pv)
                ->where('id_classe', '=', $id)
                ->where('id_cycle', '=', $id_cycle)
                ->where('classe_matiere.id_nniveau', '=', $nniveau)
                ->select(['matiere.*', 'semestre', 'type', 'coef', 'classe_matiere.id AS id_classe_matiere'])
                ->get();

            foreach ($e as $eleve) {
                Log::debug('Deliberating student', ['nom' => $eleve->nom . ' ' . $eleve->prenom]);
                foreach ($m as $matiere) {
                    Log::debug('Subject', ['nom' => $matiere->nom ]);

                    $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                        ->where('id_eleve', '=', $eleve->id)
                        ->where('id_matiere', '=', $matiere->id)
                        ->whereIn('sequence', $sequences_pv)
                        ->get();

                    $note_cc = $notes_sequences_pv->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->where('sequence', $cc, false)
                        ->first();

                    $note_sn = $notes_sequences_pv->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->where('sequence', $sn, false)
                        ->first();
                    $note_rattrap = $notes_sequences_pv->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->where('sequence', $rattrap, false)
                        ->first();

                    if ($note_cc != null || $note_rattrap != null) {
                        @$note1 = $note_cc->note + $note_rattrap->note;
                        Log::debug('Current notes', ['note_cc' => $note_cc ? $note_cc->note : 'null',  'note_rattrap' => $note_rattrap ? $note_rattrap->note : 'null']);

                        if ($notes_sequences_pv!= null) {
                            if ($note1 < 50 && $note1 >=0)  {
                                Log::debug('Subject can be deliberated');

                                $diff = 50 - $note1;
                                if($note_rattrap!=null) {

                                    $current_note = note::find($note_rattrap->id);
                                    $current_note->note += $diff;
                                    $current_note->save();
                                    Log::debug('New note', ['note_rattrapage' => $current_note->note]);
                                }

                            }
                        }
                        $values['semestre'] = $semestre;


                    }

                }
            }
			  return redirect()->to('/eleves/notes/matieres/show?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau);
           // return redirect()->to(URL::previous());

        }  }



    /**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
  public function print_note_sn(Request $request,$id)
    {
        $this->validate($request, [
            'sequence' => 'required|numeric',
            'cycle' => 'required|numeric',
            'nniveau' => 'required|numeric',
        ]);

        $values = array('titre' => 'Notes');
        $sequence = $request->input('sequence');
        $id_m = $request->input('id');
        $nniveau = $request->input('nniveau');
        $id_cycle = $request->input('cycle');

        if ($sequence == 1 || $sequence == 2) {
            $mat = matiere::find($id_m);
            $cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->where('id_nniveau', '=', $nniveau)->first();

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();


            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['sequence'] = $sequence;

            return view('eleves/print_note_cc', $values);
        }

        if ($sequence == 3 || $sequence == 4) {
            if ($sequence == 3)
                $cc = 1;
            else
                $cc = 2;
            $mat = matiere::find($id_m);
            //$cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->first();
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();

            $tns = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $cc)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();
            if ($tns == null) {
                session(['erreur' => trans('admin.no_cc', ['matiere' => $mat->nom . '(' . $mat->nom_anglais . ')'])]);
                return redirect()->to('/eleves/notes/matieres/show?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau);
            }
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;
            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            if ($sequence == 3)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves/print_note_sn', $values);
        }
        if ($sequence == 5 || $sequence == 6) {
            if ($sequence == 5) {
                $cc = 1;
                $sn = 3;
            } else {
                $cc = 2;
                $sn = 4;
            }
            $mat = matiere::find($id_m);
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $id_eleves2 = $n->fetch('id_eleve')->toArray();
            $e = eleve::whereIn('id', $id_eleves2)->orderBy('nom')->orderBy('prenom')->get();


            $tns = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $cc)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();
            $tnr = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $sn)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();

            if ($tns == null || $tnr == null) {
                session(['erreur' => trans('admin.no_note', ['matiere' => $mat->nom . '(' . $mat->nom_anglais . ')'])]);
                return redirect()->to('/eleves/notes/matieres/show?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau);
            }

            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;

            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            if ($sequence == 5)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves/print_note_rat', $values);
        }
        if ($sequence == 7 || $sequence == 8) {
            if ($sequence == 7) {
                $semestre = 1;
                $sn = 3;
                $cc = 1;
                $rattrap = 5;
                $sequences_pv = array(1, 3, 5);
                //   $rettrapage_pv=array(1,3,5);
            } else {
                $semestre = 2;
                $sn = 4;
                $cc = 2;
                $rattrap = 6;
                $sequences_pv = array(2, 4, 6);
                // $rettrapage_pv=array(2,4,6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            $ccc = 0;
            $matieres = array();
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $rattrap)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $sn)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve', $id_eleves)
                ->whereIn('sequence', $sequences_pv)
                ->get();
            /*  $ccc=$n_sn+$n_cc;
              $note_ratrap=note::whereAnneeScolaire(\session('year'))
                  ->whereIn('id_eleve',$id_eleves)
                  ->where(is_numeric($ccc<50),$rettrapage_pv)
                  ->get();*/

            $matieres_pv = $notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef = 0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->where('id_cycle', '=', $id_cycle)->get();
            $gm = groupeMatieres::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->get();
            $m_cps = $gm->fetch('id_matiere_mere')->toArray();


            foreach ($cm as $entry) {
                if (!($this->isIn($entry->id_matiere, $m_cps)))
                    $sCoef += $entry->coef;
            }
            $matiere_list = $cm->fetch('id_matiere')->toArray();
            $m = matiere::whereIn('id', $matiere_list)->whereIn('id', $matieres_pv)->whereNotIn('id', $m_cps)->get();


            $k = 0;
            foreach ($e as $eleve) {

                $l = 0;
                $sNote = 0;
                $sCoef = 0;
                $es = $eleve->id;
                $idelevess = 0;

                foreach ($m as $matiere) {
                    $cps = false;
                    $cpst = false;

                    if ($this->isIn($matiere->id, $m_cps)) {
                        $cps = true;

                    }

                    $credit = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->coef;
                    $note_cc = $n_cc->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $es, false)
                        ->first();
                    $note_sn = $n_sn->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    $note_rattrap = $n_rattrap->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    /*  $note_re=$note_ratrap->where('id_matiere',$matiere->id,false)
                          ->first();*/

                    if ($note_cc != null || $note_sn != null) {
                        @$note1 = $note_cc->note + $note_sn->note;

                        @$ides = $es;

                        @$note2 = $note_sn->note;
                        @$note3 = $note_sn->note < 25;
                        /*    dump($note2);
                            die();*/

                        @$rattrape = 0;

                        if ($note1 > 50) {

                            $rattrape = $note1;
                        }
                       if ($note_cc != null) {
                            $controle = $note_cc->note;
                            $id_eleve = $eleve->id;
                        } else {
                            $controle = '-';
                            $id_eleve = $eleve->id;
                        }
                       /*  if ($note_sn != null) {
                            $normal = $note_cc->note;

                            $id_eleve = $eleve->id;
                        } else {
                            $normal = '-';
                            $id_eleve = $eleve->id;
                        }*/
                           if ($note_sn != null) {
                            $normal = $note_sn->note;

                            $id_eleve = $eleve->id;
                        } else {
                            $normal = '-';
                            $id_eleve = $eleve->id;
                        }



                        if ($note_rattrap != null) {
                            if ($note1 >= 50) {
                                $note = $note2;
                                $normal = $note_sn->note < 25;
                                $id_eleve = $eleve->id;

                            } else {
                                /*                                $note=($note_cc?$note_cc->note:0)+$note_rattrap->note;*/
                                $note = $note_sn->note;
                                $id_eleve = $eleve->id;

                                $normal = $note_rattrap->note;
                            }
                        } else {
                            $note = $note2;
                            $id_eleve = $eleve->id;

                        }

                    } else {
                        if ($note_rattrap != null) {

                            $note = $note_rattrap->note;
                            $normal = $note_rattrap->note;
                            $controle = '-';


                        } else {
                            $note = '-';
                            $normal = '-';
                            $controle = '-';
                            $id_eleve = $eleve->id;


                        }

                    }
                    if (!$cps)
                        if (is_numeric($note)) {
                            $sNote += $note * $credit;

                            $sCoef += $credit;
                            $id_eleve = $eleve->id;
                        }
                       

                    if (!$cps)
                        $matieres[$l] = new pv_matiere($matiere->id, $note, $credit, $controle, $normal, $id_eleve);
                       /* $matieres[$l] = new pv_matiere($matiere->id, $note, $credit, $controle, $normal, $id_eleve);*/
                /*$matieres[$l] = new pv_matiere($matiere->id, $n, $credit, $cc, $nor, $id_eleve);*/


                    $l++;
                }
                /*  dump($matieres);
                  die();*/

                //dump($matieres);
                if ($sCoef <= 0)
                    $sCoef = 1;
                $proces_v[$k] = new PV($eleve->id, $matieres, $sNote / $sCoef);


                $k++;
                
            }
            /*dump($proces_v);
             die();*/
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['semestre'] = $semestre;

            return view('eleves/print_pv_sn', $values);

        }
    }




    public function print_note_cc(Request $request,$id)
    {
        $this->validate($request, [
            'sequence' => 'required|numeric',
            'cycle' => 'required|numeric',
            'nniveau' => 'required|numeric',
        ]);

        $values = array('titre' => 'Notes');
        $sequence = $request->input('sequence');
        $id_m = $request->input('id');
        $nniveau = $request->input('nniveau');
        $id_cycle = $request->input('cycle');

        if ($sequence == 1 || $sequence == 2) {
            $mat = matiere::find($id_m);
            $cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->where('id_nniveau', '=', $nniveau)->first();

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();


            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['sequence'] = $sequence;

            return view('eleves/print_note_cc', $values);
        }

        if ($sequence == 3 || $sequence == 4) {
            if ($sequence == 3)
                $cc = 1;
            else
                $cc = 2;
            $mat = matiere::find($id_m);
            //$cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->first();
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();

            $tns = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $cc)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();
            if ($tns == null) {
                session(['erreur' => trans('admin.no_cc', ['matiere' => $mat->nom . '(' . $mat->nom_anglais . ')'])]);
                return redirect()->to('/eleves/notes/matieres/show?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau);
            }
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;
            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            if ($sequence == 3)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves/print_note_sn', $values);
        }
        if ($sequence == 5 || $sequence == 6) {
            if ($sequence == 5) {
                $cc = 1;
                $sn = 3;
            } else {
                $cc = 2;
                $sn = 4;
            }
            $mat = matiere::find($id_m);
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $id_eleves2 = $n->fetch('id_eleve')->toArray();
            $e = eleve::whereIn('id', $id_eleves2)->orderBy('nom')->orderBy('prenom')->get();


            $tns = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $cc)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();
            $tnr = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $sn)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();

            if ($tns == null || $tnr == null) {
                session(['erreur' => trans('admin.no_note', ['matiere' => $mat->nom . '(' . $mat->nom_anglais . ')'])]);
                return redirect()->to('/eleves/notes/matieres/show?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau);
            }

            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;

            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            if ($sequence == 5)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves/print_note_rat', $values);
        }
        if ($sequence == 7 || $sequence == 8) {
            if ($sequence == 7) {
                $semestre = 1;
                $sn = 3;
                $cc = 1;
                $rattrap = 5;
                $sequences_pv = array(1, 3, 5);
                //   $rettrapage_pv=array(1,3,5);
            } else {
                $semestre = 2;
                $sn = 4;
                $cc = 2;
                $rattrap = 6;
                $sequences_pv = array(2, 4, 6);
                // $rettrapage_pv=array(2,4,6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            $ccc = 0;
            $matieres = array();
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $rattrap)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $sn)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve', $id_eleves)
                ->whereIn('sequence', $sequences_pv)
                ->get();
            /*  $ccc=$n_sn+$n_cc;
              $note_ratrap=note::whereAnneeScolaire(\session('year'))
                  ->whereIn('id_eleve',$id_eleves)
                  ->where(is_numeric($ccc<50),$rettrapage_pv)
                  ->get();*/

            $matieres_pv = $notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef = 0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->where('id_cycle', '=', $id_cycle)->get();
            $gm = groupeMatieres::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->get();
            $m_cps = $gm->fetch('id_matiere_mere')->toArray();


            foreach ($cm as $entry) {
                if (!($this->isIn($entry->id_matiere, $m_cps)))
                    $sCoef += $entry->coef;
            }
            $matiere_list = $cm->fetch('id_matiere')->toArray();
            $m = matiere::whereIn('id', $matiere_list)->whereIn('id', $matieres_pv)->whereNotIn('id', $m_cps)->get();


            $k = 0;
            foreach ($e as $eleve) {

                $l = 0;
                $sNote = 0;
                $sCoef = 0;
                $es = $eleve->id;
                $idelevess = 0;

                foreach ($m as $matiere) {
                    $cps = false;
                    $cpst = false;

                    if ($this->isIn($matiere->id, $m_cps)) {
                        $cps = true;

                    }

                    $credit = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->coef;
                    $note_cc = $n_cc->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $es, false)
                        ->first();
                    $note_sn = $n_sn->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    $note_rattrap = $n_rattrap->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    /*  $note_re=$note_ratrap->where('id_matiere',$matiere->id,false)
                          ->first();*/

                    if ($note_cc != null || $note_sn != null) {
                        @$note1 = $note_cc->note + $note_sn->note;
                        @$ides = $es;

                        @$note2 = $note_cc->note;
                        @$note3 = $note_cc->note < 25;
                        /*    dump($note2);
                            die();*/

                        @$rattrape = 0;

                        if ($note1 > 50) {

                            $rattrape = $note1;
                        }
                        if ($note_cc != null) {
                            $controle = $note_cc->note;
                            $id_eleve = $eleve->id;
                        } else {
                            $controle = '-';
                            $id_eleve = $eleve->id;
                        }
                      /*  if ($note_sn != null) {
                            $normal = $note_cc->note;

                            $id_eleve = $eleve->id;
                        } else {
                            $normal = '-';
                            $id_eleve = $eleve->id;
                        }
                           if ($note_sn != null) {
                            $normal = $note_cc->note;

                            $id_eleve = $eleve->id;
                        } else {
                            $normal = '-';
                            $id_eleve = $eleve->id;
                        }

                       */

                       if ($note_rattrap != null) {
                            if ($note1 >= 50) {
                                $note = $note2;
                                
                              //  $normal = $note_cc->note < 25;
                                $normal=($note_cc?$note_cc->note:0 <25);
                                $id_eleve = $eleve->id;

                            } else {
                             $note=($note_cc?$note_cc->note:0);
                              // $note = $note_cc->note;
                                $id_eleve = $eleve->id;

                                $normal = $note_rattrap->note;
                            }
                        } else {
                            $note = $note2;
                            $id_eleve = $eleve->id;

                        }

                    } else {
                        if ($note_rattrap != null) {

                            $note = $note_rattrap->note;
                            $normal = $note_rattrap->note;
                            $controle = '-';


                        } else {
                            $note = '-';
                            $normal = '-';
                            $controle = '-';
                            $id_eleve = $eleve->id;


                        }

                    }
                    if (!$cps)
                        if (is_numeric($note)) {
                            $sNote += $note * $credit;

                            $sCoef += $credit;
                            $id_eleve = $eleve->id;
                        }
                       

                    if (!$cps)
                        $matieres[$l] = new pv_matierecc($matiere->id, $note, $credit, $controle, $id_eleve);
                       /* $matieres[$l] = new pv_matiere($matiere->id, $note, $credit, $controle, $normal, $id_eleve);*/
                /*$matieres[$l] = new pv_matiere($matiere->id, $n, $credit, $cc, $nor, $id_eleve);*/


                    $l++;
                }
                /*  dump($matieres);
                  die();*/

                //dump($matieres);
                if ($sCoef <= 0)
                    $sCoef = 1;
                $proces_v[$k] = new PV($eleve->id, $matieres, $sNote / $sCoef);


                $k++;
				
            }
            /*dump($proces_v);
             die();*/
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['semestre'] = $semestre;

            return view('eleves/print_pv_cc', $values);

        }
    }



    public function fiche_note_cc(Request $request,$id)
    {
        $this->validate($request, [
            'sequence' => 'required|numeric',
            'cycle' => 'required|numeric',
            'nniveau' => 'required|numeric',
        ]);

        $values = array('titre' => 'Notes');
        $sequence = $request->input('sequence');
        $id_m = $request->input('id');
        $nniveau = $request->input('nniveau');
        $id_cycle = $request->input('cycle');

        if ($sequence == 5 || $sequence == 6) {
            if ($sequence == 5) {
                $semestre = 1;
                $sn = 3;
                $cc = 1;
                $rattrap = 5;
                $sequences_pv = array(1, 3, 5);
                //   $rettrapage_pv=array(1,3,5);
            } else {
                $semestre = 2;
                $sn = 4;
                $cc = 2;
                $rattrap = 6;
                $sequences_pv = array(2, 4, 6);
                // $rettrapage_pv=array(2,4,6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            $ccc = 0;
            $matieres = array();
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $rattrap)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $sn)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve', $id_eleves)
                ->whereIn('sequence', $sequences_pv)
                ->get();
            /*  $ccc=$n_sn+$n_cc;
              $note_ratrap=note::whereAnneeScolaire(\session('year'))
                  ->whereIn('id_eleve',$id_eleves)
                  ->where(is_numeric($ccc<50),$rettrapage_pv)
                  ->get();*/

            $matieres_pv = $notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef = 0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->where('id_cycle', '=', $id_cycle)->get();
            $gm = groupeMatieres::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->get();
            $m_cps = $gm->fetch('id_matiere_mere')->toArray();


            foreach ($cm as $entry) {
                if (!($this->isIn($entry->id_matiere, $m_cps)))
                    $sCoef += $entry->coef;
            }
            $matiere_list = $cm->fetch('id_matiere')->toArray();
            $m = matiere::whereIn('id', $matiere_list)->whereIn('id', $matieres_pv)->whereNotIn('id', $m_cps)->get();


            $k = 0;
            foreach ($e as $eleve) {

                $l = 0;
                $sNote = 0;
                $sCoef = 0;
                $es = $eleve->id;
                $idelevess = 0;

                foreach ($m as $matiere) {
                    $cps = false;
                    $cpst = false;

                    if ($this->isIn($matiere->id, $m_cps)) {
                        $cps = true;

                    }

                    $credit = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->coef;
                    $type = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->type;
                    $semestre = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->semestre;
                    $id_code = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->id_code;

                    $note_cc = $n_cc->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $es, false)
                        ->first();
                    $note_sn = $n_sn->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    $note_rattrap = $n_rattrap->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    /*  $note_re=$note_ratrap->where('id_matiere',$matiere->id,false)
                          ->first();*/

                    if($note_cc!=null || $note_sn!=null ){
                        @$note1=$note_cc->note+$note_sn->note;
                        if($note_rattrap!=null){
                            if($note1>=50){
                                $note=$note1;
                                if($note_cc)
                                    $controle=$note_cc->note;
                                else
                                    $controle='-';
                                if($note_sn)
                                    $normal=$note_sn->note;
                                else
                                    $normal='-';

                            }else{
                                $note=($note_cc?$note_cc->note:0)+$note_rattrap->note;
                                if($note_cc)
                                    $controle=$note_cc->note;
                                else
                                    $controle='-';

                                $normal=$note_rattrap->note;

                            }
                        }else{
                            $note=$note1;
                            if($note_cc)
                                $controle=$note_cc->note;
                            else
                                $controle='-';
                            if($note_sn)
                                $normal=$note_sn->note;
                            else
                                $normal='-';
                        }
                    }else{
                        if($note_rattrap!=null){

                            $note=$note_rattrap->note;
                            $controle='-';
                            $normal=$note_rattrap->note;


                        }else{
                            $note='-';
                            $controle='-';
                            $normal='-';
                        }

                    }

                    if(is_numeric($note)){
                        $sNote+=$note*$credit;
                        $sCoef+=$credit;
                        $note=round($note,2);
                    }
                    if(is_numeric($controle)){
                        $controle=round($controle,2);

                    }
                    if(is_numeric($normal)){
                        $normal=round($normal,2);

                    }



                    if (!$cps)
                        $matieres[$l] = new pv_matiere2($id_code,$matiere->nom,$note,$controle,$type, $credit,$matiere->id, $semestre,  $normal);


                    $l++;
                }
                /*  dump($matieres);
                  die();*/

                //dump($matieres);
                if ($sCoef <= 0)
                    $sCoef = 1;
                $proces_v[$k] = new PVliste($eleve->id, $matieres, $sNote / $sCoef);


                $k++;

            }
            /*dump($proces_v);
             die();*/
            $classe=classe::find($id);
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['cycle'] = cycle::find($id_cycle);
            $values['classe'] =$classe;
            $values['niveau'] = niveau::find($classe->id_niveau);
            $values['semestre'] = $semestre;

            return view('eleves/liste_matiere', $values);

        }



        if ($sequence == 7 || $sequence == 8) {
            if ($sequence == 7) {
                $semestre = 1;
                $sn = 3;
                $cc = 1;
                $rattrap = 5;
                $sequences_pv = array(1, 3, 5);
                //   $rettrapage_pv=array(1,3,5);
            } else {
                $semestre = 2;
                $sn = 4;
                $cc = 2;
                $rattrap = 6;
                $sequences_pv = array(2, 4, 6);
                // $rettrapage_pv=array(2,4,6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            $ccc = 0;
            $matieres = array();
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $rattrap)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $sn)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve', $id_eleves)
                ->whereIn('sequence', $sequences_pv)
                ->get();
            /*  $ccc=$n_sn+$n_cc;
              $note_ratrap=note::whereAnneeScolaire(\session('year'))
                  ->whereIn('id_eleve',$id_eleves)
                  ->where(is_numeric($ccc<50),$rettrapage_pv)
                  ->get();*/

            $matieres_pv = $notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef = 0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->where('id_cycle', '=', $id_cycle)->get();
            $gm = groupeMatieres::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->get();
            $m_cps = $gm->fetch('id_matiere_mere')->toArray();


            foreach ($cm as $entry) {
                if (!($this->isIn($entry->id_matiere, $m_cps)))
                    $sCoef += $entry->coef;
            }
            $matiere_list = $cm->fetch('id_matiere')->toArray();
            $m = matiere::whereIn('id', $matiere_list)->whereIn('id', $matieres_pv)->whereNotIn('id', $m_cps)->get();


            $k = 0;
            foreach ($e as $eleve) {

                $l = 0;
                $sNote = 0;
                $sCoef = 0;
                $es = $eleve->id;
                $idelevess = 0;

                foreach ($m as $matiere) {
                    $cps = false;
                    $cpst = false;

                    if ($this->isIn($matiere->id, $m_cps)) {
                        $cps = true;

                    }

                    $credit = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->coef;
                    $type = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->type;
                    $semestre = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->semestre;
                    $id_code = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->id_code;

                    $note_cc = $n_cc->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $es, false)
                        ->first();
                    $note_sn = $n_sn->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    $note_rattrap = $n_rattrap->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    /*  $note_re=$note_ratrap->where('id_matiere',$matiere->id,false)
                          ->first();*/

                    if ($note_cc != null || $note_sn != null) {
                        @$note1 = $note_cc->note + $note_sn->note;
                        @$ides = $es;

                        @$note2 = $note_cc->note;
                        @$note3 = $note_cc->note < 15;
                        /*    dump($note2);
                            die();*/

                        @$rattrape = 0;

                        if ($note1 > 30) {

                            $rattrape = $note1;
                        }
                        if ($note_cc != null) {
                            $controle = $note_cc->note;
                            $id_eleve = $eleve->id;
                        } else {
                            $controle = '-';
                            $id_eleve = $eleve->id;
                        }
                        if ($note_sn != null) {
                            $normal = $note_sn->note;
                            $id_eleve = $eleve->id;
                        } else {
                            $normal = '-';
                            $id_eleve = $eleve->id;
                        }

                        if ($note_rattrap != null) {
                            if ($note1 >= 30) {
                                $note = $note2;
                                $normal = $note_cc->note < 15;
                                $id_eleve = $eleve->id;

                            } else {
                                /*                                $note=($note_cc?$note_cc->note:0)+$note_rattrap->note;*/
                                $note = $note_cc->note;
                                $id_eleve = $eleve->id;

                                $normal = $note_rattrap->note;
                            }
                        } else {
                            $note = $note2;
                            $id_eleve = $eleve->id;

                        }

                    } else {
                        if ($note_rattrap != null) {

                            $note = $note_rattrap->note;
                            $normal = $note_rattrap->note;
                            $controle = '-';


                        } else {
                            $note = '-';
                            $normal = '-';
                            $controle = '-';
                            $id_eleve = $eleve->id;


                        }

                    }
                    if (!$cps)
                        if (is_numeric($note)) {
                            $sNote += $note * $credit;

                            $sCoef += $credit;
                            $id_eleve = $eleve->id;
                        }


                    if (!$cps)
                        $matieres[$l] = new pv_matiere1($id_code,$matiere->nom,$controle,$type, $credit,$matiere->id, $semestre,  $normal);


                    $l++;
                }
                /*  dump($matieres);
                  die();*/

                //dump($matieres);
                if ($sCoef <= 0)
                    $sCoef = 1;
                $proces_v[$k] = new PVliste($eleve->id, $matieres, $sNote / $sCoef);


                $k++;

            }
            /*dump($proces_v);
             die();*/
            $classe=classe::find($id);
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['cycle'] = cycle::find($id_cycle);
            $values['classe'] =$classe;
            $values['niveau'] = niveau::find($classe->id_niveau);
            $values['semestre'] = $semestre;

            return view('eleves/fiche_note', $values);

        }



        //fiche individualisé sn


        if ($sequence == 9 || $sequence == 10) {
            if ($sequence == 9) {
                $semestre = 1;
                $sn = 3;
                $cc = 1;
                $rattrap = 5;
                $sequences_pv = array(1, 3, 5);
                //   $rettrapage_pv=array(1,3,5);
            } else {
                $semestre = 2;
                $sn = 4;
                $cc = 2;
                $rattrap = 6;
                $sequences_pv = array(2, 4, 6);
                // $rettrapage_pv=array(2,4,6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            $ccc = 0;
            $matieres = array();
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $rattrap)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $sn)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve', $id_eleves)
                ->whereIn('sequence', $sequences_pv)
                ->get();
            /*  $ccc=$n_sn+$n_cc;
              $note_ratrap=note::whereAnneeScolaire(\session('year'))
                  ->whereIn('id_eleve',$id_eleves)
                  ->where(is_numeric($ccc<50),$rettrapage_pv)
                  ->get();*/

            $matieres_pv = $notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef = 0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->where('id_cycle', '=', $id_cycle)->get();
            $gm = groupeMatieres::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->get();
            $m_cps = $gm->fetch('id_matiere_mere')->toArray();


            foreach ($cm as $entry) {
                if (!($this->isIn($entry->id_matiere, $m_cps)))
                    $sCoef += $entry->coef;
            }
            $matiere_list = $cm->fetch('id_matiere')->toArray();
            $m = matiere::whereIn('id', $matiere_list)->whereIn('id', $matieres_pv)->whereNotIn('id', $m_cps)->get();


            $k = 0;
            foreach ($e as $eleve) {

                $l = 0;
                $sNote = 0;
                $sCoef = 0;
                $es = $eleve->id;
                $idelevess = 0;

                foreach ($m as $matiere) {
                    $cps = false;
                    $cpst = false;

                    if ($this->isIn($matiere->id, $m_cps)) {
                        $cps = true;

                    }

                    $credit = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->coef;
                    $type = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->type;
                    $semestre = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->semestre;
                    $id_code = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->id_code;

                    $note_cc = $n_cc->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $es, false)
                        ->first();
                    $note_sn = $n_sn->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    $note_rattrap = $n_rattrap->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    /*  $note_re=$note_ratrap->where('id_matiere',$matiere->id,false)
                          ->first();*/

                    if($note_cc!=null || $note_sn!=null ){
                        @$note1=$note_cc->note+$note_sn->note;
                        if($note_rattrap!=null){
                            if($note1>=50){
                                $note=$note1;
                                if($note_cc)
                                    $controle=$note_cc->note;
                                else
                                    $controle='-';
                                if($note_sn)
                                    $normal=$note_sn->note;
                                else
                                    $normal='-';

                            }else{
                                $note=($note_cc?$note_cc->note:0)+$note_rattrap->note;
                                if($note_cc)
                                    $controle=$note_cc->note;
                                else
                                    $controle='-';

                                $normal=$note_rattrap->note;

                            }
                        }else{
                            $note=$note1;
                            if($note_cc)
                                $controle=$note_cc->note;
                            else
                                $controle='-';
                            if($note_sn)
                                $normal=$note_sn->note;
                            else
                                $normal='-';
                        }
                    }else{
                        if($note_rattrap!=null){

                            $note=$note_rattrap->note;
                            $controle='-';
                            $normal=$note_rattrap->note;


                        }else{
                            $note='-';
                            $controle='-';
                            $normal='-';
                        }

                    }

                    if(is_numeric($note)){
                        $sNote+=$note*$credit;
                        $sCoef+=$credit;
                        $note=round($note,2);
                    }
                    if(is_numeric($controle)){
                        $controle=round($controle,2);

                    }
                    if(is_numeric($normal)){
                        $normal=round($normal,2);

                    }



                    if (!$cps)
                        $matieres[$l] = new pv_matiere2($id_code,$matiere->nom,$note,$controle,$type, $credit,$matiere->id, $semestre,  $normal);


                    $l++;
                }
                /*  dump($matieres);
                  die();*/

                //dump($matieres);
                if ($sCoef <= 0)
                    $sCoef = 1;
                $proces_v[$k] = new PV_sn($eleve->id, $matieres, $sNote / $sCoef);


                $k++;

            }
            /*dump($proces_v);
             die();*/
            $classe=classe::find($id);
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['cycle'] = cycle::find($id_cycle);
            $values['classe'] =$classe;
            $values['niveau'] = niveau::find($classe->id_niveau);
            $values['semestre'] = $semestre;

            return view('eleves/fiche_note_sn', $values);

        }
    }





    //pv rattrapages

    public function print_note_rat(Request $request,$id)
    {
        $this->validate($request, [
            'sequence' => 'required|numeric',
            'cycle' => 'required|numeric',
            'nniveau' => 'required|numeric',
        ]);

        $values = array('titre' => 'Notes');
        $sequence = $request->input('sequence');
        $id_m = $request->input('id');
        $nniveau = $request->input('nniveau');
        $id_cycle = $request->input('cycle');

        if ($sequence == 1 || $sequence == 2) {
            $mat = matiere::find($id_m);
            $cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->where('id_nniveau', '=', $nniveau)->first();

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();


            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['sequence'] = $sequence;

            return view('eleves/print_note_cc', $values);
        }

        if ($sequence == 3 || $sequence == 4) {
            if ($sequence == 3)
                $cc = 1;
            else
                $cc = 2;
            $mat = matiere::find($id_m);
            //$cm = classeMatiere::whereIdClasse($id)->where('id_matiere', '=', $id_m)->where('id_cycle', '=', $id_cycle)->first();
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();

            $tns = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $cc)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();
            if ($tns == null) {
                session(['erreur' => trans('admin.no_cc', ['matiere' => $mat->nom . '(' . $mat->nom_anglais . ')'])]);
                return redirect()->to('/eleves/notes/matieres/show?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau);
            }
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;
            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            if ($sequence == 3)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves/print_note_sn', $values);
        }
        if ($sequence == 5 || $sequence == 6) {
            if ($sequence == 5) {
                $cc = 1;
                $sn = 3;
            } else {
                $cc = 2;
                $sn = 4;
            }
            $mat = matiere::find($id_m);
            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $sequence)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('id_matiere', '=', $id_m)
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $id_eleves2 = $n->fetch('id_eleve')->toArray();
            $e = eleve::whereIn('id', $id_eleves2)->orderBy('nom')->orderBy('prenom')->get();


           $tns = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $cc)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();
            $tnr = note::whereAnneeScolaire(\session('year'))->where('sequence', '=', $sn)->where('id_matiere', '=', $id_m)->whereIn('id_eleve', $id_eleves)->first();

            if ($tns == null || $tnr == null) {
                session(['erreur' => trans('admin.no_note', ['matiere' => $mat->nom . '(' . $mat->nom_anglais . ')'])]);
                return redirect()->to('/eleves/notes/matieres/show?id=' . $id . '&cycle=' . $id_cycle . '&nniveau=' . $nniveau);
            }


            $values['eleves'] = $e;
            $values['notes'] = $n;
            $values['notes_cc'] = $n_cc;

            //$values['max'] = $cm->max;
            $values['matiere'] = $mat;
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['nniveau'] = NNiveau::find($nniveau);
            if ($sequence == 5)
                $values['semestre'] = 1;
            else
                $values['semestre'] = 2;
            $values['sequence'] = $sequence;

            return view('eleves/print_note_rat', $values);
        }
       if ($sequence == 7 || $sequence == 8) {
            if ($sequence == 7) {
                $semestre = 1;
                $sn = 3;
                $cc = 1;
                $rattrap = 5;
                $sequences_pv = array(1, 3, 5);
            } else {
                $semestre = 2;
                $sn = 4;
                $cc = 2;
                $rattrap = 6;
                $sequences_pv = array(2, 4, 6);
            }

            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle, $nniveau]);
            $j = 0;
            $ccc = 0;
            $matieres = array();
            foreach ($e as $el) {
                $id_eleves[$j] = $el->id;
                $j++;
            }
            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $rattrap)
                ->whereIn('id_eleve', $id_eleves)
                ->get();

            $n_sn = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $sn)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->where('sequence', '=', $cc)
                ->whereIn('id_eleve', $id_eleves)
                ->get();
            $notes_sequences_pv = note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve', $id_eleves)
                ->whereIn('sequence', $sequences_pv)
                ->get();

            $matieres_pv = $notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef = 0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->where('id_cycle', '=', $id_cycle)->get();
            $gm = groupeMatieres::whereIdClasse($id)->where('id_nniveau', '=', $nniveau)->get();
            $m_cps = $gm->fetch('id_matiere_mere')->toArray();


            foreach ($cm as $entry) {
                if (!($this->isIn($entry->id_matiere, $m_cps)))
                    $sCoef += $entry->coef;
            }
            $matiere_list = $cm->fetch('id_matiere')->toArray();
            $m = matiere::whereIn('id', $matiere_list)->whereIn('id', $matieres_pv)->whereNotIn('id', $m_cps)->get();


            $k = 0;
            foreach ($e as $eleve) {

                $l = 0;
                $sNote = 0;
                $sCoef = 0;
                $es = $eleve->id;
                $idelevess = 0;

                foreach ($m as $matiere) {
                    $cps = false;
                    $cpst = false;

                    if ($this->isIn($matiere->id, $m_cps)) {
                        $cps = true;

                    }

                    $credit = $cm->where('id_matiere', $matiere->id)
                        ->first()
                        ->coef;
                    $note_cc = $n_cc->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    $note_sn = $n_sn->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    $note_rattrap = $n_rattrap->where('id_matiere', $matiere->id, false)
                        ->where('id_eleve', $eleve->id, false)
                        ->first();
                    if($note_rattrap!=null ){
                        @$note=$note_cc->note+$note_rattrap->note;

                        $normal=$note_rattrap->note;
                        if($note_cc!=null){
                            $controle=$note_cc->note;
                        }
                        else{
                            $controle='';
                        }
                    }

                    else{
                        $normal='';
                        $note='';
                        $controle='';
                    }
                      if (!$cps)
                        if (is_numeric($note)) {
                            $sNote += $note * $credit;

                            $sCoef += $credit;
                            $id_eleve = $eleve->id;
                        }

                    $matieres[$l]=new pv_matiereRat($matiere->id,$note,$credit,$controle,$normal);



                    $l++;

                }
               
                /*dump(array_slice($matieres, 0, 10, true));
                die();*/

                if ($sCoef<=0) {
                    $sCoef=1;
                }
            

                $proces_v[$k]=new PV($eleve->id,$matieres,$sNote/$sCoef);
                $k++;
            }
           // dump($proces_v);
           //   die();
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['nniveau'] = NNiveau::find($nniveau);
            $values['id_cycle'] = $id_cycle;
            $values['classe'] = classe::find($id);
            $values['semestre'] = $semestre;

            return view('eleves/print_pv_rat', $values);

        }
    }



	public function show()
	{
        $values= array('titre'=>'Notes');
        $c=classe::orderBy('nom')->get();
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $cy=cycle::all();
        $n=niveau::all();
        $nn=NNiveau::all();

        $values['niveaux']=$n;
        $values['classes']=$c;
        $values['cycles']=$cy;
        $values['nniveaux']=$nn
        ;

        return view('eleves/classe_note_show',$values);
	}


    public function show_fiche()
    {
        $values= array('titre'=>'Notes');
        $c=classe::orderBy('nom')->get();
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $cy=cycle::all();
        $n=niveau::all();
        $nn=NNiveau::all();

        $values['niveaux']=$n;
        $values['classes']=$c;
        $values['cycles']=$cy;
        $values['nniveaux']=$nn
        ;

        return view('eleves/classe_note_fiche',$values);
    }



    public function show_deliberer()
    {
        $values= array('titre'=>'Notes');
        $c=classe::orderBy('nom')->get();
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $cy=cycle::all();
        $n=niveau::all();
        $nn=NNiveau::all();

        $values['niveaux']=$n;
        $values['classes']=$c;
        $values['cycles']=$cy;
        $values['nniveaux']=$nn
        ;

        return view('eleves/classe_note_show_deliberer',$values);
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

    function isIn($val,$array)
    {
        $n=sizeof($array);
        for($i=0;$i<$n;$i++){
            if($val==$array[$i])
                return true;
        }
        return false;
    }

    function getGrade($note){
        if($note>=80){
            $grade[0]='A';
            $grade[1]=4;
            return $grade;
        }
        if($note>=75){
            $grade[0]='A-';
            $grade[1]=3.7;
            return $grade;
        }
        if($note>=70){
            $grade[0]='B+';
            $grade[1]=3.3;
            return $grade;
        }
        if($note>=65){
            $grade[0]='B';
            $grade[1]=3;
            return $grade;
        }
        if($note>=60){
            $grade[0]='B-';
            $grade[1]=2.7;
            return $grade;
        }
        if($note>=55){
            $grade[0]='C+';
            $grade[1]=2.3;
            return $grade;
        }
        if($note>=50){
            $grade[0]='C';
            $grade[1]=2;
            return $grade;
        }

        if($note>=45){
            $grade[0]='C-';
            $grade[1]=1.7;
            return $grade;
        }
        if($note>=40){
            $grade[0]='D+';
            $grade[1]=1.3;
            return $grade;
        }
        if($note>=35){
            $grade[0]='D';
            $grade[1]=1;
            return $grade;
        }
        if($note>=30){
            $grade[0]='E';
            $grade[1]=0;
            return $grade;
        }
        $grade[0]='F';
        $grade[1]=0;
        return $grade;

    }

}
class PV{
    public $id_eleve;
    public $matieres;
    public $mgp;
    public  function __construct($id_e,$ma,$mg)
    {
        //$this->eleve=eleve::find($id_e);
       $this->id_eleve=$id_e;
        $this->matieres=$ma;
        $this->mgp=$mg;

    }
}
class PVliste{
    public $id_eleve;
    public $matieres;
    public $mgp;
    public  function __construct($id_e,$ma,$mg)
    {
        $this->eleve=eleve::find($id_e);
       //$this->id_eleve=$id_e;
        $this->matieres=$ma;
        $this->mgp=$mg;

    }
}

class PV_sn{
    public $id_eleve;
    public $matieres;
    public $mgp;
    public  function __construct($id_e,$ma,$mg)
    {
         $this->eleve=eleve::find($id_e);
       // $this->id_eleve=$id_e;
        $this->matieres=$ma;
        $this->mgp=$mg;

    }
}

// app/Models/PV.php or inside controller file if you use inner classes

class pv_matiereSem {
    public $id_matiere;
    public $note; // total sur 20 (float|null)
    public $coef; // credit / coefficient
    public $cc;   // cc /20 (float|null)
    public $sn;   // sn ou rat /20 (float|null)
    public $tpe;  // tpe /20 (float|null)
    public $type;
    public $total20;    // 🆕 TOTAL SUR 20
    public function __construct($id_matiere, $note = null, $coef = 0, $cc = null, $sn = null, $tpe = null,$type, $total20) {
        $this->id_matiere = $id_matiere;
        $this->note       = $note;
        $this->coef       = $coef;
        $this->cc         = $cc;
        $this->sn         = $sn;
        $this->tpe        = $tpe;
        $this->type       = $type;
        $this->total20    = $total20; // 🆕 ajouté
    }
}

class PVsem {
    public $id_eleve;
    public $matieres; // array of pv_matiere
    public $mgp;      // moyenne (échelle 0-20 ou 0-100 selon usage) — on précise dans le code

    public function __construct($id_eleve, $matieres = array(), $mgp = 0) {
        $this->id_eleve = $id_eleve;
        $this->matieres = $matieres;
        $this->mgp      = $mgp;
    }
}





class pv_matiere{
    public $id_matiere;
    public $note;      // total = cc + sn + tpe
    public $controle;  // CC
    public $normal;    // SN
    public $tpe;       // TPE /20
    public $credit;
    public $type;

    public function __construct($id_m, $not, $cr, $cc, $sn, $tpe, $type)
    {
        $this->id_matiere = $id_m;
        $this->note       = $not;   // total CC+SN+TPE (déjà calculé dans le controller)
        $this->credit     = $cr;
        $this->controle   = $cc;
        $this->normal     = $sn;
        $this->tpe        = $tpe;
        $this->type       = $type;
    }
}

class pv_matiereancien{
    public $id_matiere;
    public $note;
    public $controle;
    public $normal;
    //public $id_code;
    public $credit;
    public $type;

    public  function __construct($id_m,$not,$cr,$cc,$sn,$type)
    {
        $this->id_matiere=$id_m;
        $this->note=$not;
        $this->credit=$cr;
        $this->controle=$cc;
        $this->normal=$sn;
        //$this->id_code=$id_code;
        $this->type=$type;

    }
}



class pv_matierecc{
    public $id_matiere;
    public $note;
    public $controle;
   // public $normal;
    //public $id_code;
    public $credit;
    public $type;

    public  function __construct($id_m,$not,$cr,$cc,$type)
    {
        $this->id_matiere=$id_m;
        $this->note=$not;
        $this->credit=$cr;
        $this->controle=$cc;
        //$this->normal=$sn;
        //$this->id_code=$id_code;
        $this->type=$type;

    }
}
class pv_matiere1{
    public $id_code;
    public $altName;
    public $controle;
    public $type;
    public $credit;
    public $id_matiere;
    public $semestre;
    public $normal;
    public  function __construct($id_code,$altName,$cc,$type,$cr,$id_m,$sem,$sn)
    {
        $this->id_code=$id_code;
        $this->altName=$altName;
        $this->controle=$cc;
        $this->type=$type;
        $this->credit=$cr;
        $this->id_matiere=$id_m;
        $this->semestre=$sem;
        $this->normal=$sn;




    }
}

class pv_matiereRat{
    public $id_matiere;
    public $note;
    public $controle;
    public $normal;
    //public $id_code;
    public $credit;
    //public $type;

    public  function __construct($id_m,$not,$cr,$cc,$sn)
    {
        $this->id_matiere=$id_m;
        $this->note=$not;
        $this->credit=$cr;
        $this->controle=$cc;
        $this->normal=$sn;
       // $this->id_code=$id_code;
       // $this->type=$type;

    }
}


class pv_matiere2{
    public $id_code;
    public $altName;
    public $note;
    public $controle;
    public $type;
    public $credit;
    public $id_matiere;
    public $semestre;
    public $normal;





    public  function __construct($id_code,$altName,$note,$cc,$type,$cr,$id_m,$sem,$sn)
    {
        $this->id_code=$id_code;
        $this->altName=$altName;
        $this->note=$note;
        $this->controle=$cc;
        $this->type=$type;
        $this->credit=$cr;
        $this->id_matiere=$id_m;
        $this->semestre=$sem;
        $this->normal=$sn;




    }
}

class matiere_rattraper{
    public $id_matiere;
    public $note;
    public $id_etudiant;
    public $semestre;

    public  function __construct($id_m,$not,$cr,$cc,$sn)
    {
        $this->id_matiere=$id_m;
        $this->note=$not;
        $this->id_etudiant=$cc;
        $this->semestre=$sn;

    }



}

class matiere_groupe
{
    public $id;
    public $nom;

    /**
     * matiere_groupe constructor.
     * @param $id
     * @param $nom
     */
    public function __construct($id, $nom)
    {
        $this->id = $id;
        $this->nom = $nom;
    }


}

class composante_pv{
    public $id_matiere;
    public $note;
    public $id_classe_matiere;
    public $nom;
    public $credit;
    public $id_code;

    public function __construct($id,$n,$icm,$no,$credit,$id_code)
    {
        $this->id_matiere=$id;
        $this->note=$n;
        $this->id_classe_matiere=$icm;
        $this->nom=$no;
        $this->credit=$credit;
        $this->id_code=$id_code;
    }
}
class compose_pv{
    public $id_matiere;
    public $note;
    public $coef;
    public $cpsList;
    public $nom;
    public $credit;
    public $type;
    public $semestre;
    public $id_code;

    public function __construct($id,$n,$c,$cl,$no,$cr,$ty,$se,$id_code)
    {
        $this->id_matiere=$id;
        $this->note=$n;
        $this->coef=$c;
        $this->cpsList=$cl;
        $this->nom=$no;
        $this->credit=$cr;
        $this->type=$ty;
        $this->semestre=$se;
        $this->id_code=$id_code;
    }
}