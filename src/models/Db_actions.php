<?php

namespace Bot;

class Db_actions extends Db {
  public static function select_values($table, $where = null) {
    $query = "SELECT * FROM {$table}";
    if (isset($where)) {
      $query .= " WHERE " . http_build_query($where, '', ' AND ');
    }

    $result = self::$connect->query($query);
    return $result;
  }

  public static function insert_values($table, $coll) {
    $query = "INSERT INTO {$table} ";

    $keys = array_keys($coll);
    $values = array_values($coll);

    $namesString = implode(', ', $keys);
    $rowValues = array_map(fn($el) => is_string($el) ? "'{$el}'" : $el, $values);
    $valuesString = implode(', ', $rowValues);
    $query .= "({$namesString}) VALUES ({$valuesString})";

    $result = self::$connect->query($query);
    if (self::$connect->error) {
      die("Opeatrion insert is failed: " . self::$connect->error);
    }
    return $result;
  }

  public static function update_values($table, $coll, $where) {
    $query = "UPDATE {$table} SET ";

    $rows = array_map(fn($el) => is_string($el) ? "'{$el}'" : $el, $coll);

    $query .= http_build_query($rows, '', ', ') .  'WHERE ' . http_build_query($where, '', ' AND ');

    $result = self::$connect->query($query);
    if (self::$connect->error) {
      die("Opeatrion update is failed: " . self::$connect->error);
    }
    return $result;
  }

  public static function delete_values($table, $where) {
    $query = "DELETE FROM ${table} WHERE ";
    $query .= http_build_query($where, '', ' AND ');

    $result = self::$connect->query($query);
    if (self::$connect->error) {
      die("Opeatrion delete is failed: " . self::$connect->error);
    }
    return $result;
  }
}
