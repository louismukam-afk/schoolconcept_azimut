<?php namespace gesecole\Http\Controllers\eleves;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use gesecole\classe;
use gesecole\cycle;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\ReleveNoteGeneration;
use gesecole\Services\BtsAnnualTranscript;

class bts_releveAnnuelController extends bts_releveSessionController
{
    protected $transcriptType = 'bts';
    protected $transcriptService = 'gesecole\Services\BtsAnnualTranscript';

    protected function prepareTranscriptRows($rows, $semester, $classe)
    {
        return $rows;
    }

    protected function availableCycles()
    {
        return cycle::all();
    }

    public function index()
    {
        return view('eleves/classe_releves_bts_annuel', array(
            'titre' => 'Relevé de notes '.strtoupper($this->transcriptType).' annuel',
            'documentLabel' => strtoupper($this->transcriptType),
            'formBase' => '/eleves/releves_'.$this->transcriptType.'_annuel',
            'historyBase' => '/eleves/releves_'.$this->transcriptType,
            'classes' => classe::orderBy('nom')->get(),
            'cycles' => $this->availableCycles(), 'nniveaux' => NNiveau::all(),
        ));
    }

    public function generate(Request $request)
    {
        $this->validate($request, array(
            'id' => 'required|integer|exists:classe,id',
            'cycle' => 'required|integer|exists:cycle,id',
            'nniveau' => 'required|integer|exists:n_niveaus,id',
            'periode_debut' => 'required|date_format:Y-m-d',
            'periode_fin' => 'required|date_format:Y-m-d',
            'date_signature' => 'required|date_format:Y-m-d',
        ));
        if ($request->input('periode_fin') < $request->input('periode_debut')) {
            return redirect()->back()->withInput()->with('error', 'La date de fin doit être postérieure ou égale à la date de début.');
        }
        $classe = classe::findOrFail($request->input('id'));
        $cycle = cycle::findOrFail($request->input('cycle'));
        if ($this->transcriptType === 'hnd' && !preg_match('/\bHND\b|HIGHER NATIONAL DIPLOMA/i', $cycle->nom)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez sélectionner le cycle HND.');
        }
        $nniveau = NNiveau::where('id_cycle', $cycle->id)->findOrFail($request->input('nniveau'));
        $annee = session('year');
        $eleves = DB::table('eleve as e')->join('inscription as i', 'i.id_eleve', '=', 'e.id')
            ->where('i.annee_scolaire', $annee)->where('i.id_classe', $classe->id)
            ->where('i.id_cycle', $cycle->id)->where('i.id_nniveau', $nniveau->id)
            ->select('e.*')->distinct()->orderBy('e.nom')->orderBy('e.prenom')->get();
        $matieres = DB::table('classe_matiere as cm')->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->join('session_exams as se', 'se.id_classe_matiere', '=', 'cm.id')
            ->where('cm.id_classe', $classe->id)->where('cm.id_cycle', $cycle->id)
            ->where('cm.id_nniveau', $nniveau->id)->where('se.annee_academique', $annee)
            ->select('m.nom', 'm.nom_anglais', 'm.id as id_matiere', 'cm.id as id_classe_matiere', 'cm.semestre',
                'cm.coef', 'cm.type', 'cm.id_code', 'se.id as id_session',
                'se.cc1', 'se.sn1', 'se.tpe1', 'se.cc2', 'se.sn2', 'se.tpe2')
            ->orderBy('cm.semestre')->orderBy('cm.id_code')->orderBy('se.id')->get();
        $ids = array();
        foreach ($eleves as $eleve) $ids[] = $eleve->id;
        // La période porte sur la date d'enregistrement initial des notes, bornes incluses.
        $notes = count($ids) ? DB::table('note')->where('annee_scolaire', $annee)
            ->whereIn('id_eleve', $ids)->where('created_at', '>=', $request->input('periode_debut').' 00:00:00')
            ->where('created_at', '<', date('Y-m-d', strtotime($request->input('periode_fin').' +1 day')))
            ->orderBy('id')->get() : array();
        if (!count($eleves) || !count($matieres) || !count($notes)) {
            return redirect()->back()->withInput()->with('error', 'Aucun étudiant, enseignement ou note enregistré sur cette période pour cette sélection.');
        }
        $groups = $this->getGroupConfig($classe->id, $nniveau->id);
        $notesIndex = array();
        foreach ($notes as $note) $notesIndex[$note->id_eleve][$note->id_matiere][] = $note;
        $pv = array();
        $service = $this->transcriptService;
        foreach ($eleves as $eleve) {
            $semestres = array();
            foreach (array(1, 2) as $semestre) {
                $rows = array();
                $semesterNumber = $semestre;
                foreach ($matieres as $matiere) {
                    $configuredSemester = (int) $matiere->semestre;
                    if ($configuredSemester > 0 && (($configuredSemester - 1) % 2 + 1) !== $semestre) continue;
                    if ($configuredSemester > 0) $semesterNumber = $configuredSemester;
                    $map = array();
                    $subjectNotes = isset($notesIndex[$eleve->id][$matiere->id_matiere]) ? $notesIndex[$eleve->id][$matiere->id_matiere] : array();
                    foreach ($subjectNotes as $note) {
                        if (!empty($note->id_session) && $note->id_session != $matiere->id_session) continue;
                        if (!empty($note->id_classe_matiere) && $note->id_classe_matiere != $matiere->id_classe_matiere) continue;
                        if (!empty($note->id_classe) && $note->id_classe != $classe->id) continue;
                        if ($note->note !== null && $note->note !== '') $map[$note->sequence] = (float) $note->note;
                    }
                    $key = $matiere->id_classe_matiere;
                    // Une seule ligne par enseignement, même si plusieurs sessions sont configurées.
                    $value = $this->calculateSemestreNote($map, $semestre, $matiere);
                    if (isset($rows[$key]) && $value === null) continue;
                    $rows[$key] = (object) array('id_matiere' => $matiere->id_matiere,
                        'id_classe_matiere' => $key, 'code' => $matiere->id_code,
                        'nom' => $this->transcriptType === 'hnd' && !empty($matiere->nom_anglais) ? $matiere->nom_anglais : $matiere->nom, 'type' => $matiere->type,
                        'credit' => (float) $matiere->coef, 'note' => $value);
                }
                $rows = $service::groupRows($rows, $groups);
                $rows = $this->prepareTranscriptRows($rows, $semesterNumber, $classe);
                $semestres[$semestre] = $service::summarize($rows);
                $semestres[$semestre]->numero = $semesterNumber;
            }
            $pv[] = (object) array('eleve' => $eleve, 'semestres' => $semestres,
                'annuel' => $service::summarize(array_merge($semestres[1]->rows, $semestres[2]->rows)));
        }
        $payload = array('modele' => $this->transcriptType.'_annuel', 'PV' => $pv, 'classe' => $classe,
            'cycle' => $cycle, 'nniveau' => $nniveau, 'niveau' => niveau::find($classe->id_niveau),
            'annee' => $annee, 'periode_debut' => $request->input('periode_debut'),
            'periode_fin' => $request->input('periode_fin'), 'date_signature' => $request->input('date_signature'));
        $generation = new ReleveNoteGeneration();
        $generation->type = $this->transcriptType;
        $generation->annee_academique = $annee;
        $generation->id_classe = $classe->id;
        $generation->id_niveau = $classe->id_niveau;
        $generation->id_cycle = $cycle->id;
        $generation->id_nniveau = $nniveau->id;
        $generation->periode_debut = $payload['periode_debut'];
        $generation->periode_fin = $payload['periode_fin'];
        $generation->contenu = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $generation->hash_generation = hash('sha256', $generation->contenu);
        $generation->nb_releves = count($pv);
        $generation->id_creator = \Auth::id();
        $generation->save();
        return redirect()->to('/eleves/releves_'.$this->transcriptType.'/generation/'.$generation->id.'/print');
    }
}
