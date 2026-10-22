<?php namespace gesecole\Services;

use Illuminate\Support\Facades\DB;

class ProgrammeReferenceImport
{
    public static function catalogue($file = 'programme_references_20261022.json')
    {
        $path = dirname(dirname(__DIR__)).'/database/data/'.$file;
        $data = json_decode(file_get_contents($path), true);
        if (!$data || !isset($data['profiles'])) throw new \RuntimeException('Catalogue des programmes invalide.');
        return $data;
    }

    public static function normalize($value)
    {
        $value = html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8');
        for ($i = 0; $i < 2 && preg_match('/[\x{00C3}\x{00C2}\x{00E2}]/u', $value); $i++) {
            $decoded = @iconv('UTF-8', 'Windows-1252', $value);
            if ($decoded === false || !preg_match('//u', $decoded)) break;
            $value = $decoded;
        }
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        return trim(preg_replace('/[^A-Z0-9]+/', ' ', strtoupper($ascii === false ? $value : $ascii)));
    }

    private static function index($rows)
    {
        $result = array();
        foreach ($rows as $row) $result[$row->id] = $row;
        return $result;
    }

    private static function level($name)
    {
        if (!preg_match('/(?:^| )(1|2|3|I|II|III)$/', self::normalize($name), $m)) return null;
        $roman = array('I'=>1,'II'=>2,'III'=>3);
        return isset($roman[$m[1]]) ? $roman[$m[1]] : (int) $m[1];
    }

    private static function isParentCode($existing, $reference)
    {
        $reference = str_replace(' ', '', self::normalize($reference));
        $existing = str_replace(' ', '', self::normalize($existing));
        return preg_match('/^([A-Z]+)([0-9]{3})[0-9]$/', $reference, $m) && $existing === $m[1].$m[2];
    }

    public static function databasePlan($catalogue = null)
    {
        return self::plan(DB::table('classe_matiere as cm')->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->select('cm.id', 'cm.id_classe', 'cm.id_cycle', 'cm.id_nniveau', 'cm.semestre', 'cm.type', 'cm.id_code', 'm.nom', 'm.nom_anglais')->get(),
            DB::table('classe')->get(), DB::table('cycle')->get(), DB::table('n_niveaus')->get(),
            DB::table('codages')->get(), $catalogue === null ? self::catalogue() : $catalogue);
    }

