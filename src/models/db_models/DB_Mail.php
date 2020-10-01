<?php

namespace Bot;

class DB_Mail {
  private static $connect;

  public static function up($connect) {
    self::$connect = $connect;
    $query = "SELECT * FROM mail";
    $result = self::$connect->query($query);

    if (empty($result)) {
      $query = "CREATE TABLE mail (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        mail_number BIGINT(15),
        status TEXT,
        created_at DATETIME,
        updated_at DATETIME,
        user_id INT(11),
        FOREIGN KEY (user_id) REFERENCES user (id)
        )";
      $result = self::$connect->query($query);
      if (self::$connect->error) {
        die("Model Mail is failed: " . self::$connect->error);
      }
    }
  }

  public static function select_values($where = null) {
    $query = "SELECT * FROM mail";
    if (isset($where)) {
      $query .= ' WHERE ' . http_build_query($where, '', ' AND ');
    }

    $result = self::$connect->query($query);
    return $result;
  }
}
