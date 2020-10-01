<?php

namespace Bot\Db;

use Bot\DB_User;
use Bot\DB_Mail;
use Bot\DB_Weather;

function init_db() {
  define("DB_LOGIN", $_ENV['DB_LOGIN']);
  define("DB_NAME", $_ENV['DB_NAME']);
  define("DB_PASSWORD", $_ENV['DB_PASSWORD']);
  define("DB_SERVERNAME", $_ENV['DB_SERVERNAME']);

  $connect = new \mysqli(DB_SERVERNAME, DB_LOGIN, DB_PASSWORD, DB_NAME);

  if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
  }

  DB_User::up($connect);
  DB_Mail::up($connect);
  DB_Weather::up($connect);
}
