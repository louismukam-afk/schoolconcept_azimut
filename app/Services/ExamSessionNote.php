<?php namespace gesecole\Services;

class ExamSessionNote
{
    public static function phases($session, $semester)
    {
        $result = array();
        foreach (array(1=>array('cc1','CC 1',1),7=>array('tpe1','TPE 1',1),3=>array('sn1','Session normale 1',1),5=>array('ratrappage1','Rattrapage 1',1),
            2=>array('cc2','CC 2',2),8=>array('tpe2','TPE 2',2),4=>array('sn2','Session normale 2',2),6=>array('ratrappage2','Rattrapage 2',2)) as $sequence=>$phase) {
            if ((int)$semester > 0 && (((int)$semester - 1) % 2 + 1) !== $phase[2]) continue;
            $weight = isset($session->{$phase[0]}) ? (float)$session->{$phase[0]} : 0;
            $usesNormalWeight = false;
            if ($weight <= 0 && in_array($sequence,array(5,6))) {
                $normalField = $sequence === 5 ? 'sn1' : 'sn2';
                $weight = isset($session->$normalField) ? (float)$session->$normalField : 0;
                $usesNormalWeight = $weight > 0;
            }
            if ($weight <= 0) continue;
            $result[$sequence] = (object)array('sequence'=>$sequence,'label'=>$phase[1],'weight'=>$weight,'semester'=>$phase[2],'usesNormalWeight'=>$usesNormalWeight);
        }
        return $result;
    }

    public static function weightedMark($mark, $weight)
    {
        if (!is_numeric($mark) || $mark < 0 || $mark > 20 || $weight <= 0 || $weight > 100) {
            throw new \InvalidArgumentException('Note ou pondération invalide.');
        }
        return round((float)$mark * (float)$weight / 20, 4);
    }
}
