<?php

namespace Bot;

class DB_Weather {
  private static $connect;

  public static function up($connect) {
    self::$connect = $connect;
    $query = "SELECT * FROM weather";
    $result = self::$connect->query($query);

    if (empty($result)) {
      $query = "CREATE TABLE weather (
        id int(11) AUTO_INCREMENT PRIMARY KEY,
        city VARCHAR(60) NOT NULL,
        created_at DATETIME,
        updated_at DATETIME,
        user_id INT(11) UNIQUE,
        FOREIGN KEY (user_id) REFERENCES user (id)
        )";
      $result = self::$connect->query($query);
      if (self::$connect->error) {
        die("Model Weather is failed: " . self::$connect->error);
      }
    }
  }

  public static function select_values($where = null) {
    $query = "SELECT * FROM weather";
    if (isset($where)) {
      $query .= ' WHERE ' . http_build_query($where, '', ' AND ');
    }

    $result = self::$connect->query($query);
    return $result;
  }
}

