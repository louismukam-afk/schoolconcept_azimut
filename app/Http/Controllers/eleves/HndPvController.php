<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\Http\Controllers\Controller;
use gesecole\Services\HndPv;
use gesecole\classe;
use gesecole\cycle;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\TutelleAcademique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HndPvController extends Controller
{
    private function pvTutelle($classe)
    {
        $attached = $classe->tutelles()->orderBy('tutelles_academiques.nom')->get();
        foreach ($attached as $tutelle) {
            if (stripos($tutelle->nom.' '.$tutelle->tutelle_anglophone.' '.$tutelle->ville, 'BAMENDA') !== false) return $tutelle;
        }
        if (count($attached) === 1) return $attached->first();
        return TutelleAcademique::where(function ($query) {
            $query->whereRaw('UPPER(nom) LIKE ?', array('%BAMENDA%'))
                ->orWhereRaw('UPPER(tutelle_anglophone) LIKE ?', array('%BAMENDA%'))
                ->orWhereRaw('UPPER(ville) LIKE ?', array('%BAMENDA%'));
        })->orderBy('id')->first();
    }

    public function index()
    {
        return view('eleves.hnd_pv.selection', array('titre' => 'Procès-verbaux HND / anglophone',
            'cycles' => cycle::orderBy('nom')->get(), 'nniveaux' => NNiveau::orderBy('nom')->get(),
            'niveaux' => niveau::orderBy('nom')->get(), 'classes' => classe::orderBy('nom')->get()));
    }

    private function context(Request $request)
    {
        $this->validate($request, array('id' => 'required|integer|min:1', 'cycle' => 'required|integer|min:1', 'nniveau' => 'required|integer|min:1'));
        $classe = classe::findOrFail((int) $request->input('id'));
        $cycle = cycle::findOrFail((int) $request->input('cycle'));
        $level = NNiveau::where('id_cycle', $cycle->id)->findOrFail((int) $request->input('nniveau'));
        return array('titre' => 'Procès-verbaux HND / anglophone', 'classe' => $classe, 'cycle' => $cycle,
            'nniveau' => $level, 'filiere' => niveau::find($classe->id_niveau), 'exams' => HndPv::exams());
    }

    public function sessions(Request $request)
    {
        $values = $this->context($request);
        $years = DB::table('inscription')->select('annee_scolaire')->distinct()->orderBy('annee_scolaire', 'desc')->lists('annee_scolaire');
        $year = session('year');
        if ($year && !in_array($year, $years)) $years[] = $year;
        $values['annees'] = $years;
        $values['annee'] = $year;
        $values['papers'] = HndPv::paperSizes();
        return view('eleves.hnd_pv.sessions', $values);
    }

    public function printPv(Request $request)
    {
        $values = $this->context($request);
        $this->validate($request, array('annee' => 'required|string|max:50',
            'examen' => 'required_without:semestre_synthetique|in:'.implode(',', array_keys(HndPv::exams())),
            'semestre_synthetique' => 'sometimes|in:1,2',
            'session_synthetique' => 'required_with:semestre_synthetique|in:normale,rattrapage',
            'papier' => 'sometimes|in:a4,a3',
            'date_debut' => 'date_format:Y-m-d', 'date_fin' => 'date_format:Y-m-d'));
        $start = (string) $request->input('date_debut', '');
        $end = (string) $request->input('date_fin', '');
        if ($start !== '' && $end !== '' && $end < $start) abort(422, 'La date de fin doit être supérieure ou égale à la date de début.');
        $year = $request->input('annee');
        $compact = $request->has('semestre_synthetique');
        $examKey = $compact
            ? ($request->input('session_synthetique') === 'rattrapage' ? 'resit_' : 'semester_').$request->input('semestre_synthetique')
            : $request->input('examen');
        $values['pv'] = $this->classResults($values['classe']->id, $values['cycle']->id, $values['nniveau']->id, $year, $examKey, $start, $end);
        $values['papier'] = $request->input('papier', 'a3');
        $values['papers'] = HndPv::paperSizes();
        $annual = $examKey === 'annual';
        $values['layout'] = HndPv::layout($values['papier'], count($values['pv']['subjects']), count($values['pv']['modules']), $annual || $compact);
        $values['annee'] = $year;
        $values['date_debut'] = $start;
        $values['date_fin'] = $end;
        $values['campus'] = $this->currentCampus();
        $values['tutelle'] = $this->pvTutelle($values['classe']);
        return view($annual || $compact ? 'eleves.hnd_pv.annual' : 'eleves.hnd_pv.print', $values);
    }
    protected function classResults($classId, $cycleId, $levelId, $year, $examKey, $start = '', $end = '', $fullSemester = false)
    {
        $campusId = $this->currentCampusId();
        $students = DB::table('eleve as e')->join('inscription as i', 'i.id_eleve', '=', 'e.id')
            ->where('i.annee_scolaire', $year)->where('i.id_classe', $classId)->where('i.id_cycle', $cycleId)->where('i.id_nniveau', $levelId);
        if ($campusId) $students->where(function ($q) use ($campusId) { $q->where('i.id_campus', $campusId)->orWhereNull('i.id_campus'); });
        $students = $students->select('e.id', 'e.matricule', 'e.nom', 'e.prenom')->distinct()->orderBy('e.nom')->orderBy('e.prenom')->get();
        $programme = DB::table('classe_matiere as cm')->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->leftJoin('codages as code', DB::raw('code.id::text'), '=', 'cm.id_code')
            ->leftJoin('session_exams as se', function ($join) use ($year) { $join->on('se.id_classe_matiere', '=', 'cm.id')->where('se.annee_academique', '=', $year); })
            ->where('cm.id_classe', $classId)->where('cm.id_cycle', $cycleId)->where('cm.id_nniveau', $levelId)
            ->whereNotIn('cm.id_matiere', function ($q) use ($classId, $levelId) {
                $q->select('id_matiere_mere')->from('groupe_matieres')->where('id_classe', $classId)->where('id_nniveau', $levelId)->whereNotNull('id_matiere_mere');
            })
            ->select('cm.id as id_classe_matiere', 'cm.id_matiere', 'cm.semestre', 'cm.coef as credit', 'm.nom', 'm.nom_anglais',
                DB::raw("COALESCE(code.nom, cm.id_code, '') as code"), 'se.id as id_session')
            ->orderBy('cm.semestre')->orderBy('code.nom')->orderBy('m.nom')->get();
        $subjects = array();
        foreach ($programme as $subject) {
            $key = $subject->id_classe_matiere;
            if (!isset($subjects[$key])) { $subjects[$key] = $subject; $subjects[$key]->sessions = array(); }
            if ($subject->id_session) $subjects[$key]->sessions[] = (int) $subject->id_session;
        }
        $ids = array(); foreach ($students as $student) $ids[] = $student->id;
        $moduleRows = DB::table('groupe_matieres as gm')
            ->join('classe_matiere as child', 'child.id', '=', 'gm.id_classe_matiere')
            ->join('matiere as m', 'm.id', '=', 'gm.id_matiere_mere')
            ->leftJoin('classe_matiere as parent', function ($join) use ($classId, $cycleId, $levelId) {
                $join->on('parent.id_matiere', '=', 'gm.id_matiere_mere')->where('parent.id_classe', '=', $classId)
                    ->where('parent.id_cycle', '=', $cycleId)->where('parent.id_nniveau', '=', $levelId);
            })
            ->leftJoin('codages as code', DB::raw('code.id::text'), '=', 'parent.id_code')
            ->where('gm.id_classe', $classId)->where('gm.id_nniveau', $levelId)->where('child.id_cycle', $cycleId)
            ->select('gm.id_classe_matiere', 'gm.id_matiere_mere as id_module', 'm.nom', 'm.nom_anglais', DB::raw("COALESCE(code.nom, parent.id_code, '') as code"))
            ->orderBy('gm.id')->get();
        $moduleLinks = array();
        foreach ($moduleRows as $module) $moduleLinks[$module->id_classe_matiere] = $module;
        $notes = array();
        if ($ids) {
            $notes = DB::table('note')->where('annee_scolaire', $year)->whereIn('id_eleve', $ids)
                ->whereIn('sequence', array(1,2,3,4,5,6,7,8))
                ->where(function ($q) use ($classId) { $q->where('id_classe', $classId)->orWhereNull('id_classe'); })
                ->orderBy('updated_at')->orderBy('id')->get();
        }
        return HndPv::build($students, array_values($subjects), $notes, $examKey, $start, $end, $moduleLinks, $fullSemester);
    }
}
