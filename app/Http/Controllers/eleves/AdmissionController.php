<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\cycle;
use gesecole\Services\AdmissionList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionController extends HndPvController
{
    private function sessionsList()
    {
        return array('semester_1' => 'Fin de semestre 1', 'semester_2' => 'Fin de semestre 2',
            'resit_1' => 'Après rattrapage 1', 'resit_2' => 'Après rattrapage 2', 'annual' => 'Admission annuelle');
    }

    public function index()
    {
        $this->renewSchoolYear();
        return view('eleves.admissions.selection', array('titre' => 'Listes des étudiants admis',
            'cycles' => cycle::orderBy('nom')->get(), 'sessions' => $this->sessionsList(), 'annee' => session('year')));
    }

    public function report(Request $request)
    {
        $this->validate($request, array('cycle' => 'required|integer|min:1', 'annee' => 'required|string|max:50',
            'session' => 'required|in:'.implode(',', array_keys($this->sessionsList()))));
        $cycle = cycle::findOrFail((int) $request->input('cycle'));
        $year = $request->input('annee');
        $session = $request->input('session');
        $campus = $this->currentCampus();
        $query = DB::table('inscription as i')->join('classe as c', 'c.id', '=', 'i.id_classe')
            ->join('niveau as f', 'f.id', '=', 'c.id_niveau')->join('n_niveaus as n', 'n.id', '=', 'i.id_nniveau')
            ->where('i.annee_scolaire', $year)->where('i.id_cycle', $cycle->id)->where('n.id_cycle', $cycle->id);
        if ($campus) $query->where(function ($q) use ($campus) { $q->where('i.id_campus', $campus->id)->orWhereNull('i.id_campus'); });
        $classes = $query->select('c.id', 'c.nom as specialite', 'f.nom as filiere', 'n.id as niveau_id', 'n.nom as niveau')
            ->distinct()->orderBy('f.nom')->orderBy('c.nom')->orderBy('n.nom')->get();
        $groups = array(); $total = 0;
        foreach ($classes as $class) {
            // A resit admission concerns the whole semester, including courses already passed in normal session.
            $pv = $this->classResults($class->id, $cycle->id, $class->niveau_id, $year, $session, '', '', strpos($session, 'resit_') === 0);
            $rows = AdmissionList::admitted($pv);
            if (!$rows) continue;
            $students = array();
            foreach ($rows as $row) $students[] = array($row['rank'], $row['student']->matricule,
                trim($row['student']->nom.' '.$row['student']->prenom), number_format($row['average20'], 2, ',', ''), $row['credits'], 'Admis');
            $groups[] = array('filiere' => $class->filiere, 'specialite' => $class->specialite, 'niveau' => $class->niveau,
                'effectif' => count($pv['rows']), 'credits' => $pv['credits'], 'rows' => $students);
            $total += count($students);
        }
        $logo = null;
        $logoPath = $campus && $campus->logo ? $campus->logo : 'img/azimut.png';
        if ($logoPath) {
            $root = realpath(public_path()); $path = realpath(public_path(ltrim($logoPath, '/\\')));
            if ($path && strpos($path, $root.DIRECTORY_SEPARATOR) === 0 && is_readable($path)) {
                $info = @getimagesize($path);
                if ($info && in_array($info['mime'], array('image/png', 'image/jpeg'))) $logo = 'data:'.$info['mime'].';base64,'.base64_encode(file_get_contents($path));
            }
        }
        $payload = array('cycle' => $cycle->nom, 'annee' => $year, 'session' => $this->sessionsList()[$session],
            'campus' => $campus ? $campus->nom : session('nom_etab'), 'adresse' => $campus ? trim($campus->localisation.' '.$campus->ville.' '.$campus->telephone) : '',
            'logo' => $logo, 'groups' => $groups);
        return view('eleves.admissions.report', array('payload' => $payload, 'total' => $total));
    }
}
