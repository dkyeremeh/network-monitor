<?php

global $tables;

$tables = [
		"access" => "access_control",
		"tool" => "tools",
	];

function load(){
	$data["tools"] = DF\DB::load("tools");
	$data["access"] = DF\DB::load("access_control");
	
	DF\Response::printSuccess([data=>$data]);
}

function create($values,$params){
	global $tables;
	$feature = $params[0];
	extract($values);
	
	//Map new data to original variables
	$module = "system";
	
	if($role=="new")
		$role=$newRole;
	
	$required =[
		"access"=>["role","tool","module"],
		"tool"=>["tool","title","module"],
	];
	$allowed = [
		"access" => ["id","role", "tool", "module"],
		"tool" => ["id","tool","title", "module", "icon_class", "order"],
	];
	
	foreach($required[$feature] as $req)
		if(!$$req)
			DF\Response::printFailure("Required field '$req' is not set", 1);
			
	
	if(isset($tables[$feature])){
		$table = $tables[$feature];
		
		$values = compact($allowed[$feature]);
		
		$output = DF\DB::update($table, $values);
		DF\Response::printSuccess(["msg"=>"The system has been updated", "reload"=> true]);
	}
	else
		DF\Response::printFailure("Thy system does not understand your request");
}

function edit($values,$params){
	create($values,$params);
}

function delete($values,$params){
	global $tables;
	$feature = $params[0];
	extract($values);
	
	$tool = $id;
	
	$allowed = [
		"access" => "id",
		"tool" => "tool",
	];
	
	if(isset($tables[$feature])){
		$table = $tables[$feature];
		
		$values = compact($allowed[$feature]);
		
		$output = DF\DB::delete($table, $values);
		DF\Response::printSuccess(["msg"=>"Entry has been deleted", "reload"=> true]);
	}
	else
		DF\Response::printFailure("Thy system does not understand your request");
}


if(function_exists($ROUTES[0])){
	$fn = $ROUTES[0];
	array_splice( $ROUTES ,0, 1);
	$fn($_POST, $ROUTES);
}

else
	DF\Response::printFailure("No candidate exists for this request");