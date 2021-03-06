<?php

namespace Bot;

class Db_actions extends Db {
  public static function select_values($table, $where = null) {
    $query = "SELECT * FROM {$table}";
    if (isset($where)) {
      $query_rest = [];
      foreach($where as $key => $value) {
        $modValue = is_string($value) ? "'{$value}'" : $value;
        $query_rest[] = "${key} = ${modValue}";
      }
      $query .= " WHERE " . implode(' AND ', $query_rest);
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

    $query_rows = [];
    foreach($coll as $key => $value) {
      $query_value = is_string($value)  ? "'{$value}'" : $value;
      $query_rows[] = "${key} = ${query_value} ";
    }

    $query_rest = [];
    foreach($where as $key => $value) {
      $modValue = is_string($value) ? "'{$value}'" : $value;
      $query_rest[] = "${key} = ${modValue}";
    }

    $query .= implode(', ', $query_rows) . 'WHERE ' .implode(' AND ', $query_rest);
    $result = self::$connect->query($query);
    if (self::$connect->error) {
      die("Opeatrion update is failed: " . self::$connect->error);
    }
    return $result;
  }

  public static function delete_values($table, $where) {
    $query = "DELETE FROM ${table} WHERE ";
    $query_rest = [];
    foreach($where as $key => $value) {
      $modValue = is_string($value) ? "'{$value}'" : $value;
      $query_rest[] = "${key} = ${modValue}";
    }
    $query .= " WHERE " . implode(' AND ', $query_rest);

    $result = self::$connect->query($query);
    if (self::$connect->error) {
      die("Opeatrion delete is failed: " . self::$connect->error);
    }
    return $result;
  }
}
