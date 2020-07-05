<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!isset($ROOT)) {
    $ROOT = __DIR__;
}
if (!isset($PROJECT_ROOT)) {
    $APP_ROOT = "$ROOT/app";
}

require_once "$ROOT/vendor/autoload.php";

// Load environmental variables
Dotenv\Dotenv::createImmutable($ROOT)->load();

if (!isset($AUTOLOAD) || gettype($AUTOLOAD) != "array") {
    $AUTOLOAD = [];
}

$AUTOLOAD[] = "$ROOT/classes";

require_once "$ROOT/functions/base.php";
require_once "$APP_ROOT/config.php";
require_once "$APP_ROOT/functions.php";
$db = require_once "$ROOT/database.php";

$capsule = new Capsule();
$capsule->addConnection($db["connections"][$db["default"]]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

define("DB_HOST", $_ENV["DB_HOST"] );
define("DB_USER", $_ENV["DB_USERNAME"] );
define("DB_NAME", $_ENV["DB_DATABASE"] );
define("DB_PASS", $_ENV["DB_PASSWORD"]);