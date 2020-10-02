<?php

namespace Bot;

use Bot\Db_actions;

class User {
  private static $table = 'user';

  public function register($id) {
    Db_actions::insert_values(self::$table, [ 'user_id' => $id ]);
  }

  public function get_id($user_id) {
    $result = Db_actions::select_values(self::$table, ['user_id' => $user_id]);
    return $result->num_rows > 0 ? $result->fetch_assoc()['id'] : null;
  }

  public function get_user_id($id) {
    $result = Db_actions::select_values(self::$table, ['id' => $id]);
    return $result->num_rows > 0 ? $result->fetch_assoc()['user_id'] : null;
  }
}

