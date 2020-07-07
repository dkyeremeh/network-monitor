<?php

function load($post,$params){
	global $OPTIONS;
	$data = $OPTIONS ? $OPTIONS : [
		"notify" => ["email" => true, "sms"]
	];
	DF\Response::printSuccess(["data"=>$data]);
}

function edit($values){
	global $APP_ROOT;
	extract($values);
	// Save fields
	$allowed = ["notify","infobip", "smtp"];
	
	$options = json_encode( compact($allowed) );
	file_put_contents("$ROOT/data/options.json", $options);

	DF\Response::printSuccess("Settings Saved");
}


return route_api;