<?php

global $tables;

$tables = [
		"options" => "categories_options",
		"relations" => "cat_options_relation",
		"categories" => "categories",
	];
	
function load($post,$params){
	$data = [];
	$data["categories"] = DF\DB::load("categories");
	$data["options"] = DF\DB::load("categories_options");
	$data["relations"] = DF\DB::load("cat_options_relation");
	DF\Response::printSuccess(["data"=>$data]);
}

function create($values,$params){
	global $tables;
	$feature = $params[0];
	extract($values);
	$allowed = [
		"options" => ["id","name", "type","attributes", "options"],
		"relations" => ["id","option", "category"],
		"categories" => ["id", "name", "parent"],
	];
	
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
	$feature = $params[0];
	extract($values);
	$tables = [
		"options" => "categories_options",
		"relations" => "cat_options_relation",
		"categories" => "categories",
	];
	if(isset($tables[$feature])){
		$table = $tables[$feature];
		
		$values = compact("id");
		
		$output = DF\DB::delete($table, $values);
		DF\Response::printSuccess(["msg"=>"Entry has been deleted", "reload"=> true]);
	}
	else
		DF\Response::printFailure("Thy system does not understand your request");
}

if(function_exists($ROUTES[0])){
	$fn = $ROUTES[0];
	array_splice( $ROUTES ,0, 1);
	$fn($_POST,$ROUTES);
}

else
	DF\Response::printFailure("No candidate exists for this request");