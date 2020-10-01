<?php

namespace Bot;

class DB_User {
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

  public static function select_values($where = null) {
    $query = "SELECT * FROM user";
    if (isset($where)) {
      $query .= ' WHERE ' . http_build_query($where, '', ' AND ');
    }

    $result = self::$connect->query($query);
    return $result;
  }

  public static function insert_values($coll) {
    $query = "INSERT INTO user";

    $keys = array_keys($coll);
    $values = array_values($coll);

    $namesString = implode(', ', $keys);
    $rowValues = array_map(fn($el) => is_string($el) ? "'{$el}'" : $el, $values);
    $valuesString = implode(', ', $rowValues);
    $query .= " ({$namesString}) VALUES ({$valuesString})";

    $result = self::$connect->query($query);
    return $result;
  }
}

