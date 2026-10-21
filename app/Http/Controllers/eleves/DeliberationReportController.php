<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\Http\Controllers\Controller;
use gesecole\Services\DeliberationReport;
use gesecole\cycle;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliberationReportController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, array(
            'portee' => 'sometimes|in:globale,selection',
            'format' => 'sometimes|in:synthetique,detaille',
            'semestre' => 'sometimes|in:tous,1,2,3,4,5,6',
            'type_examen' => 'sometimes|in:'.implode(',', array_keys(DeliberationReport::exams())),
            'cycle' => 'sometimes|integer|min:1',
            'nniveau' => 'sometimes|integer|min:1',
            'filiere' => 'sometimes|integer|min:1',
            'id' => 'sometimes|integer|min:1',
            'type_deliberation' => 'sometimes|in:'.implode(',', array_keys(DeliberationReport::types()))
        ));
        $campusId = $this->currentCampusId();
        $annee = (string) $request->input('annee_academique', session('year'));
        $scope = $request->input('portee', 'globale');
        $format = $request->input('format', 'synthetique');
        $semester = $request->input('semestre', 'tous');
        $exam = $request->input('type_examen', 'tous');
        $type = $request->input('type_deliberation', '');
        $cycleId = (int) $request->input('cycle');
        $levelId = (int) $request->input('nniveau');
        $fieldId = (int) $request->input('filiere');
        $classId = (int) $request->input('id');
        $years = DB::table('inscription')->select('annee_scolaire')->distinct()->orderBy('annee_scolaire', 'desc');
        if ($campusId) $years->where(function ($q) use ($campusId) { $q->where('id_campus', $campusId)->orWhereNull('id_campus'); });
        $years = $years->lists('annee_scolaire');
        if (!in_array($annee, $years)) $years[] = $annee;
        $report = null;
        if ($request->has('consulter')) {
            if ($scope === 'selection') {
                $this->validate($request, array('cycle' => 'required|integer', 'nniveau' => 'required|integer', 'filiere' => 'required|integer'));
                if (!NNiveau::where('id', $levelId)->where('id_cycle', $cycleId)->exists()
                    || !niveau::where('id', $fieldId)->exists()
                    || ($classId && !classe::where('id', $classId)->where('id_niveau', $fieldId)->exists())) {
                    abort(422, 'La selection cycle, niveau, filiere et specialite est incoherente.');
                }
            }
            $query = DB::table('note as n')
                ->leftJoin('session_exams as se', 'se.id', '=', 'n.id_session')
                ->join('classe_matiere as cm', 'cm.id', '=', DB::raw('COALESCE(NULLIF(n.id_classe_matiere, 0), se.id_classe_matiere)'))
                ->join('classe as c', 'c.id', '=', 'cm.id_classe')
                ->join('niveau as f', 'f.id', '=', 'c.id_niveau')
                ->join('cycle as cy', 'cy.id', '=', 'cm.id_cycle')
                ->join('n_niveaus as nn', 'nn.id', '=', 'cm.id_nniveau')
                ->join('matiere as m', 'm.id', '=', 'n.id_matiere')
                ->join('eleve as e', 'e.id', '=', 'n.id_eleve')
                ->where('n.annee_scolaire', $annee)
                ->where('n.edition_details', 'like', 'deliberation %')
                ->whereIn('n.sequence', array(3, 4, 5, 6))
                ->whereExists(function ($q) use ($annee, $campusId) {
                    $q->select(DB::raw('1'))->from('inscription as i')
                        ->whereRaw('i.id_eleve = n.id_eleve AND i.id_classe = cm.id_classe AND i.id_cycle = cm.id_cycle AND i.id_nniveau = cm.id_nniveau')
                        ->where('i.annee_scolaire', $annee);
                    if ($campusId) $q->where(function ($campus) use ($campusId) { $campus->where('i.id_campus', $campusId)->orWhereNull('i.id_campus'); });
                });
            if ($scope === 'selection') {
                $query->where('cm.id_cycle', $cycleId)->where('cm.id_nniveau', $levelId)->where('c.id_niveau', $fieldId);
                if ($classId) $query->where('cm.id_classe', $classId);
            }
            if ($semester !== 'tous') {
                $query->where('cm.semestre', (int) $semester)
                    ->whereIn('n.sequence', (int) $semester % 2 === 0 ? array(4, 6) : array(3, 5));
            }
            $query->whereIn('n.sequence', DeliberationReport::examSequences($exam));
            $rows = $query->select('n.id', 'n.id_eleve', 'n.edition_details', 'n.updated_at', 'n.sequence',
                'cm.id as id_classe_matiere', 'cm.id_classe', 'cm.id_cycle', 'cm.id_nniveau', 'cm.semestre',
                'c.id_niveau as id_filiere', 'c.nom as classe_nom', 'f.nom as filiere_nom', 'cy.nom as cycle_nom', 'nn.nom as niveau_nom',
                'm.nom as matiere_nom', 'e.matricule', 'e.nom', 'e.prenom')
                ->orderBy('f.nom')->orderBy('c.nom')->orderBy('nn.nom')->orderBy('cy.nom')->orderBy('m.nom')
                ->orderBy('e.nom')->orderBy('e.prenom')->orderBy('n.updated_at')->get();
            $report = DeliberationReport::build($rows, $type);
        }
        return view('eleves.rapport_deliberations', array(
            'titre' => 'Rapports des délibérations', 'annees' => $years, 'annee_selectionnee' => $annee,
            'cycles' => cycle::orderBy('nom')->get(), 'nniveaux' => NNiveau::orderBy('nom')->get(),
            'niveaux' => niveau::orderBy('nom')->get(), 'classes' => classe::orderBy('nom')->get(),
            'id_cycle' => $cycleId, 'id_nniveau' => $levelId, 'id_filiere' => $fieldId, 'id_classe' => $classId,
            'portee' => $scope, 'format' => $format, 'semestre' => $semester, 'type_examen' => $exam,
            'type_deliberation' => $type, 'types' => DeliberationReport::types(),
            'exam_options' => DeliberationReport::exams(), 'report' => $report
        ));
    }
}
