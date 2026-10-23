<?php namespace gesecole\Services;

class AdmissionList
{
    public static function admitted($pv)
    {
        $rows = array();
        foreach ($pv['rows'] as $row) {
            // Check every course, including courses carrying zero credits.
            if ($pv['credits'] <= 0 || $row['average20'] === null) continue;
            $passed = count($row['marks']) > 0;
            foreach ($row['marks'] as $mark) {
                if ($mark['retained'] === null || $mark['retained'] < 50) $passed = false;
            }
            if ($passed && in_array($row['decision'], array('V', 'ADMIS'))) $rows[] = $row;
        }
        usort($rows, function ($a, $b) {
            if ($a['rank'] != $b['rank']) return $a['rank'] < $b['rank'] ? -1 : 1;
            return strcmp($a['student']->nom.' '.$a['student']->prenom, $b['student']->nom.' '.$b['student']->prenom);
        });
        return $rows;
    }
}
