<?php namespace gesecole\Services;

class HndPv
{
    public static function markClass($value)
    {
        if ($value === null || !is_numeric($value)) return '';
        if ((float) $value >= 50) return 'mark-green';
        if ((float) $value >= 40) return 'mark-yellow';
        return 'mark-red';
    }

    public static function paperSizes()
    {
        return array('a4' => 'A4 paysage', 'a3' => 'A3 paysage');
    }

    public static function layout($paper, $courseCount, $moduleCount, $annual = false)
    {
        if (!isset(self::paperSizes()[$paper])) throw new \InvalidArgumentException('Format de papier inconnu.');
        $subjectsPerBlock = $paper === 'a4' ? 4 : 6;
        $columns = max(1, $annual ? $courseCount : min($courseCount, $subjectsPerBlock) * 4);
        $width = $paper === 'a4' ? 285 : 408;
        $registration = $paper === 'a4' ? 85 : 105;
        $name = $paper === 'a4' ? 125 : 155;
        $summary = $paper === 'a4' ? 50 : 60;
        $available = $width * 3.78 - (20 + $registration + $name + 40 + 3 * $summary + 28);
        $font = .9 * min($paper === 'a4' ? 10 : 12, max(5.5, ($available / $columns - 4) / 2.8));
        return array('paper' => strtoupper($paper), 'width' => $width, 'font' => round($font, 1),
            'subjects_per_block' => $subjectsPerBlock,
            'name_font' => $paper === 'a4' ? 8.2 : 10, 'legend_font' => $paper === 'a4' ? 8.2 : 10,
            'registration_width' => $registration, 'name_width' => $name, 'summary_width' => $summary,
            'code_font' => round(min($font, max(5, $available / $columns / 4.5)), 1));
    }

    public static function exams()
    {
        return array('semester_1' => array('semester' => 1, 'sequence' => 3, 'label' => 'Examen de fin de semestre 1', 'title' => 'FINAL RESULTS — SEMESTER 1'),
            'semester_2' => array('semester' => 2, 'sequence' => 4, 'label' => 'Examen de fin de semestre 2', 'title' => 'FINAL RESULTS — SEMESTER 2'),
            'resit_1' => array('semester' => 1, 'sequence' => 5, 'label' => 'Rattrapage 1', 'title' => 'RESIT RESULTS — SEMESTER 1'),
            'resit_2' => array('semester' => 2, 'sequence' => 6, 'label' => 'Rattrapage 2', 'title' => 'RESIT RESULTS — SEMESTER 2'),
            'annual' => array('semester' => 0, 'sequence' => 9, 'label' => 'Procès-verbal annuel', 'title' => 'ANNUAL FINAL RESULTS'));
    }

    public static function marks($map, $semester, $resit)
    {
        $cc = $semester === 1 ? 1 : 2;
        $normal = $semester === 1 ? 3 : 4;
        $exam = $resit ? ($semester === 1 ? 5 : 6) : $normal;
        $tpe = $semester === 1 ? 7 : 8;
        $ca = isset($map[$cc]) || isset($map[$tpe]) ? (isset($map[$cc]) ? $map[$cc] : 0) + (isset($map[$tpe]) ? $map[$tpe] : 0) : null;
        $examMark = isset($map[$exam]) ? $map[$exam] : null;
        $total = $examMark === null ? null : round(($ca === null ? 0 : $ca) + $examMark, 2);
        $retained = $total;
        if ($resit && isset($map[$normal])) {
            $normalTotal = round(($ca === null ? 0 : $ca) + $map[$normal], 2);
            if ($retained === null || $normalTotal > $retained) $retained = $normalTotal;
        }
        return array('ca' => $ca, 'exam' => $examMark, 'total' => $total, 'retained' => $retained);
    }

    private static function noteMatchesSubject($note, $subject)
    {
        return (int) $note->id_matiere === (int) $subject->id_matiere
            && (empty($note->id_classe_matiere) || (int) $note->id_classe_matiere === (int) $subject->id_classe_matiere)
            && (empty($note->id_session) || in_array((int) $note->id_session, $subject->sessions));
    }

