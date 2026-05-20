<?php namespace gesecole\Services;

class Utils {
  /**
   * Generate a list of school years around the from value
   * @param int $from [Optional] The base value for generating school years, default current year
   * @param int $radius [Optional] The number of school years to generate around the base value, before and after
   * @return array An array of school years
   */
  public static function getSchoolYears($from = 0, $radius = 5) {
    if (intval($from) < 1900) {
      $from = intval(date('Y'));
    } else {
      $from = intval($from);
    }

    $years = [];

    for ($year = $from - $radius; $year <= $from + $radius; $year++) {
      array_push($years, $year . '-' . ($year + 1));
    }

    return $years;
  }
}