    public static function plan($programmes, $classes, $cycles, $levels, $codages, $catalogue)
    {
        $classes = self::index($classes); $cycles = self::index($cycles); $levels = self::index($levels);
        $codes = self::index($codages); $result = array(); $targets = array();
        $mode = isset($catalogue['mode']) ? $catalogue['mode'] : 'combined';
        if (!in_array($mode, array('codes','categories','combined'), true)) throw new \InvalidArgumentException('Mode import invalide.');
        $profileCounts = array();
        $prepared = array(); $classMetadata = array(); $cycleNames = array(); $levelNumbers = array();
        foreach ($classes as $id=>$class) $classMetadata[$id] = array(self::normalize($class->code), self::normalize($class->nom));
        foreach ($cycles as $id=>$cycle) $cycleNames[$id] = self::normalize($cycle->nom);
        foreach ($levels as $id=>$level) $levelNumbers[$id] = self::level($level->nom);
        foreach ($catalogue['profiles'] as $profile) {
            $profile['normalized_codes'] = array_map(array(__CLASS__, 'normalize'), $profile['class_codes']);
            $profile['normalized_names'] = array_map(array(__CLASS__, 'normalize'), $profile['class_names']);
            $profile['subject_index'] = array(); $profile['code_names'] = array();
            foreach ($profile['subjects'] as $subject) {
                $name = self::normalize($subject['name']);
                $profile['subject_index'][$subject['semester']][$name][] = $subject;
                $profile['code_names'][$subject['semester']][$subject['code']][$name] = true;
            }
            $prepared[] = $profile;
        }
        foreach ($programmes as $row) {
            if (!isset($classes[$row->id_classe], $cycles[$row->id_cycle], $levels[$row->id_nniveau])) continue;
            $class = $classes[$row->id_classe]; $cycle = $cycles[$row->id_cycle]; $level = $levels[$row->id_nniveau];
            if ((int) $level->id_cycle !== (int) $cycle->id) continue;
            foreach ($prepared as $profile) {
                if ($cycleNames[$cycle->id] !== $profile['cycle'] || $levelNumbers[$level->id] !== $profile['level']
                    || !in_array($classMetadata[$class->id][0], $profile['normalized_codes'], true)
                    || !in_array($classMetadata[$class->id][1], $profile['normalized_names'], true)) continue;
                $profileCounts[$profile['id']] = true;
                $semester = (int) $row->semestre;
                $maxSemester = isset($catalogue['version']) && $catalogue['version'] >= 2 ? 6 : 4;
                $semester = $semester > 0 && $semester <= $maxSemester ? (($semester - 1) % 2 + 1) : 0;
                $entry = array('id'=>$row->id, 'profile'=>$profile['id'], 'specialty'=>$class->nom,
                    'class_id'=>$class->id, 'cycle'=>$cycle->nom, 'level'=>$level->nom, 'semester'=>$row->semestre,
                    'subject'=>$row->nom, 'old_code'=>$row->id_code, 'old_type'=>$row->type,
                    'new_code'=>$row->id_code, 'new_type'=>$row->type, 'status'=>'unmatched_subject');
                $matches = array();
                foreach (array_unique(array(self::normalize($row->nom), self::normalize($row->nom_anglais))) as $name) {
                    if (!isset($profile['subject_index'][$semester][$name])) continue;
                    foreach ($profile['subject_index'][$semester][$name] as $subject) {
                        $key = $subject['code'].'|'.var_export($subject['type'], true);
                        $matches[$key] = $subject;
                    }
                }
                if (!$semester) $entry['status'] = 'missing_semester';
                elseif (count($matches) > 1) $entry['status'] = 'source_conflict';
                elseif (count($matches) === 1) {
                    $subject = reset($matches);
                    $entry['reference_code'] = $subject['code']; $entry['reference_type'] = $subject['type'];
                    $entry['category_basis'] = isset($subject['category_basis']) ? $subject['category_basis'] : null;
                    $entry['source'] = $subject['source'];
                    $sourceConflict = count($profile['code_names'][$semester][$subject['code']]) > 1;
                    if (!$sourceConflict && $subject['code'] !== '') {
                        $key = implode('|', array($profile['id'], $class->id, $cycle->id, $level->id, $semester, $subject['code']));
                        $targets[$key][] = count($result);
                    }
                    $existing = trim((string) $row->id_code);
                    if (ctype_digit($existing) && isset($codes[(int) $existing])) $existing = $codes[(int) $existing]->nom;
                    $parentCode = self::isParentCode($existing, $subject['code']);
                    if (!$subject['code']) $entry['status'] = 'missing_source_code';
                    elseif ($sourceConflict) $entry['status'] = 'source_conflict';
                    elseif ($existing !== '' && !$parentCode && str_replace(' ', '', self::normalize($existing)) !== str_replace(' ', '', self::normalize($subject['code']))) {
                        $entry['status'] = 'existing_code_conflict';
                    } elseif ($mode !== 'codes' && $subject['type'] !== null && $row->type !== null && (int) $row->type !== 0 && (int) $row->type !== $subject['type']) {
                        $entry['status'] = 'existing_category_conflict';
                    } elseif ($mode === 'categories' && $subject['type'] === null) {
                        $entry['status'] = 'category_unclassified';
                    } else {
                        $entry['new_code'] = $mode !== 'categories' && (trim((string) $row->id_code) === '' || $parentCode) ? $subject['code'] : $row->id_code;
                        $entry['new_type'] = $mode === 'codes' || $subject['type'] === null ? $row->type : $subject['type'];
                        $entry['status'] = (string) $entry['new_code'] === (string) $row->id_code
                            && (string) $entry['new_type'] === (string) $row->type ? 'unchanged' : 'update';
                    }
                }
                $result[] = $entry;
            }
        }
        foreach ($targets as $indices) {
            if (count($indices) > 1) foreach ($indices as $index) {
                if (in_array($result[$index]['status'], array('update','unchanged'), true)) $result[$index]['status'] = 'ambiguous_programme';
            }
        }
        $summary = array();
        foreach ($result as $entry) {
            if (!isset($summary[$entry['status']])) $summary[$entry['status']] = 0;
            $summary[$entry['status']]++;
        }
        $missing = array();
        foreach ($catalogue['profiles'] as $profile) if (!isset($profileCounts[$profile['id']])) $missing[] = $profile['id'];
        return array('summary'=>$summary, 'rows'=>$result, 'profiles_without_programme'=>$missing);
    }
}
