<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

//Set framework root
if (!isset($ROOT)) {
    $ROOT = dirname(__FILE__);
}

//Set project root
if (!isset($PROJECT_ROOT)) {
    $APP_ROOT = "$ROOT/app";
}

if (!isset($AUTOLOAD) || gettype($AUTOLOAD) != "array") {
    $AUTOLOAD = [];
}

$AUTOLOAD[] = "$ROOT/classes";