<?php namespace gesecole\Services;

class DeliberationReport
{
    public static function exams()
    {
        return array(
            'tous' => 'Toutes les sessions',
            'fin_semestre_1' => 'Fin de semestre 1',
            'fin_semestre_2' => 'Fin de semestre 2',
            'rattrapage_1' => 'Rattrapage 1',
            'rattrapage_2' => 'Rattrapage 2',
            'fin_semestre' => 'Fins de semestre 1 et 2',
            'rattrapage' => 'Rattrapages 1 et 2'
        );
    }

    public static function examSequences($exam)
    {
        $sequences = array('tous' => array(3, 4, 5, 6),
            'fin_semestre_1' => array(3), 'fin_semestre_2' => array(4),
            'rattrapage_1' => array(5), 'rattrapage_2' => array(6),
            'fin_semestre' => array(3, 4), 'rattrapage' => array(5, 6));
        if (!isset($sequences[$exam])) throw new \InvalidArgumentException('Examen inconnu.');
        return $sequences[$exam];
    }

    public static function types()
    {
        return array(
            'matiere_note' => 'Par matière',
            'credit' => 'Par crédits',
            'ue_note' => 'Par module / UE',
            'matiere_credit' => 'Par matières et crédits',
            'ue_credit' => 'Par UE et crédits',
            'individuelle' => 'Délibération individuelle'
        );
    }

    public static function trace($details)
    {
        if (preg_match('/^deliberation criteres (matiere_note|credit|ue_note|matiere_credit|ue_credit) vers ([0-9.,]+)\/20(?:; avant ([0-9.,]+)\/20)?$/', trim($details), $matches)) {
            return array('type' => $matches[1], 'target' => (float) str_replace(',', '.', $matches[2]),
                'before' => isset($matches[3]) ? (float) str_replace(',', '.', $matches[3]) : null);
        }
        if (preg_match('/^deliberation (semestre|rattrapage) vers ([0-9.,]+)\/20(?:; avant ([0-9.,]+)\/20)?$/', trim($details), $matches)) {
            return array('type' => 'individuelle', 'target' => (float) str_replace(',', '.', $matches[2]),
                'before' => isset($matches[3]) ? (float) str_replace(',', '.', $matches[3]) : null);
        }
        return null;
    }

    public static function withBefore($details, $before)
    {
        return $before === null ? $details : $details.'; avant '.number_format((float) $before, 2, '.', '').'/20';
    }

    public static function build($rows, $typeFilter = '')
    {
        $groups = array();
        $students = array();
        $subjects = array();
        $count = 0;
        foreach ($rows as $row) {
            $trace = self::trace($row->edition_details);
            if (!$trace || ($typeFilter !== '' && $trace['type'] !== $typeFilter)) continue;
            $key = $row->id_cycle.'-'.$row->id_nniveau.'-'.$row->id_filiere.'-'.$row->id_classe;
            if (!isset($groups[$key])) {
                $groups[$key] = array('cycle' => $row->cycle_nom, 'niveau' => $row->niveau_nom,
                    'filiere' => $row->filiere_nom, 'specialite' => $row->classe_nom,
                    'types' => array(), 'students' => array(), 'subjects' => array(), 'count' => 0);
            }
            $group =& $groups[$key];
            if (!isset($group['types'][$trace['type']])) {
                $group['types'][$trace['type']] = array('subjects' => array(), 'students' => array(), 'count' => 0);
            }
            $type =& $group['types'][$trace['type']];
            $subjectKey = $row->id_classe_matiere.'-'.$row->semestre;
            if (!isset($type['subjects'][$subjectKey])) {
                $type['subjects'][$subjectKey] = array('nom' => $row->matiere_nom, 'semestre' => $row->semestre, 'rows' => array());
            }
            $row->target20 = $trace['target'];
            $row->before20 = $trace['before'];
            $examKeys = array(3 => 'fin_semestre_1', 4 => 'fin_semestre_2', 5 => 'rattrapage_1', 6 => 'rattrapage_2');
            $examLabels = self::exams();
            $row->exam_label = isset($examKeys[$row->sequence]) ? $examLabels[$examKeys[$row->sequence]] : 'Examen inconnu';
            $type['subjects'][$subjectKey]['rows'][] = $row;
            $type['students'][$row->id_eleve] = true;
            $type['count']++;
            $group['students'][$row->id_eleve] = true;
            $group['subjects'][$subjectKey] = true;
            $group['count']++;
            $students[$row->id_eleve] = true;
            $subjects[$subjectKey] = true;
            $count++;
            unset($group, $type);
        }
        return array('groups' => $groups, 'students' => count($students), 'subjects' => count($subjects), 'count' => $count);
    }
}
