<?php

error_reporting( E_ERROR | E_WARNING | E_PARSE);

$GEN_ROOT = __DIR__;

//Load config files
require_once "$GEN_ROOT/config.php";
require_once "$GEN_ROOT/app/config.php";

//Composer AutoLoad
require_once "$GEN_ROOT/vendor/autoload.php";

//Load basic functions
require_once "$GEN_ROOT/functions/base.php";
//Load user-defined(contents) functions
if(file_exists("$GEN_ROOT/app/functions.php"))
	require_once "$GEN_ROOT/app/functions.php";

//Execute init event functions
Events::call_action("init");

//Load Firewall
require_once "$GEN_ROOT/firewall.php";
	
//Load Routes

foreach($GEN_ROUTES as $route){
	if($route["pattern"] == DF\Request::$path || @preg_match("/^\/".$route["pattern"]."/", DF\Request::$path, $CAPTURE ) ){

		//check_cache($CAPTURE, $route["template"] );

		//Load Business Logic
		$data = get_controller($route["controller"], $CAPTURE);
		//Load template
		
		if($data){
			$data = gettype($data) == "array" ? $data : [];
			get_template($route["template"], $data);
			return;
		}
	}
}
http_response_code(404);
get_template("404");