<?php namespace gesecole\Services;

class HndAnnualTranscript extends BtsAnnualTranscript
{
    // Programme metadata is authoritative, including for saved reports.
    // Presentation works on copies and preserves the saved marks and credits.
    public static function presentationRows($rows, $semester, $department, $programme = array(), $codages = array())
    {
        $result = array();
        foreach ($rows as $row) {
            $copy = clone $row;
            $id = isset($row->id_classe_matiere) ? $row->id_classe_matiere : null;
            if ($id && isset($programme[$id])) {
                $configured = $programme[$id];
                if (isset($configured->type) && in_array((int) $configured->type, array(0, 1, 2), true)) {
                    $copy->type = (int) $configured->type;
                }
                if (isset($configured->id_code) && trim((string) $configured->id_code) !== '') {
                    $copy->code = $configured->id_code;
                }
            }
            $copy->code = self::courseCode(isset($copy->code) ? $copy->code : '', $codages);
            $result[] = $copy;
        }
        usort($result, function ($a, $b) {
            $aCode = isset($a->code) ? trim($a->code) : '';
            $bCode = isset($b->code) ? trim($b->code) : '';
            if ($aCode === '' && $bCode !== '') return 1;
            if ($bCode === '' && $aCode !== '') return -1;
            $order = strnatcasecmp($aCode, $bCode);
            return $order ?: strcasecmp($a->nom, $b->nom);
        });
        return $result;
    }

    public static function courseCode($value, $codages = array())
    {
        $value = trim((string) $value);
        if ($value !== '' && ctype_digit($value) && isset($codages[(int) $value])) {
            return trim((string) $codages[(int) $value]);
        }
        return $value;
    }

    public static function grade($note)
    {
        if ($note === null) return array('—', null);
        foreach (array(array(80,'A',4), array(70,'B+',3.5), array(60,'B',3),
            array(55,'C+',2.5), array(50,'C',2), array(45,'D+',1.5), array(40,'D',1), array(0,'F',0)) as $grade) {
            if ($note >= $grade[0]) return array($grade[1], $grade[2]);
        }
        return array('F',0);
    }

    protected static function classification($gpa)
    {
        if ($gpa === null) return 'UNCLASSIFIED';
        if ($gpa >= 3.6) return 'FIRST CLASS';
        if ($gpa >= 3) return 'SECOND CLASS (Upper Division)';
        if ($gpa >= 2.5) return 'SECOND CLASS (Lower Division)';
        if ($gpa >= 2.25) return 'THIRD CLASS';
        if ($gpa >= 2) return 'PASS';
        return 'FAIL';
    }
}
