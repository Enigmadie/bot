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
        user_id VARCHAR(20) NOT NULL
        )";
      $result = self::$connect->query($query);
    }
      if (self::$connect->error) {
        die("Model User is failed: " . self::$connect->error);
      }
  }
}

