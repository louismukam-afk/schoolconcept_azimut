<?php namespace gesecole\Services;

use Illuminate\Support\Facades\DB;

class ProgrammeReferenceImport
{
    public static function catalogue()
    {
        $path = dirname(dirname(__DIR__)).'/database/data/programme_references_20261022.json';
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
        if (!preg_match('/(?:^| )(1|2|I|II)$/', self::normalize($name), $m)) return null;
        return $m[1] === 'I' ? 1 : ($m[1] === 'II' ? 2 : (int) $m[1]);
    }

    private static function isParentCode($existing, $reference)
    {
        $reference = str_replace(' ', '', self::normalize($reference));
        $existing = str_replace(' ', '', self::normalize($existing));
        return preg_match('/^([A-Z]+)([0-9]{3})[0-9]$/', $reference, $m) && $existing === $m[1].$m[2];
    }

    public static function databasePlan()
    {
        return self::plan(DB::table('classe_matiere as cm')->join('matiere as m', 'm.id', '=', 'cm.id_matiere')
            ->select('cm.id', 'cm.id_classe', 'cm.id_cycle', 'cm.id_nniveau', 'cm.semestre', 'cm.type', 'cm.id_code', 'm.nom', 'm.nom_anglais')->get(),
            DB::table('classe')->get(), DB::table('cycle')->get(), DB::table('n_niveaus')->get(),
            DB::table('codages')->get(), self::catalogue());
    }

    public static function plan($programmes, $classes, $cycles, $levels, $codages, $catalogue)
    {
        $classes = self::index($classes); $cycles = self::index($cycles); $levels = self::index($levels);
        $codes = self::index($codages); $result = array(); $targets = array();
        foreach ($programmes as $row) {
            if (!isset($classes[$row->id_classe], $cycles[$row->id_cycle], $levels[$row->id_nniveau])) continue;
            $class = $classes[$row->id_classe]; $cycle = $cycles[$row->id_cycle]; $level = $levels[$row->id_nniveau];
            if ((int) $level->id_cycle !== (int) $cycle->id) continue;
            foreach ($catalogue['profiles'] as $profile) {
                $classCodes = array_map(array(__CLASS__, 'normalize'), $profile['class_codes']);
                $classNames = array_map(array(__CLASS__, 'normalize'), $profile['class_names']);
                if (self::normalize($cycle->nom) !== $profile['cycle'] || self::level($level->nom) !== $profile['level']
                    || !in_array(self::normalize($class->code), $classCodes, true)
                    || !in_array(self::normalize($class->nom), $classNames, true)) continue;
                $semester = (int) $row->semestre;
                $semester = $semester > 0 && $semester <= 4 ? (($semester - 1) % 2 + 1) : 0;
                $entry = array('id'=>$row->id, 'profile'=>$profile['id'], 'specialty'=>$class->nom,
                    'class_id'=>$class->id, 'cycle'=>$cycle->nom, 'level'=>$level->nom, 'semester'=>$row->semestre,
                    'subject'=>$row->nom, 'old_code'=>$row->id_code, 'old_type'=>$row->type,
                    'new_code'=>$row->id_code, 'new_type'=>$row->type, 'status'=>'unmatched_subject');
                $matches = array();
                foreach ($profile['subjects'] as $subject) {
                    if ($subject['semester'] !== $semester) continue;
                    $name = self::normalize($subject['name']);
                    if ($name === self::normalize($row->nom) || (!empty($row->nom_anglais) && $name === self::normalize($row->nom_anglais))) {
                        $key = $subject['code'].'|'.var_export($subject['type'], true);
                        $matches[$key] = $subject;
                    }
                }
                if (!$semester) $entry['status'] = 'missing_semester';
                elseif (count($matches) > 1) $entry['status'] = 'source_conflict';
                elseif (count($matches) === 1) {
                    $subject = reset($matches);
                    $entry['reference_code'] = $subject['code']; $entry['reference_type'] = $subject['type'];
                    $entry['source'] = $subject['source'];
                    $sourceConflict = false;
                    foreach ($profile['subjects'] as $other) {
                        if ($other['semester'] === $semester && $other['code'] === $subject['code']
                            && self::normalize($other['name']) !== self::normalize($subject['name'])) $sourceConflict = true;
                    }
                    $existing = trim((string) $row->id_code);
                    if (ctype_digit($existing) && isset($codes[(int) $existing])) $existing = $codes[(int) $existing]->nom;
                    $parentCode = self::isParentCode($existing, $subject['code']);
                    if (!$subject['code']) $entry['status'] = 'missing_source_code';
                    elseif ($sourceConflict) $entry['status'] = 'source_conflict';
                    elseif ($existing !== '' && !$parentCode && str_replace(' ', '', self::normalize($existing)) !== str_replace(' ', '', self::normalize($subject['code']))) {
                        $entry['status'] = 'existing_code_conflict';
                    } elseif ($subject['type'] !== null && $row->type !== null && (int) $row->type !== 0 && (int) $row->type !== $subject['type']) {
                        $entry['status'] = 'existing_category_conflict';
                    } else {
                        $entry['new_code'] = trim((string) $row->id_code) === '' || $parentCode ? $subject['code'] : $row->id_code;
                        $entry['new_type'] = $subject['type'] === null ? $row->type : $subject['type'];
                        $entry['status'] = (string) $entry['new_code'] === (string) $row->id_code
                            && (string) $entry['new_type'] === (string) $row->type ? 'unchanged' : 'update';
                        $key = implode('|', array($profile['id'], $class->id, $cycle->id, $level->id, $semester, $subject['code']));
                        $targets[$key][] = count($result);
                    }
                }
                $result[] = $entry;
            }
        }
        foreach ($targets as $indices) {
            if (count($indices) > 1) foreach ($indices as $index) $result[$index]['status'] = 'ambiguous_programme';
        }
        $summary = array();
        foreach ($result as $entry) {
            if (!isset($summary[$entry['status']])) $summary[$entry['status']] = 0;
            $summary[$entry['status']]++;
        }
        return array('summary'=>$summary, 'rows'=>$result);
    }
}
