<?php


$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
error_log(print_r(__DIR__ . '/.env'), true);
$dotenv->load(__DIR__.'/.env');

Bot\Db\init_db();

require_once './src/index.php';
