<?php namespace gesecole\Services;

class BtsAnnualTranscript
{
    public static function displayText($text)
    {
        $text = (string) $text;
        for ($i = 0; $i < 2 && preg_match('/Ã|Â|â€/', $text); $i++) {
            $decoded = @iconv('UTF-8', 'Windows-1252', $text);
            if ($decoded === false || !preg_match('//u', $decoded)) break;
            $text = $decoded;
        }
        return $text;
    }

    public static function levelLabel($text)
    {
        $text = self::displayText($text);
        if (preg_match('/(?:^|\s)(III|II|I|[123])$/i', trim($text), $match)) {
            $labels = array('1'=>'I', '2'=>'II', '3'=>'III');
            return isset($labels[$match[1]]) ? $labels[$match[1]] : strtoupper($match[1]);
        }
        return $text;
    }

    public static function fromLegacy($transcripts)
    {
        $result = array();
        foreach ($transcripts as $transcript) {
            $semesters = array();
            foreach (array(1, 2) as $position) {
                $rows = array();
                $number = $position;
                foreach ($transcript->matieres as $subject) {
                    $semester = (int) $subject->semestre;
                    if ($semester > 0 && (($semester - 1) % 2 + 1) !== $position) continue;
                    if ($semester > 0) $number = $semester;
                    $rows[] = (object) array('id_matiere' => $subject->id_matiere,
                        'id_classe_matiere' => $subject->id_classe_matiere,
                        'code' => $subject->id_code, 'nom' => $subject->altName,
                        'type' => $subject->type, 'credit' => (float) $subject->credit,
                        'note' => is_numeric($subject->note) ? (float) $subject->note : null);
                }
                $semesters[$position] = self::summarize($rows);
                $semesters[$position]->numero = $number;
            }
            $result[] = (object) array('eleve' => $transcript->eleve, 'semestres' => (object) $semesters,
                'annuel' => self::summarize(array_merge($semesters[1]->rows, $semesters[2]->rows)));
        }
        return $result;
    }

    // Barème du modèle BTS fourni (distinct du barème universitaire existant).
    public static function grade($note)
    {
        if ($note === null) return array('—', null);
        foreach (array(array(80, 'A', 4), array(70, 'B+', 3.5), array(60, 'B', 3),
            array(55, 'C+', 2.5), array(50, 'C', 2), array(45, 'C-', 1.5),
            array(40, 'D+', 1), array(0, 'F', 0)) as $grade) {
            if ($note >= $grade[0]) return array($grade[1], $grade[2]);
        }
        return array('F', 0);
    }

    public static function groupRows($rows, $groups)
    {
        $components = array();
        foreach ($rows as $row) {
            if (isset($groups['composantes'][$row->id_classe_matiere])) {
                $components[$groups['composantes'][$row->id_classe_matiere]][] = $row;
            }
        }
        $result = array();
        foreach ($rows as $row) {
            if (isset($groups['composantes'][$row->id_classe_matiere])) continue;
            if (isset($components[$row->id_matiere])) {
                $total = 0; $credits = 0;
                foreach ($components[$row->id_matiere] as $part) {
                    if ($part->note === null) continue;
                    $total += $part->note * $part->credit;
                    $credits += $part->credit;
                }
                $row->note = $credits > 0 ? round($total / $credits, 2) : null;
            }
            $result[] = $row;
        }
        return $result;
    }

    public static function summarize($rows)
    {
        $credits = 0; $points = 0; $weighted = 0; $gradedCredits = 0; $validated = 0;
        foreach ($rows as $row) {
            $grade = static::grade($row->note);
            $row->grade = $grade[0]; $row->point = $grade[1];
            $row->weighted = $grade[1] === null ? null : $grade[1] * $row->credit;
            $credits += $row->credit;
            if ($row->note === null) continue;
            $points += $row->point; $weighted += $row->weighted; $gradedCredits += $row->credit;
            if ($row->note >= 50) $validated += $row->credit;
        }
        $mgp = $gradedCredits > 0 ? $weighted / $gradedCredits : null;
        $classification = static::classification($mgp);
        return (object) compact('rows', 'credits', 'points', 'weighted', 'gradedCredits', 'validated', 'mgp', 'classification');
    }

    protected static function classification($mgp)
    {
        return $mgp === null ? 'Non classé' : ($mgp >= 3.6 ? 'Première classe' :
            ($mgp >= 3 ? 'Deuxième classe (Division supérieure)' :
            ($mgp >= 2.5 ? 'Deuxième CLASSE (Division Inférieure)' : ($mgp >= 2 ? 'Troisième classe' : 'Non admis'))));
    }
}
