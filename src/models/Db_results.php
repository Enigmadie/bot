<?php

namespace Bot;

class Db_results {
  public static function is_rowEmpty($result) {
    return $result->num_rows === 0;
  }

  public static function get_row_params($result, $params) {
    return $result->fetch_assoc()[$params];
  }

  public static function get_rows($result) {
    return [$result->fetch_assoc()];
  }
}
