<?php namespace gesecole\Services;

class HndAnnualTranscript extends BtsAnnualTranscript
{
    // Reference labels/codes from the supplied HND Accountancy template.
    // This repairs presentation of legacy programmes whose types are all zero;
    // configured mixed categories, credits and marks remain authoritative.
    public static function presentationRows($rows, $semester, $department)
    {
        if (!preg_match('/ACCOUNTANCY|COMPTABILIT/i', self::displayText($department))) return $rows;
        $reference = array(
            'GENERAL MATHEMATICS' => array(0, '1111', '1211'),
            'GENERAL COMPUTING' => array(0, '1112', '1212'),
            'FINANCIAL MATHEMATICS' => array(0, '1121', '1221'),
            'STATISTICS' => array(0, '1122', '1222'),
            'DAILY OPERATIONS' => array(2, '1131', '1231'),
            'SPECIFIC OPERATIONS AND END OF YEAR ADJUSTMENT' => array(2, '1132', '1232'),
            'FULL COSTING' => array(2, '1141', '1241'),
            'MARGINAL COSTING AND THE BREAK-EVEN ANALYSIS' => array(2, '1142', '1242'),
            'INTRODUCTION TO TAXATION' => array(2, '1151', null),
            'COMPUTER ASSISTED ACCOUNTING' => array(2, '1161', '1261'),
            'INTRODUCTION TO FINANCIAL ANALYSIS' => array(2, '1162', null),
            'RESEARCH METHODOLOGY' => array(2, null, '1251'),
            'VAT AND PIT' => array(2, null, '1252'),
            'FRENCH EXPRESSION' => array(1, '1171', null),
            'ENGLISH EXPRESSION' => array(1, null, '1271'),
            'GENERAL ECONOMICS' => array(1, '1172', '1273'),
            'ECONOMY AND ORGANIZATION OF ENTERPRISES' => array(1, null, '1272'),
            'CIVIC EDUCATION AND ETHISCS' => array(1, null, null),
            'CIVIC EDUCATION AND ETHICS' => array(1, null, null),
        );
        $legacyTypes = true;
        foreach ($rows as $row) {
            if (isset($row->type) && (int) $row->type !== 0) $legacyTypes = false;
        }
        $result = array();
        foreach ($rows as $row) {
            $copy = clone $row;
            $name = strtoupper(trim(preg_replace('/\s+/', ' ', self::displayText($row->nom))));
            $name = preg_replace('/\s+(?:III|II|I|[123])$/', '', $name);
            if (isset($reference[$name])) {
                $item = $reference[$name];
                if ($legacyTypes) $copy->type = $item[0];
                $position = (int) $semester === 1 ? 1 : ((int) $semester === 2 ? 2 : null);
                if (empty($copy->code) && $position && $item[$position]) $copy->code = 'ACC '.$item[$position];
            }
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
