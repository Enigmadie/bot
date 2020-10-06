<?php

namespace Bot;

use Bot\Db_actions;
use Bot\Db_results;

class User {
  private static $table = 'user';

  public function register($id) {
    Db_actions::insert_values(self::$table, [ 'user_id' => $id ]);
  }

  public function get_id($user_id) {
    $result = Db_actions::select_values(self::$table, ['user_id' => $user_id]);
    $is_rowEmpty = Db_results::is_rowEmpty($result);

    return !$is_rowEmpty ? Db_results::get_row_params($result, 'id') : null;
  }

  public function get_user_id($id) {
    $result = Db_actions::select_values(self::$table, ['id' => $id]);
    $is_rowEmpty = Db_results::is_rowEmpty($result);

    return !$is_rowEmpty ? Db_results::get_row_params($result, 'user_id') : null;
  }
}

