<?php
//Set framework root
if (!isset($ROOT)) {
    $ROOT = dirname(__FILE__);
}

//Set project root
if (!isset($PROJECT_ROOT)) {
    $APP_ROOT = "$ROOT/app";
}

require_once "$ROOT/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable($ROOT);

if (!isset($AUTOLOAD) || gettype($AUTOLOAD) != "array") {
    $AUTOLOAD = [];
}

$AUTOLOAD[] = "$ROOT/classes";

require_once __DIR__ . "/app/config.php";
require_once __DIR__ . "/functions/base.php";
require_once __DIR__ . "/app/functions.php";