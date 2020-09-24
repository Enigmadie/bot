<?php

namespace Bot\Db;

use Bot\User;
use Bot\Mail;
use Bot\Weather;

function init_db() {
  define("DB_LOGIN", $_ENV['DB_LOGIN']);
  define("DB_NAME", $_ENV['DB_NAME']);
  define("DB_PASSWORD", $_ENV['DB_PASSWORD']);
  define("DB_SERVERNAME", $_ENV['DB_SERVERNAME']);

  $connect = new \mysqli(DB_SERVERNAME, DB_LOGIN, DB_PASSWORD, DB_NAME);

  if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
  }

  User::up($connect);
  Mail::up($connect);
  Weather::up($connect);
}
