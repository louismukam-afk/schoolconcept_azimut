<?php namespace gesecole\Services;

class SoaPvSummary
{
    public static function build($transcripts)
    {
        $summaries = array(); $averages = array();
        foreach ($transcripts as $transcript) {
            $validated = 0; $hasNotes = false; $seen = array(); $subjects = array();
            foreach ($transcript->matieres as $subject) {
                $subjects[$subject->id_matiere] = $subject;
                if (isset($seen[$subject->id_matiere])) continue;
                $seen[$subject->id_matiere] = true;
                if (!is_numeric($subject->note)) continue;
                $hasNotes = true;
                if ((float) $subject->note >= 50) $validated += (float) $subject->credit;
            }
            $average = $hasNotes && is_numeric($transcript->mgp) ? round($transcript->mgp / 5, 2) : null;
            $summaries[$transcript->id_eleve] = (object) array('average'=>$average,
                'mgp'=>$average !== null ? round($transcript->mgp / 25, 2) : null,
                'validated'=>$validated, 'admitted'=>$average !== null && $transcript->mgp >= 50,
                'rank'=>null, 'subjects'=>$subjects);
            if ($average !== null) $averages[] = $average;
        }
        rsort($averages, SORT_NUMERIC);
        foreach ($summaries as $summary) {
            if ($summary->average !== null) $summary->rank = array_search($summary->average, $averages, true) + 1;
        }
        return $summaries;
    }
}
