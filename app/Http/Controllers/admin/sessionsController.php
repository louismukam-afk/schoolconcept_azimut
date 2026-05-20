<?php namespace gesecole\Http\Controllers\admin;

use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\inscription;
use gesecole\matiere;
use gesecole\NNiveau;
use gesecole\Session_exam;
use Illuminate\Http\Request;

class sessionsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title' => trans('admin.dashboard')]);

        $year = session('year');
        if ($year == null) {
            $p = parametres::whereNom('annee_scolaire')->first();

            if (!$p) {
                $p = new parametres();
                $p->nom = 'annee_scolaire';
                $p->valeur = '2017-2018';
                $p->save();
            }
            session(['year' => $p->valeur]);
        }
    }

    /**
     * Liste des sessions pour une classe / niveau, à partir du programme de classe
     * URL type : /admin/sessions?id=1&cycle=2&nniveau=3
     */
    public function index(Request $request, $id_classe_matiere)
    {
        $id_classe  = $request->get('id_classe');   // envoyé dans l’URL
        $id_matiere = $request->get('id_matiere');  // envoyé dans l’URL
        $annee      = session('year');

        // Liste des années académiques (pour une future liste déroulante)
        $annees = inscription::select('annee_scolaire')
            ->distinct()
            ->orderBy('annee_scolaire', 'desc')
            ->get();

        // 1. Classe
        $classe = classe::findOrFail($id_classe);

        // 2. Programme (classe_matiere)
        $programme = classeMatiere::findOrFail($id_classe_matiere);

        // 3. Niveau + cycle
        $id_nniveau = $programme->id_nniveau;
        $id_cycle   = property_exists($programme, 'id_cycle')
            ? $programme->id_cycle
            : null;

        $niveau = NNiveau::find($id_nniveau);

        // 4. Matière (pour afficher son nom dans le titre)
        $matiere = matiere::find($id_matiere); // ⚠️ change en "matiere" si besoin
        $libelle_matiere = '';
        if ($matiere) {
            $libelle_matiere = (app()->getLocale() == 'en')
                ? $matiere->nom_anglais
                : $matiere->nom;
        }

        // 5. On garde un tableau $programmes pour rester compatible avec la vue
        $programmes = [$programme];

        // 6. Sessions de ce programme + année
      /*  $sessions = Session_exam::where('id_classe_matiere', $id_classe_matiere)
            ->where('annee_academique', $annees)
            ->get();*/
        $sessions = Session_exam::where('id_classe_matiere', $id_classe_matiere)
            ->orderBy('annee_academique', 'desc')
            ->get();


        $values = [
            'titre'           => trans('admin.sessions_titre'),
            'classe'          => $classe,
            'id_cycle'        => $id_cycle,
            'niveau'          => $niveau,
            'id_nniveau'      => $id_nniveau,
            'programmes'      => $programmes,
            'sessions'        => $sessions,
            'annees'          => $annees,
            'annee'           => $annee,
            'id_matiere'      => $id_matiere,
            'libelle_matiere' => $libelle_matiere,
        ];

        if(session('success')){
            \Session::forget('success');
            $values['success']=true;
        }


        return view('admin/sessions', $values);
    }
    public function index3(Request $request, $id_classe_matiere)
    {
        $id_classe  = $request->get('id_classe');   // envoyé dans l’URL
        $id_matiere = $request->get('id_matiere');  // envoyé dans l’URL
        $annee      = session('year');
        $annees = inscription::select('annee_scolaire')
            ->distinct()
            ->orderBy('annee_scolaire', 'desc')
            ->get();
        // 1. Classe
        $classe = classe::findOrFail($id_classe);

        // 2. Programme (classe_matiere)
        $programme = classeMatiere::findOrFail($id_classe_matiere);

        // 3. Récupérer cycle / nniveau à partir du programme
        $id_nniveau = $programme->id_nniveau;
        $id_cycle   = property_exists($programme, 'id_cycle')
            ? $programme->id_cycle
            : null;
// 4. Niveau (objet complet, envoyé à la vue)
        $niveau = NNiveau::find($id_nniveau);
        // 4. Pour garder la même vue (qui boucle sur $programmes)
        $programmes = [$programme];

        // ⚠️ Attention au nom de la colonne :
        // si dans ta table c'est "classe_matiere", garde comme ça :
        $sessions = Session_exam::where('id_classe_matiere', $id_classe_matiere)
            ->where('annee_academique',$annee)
            ->get();

        $values = [
            'titre'      => trans('admin.sessions_titre'),
            'classe'     => $classe,
            'id_cycle'   => $id_cycle,
            'niveau'     => $niveau,     // 🔹 envoyé à la vue
            'id_nniveau' => $id_nniveau,
            'programmes' => $programmes,
            'sessions'   => $sessions,
            'annees'   => $annees,
            'annee'      => $annee,
            'id_matiere' => $id_matiere
        ];

        if (session('success')) {
            Session::forget('success');
            $values['success'] = true;
        }

        return view('admin/sessions', $values);
    }

    // store / update / destroy restent comme tu les avais adaptés

    public function indexko(Request $request, $id_classe_matiere)
    {
        $id_classe  = $request->get('id_classe');
        $id_matiere = $request->get('id_matiere');
        $id_cycle   = $request->get('cycle');
        $id_nniveau = $request->get('nniveau');
        $annee      = session('year');

        // Classe
        $classe = classe::findOrFail($id_classe);

        // Le programme (classe_matiere) concerné
        $programme = classeMatiere::findOrFail($id_classe_matiere);

        // Pour garder la même vue (qui boucle sur $programmes)
        $programmes = [$programme];

        // Session(s) pour cette classe_matiere et cette année
        $sessions = Session_exam::where('id_classe_matiere', $id_classe_matiere)
            ->where('annee_academique', $annee)
            ->get();

        $titre = trans('admin.sessions_titre');

        $values = [
            'titre'      => $titre,
            'classe'     => $classe,
            'id_cycle'   => $id_cycle,
            'id_nniveau' => $id_nniveau,
            'programmes' => $programmes,
            'sessions'   => $sessions,
        ];

        // Gestion du message de succès (comme dans ton codagesController)
        if (session('success')) {
            Session::forget('success');
            $values['success'] = true;
        }

        return view('admin/sessions', $values);
    }


    public function index1(Request $request)
    {
        $id_classe   = $request->get('id');
        $id_cycle    = $request->get('cycle');
        $id_nniveau  = $request->get('nniveau');
        $annee       = session('year');

        $classe = classe::findOrFail($id_classe);

        // Programmes de la classe (classe_matiere)
        $programmes = classeMatiere::where('id_classe', $id_classe)
            ->where('id_nniveau', $id_nniveau)
            ->get();

        // Sessions existantes pour ces programmes
        $sessions = Session_exam::whereIn('classe_matiere', $programmes->pluck('id'))
            ->where('annee_academique', $annee)
            ->get();

        $titre = trans('admin.sessions_titre'); // à ajouter dans tes fichiers de langue

        $values = [
            'titre'      => $titre,
            'classe'     => $classe,
            'id_cycle'   => $id_cycle,
            'id_nniveau' => $id_nniveau,
            'programmes' => $programmes,
            'sessions'   => $sessions,
        ];

        if (session('success')) {
            Session_exam::forget('success');
            $values['success'] = true;
        }

        return view('admin/sessions', $values);
    }

    /**
     * Création d'une session
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_classe_matiere' => 'required|numeric',
            'id_matiere'        => 'required|numeric',
            'annee_academique'  => 'required',   // 🔹 sélectionnée dans le formulaire

            // Tous les champs de pourcentage doivent être numériques (0–100)
            'cc1'           => 'numeric|min:0|max:100',
            'tpe1'          => 'numeric|min:0|max:100',
            'sn1'           => 'numeric|min:0|max:100',
            'ratrappage1'   => 'numeric|min:0|max:100',
            'cc2'           => 'numeric|min:0|max:100',
            'tpe2'          => 'numeric|min:0|max:100',
            'sn2'           => 'numeric|min:0|max:100',
            'ratrappage2'   => 'numeric|min:0|max:100',
        ]);

        // 🔹 On ne prend plus la session('year'), mais la valeur choisie dans le select
        $annee = $request->input('annee_academique');

        $s = new Session_exam();
        $s->id_classe_matiere  = $request->input('id_classe_matiere');
        $s->id_matiere         = $request->input('id_matiere');

        $s->cc1         = $request->input('cc1', 0);
        $s->tpe1        = $request->input('tpe1', 0);
        $s->sn1         = $request->input('sn1', 0);
        $s->ratrappage1 = $request->input('ratrappage1', 0);

        $s->cc2         = $request->input('cc2', 0);
        $s->tpe2        = $request->input('tpe2', 0);
        $s->sn2         = $request->input('sn2', 0);
        $s->ratrappage2 = $request->input('ratrappage2', 0);

        $s->annee_academique = $annee;   // 🔹 année venant du formulaire
        $s->save();

        session(['success' => true]);

        // 🔹 Redirection vers la même page de sessions de la matière
        return redirect()->to(
            '/admin/sessions/' . $request->input('id_classe_matiere')
            . '?id_classe=' . $request->input('id_classe')
            . '&id_matiere=' . $request->input('id_matiere')
        );
    }

    public function store2(Request $request)
    {
        $this->validate($request, [
            'id_classe_matiere'  => 'required|numeric',
            'id_matiere'         => 'required|numeric',
            'annee_academique'   => 'required',   // ✅ année choisie dans le formulaire
            'cc1'                => 'nullable|numeric',
            'tpe1'               => 'nullable|numeric',
            'sn1'                => 'nullable|numeric',
            'ratrappage1'        => 'nullable|numeric',
            'cc2'                => 'nullable|numeric',
            'tpe2'               => 'nullable|numeric',
            'sn2'                => 'nullable|numeric',
            'ratrappage2'        => 'nullable|numeric',
        ]);

        // ⚠️ On ne prend plus session('year') mais la valeur envoyée par le formulaire
        $annee = $request->input('annee_academique');

        $s = new Session_exam();
        $s->id_classe_matiere  = $request->input('id_classe_matiere');
        $s->id_matiere         = $request->input('id_matiere');
        $s->cc1                = $request->input('cc1', 0);
        $s->tpe1               = $request->input('tpe1', 0);
        $s->sn1                = $request->input('sn1', 0);
        $s->ratrappage1        = $request->input('ratrappage1', 0);
        $s->cc2                = $request->input('cc2', 0);
        $s->tpe2               = $request->input('tpe2', 0);
        $s->sn2                = $request->input('sn2', 0);
        $s->ratrappage2        = $request->input('ratrappage2', 0);
        $s->annee_academique   = $annee;               // ✅ année choisie
        $s->save();

        session(['success' => true]);

        // On renvoie vers la même page (à adapter selon ton routing)
        return redirect()->to('/admin/sessions?id='
            .$request->input('id_classe')
            .'&cycle='.$request->input('id_cycle')
            .'&nniveau='.$request->input('id_nniveau')
        );
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'id'              => 'required|numeric',
            'annee_academique'=> 'required',

            'cc1'           => 'numeric|min:0|max:100',
            'tpe1'          => 'numeric|min:0|max:100',
            'sn1'           => 'numeric|min:0|max:100',
            'ratrappage1'   => 'numeric|min:0|max:100',
            'cc2'           => 'numeric|min:0|max:100',
            'tpe2'          => 'numeric|min:0|max:100',
            'sn2'           => 'numeric|min:0|max:100',
            'ratrappage2'   => 'numeric|min:0|max:100',
        ]);

        $s = Session_exam::findOrFail($request->input('id'));

        $s->cc1         = $request->input('cc1', 0);
        $s->tpe1        = $request->input('tpe1', 0);
        $s->sn1         = $request->input('sn1', 0);
        $s->ratrappage1 = $request->input('ratrappage1', 0);

        $s->cc2         = $request->input('cc2', 0);
        $s->tpe2        = $request->input('tpe2', 0);
        $s->sn2         = $request->input('sn2', 0);
        $s->ratrappage2 = $request->input('ratrappage2', 0);

        $s->annee_academique = $request->input('annee_academique');  // 🔹 update année
        $s->save();

        session(['success' => true]);

        return redirect()->to(
            '/admin/sessions/' . $request->input('id_classe_matiere')
            . '?id_classe=' . $request->input('id_classe')
            . '&id_matiere=' . $request->input('id_matiere')
        );
    }


    public function store1(Request $request)
    {
        $this->validate($request, [
            'id_classe_matiere'  => 'required|numeric',
            'id_matiere'         => 'required|numeric',
            'cc1'             => 'nullable|numeric',
            'tpe1'            => 'nullable|numeric',
            'sn1'             => 'nullable|numeric',
            'ratrappage1'     => 'nullable|numeric',
            'cc2'             => 'nullable|numeric',
            'tpe2'            => 'nullable|numeric',
            'sn2'             => 'nullable|numeric',
            'ratrappage2'     => 'nullable|numeric',
        ]);

        $annee = session('year');

        $s = new Session_exam();
        $s->id_classe_matiere  = $request->input('id_classe_matiere');
        $s->id_matiere         = $request->input('id_matiere');
        $s->cc1             = $request->input('cc1', 0);
        $s->tpe1            = $request->input('tpe1', 0);
        $s->sn1             = $request->input('sn1', 0);
        $s->ratrappage1     = $request->input('ratrappage1', 0);
        $s->cc2             = $request->input('cc2', 0);
        $s->tpe2            = $request->input('tpe2', 0);
        $s->sn2             = $request->input('sn2', 0);
        $s->ratrappage2     = $request->input('ratrappage2', 0);
        $s->annee_academique = $annee;
        $s->save();

        session(['success' => true]);

        // On renvoie vers la même page (classe / cycle / niveau)
        return redirect()->to('/admin/sessions?id='
            .$request->input('id_classe')
            .'&cycle='.$request->input('id_cycle')
            .'&nniveau='.$request->input('id_nniveau')
        );
    }

    /**
     * Mise à jour d'une session
     */
    public function update1(Request $request)
    {
        $this->validate($request, [
            'id'             => 'required|numeric',
            'cc1'            => 'nullable|numeric',
            'tpe1'           => 'nullable|numeric',
            'sn1'            => 'nullable|numeric',
            'ratrappage1'    => 'nullable|numeric',
            'cc2'            => 'nullable|numeric',
            'tpe2'           => 'nullable|numeric',
            'sn2'            => 'nullable|numeric',
            'ratrappage2'    => 'nullable|numeric',
        ]);

        $s = Session_exam::findOrFail($request->input('id'));

        $s->cc1         = $request->input('cc1', 0);
        $s->tpe1        = $request->input('tpe1', 0);
        $s->sn1         = $request->input('sn1', 0);
        $s->ratrappage1 = $request->input('ratrappage1', 0);
        $s->cc2         = $request->input('cc2', 0);
        $s->tpe2        = $request->input('tpe2', 0);
        $s->sn2         = $request->input('sn2', 0);
        $s->ratrappage2 = $request->input('ratrappage2', 0);
        $s->save();

        session(['success' => true]);

        return redirect()->to('/admin/sessions?id='
            .$request->input('id_classe')
            .'&cycle='.$request->input('id_cycle')
            .'&nniveau='.$request->input('id_nniveau')
        );
    }

    /**
     * Suppression d'une session
     */

    public function destroy($id, Request $request)
    {
        // On supprime la session d'examen
        Session_exam::destroy($id);
        session(['success' => true]);

        // On récupère les paramètres envoyés dans l’URL (GET)
        $id_classe         = $request->get('id_classe');
        $id_matiere        = $request->get('id_matiere');
        $id_classe_matiere = $request->get('id_classe_matiere');

        // 🔁 On revient sur la même page que store()
        return redirect()->to(
            '/admin/sessions/' . $id_classe_matiere
            . '?id_classe=' . $id_classe
            . '&id_matiere=' . $id_matiere
        );
    }



    public function destroy1($id, Request $request)
    {
        Session_exam::destroy($id);
        session(['success' => true]);

        $id_classe  = $request->get('id');
        $id_cycle   = $request->get('cycle');
        $id_nniveau = $request->get('nniveau');

        return redirect()->to('/admin/sessions?id='
            .$id_classe.'&cycle='.$id_cycle.'&nniveau='.$id_nniveau);
    }

}
