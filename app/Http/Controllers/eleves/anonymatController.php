<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\AnonymatExamen;
use gesecole\Campus;
use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\cycle;
use gesecole\Http\Controllers\Controller;
use gesecole\matiere;
use gesecole\NNiveau;
use gesecole\niveau;
use gesecole\note;
use gesecole\PvGeneration;
use gesecole\Session_exam;
use gesecole\TutelleAcademique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class anonymatController extends Controller
{
    public function __construct()
    {
        session(array('big_title' => 'Gestion des anonymats'));
        $this->renewSchoolYear();
    }

    public function index(Request $request)
    {
        $data = $this->formData($request);
        $data['generations'] = array();
        $data['migrationRequired'] = !Schema::hasTable('anonymats_examens');
        if (!$data['migrationRequired']) {
            $data['generations'] = DB::table('anonymats_examens as a')
                ->leftJoin('classe as c', 'c.id', '=', 'a.id_classe')
                ->leftJoin('campus as cp', 'cp.id', '=', 'a.id_campus')
                ->where('a.annee_scolaire', session('year'))
                ->select('a.id_classe', 'a.id_cycle', 'a.id_nniveau', 'a.id_campus', 'a.semestre',
                    'a.type_examen', 'a.mode_generation', 'a.id_tutelle',
                    'c.id_niveau', 'c.nom as classe_nom', 'cp.nom as campus_nom', DB::raw('COUNT(*) as total'))
                ->groupBy('a.id_classe', 'a.id_cycle', 'a.id_nniveau', 'a.id_campus', 'a.semestre',
                    'a.type_examen', 'a.mode_generation', 'a.id_tutelle', 'c.id_niveau', 'c.nom', 'cp.nom')
                ->orderBy('c.nom')->get();
        }
        return view('eleves.anonymats.index', $data);
    }

    public function generer(Request $request)
    {
        $this->validateSelection($request);
        if (($request->input('type_examen') === 'fin_semestre_1' || $request->input('type_examen') === 'rattrapage_1') && $request->input('semestre') !== 'S1') {
            return redirect()->back()->withInput()->withErrors(array('Cet examen doit etre genere pour le semestre S1.'));
        }
        if (($request->input('type_examen') === 'fin_semestre_2' || $request->input('type_examen') === 'rattrapage_2') && $request->input('semestre') !== 'S2') {
            return redirect()->back()->withInput()->withErrors(array('Cet examen doit etre genere pour le semestre S2.'));
        }
        if (!Schema::hasTable('anonymats_examens')) {
            return redirect()->back()->withErrors(array('Executez d abord la migration des anonymats.'));
        }

        $selection = $this->selection($request);
        $selectedLevel = NNiveau::where('id', $selection['id_nniveau'])
            ->where('id_cycle', $selection['id_cycle'])->first();
        if (!$selectedLevel) {
            return redirect()->back()->withInput()->withErrors(array('Le niveau ne correspond pas au cycle selectionne.'));
        }
        $selectedClass = classe::where('id', $selection['id_classe'])->where('id_niveau', $selection['id_niveau'])->first();
        if (!$selectedClass) {
            return redirect()->back()->withInput()->withErrors(array('La specialite ne correspond pas a la filiere selectionnee.'));
        }
        $students = DB::table('inscription as i')->join('eleve as e', 'e.id', '=', 'i.id_eleve')
            ->where('i.annee_scolaire', session('year'))->where('i.id_classe', $selection['id_classe'])
            ->where('i.id_cycle', $selection['id_cycle'])->where('i.id_nniveau', $selection['id_nniveau'])
            ->where('i.desinscrire', 0)->where(function ($query) use ($selection) {
                $query->where('i.id_campus', $selection['id_campus'])->orWhereNull('i.id_campus');
            })->select('e.id')->distinct()->orderBy('e.id')->get();
        if (count($students) === 0) {
            return redirect()->back()->withInput()->withErrors(array('Aucun etudiant inscrit pour cette selection.'));
        }

        $subjects = array((object) array('id_matiere' => 0, 'id_classe_matiere' => null));
        if ($selection['mode_generation'] === 'matiere') {
            $programmeSemester = $this->programmeSemester(
                $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
            );
            $programmeSemesters = $this->programmeSemesterCandidates(
                $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
            );
            $selectedSubjectIds = $this->selectedSubjectIds($request);
            if ($request->input('selection_matieres_active') && count($selectedSubjectIds) === 0) {
                return redirect()->back()->withInput()->withErrors(array(
                    'Cochez au moins une matiere pour generer les anonymats par matiere.'
                ));
            }
            $subjects = DB::table('classe_matiere')->where('id_classe', $selection['id_classe'])
                ->where('id_cycle', $selection['id_cycle'])->where('id_nniveau', $selection['id_nniveau'])
                ->whereIn('semestre', $programmeSemesters);
            if (count($selectedSubjectIds)) {
                $subjects->whereIn('id_matiere', $selectedSubjectIds);
            }
            $subjects = $subjects->select('id as id_classe_matiere', 'id_matiere')->orderBy('id_matiere')->get();
            if (count($subjects) === 0) {
                $message = count($selectedSubjectIds)
                    ? 'Aucune des matieres cochees ne correspond au semestre pedagogique S'.$programmeSemester.'.'
                    : 'Aucune matiere configuree pour le semestre pedagogique S'.$programmeSemester.'.';
                return redirect()->back()->withInput()->withErrors(array($message));
            }
        }

        $created = 0;
        $marker = $this->marker($selection['type_examen'], $selection['semestre']);
        DB::transaction(function () use ($students, $subjects, $selection, $marker, &$created) {
            if (DB::connection()->getDriverName() === 'pgsql') {
                $namespaceLock = crc32('anonymats_examens') & 0x7fffffff;
                $seriesLock = crc32(session('year').'|'.$marker) & 0x7fffffff;
                DB::select('select pg_advisory_xact_lock(?, ?)', array($namespaceLock, $seriesLock));
            }

            $lastAnonymat = AnonymatExamen::where('annee_scolaire', session('year'))
                ->where('code', 'like', 'AN'.$marker.'%')
                ->orderBy('rang', 'desc')->lockForUpdate()->first();
            $rank = $lastAnonymat ? (int) $lastAnonymat->rang : 0;
            foreach ($subjects as $subject) {
                foreach ($students as $student) {
                    $scope = sha1(implode('|', array(session('year'), $selection['id_campus'],
                        $selection['id_tutelle'], $selection['id_classe'], $selection['id_cycle'],
                        $selection['id_nniveau'], $selection['semestre'], $selection['type_examen'],
                        $selection['mode_generation'], $subject->id_matiere, $student->id)));
                    if (AnonymatExamen::where('scope_key', $scope)->exists()) continue;
                    $rank++;
                    AnonymatExamen::create(array(
                        'scope_key' => $scope, 'code' => 'AN'.$marker.str_pad($rank, 5, '0', STR_PAD_LEFT),
                        'rang' => $rank, 'id_eleve' => $student->id, 'id_classe' => $selection['id_classe'],
                        'id_cycle' => $selection['id_cycle'], 'id_nniveau' => $selection['id_nniveau'],
                        'id_matiere' => $subject->id_matiere, 'id_classe_matiere' => $subject->id_classe_matiere,
                        'id_campus' => $selection['id_campus'], 'id_tutelle' => $selection['id_tutelle'],
                        'annee_scolaire' => session('year'), 'semestre' => $selection['semestre'],
                        'type_examen' => $selection['type_examen'], 'mode_generation' => $selection['mode_generation'],
                        'id_creator' => Auth::check() ? Auth::id() : null,
                        'id_last_editor' => Auth::check() ? Auth::id() : null
                    ));
                    $created++;
                }
            }
        });

        return redirect('/eleves/notes/anonymats/show?'.http_build_query($selection))
            ->with('success', $created.' anonymat(s) genere(s).');
    }

    public function show(Request $request)
    {
        return view('eleves.anonymats.show', $this->reportData($request));
    }

    public function saisie(Request $request)
    {
        if ($request->input('type_examen') === 'session_normale') {
            $request->merge(array(
                'type_examen' => $request->input('semestre') === 'S2' ? 'fin_semestre_2' : 'fin_semestre_1'
            ));
        }
        $data = $this->formData($request);
        $data['examLabels'] = array(
            'fin_semestre_1' => 'Examen semestre 1',
            'fin_semestre_2' => 'Examen semestre 2',
            'rattrapage_1' => 'Rattrapage 1',
            'rattrapage_2' => 'Rattrapage 2'
        );
        $data['rows'] = array();
        $data['selectedMatiere'] = null;
        $data['selectionError'] = null;
        if ($request->has('id_classe') && $request->has('id_matiere') && Schema::hasTable('anonymats_examens')) {
            $selection = $this->selection($request);
            $idMatiere = (int) $request->input('id_matiere');
            $programmeSemester = $this->programmeSemester(
                $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
            );
            $programmeSemesters = $this->programmeSemesterCandidates(
                $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
            );
            $classeMatiere = classeMatiere::where('id_classe', $selection['id_classe'])
                ->where('id_cycle', $selection['id_cycle'])->where('id_nniveau', $selection['id_nniveau'])
                ->where('id_matiere', $idMatiere)
                ->whereIn('semestre', $programmeSemesters)->first();
            if (!$classeMatiere) {
                $data['selectionError'] = 'Cette matiere ne fait pas partie du semestre pedagogique S'.$programmeSemester.' pour la specialite selectionnee.';
            } else {
                $data['selectedMatiere'] = matiere::find($idMatiere);
                $records = $this->anonymatQuery($selection)
                    ->where('a.id_matiere', $selection['mode_generation'] === 'matiere' ? $idMatiere : 0)
                    ->orderBy('a.code')->get();
                $sequence = $this->sequence($selection['type_examen'], $selection['semestre']);
                foreach ($records as $record) {
                    $sessionExam = $this->sessionExamForSelection($selection, $classeMatiere, $idMatiere);
                    $existing = $this->findNote($record->id_eleve, $idMatiere, $sequence, $selection, $classeMatiere, $sessionExam);
                    $classeMatiereId = $record->id_classe_matiere ?: $classeMatiere->id;
                    $record->note_saisie = $this->displayNote($existing, $sequence, $classeMatiereId, $idMatiere);
                    $data['rows'][] = $record;
                }
            }
        }
        return view('eleves.anonymats.saisie', $data);
    }

    public function ccTpe(Request $request)
    {
        $data = $this->formData($request);
        $data['rows'] = array();
        $data['selectedMatiere'] = null;
        $data['selectionError'] = null;
        $data['noteType'] = in_array($request->input('note_type'), array('cc', 'tpe'))
            ? $request->input('note_type') : 'cc';
        $data['noteTypeLabels'] = array('cc' => 'Controle continu', 'tpe' => 'TPE');
        $data['selectedSequence'] = null;

        if ($request->has('id_classe') && $request->has('id_matiere')) {
            $selection = $this->selection($request);
            $idMatiere = (int) $request->input('id_matiere');
            $classeMatiere = $this->classeMatiereForSelection($selection, $idMatiere);
            if (!$classeMatiere) {
                $data['selectionError'] = 'Cette matiere ne fait pas partie du semestre pedagogique de la specialite selectionnee.';
                return view('eleves.anonymats.cc_tpe', $data);
            }

            $sequence = $this->ccTpeSequence($data['noteType'], $selection['semestre']);
            $sessionExam = $this->sessionExamForSelection($selection, $classeMatiere, $idMatiere);
            $percentage = $this->sessionPercentage($sessionExam, $sequence);
            if (!$sessionExam || $percentage <= 0) {
                $data['selectionError'] = 'Cette evaluation '.$this->ccTpeShortLabel($data['noteType'], $selection['semestre']).' n existe pas dans session_examen pour cette matiere.';
                return view('eleves.anonymats.cc_tpe', $data);
            }

            $data['selectedMatiere'] = matiere::find($idMatiere);
            $data['selectedSequence'] = $sequence;
            $students = $this->studentsForSelection($selection);
            foreach ($students as $student) {
                $existing = $this->findNote($student->id, $idMatiere, $sequence, $selection, $classeMatiere, $sessionExam);
                $student->note_saisie = $this->displayNote($existing, $sequence, $classeMatiere->id, $idMatiere);
                $data['rows'][] = $student;
            }
        }

        return view('eleves.anonymats.cc_tpe', $data);
    }

    public function storeNotes(Request $request)
    {
        $this->validateSelection($request);
        $this->validate($request, array(
            'id_matiere' => 'required|integer', 'notes' => 'required|array',
            'type_examen' => 'required|in:fin_semestre_1,fin_semestre_2,rattrapage_1,rattrapage_2'
        ));
        $selection = $this->selection($request);
        if (!NNiveau::where('id', $selection['id_nniveau'])->where('id_cycle', $selection['id_cycle'])->exists()) {
            return redirect()->back()->withErrors(array('Le niveau ne correspond pas au cycle selectionne.'));
        }
        if (!classe::where('id', $selection['id_classe'])->where('id_niveau', $selection['id_niveau'])->exists()) {
            return redirect()->back()->withErrors(array('La specialite ne correspond pas a la filiere selectionnee.'));
        }
        $idMatiere = (int) $request->input('id_matiere');
        $sequence = $this->sequence($selection['type_examen'], $selection['semestre']);
        $programmeSemester = $this->programmeSemester(
            $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
        );
        $programmeSemesters = $this->programmeSemesterCandidates(
            $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
        );
        $classeMatiere = classeMatiere::where('id_classe', $selection['id_classe'])
            ->where('id_cycle', $selection['id_cycle'])->where('id_nniveau', $selection['id_nniveau'])
            ->where('id_matiere', $idMatiere)
            ->whereIn('semestre', $programmeSemesters)->first();
        if (!$classeMatiere) {
            return redirect()->back()->withErrors(array('La matiere ne correspond pas au semestre et a la specialite selectionnes.'));
        }
        $sessionExam = $this->sessionExamForSelection($selection, $classeMatiere, $idMatiere);
        $this->assertWritableSession($selection, $classeMatiere, $sessionExam, $sequence);
        $this->validateMarkInputs($request->input('notes'));
        $studentIds = array();
        foreach ($this->studentsForSelection($selection) as $student) $studentIds[(int) $student->id] = true;
        $saved = 0;
        DB::transaction(function () use ($request, $selection, $idMatiere, $sequence, $classeMatiere, $sessionExam, $studentIds, &$saved) {
            $this->synchronizeNoteIdSequence();
            foreach ((array) $request->input('notes') as $anonymatId => $rawValue) {
                $rawValue = str_replace(',', '.', $rawValue);
                if ($rawValue === '' || !is_numeric($rawValue) || $rawValue < 0 || $rawValue > 20) continue;
                $anonymat = AnonymatExamen::where('id', (int) $anonymatId)
                    ->where('id_classe', $selection['id_classe'])->where('id_cycle', $selection['id_cycle'])
                    ->where('id_nniveau', $selection['id_nniveau'])->where('id_campus', $selection['id_campus'])
                    ->where('id_tutelle', $selection['id_tutelle'])->where('semestre', $selection['semestre'])
                    ->where('type_examen', $selection['type_examen'])->where('mode_generation', $selection['mode_generation'])
                    ->where('annee_scolaire', session('year'))->first();
                if (!$anonymat || ($anonymat->mode_generation === 'matiere' && (int) $anonymat->id_matiere !== $idMatiere)) continue;
                if (!isset($studentIds[(int) $anonymat->id_eleve])) continue;
                $n = $this->findNote($anonymat->id_eleve, $idMatiere, $sequence, $selection, $classeMatiere, $sessionExam);
                if (!$n) { $n = new note(); $n->id_creator = Auth::check() ? Auth::id() : null; }
                $n->id_eleve = $anonymat->id_eleve;
                $n->id_matiere = $idMatiere;
                $n->sequence = $sequence;
                $n->note = $this->storedNote((float) $rawValue, $sequence, $sessionExam);
                $n->annee_scolaire = session('year');
                $n->id_classe = $selection['id_classe'];
                $n->id_campus = $selection['id_campus'];
                $n->id_anonymat = $anonymat->id;
                $n->id_classe_matiere = $classeMatiere ? $classeMatiere->id : null;
                $n->id_session = $sessionExam ? $sessionExam->id : null;
                $n->id_last_editor = Auth::check() ? Auth::id() : null;
                $n->edition_details = 'saisie par anonymat '.$anonymat->code.' : '.$rawValue.'/20';
                $n->save();
                $saved++;
            }
        });
        return redirect()->back()->with('success', $saved.' note(s) enregistree(s) par anonymat.');
    }

    public function storeCcTpe(Request $request)
    {
        $this->validateSelection($request);
        $this->validate($request, array(
            'id_matiere' => 'required|integer',
            'note_type' => 'required|in:cc,tpe',
            'notes' => 'required|array'
        ));

        $selection = $this->selection($request);
        if (!NNiveau::where('id', $selection['id_nniveau'])->where('id_cycle', $selection['id_cycle'])->exists()) {
            return redirect()->back()->withErrors(array('Le niveau ne correspond pas au cycle selectionne.'));
        }
        if (!classe::where('id', $selection['id_classe'])->where('id_niveau', $selection['id_niveau'])->exists()) {
            return redirect()->back()->withErrors(array('La specialite ne correspond pas a la filiere selectionnee.'));
        }

        $idMatiere = (int) $request->input('id_matiere');
        $classeMatiere = $this->classeMatiereForSelection($selection, $idMatiere);
        if (!$classeMatiere) {
            return redirect()->back()->withErrors(array('La matiere ne correspond pas au semestre et a la specialite selectionnes.'));
        }

        $sequence = $this->ccTpeSequence($request->input('note_type'), $selection['semestre']);
        $sessionExam = $this->sessionExamForSelection($selection, $classeMatiere, $idMatiere);
        if (!$sessionExam || $this->sessionPercentage($sessionExam, $sequence) <= 0) {
            return redirect()->back()->withErrors(array('Cette evaluation n existe pas dans session_examen pour cette matiere.'));
        }

        $this->assertWritableSession($selection, $classeMatiere, $sessionExam, $sequence);
        $this->validateMarkInputs($request->input('notes'));

        $students = $this->studentsForSelection($selection);
        $studentIds = array();
        foreach ($students as $student) $studentIds[(int) $student->id] = true;

        $saved = 0;
        DB::transaction(function () use ($request, $selection, $idMatiere, $sequence, $classeMatiere, $sessionExam, $studentIds, &$saved) {
            $this->synchronizeNoteIdSequence();
            foreach ((array) $request->input('notes') as $studentId => $rawValue) {
                $studentId = (int) $studentId;
                if (!isset($studentIds[$studentId])) continue;
                $rawValue = str_replace(',', '.', $rawValue);
                if ($rawValue === '' || !is_numeric($rawValue) || $rawValue < 0 || $rawValue > 20) continue;

                $n = $this->findNote($studentId, $idMatiere, $sequence, $selection, $classeMatiere, $sessionExam);
                if (!$n) { $n = new note(); $n->id_creator = Auth::check() ? Auth::id() : null; }
                $n->id_eleve = $studentId;
                $n->id_matiere = $idMatiere;
                $n->sequence = $sequence;
                $n->note = $this->storedNote((float) $rawValue, $sequence, $sessionExam);
                $n->annee_scolaire = session('year');
                $n->id_classe = $selection['id_classe'];
                $n->id_campus = $selection['id_campus'];
                $n->id_anonymat = null;
                $n->id_classe_matiere = $classeMatiere->id;
                $n->id_session = $sessionExam->id;
                $n->id_last_editor = Auth::check() ? Auth::id() : null;
                $n->edition_details = 'saisie nominative CC/TPE : '.$rawValue.'/20';
                $n->save();
                $saved++;
            }
        });

        return redirect()->back()->with('success', $saved.' note(s) CC/TPE enregistree(s).');
    }

    public function matieresOptions(Request $request)
    {
        $idClasse = (int) $request->input('id_classe');
        $idCycle = (int) $request->input('id_cycle');
        $idNniveau = (int) $request->input('id_nniveau');
        $semestre = $request->input('semestre');

        if (!$idClasse || !$idCycle || !$idNniveau || !in_array($semestre, array('S1', 'S2'))) {
            return response()->json(array());
        }

        $matieres = DB::table('classe_matiere as cm')->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->where('cm.id_classe', $idClasse)->where('cm.id_cycle', $idCycle)
            ->where('cm.id_nniveau', $idNniveau)
            ->whereIn('cm.semestre', $this->programmeSemesterCandidates($idCycle, $idNniveau, $semestre))
            ->select('m.id', 'm.nom')->distinct()->orderBy('m.nom')->get();

        return response()->json($matieres);
    }

    public function printListe(Request $request)
    {
        $data = $this->reportData($request);
        $data['sheets'] = $this->subjectSheets(
            $data['selection'], (int) $request->input('id_matiere')
        );
        return view('eleves.anonymats.print_liste', $data);
    }

    public function printFicheMatiere(Request $request)
    {
        $data = $this->reportData($request);
        $sheets = $this->subjectSheets($data['selection'], (int) $request->input('id_matiere'));
        $data['selectedMatiere'] = count($sheets) ? $sheets[0]->matiere : null;
        $data['rows'] = count($sheets) ? $sheets[0]->rows : array();
        return view('eleves.anonymats.print_fiche_matiere', $data);
    }

    public function printFichePresence(Request $request)
    {
        $data = $this->reportData($request);
        $data['sheets'] = $this->subjectSheets(
            $data['selection'], (int) $request->input('id_matiere')
        );
        return view('eleves.anonymats.print_presence', $data);
    }

    public function printFicheEtudiant(Request $request)
    {
        $anonymat = AnonymatExamen::findOrFail((int) $request->input('id_anonymat'));
        $selectedClass = classe::find($anonymat->id_classe);
        $selection = array(
            'id_classe' => (int) $anonymat->id_classe,
            'id_cycle' => (int) $anonymat->id_cycle,
            'id_nniveau' => (int) $anonymat->id_nniveau,
            'id_niveau' => $selectedClass ? (int) $selectedClass->id_niveau : 0,
            'id_campus' => (int) $anonymat->id_campus,
            'id_tutelle' => (int) $anonymat->id_tutelle,
            'semestre' => $anonymat->semestre,
            'type_examen' => $anonymat->type_examen,
            'mode_generation' => $anonymat->mode_generation
        );
        return view('eleves.anonymats.print_fiche_etudiant', array(
            'anonymat' => $anonymat, 'selection' => $selection,
            'sheets' => $this->subjectSheets(
                $selection, $anonymat->mode_generation === 'matiere' ? (int) $anonymat->id_matiere : 0
            ),
            'classe' => $selectedClass, 'campus' => Campus::find($anonymat->id_campus),
            'cycle' => cycle::find($anonymat->id_cycle),
            'nniveau' => NNiveau::find($anonymat->id_nniveau),
            'filiere' => $selectedClass ? niveau::find($selectedClass->id_niveau) : null,
            'examLabel' => $this->examLabel($anonymat->type_examen)
        ));
    }

    public function downloadFichesPdf(Request $request)
    {
        $data = $this->reportData($request);
        $data['sheets'] = $this->subjectSheets($data['selection']);
        $filename = 'fiches-report-notes-'.$this->filenamePart($data['classe'] ? $data['classe']->nom : 'classe').'.pdf';
        return $this->downloadPdf('eleves.anonymats.print_fiche_etudiant', $data, $filename);
    }

    public function downloadListesPdf(Request $request)
    {
        $data = $this->reportData($request);
        $data['sheets'] = $this->subjectSheets($data['selection']);
        $filename = 'listes-anonymats-'.$this->filenamePart($data['classe'] ? $data['classe']->nom : 'classe').'.pdf';
        return $this->downloadPdf('eleves.anonymats.print_liste', $data, $filename);
    }

    public function downloadPresencesPdf(Request $request)
    {
        $data = $this->reportData($request);
        $data['sheets'] = $this->subjectSheets($data['selection']);
        $filename = 'fiches-presence-examen-'.$this->filenamePart($data['classe'] ? $data['classe']->nom : 'classe').'.pdf';
        return $this->downloadPdf('eleves.anonymats.print_presence', $data, $filename);
    }

    public function modules(Request $request)
    {
        $data = $this->formData($request);
        $data['modules'] = array();
        $data['resultsLoaded'] = false;
        $data['lockSemesterToExam'] = true;

        if ($request->input('id_classe')) {
            $data = array_merge($data, $this->moduleReportData($request));
            $data['resultsLoaded'] = true;
        }

        return view('eleves.anonymats.modules', $data);
    }

    public function downloadModulesPdf(Request $request)
    {
        $data = array_merge($this->formData($request), $this->moduleReportData($request));
        $data['sheets'] = $data['modules'];
        $modePart = isset($data['selection']['mode_note']) && $data['selection']['mode_note'] === 'rattrapage'
            ? '-rattrapage' : '';
        $filename = 'notes-par-modules'.$modePart.'-'.$this->filenamePart($data['classe'] ? $data['classe']->nom : 'classe').'.pdf';
        $html = $this->selfContainedHtml(view('eleves.anonymats.print_modules', $data)->render());
        if (count($data['sheets'])) {
            $this->saveModulePvGeneration($data, $filename, $html);
        }
        return $this->downloadPdf('eleves.anonymats.print_modules', $data, $filename, $html);
    }

    public function modulesHistorique(Request $request)
    {
        $data = $this->formData($request);
        $query = PvGeneration::where('type_pv', 'modules_anonymats');

        $annee = $request->input('annee_academique', session('year'));
        if ($annee !== '') $query->where('annee_academique', $annee);
        if ($request->input('id_classe')) $query->where('id_classe', (int) $request->input('id_classe'));
        if ($request->input('id_cycle')) $query->where('id_cycle', (int) $request->input('id_cycle'));
        if ($request->input('id_nniveau')) $query->where('id_nniveau', (int) $request->input('id_nniveau'));
        if ($request->input('semestre')) $query->where('semestre', $request->input('semestre'));

        if (Schema::hasColumn('pv_generations', 'id_campus') && $request->input('id_campus')) {
            $query->where('id_campus', (int) $request->input('id_campus'));
        }
        if (Schema::hasColumn('pv_generations', 'id_tutelle') && $request->input('id_tutelle')) {
            $query->where('id_tutelle', (int) $request->input('id_tutelle'));
        }

        $data['pvs'] = $query->orderBy('date_generation', 'desc')->orderBy('id', 'desc')->get();
        $data['anneeHistorique'] = $annee;
        $data['annees'] = PvGeneration::where('type_pv', 'modules_anonymats')
            ->select('annee_academique')->distinct()->orderBy('annee_academique', 'desc')->get();

        return view('eleves.anonymats.modules_historique', $data);
    }

    public function visualiserModulePv($id)
    {
        $pv = PvGeneration::where('type_pv', 'modules_anonymats')->where('id', (int) $id)->first();
        if (!$pv) abort(404, 'PV par module introuvable.');
        if (!Schema::hasColumn('pv_generations', 'contenu_html') || trim((string) $pv->contenu_html) === '') {
            return redirect()->back()->withErrors(array('Ce PV ne contient pas encore de copie historique visualisable.'));
        }

        return response($pv->contenu_html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function moduleReportData(Request $request)
    {
        if (!Schema::hasTable('anonymats_examens')) abort(503, 'Executez la migration des anonymats.');

        $selection = $this->selection($request);
        $selectedClass = classe::find($selection['id_classe']);
        $selectedLevel = NNiveau::where('id', $selection['id_nniveau'])
            ->where('id_cycle', $selection['id_cycle'])->first();
        if (!$selectedClass || (int) $selectedClass->id_niveau !== (int) $selection['id_niveau'] || !$selectedLevel) {
            abort(422, 'La classe, la filiere, le cycle et le niveau selectionnes ne correspondent pas.');
        }

        $programmeSemester = $this->programmeSemester(
            $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
        );
        $programmeSemesters = $this->programmeSemesterCandidates(
            $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
        );
        $students = DB::table('inscription as i')->join('eleve as e', 'e.id', '=', 'i.id_eleve')
            ->where('i.annee_scolaire', session('year'))->where('i.id_classe', $selection['id_classe'])
            ->where('i.id_cycle', $selection['id_cycle'])->where('i.id_nniveau', $selection['id_nniveau'])
            ->where('i.desinscrire', 0)->where(function ($query) use ($selection) {
                $query->where('i.id_campus', $selection['id_campus'])->orWhereNull('i.id_campus');
            })->select('e.id', 'e.nom', 'e.prenom', 'e.matricule')->distinct()
            ->orderBy('e.nom')->orderBy('e.prenom')->get();

        $uvRows = DB::table('groupe_matieres as gm')
            ->join('classe_matiere as cm', 'cm.id', '=', 'gm.id_classe_matiere')
            ->join('matiere as uv_m', 'uv_m.id', '=', 'cm.id_matiere')
            ->join('matiere as module_m', 'module_m.id', '=', 'gm.id_matiere_mere')
            ->leftJoin('codages as uv_code', DB::raw('uv_code.id::text'), '=', 'cm.id_code')
            ->where('gm.id_classe', $selection['id_classe'])
            ->where('gm.id_nniveau', $selection['id_nniveau'])
            ->where('cm.id_cycle', $selection['id_cycle'])
            ->whereIn('cm.semestre', $programmeSemesters)
            ->select('gm.id_matiere_mere as id_module', 'module_m.nom as module_nom',
                'cm.id as id_classe_matiere', 'cm.id_matiere', 'cm.coef', 'cm.id_code',
                'uv_m.nom as matiere_nom',
                DB::raw("COALESCE(uv_code.nom, cm.id_code, '') as code_matiere"))
            ->orderBy('module_m.nom')->orderBy('uv_m.nom')->get();

        $modules = array();
        $classeMatiereIds = array();
        $matiereIds = array();
        $moduleIds = array();
        foreach ($uvRows as $uv) {
            $moduleIds[] = (int) $uv->id_module;
            $classeMatiereIds[] = (int) $uv->id_classe_matiere;
            $matiereIds[] = (int) $uv->id_matiere;
            if (!isset($modules[$uv->id_module])) {
                $modules[$uv->id_module] = (object) array(
                    'id' => (int) $uv->id_module,
                    'code' => '',
                    'nom' => $uv->module_nom,
                    'uvs' => array(),
                    'rows' => array()
                );
            }
            $uv->code_matiere = trim($uv->code_matiere) !== ''
                ? trim($uv->code_matiere) : 'MAT-'.(int) $uv->id_matiere;
            $modules[$uv->id_module]->uvs[] = $uv;
        }

        $moduleIds = array_values(array_unique($moduleIds));
        if (count($moduleIds)) {
            $moduleCodes = DB::table('classe_matiere as cm')
                ->leftJoin('codages as c', DB::raw('c.id::text'), '=', 'cm.id_code')
                ->where('cm.id_classe', $selection['id_classe'])
                ->where('cm.id_cycle', $selection['id_cycle'])
                ->where('cm.id_nniveau', $selection['id_nniveau'])
                ->whereIn('cm.id_matiere', $moduleIds)
                ->select('cm.id_matiere', DB::raw("COALESCE(c.nom, cm.id_code, '') as code_module"))->get();
            foreach ($moduleCodes as $moduleCode) {
                if (isset($modules[$moduleCode->id_matiere])) {
                    $modules[$moduleCode->id_matiere]->code = trim($moduleCode->code_module);
                }
            }
        }

        $sessions = array();
        if (count($classeMatiereIds)) {
            $sessionQuery = Session_exam::whereIn('id_classe_matiere', array_values(array_unique($classeMatiereIds)))
                ->where('annee_academique', session('year'));
            $hasSessionTutelle = $this->sessionExamHasTutelleColumn();
            if ($selection['id_tutelle'] && $hasSessionTutelle) {
                $sessionQuery->where(function ($query) use ($selection) {
                    $query->where('id_tutelle', $selection['id_tutelle'])->orWhereNull('id_tutelle');
                });
            }
            if ($hasSessionTutelle) {
                $sessionQuery->orderBy('id_tutelle', 'desc');
            }
            foreach ($sessionQuery->get() as $sessionExam) {
                if (!isset($sessions[$sessionExam->id_classe_matiere])) {
                    $sessions[$sessionExam->id_classe_matiere] = $sessionExam;
                }
            }
        }

        $studentIds = array();
        foreach ($students as $student) $studentIds[] = (int) $student->id;
        $ccSequence = $selection['semestre'] === 'S2' ? 2 : 1;
        $examSequence = $selection['mode_note'] === 'rattrapage'
            ? ($selection['semestre'] === 'S2' ? 6 : 5)
            : ($selection['semestre'] === 'S2' ? 4 : 3);
        $tpeSequence = $selection['semestre'] === 'S2' ? 8 : 7;
        foreach ($modules as $module) {
            foreach ($module->uvs as $uv) {
                $sessionExam = isset($sessions[$uv->id_classe_matiere])
                    ? $sessions[$uv->id_classe_matiere] : null;
                $uv->cc_sequence = $ccSequence;
                $uv->tpe_sequence = $tpeSequence;
                $uv->cc_percentage = $this->sessionPercentage($sessionExam, $ccSequence);
                $uv->tpe_percentage = $this->sessionPercentage($sessionExam, $tpeSequence);
                $uv->has_cc_session = $uv->cc_percentage > 0;
                $uv->has_tpe_session = $uv->tpe_percentage > 0;
            }
        }
        $notes = array();
        $legacyAllowed = array();
        foreach ($modules as $module) foreach ($module->uvs as $uv) {
            $legacyAllowed[$uv->id_classe_matiere] =
                (int) Session_exam::where('id_classe_matiere', $uv->id_classe_matiere)->where('annee_academique', session('year'))->count() === 1
                && (int) classeMatiere::where('id_classe', $selection['id_classe'])->where('id_cycle', $selection['id_cycle'])
                    ->where('id_nniveau', $selection['id_nniveau'])->where('id_matiere', $uv->id_matiere)->count() === 1;
        }
        $noteAnonymatIds = array();
        $anonymatCodesById = array();
        if (count($studentIds) && count($matiereIds)) {
            $noteRows = note::where('annee_scolaire', session('year'))
                ->where(function ($q) use ($selection) { $q->whereNull('id_classe')->orWhere('id_classe', $selection['id_classe']); })
                ->where(function ($q) use ($selection) { $q->whereNull('id_campus')->orWhere('id_campus', $selection['id_campus']); })
                ->whereIn('id_eleve', $studentIds)
                ->whereIn('id_matiere', array_values(array_unique($matiereIds)))
                ->whereIn('sequence', array($ccSequence, $examSequence, $tpeSequence))
                ->orderBy('updated_at')->orderBy('id')->get();
            foreach ($noteRows as $noteRow) {
                $notes[$noteRow->id_eleve][$noteRow->id_matiere][$noteRow->sequence][] = $noteRow;
                if ($noteRow->id_anonymat) $noteAnonymatIds[] = (int) $noteRow->id_anonymat;
            }
            if (count($noteAnonymatIds)) {
                foreach (AnonymatExamen::whereIn('id', array_values(array_unique($noteAnonymatIds)))
                    ->select('id', 'code')->get() as $linkedAnonymat) {
                    $anonymatCodesById[$linkedAnonymat->id] = $linkedAnonymat->code;
                }
            }
        }

        $anonymats = array();
        if (count($studentIds)) {
            $anonymatRows = $this->anonymatQuery($selection)->get();
            foreach ($anonymatRows as $anonymat) {
                if ($selection['mode_generation'] === 'matiere') {
                    $anonymats[$anonymat->id_eleve][$anonymat->id_matiere] = $anonymat->code;
                } else {
                    $anonymats[$anonymat->id_eleve][0] = $anonymat->code;
                }
            }
        }

        foreach ($modules as $module) {
            foreach ($students as $student) {
                $row = (object) array(
                    'student' => $student,
                    'uvs' => array(),
                    'moyenne' => null,
                    'mgp' => null,
                    'decision' => 'NV'
                );
                $weightedTotal = 0;
                $totalCredits = 0;
                foreach ($module->uvs as $uv) {
                    $sessionExam = isset($sessions[$uv->id_classe_matiere])
                        ? $sessions[$uv->id_classe_matiere] : null;
                    $ccPercentage = $this->sessionPercentage($sessionExam, $ccSequence);
                    $examPercentage = $this->sessionPercentage($sessionExam, $examSequence);
                    $tpePercentage = $this->sessionPercentage($sessionExam, $tpeSequence);
                    $studentNotes = isset($notes[$student->id][$uv->id_matiere])
                        ? $notes[$student->id][$uv->id_matiere] : array();
                    $allowLegacy = $legacyAllowed[$uv->id_classe_matiere];
                    $ccNote = $this->moduleNote(isset($studentNotes[$ccSequence]) ? $studentNotes[$ccSequence] : array(), $sessionExam, $uv->id_classe_matiere, $allowLegacy);
                    $examNote = $this->moduleNote(isset($studentNotes[$examSequence]) ? $studentNotes[$examSequence] : array(), $sessionExam, $uv->id_classe_matiere, $allowLegacy);
                    $tpeNote = $this->moduleNote(isset($studentNotes[$tpeSequence]) ? $studentNotes[$tpeSequence] : array(), $sessionExam, $uv->id_classe_matiere, $allowLegacy);
                    $cc = $this->moduleRawNote($ccNote, $ccPercentage);
                    $exam = $this->moduleRawNote($examNote, $examPercentage);
                    $tpe = $this->moduleRawNote($tpeNote, $tpePercentage);
                    $total = $this->moduleTotal($cc, $exam, $tpe, $ccPercentage, $examPercentage, $tpePercentage);
                    $anonymatKey = $selection['mode_generation'] === 'matiere' ? $uv->id_matiere : 0;
                    $anonymatCode = isset($anonymats[$student->id][$anonymatKey])
                        ? $anonymats[$student->id][$anonymatKey] : '';
                    $linkedNote = $examNote ?: ($ccNote ?: $tpeNote);
                    if ($linkedNote && $linkedNote->id_anonymat
                        && isset($anonymatCodesById[$linkedNote->id_anonymat])) {
                        $anonymatCode = $anonymatCodesById[$linkedNote->id_anonymat];
                    }
                    $row->uvs[$uv->id_classe_matiere] = (object) array(
                        'anonymat' => $anonymatCode,
                        'cc' => $cc,
                        'exam' => $exam,
                        'tpe' => $tpe,
                        'total' => $total
                    );
                    if ($total !== null) {
                        $credit = (float) $uv->coef > 0 ? (float) $uv->coef : 1;
                        $weightedTotal += $total * $credit;
                        $totalCredits += $credit;
                    }
                }
                if ($totalCredits > 0) {
                    $row->moyenne = round($weightedTotal / $totalCredits, 2);
                    $row->mgp = round($row->moyenne / 5, 2);
                    $row->decision = $row->moyenne >= 10 ? 'V' : 'NV';
                }
                $module->rows[] = $row;
            }
        }

        return array(
            'selection' => $selection,
            'modules' => array_values($modules),
            'programmeSemester' => $programmeSemester,
            'examColumnLabel' => $selection['mode_note'] === 'rattrapage' ? 'RAT' : 'SN',
            'moduleNoteModeLabel' => $selection['mode_note'] === 'rattrapage' ? 'Rattrapage' : 'Session normale',
            'classe' => $selectedClass,
            'campus' => Campus::find($selection['id_campus']),
            'tutelle' => $this->moduleTutelle($selection),
            'cycle' => cycle::find($selection['id_cycle']),
            'nniveau' => $selectedLevel,
            'filiere' => niveau::find($selection['id_niveau']),
            'examLabel' => ($selection['mode_note'] === 'rattrapage' ? 'Rattrapage ' : 'Session normale ').$selection['semestre']
        );
    }

    private function saveModulePvGeneration($data, $filename, $html)
    {
        if (!Schema::hasTable('pv_generations')) return;

        $selection = isset($data['selection']) ? $data['selection'] : array();
        $modules = isset($data['modules']) ? $data['modules'] : array();
        $context = array(
            'selection' => $selection,
            'exam_label' => isset($data['examLabel']) ? $data['examLabel'] : '',
            'mode_note' => isset($selection['mode_note']) ? $selection['mode_note'] : '',
            'programme_semester' => isset($data['programmeSemester']) ? $data['programmeSemester'] : '',
            'classe' => isset($data['classe']->nom) ? $data['classe']->nom : '',
            'cycle' => isset($data['cycle']->nom) ? $data['cycle']->nom : '',
            'niveau' => isset($data['nniveau']->nom) ? $data['nniveau']->nom : '',
            'filiere' => isset($data['filiere']->nom) ? $data['filiere']->nom : '',
            'campus' => isset($data['campus']->nom) ? $data['campus']->nom : '',
            'tutelle' => isset($data['tutelle']->nom) ? $data['tutelle']->nom : ''
        );

        $record = array(
            'id_classe' => isset($selection['id_classe']) ? (int) $selection['id_classe'] : 0,
            'id_cycle' => isset($selection['id_cycle']) ? (int) $selection['id_cycle'] : 0,
            'id_nniveau' => isset($selection['id_nniveau']) ? (int) $selection['id_nniveau'] : 0,
            'annee_academique' => session('year'),
            'semestre' => isset($selection['semestre']) ? $selection['semestre'] : '',
            'type_pv' => 'modules_anonymats',
            'date_generation' => date('Y-m-d H:i:s'),
            'nombre_etudiants' => $this->moduleStudentCount($modules),
            'nom_fichier' => $filename,
            'id_user' => Auth::check() ? Auth::id() : null,
            'statut' => 'genere',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if (Schema::hasColumn('pv_generations', 'id_campus')) {
            $record['id_campus'] = isset($selection['id_campus']) ? (int) $selection['id_campus'] : null;
        }
        if (Schema::hasColumn('pv_generations', 'id_tutelle')) {
            $record['id_tutelle'] = isset($selection['id_tutelle']) ? (int) $selection['id_tutelle'] : null;
        }
        if (Schema::hasColumn('pv_generations', 'contenu_html')) {
            $record['contenu_html'] = $html;
        }
        if (Schema::hasColumn('pv_generations', 'contexte')) {
            $record['contexte'] = json_encode($context);
        }

        DB::table('pv_generations')->insert($record);
    }

    private function moduleStudentCount($modules)
    {
        if (!count($modules)) return 0;
        $studentIds = array();
        foreach ($modules as $module) {
            if (!isset($module->rows)) continue;
            foreach ($module->rows as $row) {
                if (isset($row->student->id)) $studentIds[] = (int) $row->student->id;
            }
        }
        return count(array_unique($studentIds));
    }

    private function sessionPercentage($sessionExam, $sequence)
    {
        if (!$sessionExam) return 0;
        $phases = \gesecole\Services\ExamSessionNote::phases($sessionExam, 0);
        return isset($phases[$sequence]) ? $phases[$sequence]->weight : 0;
    }

    private function moduleTutelle($selection)
    {
        if (!empty($selection['id_tutelle'])) {
            $selected = TutelleAcademique::find((int) $selection['id_tutelle']);
            if ($selected) return $selected;
        }

        $linked = TutelleAcademique::whereHas('specialites', function ($query) use ($selection) {
            $query->where('classe.id', $selection['id_classe']);
        })->first();

        return $linked ?: null;
    }

    private function moduleRawNote($noteRow, $percentage)
    {
        if (!$noteRow) return null;
        $value = (float) $noteRow->note;
        if ($percentage > 0) $value = ($value * 20) / $percentage;
        elseif ($value > 20 && $value <= 100) $value = $value / 5;
        return round(max(0, min(20, $value)), 2);
    }

    private function moduleNote($candidates, $sessionExam, $classeMatiereId, $allowLegacy)
    {
        if (!$sessionExam) return null;
        $specific = null; $legacy = null;
        foreach ($candidates as $note) {
            if ($note->id_classe_matiere && (int) $note->id_classe_matiere !== (int) $classeMatiereId) continue;
            if ((int) $note->id_session === (int) $sessionExam->id) $specific = $note;
            elseif (!$note->id_session && $allowLegacy) $legacy = $note;
        }
        return $specific ?: $legacy;
    }

    private function moduleTotal($cc, $exam, $tpe, $ccPercentage, $examPercentage, $tpePercentage)
    {
        if ($cc === null && $exam === null && $tpe === null) return null;
        $percentageTotal = $ccPercentage + $examPercentage + $tpePercentage;
        if ($percentageTotal > 0) {
            $total = ($cc !== null ? $cc * $ccPercentage / 100 : 0)
                + ($exam !== null ? $exam * $examPercentage / 100 : 0)
                + ($tpe !== null ? $tpe * $tpePercentage / 100 : 0);
            return round(max(0, min(20, $total)), 2);
        }
        $values = array_values(array_filter(array($cc, $exam, $tpe), function ($value) {
            return $value !== null;
        }));
        return count($values) ? round(array_sum($values) / count($values), 2) : null;
    }

    private function validateSelection(Request $request)
    {
        $this->validate($request, array(
            'id_classe' => 'required|integer', 'id_cycle' => 'required|integer',
            'id_nniveau' => 'required|integer', 'id_niveau' => 'required|integer', 'semestre' => 'required|in:S1,S2',
            'type_examen' => 'required|in:session_normale,fin_semestre_1,rattrapage_1,fin_semestre_2,rattrapage_2',
            'mode_generation' => 'required|in:examen,matiere'
        ));
        $this->validate($request, array('id_campus'=>'required|integer|exists:campus,id', 'id_tutelle'=>'integer|min:0'));
        if ($request->input('id_tutelle') && !TutelleAcademique::find($request->input('id_tutelle'))) abort(422, 'Tutelle invalide.');
    }

    private function selection(Request $request)
    {
        $typeExamen = $request->input('type_examen', 'fin_semestre_1');
        $semestre = $request->input('semestre', 'S1');
        if ($typeExamen === 'fin_semestre_1' || $typeExamen === 'rattrapage_1') $semestre = 'S1';
        if ($typeExamen === 'fin_semestre_2' || $typeExamen === 'rattrapage_2') $semestre = 'S2';
        $modeNote = $request->input('mode_note', '');
        if (!in_array($modeNote, array('normal', 'rattrapage'))) {
            $modeNote = in_array($typeExamen, array('rattrapage_1', 'rattrapage_2')) ? 'rattrapage' : 'normal';
        }

        return array(
            'id_classe' => (int) $request->input('id_classe'),
            'id_cycle' => (int) $request->input('id_cycle'),
            'id_nniveau' => (int) $request->input('id_nniveau'),
            'id_niveau' => (int) $request->input('id_niveau'),
            'id_campus' => (int) ($request->input('id_campus') ?: $this->currentCampusId()),
            'id_tutelle' => (int) $request->input('id_tutelle', 0),
            'semestre' => $semestre,
            'type_examen' => $typeExamen,
            'mode_generation' => $request->input('mode_generation', 'examen'),
            'mode_note' => $modeNote
        );
    }

    private function formData(Request $request)
    {
        $selection = $this->selection($request);
        $selectedSubjectIds = $this->selectedSubjectIds($request);
        $matieres = array();
        if ($selection['id_classe']) {
            $programmeSemester = $this->programmeSemester(
                $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
            );
            $programmeSemesters = $this->programmeSemesterCandidates(
                $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
            );
            $matieres = DB::table('classe_matiere as cm')->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
                ->where('cm.id_classe', $selection['id_classe'])->where('cm.id_cycle', $selection['id_cycle'])
                ->where('cm.id_nniveau', $selection['id_nniveau'])
                ->whereIn('cm.semestre', $programmeSemesters)
                ->select('m.id', 'm.nom')->distinct()->orderBy('m.nom')->get();
        }
        return array(
            'selection' => $selection, 'classes' => classe::orderBy('nom')->get(),
            'cycles' => cycle::orderBy('nom')->get(), 'nniveaux' => NNiveau::orderBy('nom')->get(),
            'filieres' => niveau::orderBy('nom')->get(),
            'campuses' => Campus::orderBy('nom')->get(), 'tutelles' => TutelleAcademique::orderBy('nom')->get(),
            'matieres' => $matieres, 'selectedSubjectIds' => $selectedSubjectIds,
            'examLabels' => $this->examLabels()
        );
    }

    private function selectedSubjectIds(Request $request)
    {
        $ids = $request->old('id_matieres', $request->input('id_matieres', array()));
        if (!is_array($ids)) $ids = array($ids);

        $result = array();
        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) $result[$id] = $id;
        }

        return array_values($result);
    }

    private function anonymatQuery($selection)
    {
        $query = DB::table('anonymats_examens as a')->join('eleve as e', 'e.id', '=', 'a.id_eleve')
            ->leftJoin('matiere as m', 'm.id', '=', 'a.id_matiere')
            ->where('a.annee_scolaire', session('year'))->where('a.id_classe', $selection['id_classe'])
            ->where('a.id_cycle', $selection['id_cycle'])->where('a.id_nniveau', $selection['id_nniveau'])
            ->where('a.id_campus', $selection['id_campus'])
            ->where('a.semestre', $selection['semestre'])->where('a.type_examen', $selection['type_examen'])
            ->where('a.mode_generation', $selection['mode_generation'])
            ->select('a.*', 'e.nom', 'e.prenom', 'e.matricule', 'm.nom as matiere_nom');
        if ($selection['id_tutelle']) $query->where('a.id_tutelle', $selection['id_tutelle']);
        return $query;
    }

    private function reportData(Request $request)
    {
        if (!Schema::hasTable('anonymats_examens')) abort(503, 'Executez la migration des anonymats.');
        $selection = $this->selection($request);
        return array_merge($this->formData($request), array(
            'rows' => $this->anonymatQuery($selection)->orderBy('m.nom')->orderBy('a.code')->get(),
            'subjects' => $this->subjectsForSelection($selection, true),
            'classe' => classe::find($selection['id_classe']), 'campus' => Campus::find($selection['id_campus']),
            'cycle' => cycle::find($selection['id_cycle']),
            'nniveau' => NNiveau::find($selection['id_nniveau']),
            'filiere' => niveau::find($selection['id_niveau']),
            'examLabel' => $this->examLabel($selection['type_examen'])
        ));
    }

    private function subjectsForSelection($selection, $generatedOnly = false)
    {
        $programmeSemester = $this->programmeSemester(
            $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
        );
        $programmeSemesters = $this->programmeSemesterCandidates(
            $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
        );
        $query = DB::table('classe_matiere as cm')->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->where('cm.id_classe', $selection['id_classe'])->where('cm.id_cycle', $selection['id_cycle'])
            ->where('cm.id_nniveau', $selection['id_nniveau'])->whereIn('cm.semestre', $programmeSemesters)
            ->select('m.id', 'm.nom')->distinct()->orderBy('m.nom');

        if ($generatedOnly && $selection['mode_generation'] === 'matiere') {
            $query->whereIn('m.id', function ($subquery) use ($selection) {
                $subquery->select('id_matiere')->from('anonymats_examens')
                    ->where('annee_scolaire', session('year'))->where('id_classe', $selection['id_classe'])
                    ->where('id_cycle', $selection['id_cycle'])->where('id_nniveau', $selection['id_nniveau'])
                    ->where('id_campus', $selection['id_campus'])->where('semestre', $selection['semestre'])
                    ->where('type_examen', $selection['type_examen'])
                    ->where('mode_generation', $selection['mode_generation']);
                if ($selection['id_tutelle']) $subquery->where('id_tutelle', $selection['id_tutelle']);
            });
        }

        return $query->get();
    }

    private function subjectSheets($selection, $onlySubjectId = 0)
    {
        $sheets = array();
        foreach ($this->subjectsForSelection($selection, true) as $subject) {
            if ($onlySubjectId && (int) $subject->id !== (int) $onlySubjectId) continue;
            $rows = $this->anonymatQuery($selection)
                ->where('a.id_matiere', $selection['mode_generation'] === 'matiere' ? $subject->id : 0)
                ->orderBy('a.code')->get();
            $sheets[] = (object) array('matiere' => $subject, 'rows' => $rows);
        }
        return $sheets;
    }

    private function classeMatiereForSelection($selection, $idMatiere)
    {
        $programmeSemester = $this->programmeSemester(
            $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
        );
        return classeMatiere::where('id_classe', $selection['id_classe'])
            ->where('id_cycle', $selection['id_cycle'])->where('id_nniveau', $selection['id_nniveau'])
            ->where('id_matiere', (int) $idMatiere)
            ->whereIn('semestre', $this->programmeSemesterCandidates(
                $selection['id_cycle'], $selection['id_nniveau'], $selection['semestre']
            ))->first();
    }

    private function sessionExamForSelection($selection, $classeMatiere, $idMatiere)
    {
        if (!$classeMatiere) return null;
        $query = Session_exam::where('id_classe_matiere', $classeMatiere->id)
            ->where('id_matiere', (int) $idMatiere)
            ->where('annee_academique', session('year'));
        if ($selection['id_tutelle'] && $this->sessionExamHasTutelleColumn()) {
            $query->where(function ($subquery) use ($selection) {
                $subquery->where('id_tutelle', $selection['id_tutelle'])->orWhereNull('id_tutelle');
            })->orderBy('id_tutelle', 'desc');
        }
        $sessions = $query->get();
        if ($selection['id_tutelle']) {
            $specific = $sessions->filter(function ($session) use ($selection) { return (int) $session->id_tutelle === (int) $selection['id_tutelle']; });
            if ($specific->count()) $sessions = $specific;
        }
        if ($sessions->count() > 1) abort(422, 'Plusieurs sessions correspondent à cette matière. Précisez la tutelle d’épreuve ou vérifiez la configuration des sessions.');
        return $sessions->first();
    }

    private function sessionExamHasTutelleColumn()
    {
        static $hasColumn = null;
        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('session_exams', 'id_tutelle');
        }
        return $hasColumn;
    }

    private function studentsForSelection($selection)
    {
        return DB::table('inscription as i')->join('eleve as e', 'e.id', '=', 'i.id_eleve')
            ->where('i.annee_scolaire', session('year'))->where('i.id_classe', $selection['id_classe'])
            ->where('i.id_cycle', $selection['id_cycle'])->where('i.id_nniveau', $selection['id_nniveau'])
            ->where('i.desinscrire', 0)->where(function ($query) use ($selection) {
                $query->where('i.id_campus', $selection['id_campus'])->orWhereNull('i.id_campus');
            })->select('e.id', 'e.nom', 'e.prenom', 'e.matricule')->distinct()
            ->orderBy('e.nom')->orderBy('e.prenom')->get();
    }

    private function ccTpeSequence($noteType, $semester)
    {
        if ($noteType === 'tpe') return $semester === 'S2' ? 8 : 7;
        return $semester === 'S2' ? 2 : 1;
    }

    private function ccTpeShortLabel($noteType, $semester)
    {
        return strtoupper($noteType === 'tpe' ? 'TPE' : 'CC').' '.($semester === 'S2' ? 'S2' : 'S1');
    }

    private function programmeSemester($idCycle, $idNniveau, $examSemester)
    {
        $position = $examSemester === 'S2' ? 2 : 1;
        $selectedCycle = cycle::find((int) $idCycle);
        $selectedLevel = NNiveau::find((int) $idNniveau);
        $cycleName = strtoupper($selectedCycle ? $selectedCycle->nom : '');
        $levelName = strtoupper($selectedLevel ? $selectedLevel->nom : '');
        $year = null;
        $base = 0;

        if (strpos($cycleName.' '.$levelName, 'MASTER') !== false) {
            $base = 6;
            if (preg_match('/MASTER[^0-9]*([12])/', $levelName.' '.$cycleName, $matches)) {
                $year = (int) $matches[1];
            }
        } elseif (strpos($cycleName.' '.$levelName, 'LICENCE') !== false) {
            if (preg_match('/(?:LICENCE|\bL)[^0-9]*([123])/', $levelName.' '.$cycleName, $matches)) {
                $year = (int) $matches[1];
            }
        } elseif (strpos($cycleName.' '.$levelName, 'DOCTOR') !== false) {
            $base = 10;
            $year = 1;
        } elseif (preg_match('/([1-6])(?:\D*)$/', $levelName, $matches)) {
            $year = (int) $matches[1];
        }

        if ($year !== null) {
            $semester = $base + (($year - 1) * 2) + $position;
            if ($semester >= 1 && $semester <= 12) return $semester;
        }

        $maximum = (int) DB::table('classe_matiere')
            ->where('id_cycle', (int) $idCycle)->where('id_nniveau', (int) $idNniveau)
            ->whereBetween('semestre', array(1, 12))->max('semestre');
        if ($maximum >= 1) {
            $firstSemester = $maximum % 2 === 0 ? $maximum - 1 : $maximum;
            return $firstSemester + ($position - 1);
        }

        return $position;
    }

    private function programmeSemesterCandidates($idCycle, $idNniveau, $examSemester)
    {
        $position = $examSemester === 'S2' ? 2 : 1;
        $calculated = (int) $this->programmeSemester($idCycle, $idNniveau, $examSemester);
        $candidates = array($calculated, $position);

        if ($calculated > 2) {
            $candidates[] = ($calculated % 2 === 0) ? 2 : 1;
        }

        $result = array();
        foreach ($candidates as $candidate) {
            $candidate = (int) $candidate;
            if ($candidate > 0) $result[$candidate] = $candidate;
        }

        return array_values($result);
    }

    private function marker($type, $semester)
    {
        if ($type === 'rattrapage_1') return 'R1';
        if ($type === 'rattrapage_2') return 'R2';
        return $semester === 'S2' ? 'S2' : 'S1';
    }

    private function sequence($type, $semester)
    {
        if ($type === 'rattrapage_1') return 5;
        if ($type === 'rattrapage_2') return 6;
        return $semester === 'S2' ? 4 : 3;
    }

    private function examLabels()
    {
        return array(
            'session_normale' => 'Session normale', 'fin_semestre_1' => 'Examen semestre 1',
            'rattrapage_1' => 'Rattrapage 1', 'fin_semestre_2' => 'Examen semestre 2',
            'rattrapage_2' => 'Rattrapage 2'
        );
    }

    private function examLabel($type)
    {
        $labels = $this->examLabels();
        return isset($labels[$type]) ? $labels[$type] : $type;
    }

    private function downloadPdf($view, $data, $filename, $html = null)
    {
        if (!count($data['sheets'])) {
            return redirect()->back()->withErrors(array('Aucune matiere ou aucun anonymat a exporter.'));
        }

        $chromeCandidates = array(
            getenv('ANONYMATS_BROWSER_PATH') ?: '',
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
            'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
            '/usr/bin/google-chrome', '/usr/bin/chromium', '/usr/bin/chromium-browser'
        );
        $chrome = null;
        foreach ($chromeCandidates as $candidate) {
            if (is_file($candidate)) {
                $chrome = $candidate;
                break;
            }
        }
        $document = $this->selfContainedHtml($html !== null ? $html : view($view, $data)->render());
        $disabledFunctions = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (!$chrome || !function_exists('exec') || in_array('exec', $disabledFunctions) || getenv('ANONYMATS_PDF_MODE') === 'browser') {
            return $this->browserPdfResponse($document);
        }

        $directory = storage_path('app/anonymats-pdf');
        if (!is_dir($directory) && !@mkdir($directory, 0775, true)) {
            return $this->browserPdfResponse($document);
        }

        $token = uniqid('anonymats-', true);
        $htmlPath = $directory.DIRECTORY_SEPARATOR.$token.'.html';
        $pdfPath = $directory.DIRECTORY_SEPARATOR.$token.'.pdf';
        $profilePath = $directory.DIRECTORY_SEPARATOR.$token.'-profile';
        file_put_contents($htmlPath, $document);

        // escapeshellarg() removes percent signs on Windows, so keep spaces quoted instead of URL-encoding them.
        $fileUrl = 'file://'.(DIRECTORY_SEPARATOR === '\\' ? '/' : '').str_replace('\\', '/', $htmlPath);
        $command = escapeshellarg($chrome)
            .' --headless --disable-gpu --allow-file-access-from-files'
            .' --no-pdf-header-footer --print-to-pdf-no-header'
            .' --user-data-dir='.escapeshellarg($profilePath)
            .' --print-to-pdf='.escapeshellarg($pdfPath)
            .' '.escapeshellarg($fileUrl).' 2>&1';
        $output = array();
        $exitCode = 1;
        @exec($command, $output, $exitCode);

        $attempts = 0;
        while ($attempts < 100) {
            clearstatcache(true, $pdfPath);
            if (is_file($pdfPath) && filesize($pdfPath) > 0) break;
            usleep(200000);
            $attempts++;
        }
        if (is_file($pdfPath)) usleep(1000000);
        @unlink($htmlPath);
        $this->removeDirectory($profilePath);

        if (!is_file($pdfPath) || filesize($pdfPath) === 0) {
            @unlink($pdfPath);
            return $this->browserPdfResponse($document);
        }

        return response()->download($pdfPath, $filename, array('Content-Type' => 'application/pdf'))
            ->deleteFileAfterSend(true);
    }

    private function filenamePart($value)
    {
        $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        $value = strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $value));
        return trim($value, '-') ?: 'classe';
    }

    private function synchronizeNoteIdSequence()
    {
        if (DB::connection()->getDriverName() !== 'pgsql') return;

        DB::select('SELECT pg_advisory_xact_lock(?)', array(crc32('note_id_seq') & 0x7fffffff));
        $maximum = (int) DB::table('note')->max('id');
        $state = DB::selectOne('SELECT last_value FROM note_id_seq');
        $lastValue = $state ? (int) $state->last_value : 0;
        if ($lastValue >= $maximum) return;

        if ($maximum > 0) {
            DB::select("SELECT setval(pg_get_serial_sequence('note', 'id'), ?, true)", array($maximum));
        } else {
            DB::select("SELECT setval(pg_get_serial_sequence('note', 'id'), 1, false)");
        }
    }

    private function removeDirectory($directory)
    {
        if (!is_dir($directory)) return;
        $root = realpath(storage_path('app/anonymats-pdf'));
        $resolved = realpath($directory);
        if (!$root || !$resolved || strpos(strtolower($resolved), strtolower($root.DIRECTORY_SEPARATOR)) !== 0) return;
        foreach (scandir($directory) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $directory.DIRECTORY_SEPARATOR.$item;
            if (is_dir($path) && !is_link($path)) $this->removeDirectory($path);
            else @unlink($path);
        }
        @rmdir($directory);
    }

    private function browserPdfResponse($html)
    {
        $script = '<script>window.addEventListener("load",function(){window.print();});</script>';
        return response(str_replace('</body>', $script.'</body>', $html), 200, array('Content-Type'=>'text/html; charset=UTF-8'));
    }

    private function selfContainedHtml($html)
    {
        // Export the report only: application menus, forms and fixed page heights
        // must not leak into a historical PV or create an empty trailing page.
        if (class_exists('DOMDocument')) {
            $dom = new \DOMDocument();
            $previous = libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8" ?>'.$html);
            libxml_clear_errors(); libxml_use_internal_errors($previous);
            $report = null;
            foreach ($dom->getElementsByTagName('div') as $element) {
                if (preg_match('/(?:^|\s)(?:anonymat-print|module-print)(?:\s|$)/', $element->getAttribute('class'))) { $report = $element; break; }
            }
            if ($report) {
                $styles = '<style>html,body{margin:0;padding:0;font-family:Arial,sans-serif;font-size:11px;line-height:1.35}*{box-sizing:border-box}h3{font-size:17px}h4{font-size:14px}p{margin:0 0 10px}button{cursor:pointer}.text-center{text-align:center}.no-print{margin-bottom:12px}</style>';
                // Application-wide Bootstrap/plugin styles are intentionally excluded.
                // Each print view carries its own table/page styles and header styles.
                foreach ($dom->getElementsByTagName('link') as $link) {
                    if (strpos($link->getAttribute('href'), 'custom-header.css') !== false) $styles .= $dom->saveHTML($link);
                }
                $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Documents des examens — Azimut</title>'.$styles
                    .'</head><body><div id="wrapper"><div id="page-wrapper"><div id="page-inner">'.$dom->saveHTML($report).'</div></div></div></body></html>';
            }
        }
        $root = realpath(public_path());
        $resolve = function ($url) use ($root) {
            $urlPath = parse_url(html_entity_decode($url, ENT_QUOTES, 'UTF-8'), PHP_URL_PATH);
            if (!$urlPath) return null;
            $publicPrefix = parse_url(asset(''), PHP_URL_PATH);
            if ($publicPrefix && strpos($urlPath, $publicPrefix) === 0) $urlPath = substr($urlPath, strlen($publicPrefix));
            $file = realpath(public_path(ltrim($urlPath, '/')));
            return $file && is_file($file) && strpos(strtolower($file), strtolower($root.DIRECTORY_SEPARATOR)) === 0 ? $file : null;
        };
        $html = preg_replace('~<script\b[^>]*>.*?</script>~is', '', $html);
        $html = preg_replace_callback('~<link\b[^>]*href="([^"]+)"[^>]*>~i', function ($match) use ($resolve) {
            $file = $resolve($match[1]);
            return $file && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'css' ? '<style>'.file_get_contents($file).'</style>' : '';
        }, $html);
        return preg_replace_callback('~(?:src="([^"]+)"|url\([\x27"]?([^\)\x27"]+)[\x27"]?\))~i', function ($match) use ($resolve) {
            $url = !empty($match[1]) ? $match[1] : $match[2];
            $file = $resolve($url);
            if (!$file || !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('png','jpg','jpeg','webp','gif'))) return $match[0];
            $image = @file_get_contents($file);
            if ($image === false) return $match[0];
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $data = 'data:image/'.($ext === 'jpg' ? 'jpeg' : $ext).';base64,'.base64_encode($image);
            return !empty($match[1]) ? 'src="'.$data.'"' : 'url("'.$data.'")';
        }, $html);
    }

    private function storedNote($rawNote, $sequence, $sessionExam)
    {
        return \gesecole\Services\ExamSessionNote::weightedMark($rawNote, $this->sessionPercentage($sessionExam, $sequence));
    }

    private function validateMarkInputs($notes)
    {
        foreach ((array) $notes as $value) {
            if (!is_scalar($value) && $value !== null) abort(422, 'Note invalide.');
            $value = str_replace(',', '.', trim((string) $value));
            if ($value !== '' && (!is_numeric($value) || $value < 0 || $value > 20)) abort(422, 'Les notes doivent être comprises entre 0 et 20.');
        }
    }

    private function assertWritableSession($selection, $classeMatiere, $sessionExam, $sequence)
    {
        $phases = $sessionExam ? \gesecole\Services\ExamSessionNote::phases($sessionExam, $classeMatiere->semestre) : array();
        if (!isset($phases[$sequence]) || $phases[$sequence]->weight > 100) abort(422, 'Cette épreuve n’est pas activée dans la session d’examen sélectionnée.');
        $now = date('Y-m-d H:i:s');
        $locks = DB::table('verrou_note')->where('annee_scolaire', session('year'))->where('sequence', $sequence)
            ->where(function ($q) { $q->whereNull('target')->orWhere('target', 'note_sequence'); })
            ->where(function ($q) { $q->whereNull('is_active')->orWhere('is_active', true); })
            ->where(function ($q) use ($now) { $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now); })
            ->where(function ($q) use ($now) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now); })
            ->where(function ($q) use ($selection) { $q->whereNull('id_campus')->orWhere('id_campus', $selection['id_campus']); });
        if ($locks->exists()) abort(403, trans('admin.mod_locked'));
    }

    private function findNote($studentId, $subjectId, $sequence, $selection, $classeMatiere, $sessionExam)
    {
        if (!$sessionExam || !$classeMatiere) return null;
        $query = note::where('id_eleve', $studentId)->where('id_matiere', $subjectId)
            ->where('sequence', $sequence)->where('annee_scolaire', session('year'))
            ->where(function ($q) use ($selection) { $q->whereNull('id_classe')->orWhere('id_classe', $selection['id_classe']); })
            ->where(function ($q) use ($selection) { $q->whereNull('id_campus')->orWhere('id_campus', $selection['id_campus']); })
            ->where(function ($q) use ($classeMatiere) { $q->whereNull('id_classe_matiere')->orWhere('id_classe_matiere', $classeMatiere->id); });
        $specific = clone $query;
        $existing = $specific->where('id_session', $sessionExam->id)->orderBy('id', 'desc')->first();
        if ($existing) return $existing;
        if (Session_exam::where('id_classe_matiere', $classeMatiere->id)->where('annee_academique', session('year'))->count() !== 1) return null;
        if (classeMatiere::where('id_classe', $selection['id_classe'])->where('id_cycle', $selection['id_cycle'])
            ->where('id_nniveau', $selection['id_nniveau'])->where('id_matiere', $subjectId)->count() !== 1) return null;
        return $query->whereNull('id_session')->orderBy('id', 'desc')->first();
    }

    private function displayNote($existing, $sequence, $classeMatiereId, $idMatiere)
    {
        if (!$existing) return null;
        $sessionExam = $existing->id_session ? Session_exam::find($existing->id_session) : ($classeMatiereId ? Session_exam::where('id_classe_matiere', $classeMatiereId)
            ->where('id_matiere', $idMatiere)->where('annee_academique', session('year'))->first() : null);
        if (!$sessionExam) return $existing->note;
        $percentage = $this->sessionPercentage($sessionExam, $sequence);
        return $percentage > 0 ? round(($existing->note * 20) / $percentage, 2) : $existing->note;
    }
}
