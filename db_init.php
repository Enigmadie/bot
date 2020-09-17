<?php

namespace Bot\Db;

include '.env.php';

define('SERVER_NAME', $DB_SERVERNAME);
define('DB_NAME', $DB_NAME);
define('PASSWORD', $DB_PASSWORD);
define('LOGIN', $DB_LOGIN);

function run() {
  $connect = new \mysqli(SERVER_NAME, LOGIN, PASSWORD, DB_NAME);

  if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
  }

  $query_user = "SELECT * FROM user";
  $user_result = $connect->query($query_user);

  if (empty($user_result)) {
    $query_user = "CREATE TABLE user (
      id int(11) AUTO_INCREMENT,
      user_id VARCHAR(20),
      )";
    $user_result = $connect->query($query_user);
  }


  $query_weather = "SELECT * FROM weather";
  $weather_result = $connect->query($query_weather);

  if (empty($weather_result)) {
    $query_weather = "CREATE TABLE weather (
      id int(11) AUTO_INCREMENT,
      city VARCHAR(60) NOT NULL,
      created_at DATE,
      user_id VARCHAR(20),
      FOREIGN_KEY (user_id) REFERENCES user (id)
      )";
    $weather_result = $connect->query($query_weather);
  }

  $query_mail = "SELECT * FROM mail";
  $mail_result = $connect->query($query_mail);

  if (empty($mail_result)) {
    $query_mail = "CREATE TABLE mail (
      id int(11) AUTO_INCREMENT,
      mail_number VARCHAR(30),
      created_at DATE,
      user_id VARCHAR(20),
      FOREIGN_KEY (user_id) REFERENCES user (id)
      )";
    $mail_result = $connect->query($query_mail);
  }
};