    public static function build($students, $subjects, $notes, $examKey, $start = '', $end = '', $moduleLinks = array(), $annualSemester = false)
    {
        if ($examKey === 'annual') return self::buildAnnual($students, $subjects, $notes, $start, $end);
        $options = self::exams();
        if (!isset($options[$examKey])) throw new \InvalidArgumentException('Examen HND inconnu.');
        $option = $options[$examKey];
        // Same semester groups as the note-entry screen: CC, normal exam, resit, TP.
        $sequences = $option['semester'] === 1 ? array(1, 3, 5, 7) : array(2, 4, 6, 8);
        $subjectSequences = $option['sequence'] >= 5 ? array($option['sequence'])
            : ($option['semester'] === 1 ? array(1, 3, 7) : array(2, 4, 8));
        if ($annualSemester) $subjectSequences = $sequences;
        $studentIds = array();
        foreach ($students as $student) $studentIds[(int) $student->id] = true;
        $notes = array_values(array_filter($notes, function ($note) use ($sequences, $studentIds) {
            return isset($studentIds[(int) $note->id_eleve]) && in_array((int) $note->sequence, $sequences)
                && is_numeric($note->note);
        }));
        $subjects = array_values(array_filter($subjects, function ($subject) use ($notes, $subjectSequences) {
            foreach ($notes as $note) {
                if (in_array((int) $note->sequence, $subjectSequences) && self::noteMatchesSubject($note, $subject)) return true;
            }
            return false;
        }));
        $index = array();
        foreach ($notes as $note) $index[$note->id_eleve][] = $note;
        $rows = array();
        $modules = array();
        foreach ($subjects as $position => $subject) {
            if (!isset($moduleLinks[$subject->id_classe_matiere])) continue;
            $module = $moduleLinks[$subject->id_classe_matiere];
            $key = $module->id_module;
            if (!isset($modules[$key])) $modules[$key] = array('id' => $key, 'code' => trim((string) $module->code) !== '' ? $module->code : 'UE-'.$key,
                'nom' => $module->nom, 'nom_anglais' => $module->nom_anglais, 'positions' => array(), 'credits' => 0);
            $modules[$key]['positions'][] = $position;
            $modules[$key]['credits'] += (float) $subject->credit;
        }
        $totalCredits = 0;
        foreach ($subjects as $subject) $totalCredits += (float) $subject->credit;
        foreach ($students as $student) {
            $marks = array(); $credits = 0; $hasExamInPeriod = false;
            foreach ($subjects as $subject) {
                $map = array();
                foreach (isset($index[$student->id]) ? $index[$student->id] : array() as $note) {
                    if (!self::noteMatchesSubject($note, $subject)) continue;
                    if ((int) $note->sequence === $option['sequence'] || ($annualSemester && (int) $note->sequence === $option['sequence'] + 2)) {
                        $date = substr((string) $note->updated_at, 0, 10);
                        if (($start !== '' || $end !== '') && $date === '') continue;
                        if (($start !== '' && $date < $start) || ($end !== '' && $date > $end)) continue;
                        $hasExamInPeriod = true;
                    }
                    $map[(int) $note->sequence] = (float) $note->note;
                }
                $mark = self::marks($map, $option['semester'], $annualSemester || $option['sequence'] >= 5);
                $mark['credit'] = $mark['retained'] !== null && $mark['retained'] >= 50 ? (float) $subject->credit : 0;
                $credits += $mark['credit'];
                $marks[] = $mark;
            }
            if (($start !== '' || $end !== '') && !$hasExamInPeriod) continue;
            $moduleMarks = array();
            foreach ($modules as $module) {
                $weighted = 0; $complete = true;
                foreach ($module['positions'] as $position) {
                    if ($marks[$position]['retained'] === null) $complete = false;
                    else $weighted += $marks[$position]['retained'] * (float) $subjects[$position]->credit;
                }
                $moduleMarks[] = $complete && $module['credits'] > 0 ? round($weighted / $module['credits'], 2) : null;
            }
            $weightedTotal = 0; $complete = true;
            foreach ($marks as $position => $mark) {
                if ($mark['retained'] === null) $complete = false;
                else $weightedTotal += $mark['retained'] * (float) $subjects[$position]->credit;
            }
            $average = $complete && $totalCredits > 0 ? round($weightedTotal / $totalCredits, 2) : null;
            $validated = $totalCredits > 0 && $credits >= $totalCredits;
            $rows[] = array('student' => $student, 'marks' => $marks, 'credits' => $credits,
                'modules' => $moduleMarks, 'average' => $average,
                'average20' => $validated && $average !== null ? round($average / 5, 2) : null,
                'rank' => null, 'decision' => $validated ? 'V' : 'NV');
        }
        $rows = self::rankRows($rows);
        return array('subjects' => $subjects, 'modules' => array_values($modules), 'rows' => $rows, 'credits' => $totalCredits, 'exam' => $option);
    }

