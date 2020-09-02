<?php

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

$app = Slim\Factory\AppFactory::create();
$app->addErrorMiddleware(true, true, true);
require_once './src/routes.php';

$app->run();
