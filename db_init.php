<?php

namespace Bot\Db;

include '.env.php';

use Bot\User;
use Bot\Mail;
use Bot\Weather;

define("SERVER_NAME", $DB_SERVERNAME);
define("DB_NAME", $DB_NAME);
define("PASSWORD", $DB_PASSWORD);
define("LOGIN", $DB_LOGIN);

function init_db() {
  $connect = new \mysqli(SERVER_NAME, LOGIN, PASSWORD, DB_NAME);

  if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
  }

  User::up($connect);
  Mail::up($connect);
  Weather::up($connect);
}
