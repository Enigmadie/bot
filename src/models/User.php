<?php

namespace Bot;

class User {
  private static $connect;

  public static function up($connect) {
    self::$connect = $connect;
    $query = "SELECT * FROM user";
    $result = self::$connect->query($query);

    if (empty($result)) {
      $query = "CREATE TABLE user (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(20) NOT NULL UNIQUE
        )";
      $result = self::$connect->query($query);
    }
      if (self::$connect->error) {
        die("Model User is failed: " . self::$connect->error);
      }
  }

  public function register($id) {
    $query = "INSERT INTO user (user_id) VALUES (${id})";
    self::$connect->query($query);
  }

  public function get_id($user_id) {
    $result = $this->select_values(['user_id' => $user_id]);
    return $result->num_rows > 0 ? $result->fetch_assoc()['id'] : null;
  }

  public function get_user_id($id) {
    $result = $this->select_values(['id' => $id]);
    return $result->num_rows > 0 ? $result->fetch_assoc()['user_id'] : null;
  }

  private function select_values($where = null) {
    $query = "SELECT * FROM user";
    if (isset($where)) {
      $query .= ' WHERE ' . http_build_query($where, '', ' AND ');
    }

    $result = self::$connect->query($query);
    return $result;
  }
}

