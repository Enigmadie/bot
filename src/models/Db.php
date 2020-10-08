<?php

namespace Bot;

class Db {
  protected static $connect;

  public static function up($connect) {
    self::$connect = $connect;

    $result_mail = self::$connect->query("
      SELECT * FROM mail
    ");

    if (empty($result_mail)) {
      self::$connect->query("
        CREATE TABLE mail (
          id INT(11) AUTO_INCREMENT PRIMARY KEY,
          mail_number VARCHAR(60) NOT NULL,
          status TEXT,
          created_at DATETIME,
          updated_at DATETIME,
          user_id INT(11),
          FOREIGN KEY (user_id) REFERENCES user (id)
        )
      ");
      if (self::$connect->error) {
        die("Model Mail is failed: " . self::$connect->error);
      }
    }

    $result_user = self::$connect->query("
      SELECT * FROM user
    ");

    if (empty($result_user)) {
      self::$connect->query("
        CREATE TABLE user (
          id INT(11) AUTO_INCREMENT PRIMARY KEY,
          user_id VARCHAR(20) NOT NULL UNIQUE
        )
      ");
      if (self::$connect->error) {
        die("Model User is failed: " . self::$connect->error);
      }
    }

    $result_weather = self::$connect->query("
      SELECT * FROM weather
    ");

    if (empty($result_weather)) {
      self::$connect->query("
        CREATE TABLE weather (
          id int(11) AUTO_INCREMENT PRIMARY KEY,
          city VARCHAR(60) NOT NULL,
          created_at DATETIME,
          updated_at DATETIME,
          user_id INT(11) UNIQUE,
          FOREIGN KEY (user_id) REFERENCES user (id)
        )
      ");
      if (self::$connect->error) {
        die("Model Weather is failed: " . self::$connect->error);
      }
    }
  }
}
