<?php

function load($post,$params){
	global $OPTIONS;
	$data = $OPTIONS ? $OPTIONS : [
		"notify" => ["email" => true, "sms"]
	];
	DF\Response::printSuccess(["data"=>$data]);
}

function edit($values){
	global $ROOT;
	extract($values);
	// Save fields
	$allowed = ["notify","infobip"];
	
	$options = json_encode( compact($allowed) );
	file_put_contents("$ROOT/options.json", $options);

	DF\Response::printSuccess("Settings Saved");
}


return route_api;