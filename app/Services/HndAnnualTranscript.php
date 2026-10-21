<?php namespace gesecole\Services;

class HndAnnualTranscript extends BtsAnnualTranscript
{
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
