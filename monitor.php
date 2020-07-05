<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/app/config.php";
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/functions/base.php";
require_once __DIR__ . "/app/functions.php";


$cron= new DF\Cron(["output"=>"$ROOT/cron.log"]);

if(file_exists("$ROOT/cron.php")){
	include "$ROOT/cron.php";
}


$cron->run();