    private static function rankRows($rows)
    {
        $ranking = array();
        foreach ($rows as $index => $row) {
            if ($row['average'] !== null) $ranking[] = array('index' => $index, 'score' => round($row['average'] / 5, 2));
        }
        usort($ranking, function ($a, $b) {
            if ($a['score'] == $b['score']) return 0;
            return $a['score'] > $b['score'] ? -1 : 1;
        });
        $lastScore = null; $rank = 0;
        foreach ($ranking as $position => $entry) {
            if ($lastScore === null || $entry['score'] != $lastScore) $rank = $position + 1;
            $rows[$entry['index']]['rank'] = $rank;
            $lastScore = $entry['score'];
        }
        return $rows;
    }

    public static function buildAnnual($students, $subjects, $notes, $start = '', $end = '')
    {
        $terms = array(); $annualSubjects = array(); $totalCredits = 0; $completeYear = true;
        foreach (array(1, 2) as $semester) {
            $pv = self::build($students, $subjects, $notes, 'semester_'.$semester, $start, $end, array(), true);
            if (!$pv['subjects']) $completeYear = false;
            foreach ($pv['subjects'] as $subject) {
                $copy = clone $subject; $copy->annual_semester = $semester;
                $annualSubjects[] = $copy;
            }
            $totalCredits += $pv['credits'];
            $rows = array(); foreach ($pv['rows'] as $row) $rows[$row['student']->id] = $row;
            $terms[$semester] = array('subjects' => $pv['subjects'], 'rows' => $rows);
        }
        $annualRows = array();
        foreach ($students as $student) {
            $marks = array(); $credits = 0; $included = false;
            foreach ($terms as $term) {
                if (isset($term['rows'][$student->id])) {
                    $row = $term['rows'][$student->id];
                    $marks = array_merge($marks, $row['marks']);
                    $credits += $row['credits']; $included = true;
                } else {
                    foreach ($term['subjects'] as $subject) $marks[] = array('ca' => null, 'exam' => null, 'total' => null, 'retained' => null, 'credit' => 0);
                }
            }
            if (!$included) continue;
            $complete = $completeYear; $weighted = 0; $allPassed = true;
            foreach ($marks as $position => $mark) {
                if ($mark['retained'] === null) $complete = false;
                else $weighted += $mark['retained'] * (float) $annualSubjects[$position]->credit;
                if ($mark['retained'] === null || $mark['retained'] < 50) $allPassed = false;
            }
            $average = $complete && $totalCredits > 0 ? round($weighted / $totalCredits, 2) : null;
            $validated = $complete && $allPassed && $totalCredits > 0 && $credits >= $totalCredits;
            $annualRows[] = array('student' => $student, 'marks' => $marks, 'modules' => array(), 'credits' => $credits,
                'average' => $average, 'average20' => $validated && $average !== null ? round($average / 5, 2) : null,
                'rank' => null, 'decision' => $validated ? 'ADMIS' : 'ECHOUE');
        }
        $exams = self::exams();
        return array('subjects' => $annualSubjects, 'modules' => array(), 'rows' => self::rankRows($annualRows),
            'credits' => $totalCredits, 'exam' => $exams['annual'], 'annual' => true, 'complete_year' => $completeYear);
    }
}
