<?php

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

//BASIC SITE CONFIG (SERVER DEPENDENT)
$SITE_INFO = [
    "name"        => "Network Devices Monitor", //Name of app
    "base"        => "{sub.domain.com}", // FQDN of app
    "static_base" => "/app/static",
];

//AUTOLOAD
$AUTOLOAD[] = "$ROOT/classes";
// $AUTOLOAD[] = "$ROOT/modules/smarty/sysplugins"
// $AUTOLOAD[] = "$ROOT/modules"

//DATABASE
define("DB_TYPE", "mysql");
define("DB_PREFIX", "gen_");
define("DB_HOST", "{VALUE}");
define("DB_USER", "{VALUE}");
define("DB_NAME", "{VALUE}");
define("DB_PASS", "{VALUE}");

//CACHE
define("CACHE_LIFESPAN", 3600);

//DDOS
define("REQUESTS_PER_MINUTE", 30);
define("DDOS_BLOCK_DURATION", 120);
