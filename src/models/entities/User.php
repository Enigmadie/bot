<?php

namespace Bot;

use Bot\DB_User;

class User {
  private static $connect;

  public function register($id) {
    $query = "INSERT INTO user (user_id) VALUES (${id})";
    self::$connect->query($query);
  }

  public function get_id($user_id) {
    $result = DB_User::select_values(['user_id' => $user_id]);
    return $result->num_rows > 0 ? $result->fetch_assoc()['id'] : null;
  }

  public function get_user_id($id) {
    $result = DB_User::select_values(['id' => $id]);
    return $result->num_rows > 0 ? $result->fetch_assoc()['user_id'] : null;
  }
}

