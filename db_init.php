<?php

namespace Bot\Db;

use Bot\User;
use Bot\Mail;
use Bot\Weather;

define("DB_LOGIN", getenv('DB_LOGIN'));
define("DB_NAME", getenv('DB_NAME'));
define("PASSWORD", getenv('DB_PASSWORD'));
define("SERVER_NAME", getenv('DB_SERVERNAME'));

function init_db() {
  $connect = new \mysqli(SERVER_NAME, DB_LOGIN, 'password', 'bot');

  if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
  }

  User::up($connect);
  Mail::up($connect);
  Weather::up($connect);
}
