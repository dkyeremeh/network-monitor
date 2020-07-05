<?php

require_once __DIR__ . "/config.php";

$cron= new DF\Cron(["output"=>"$ROOT/cron.log"]);

if(file_exists("$APP_ROOT/cron.php")){
	include "$APP_ROOT/cron.php";
}


$cron->run();