<?php

function load($post,$params){

	$data = DF\DB::load("dev_serv");
	DF\Response::printSuccess(["data"=>$data]);
}

function create($values){
	extract($values);
	$allowed = ["id","name","address","port","device","category","description"];
	$required = [
		0 => ["name","address","port","category"],
		1 => ["name","address","device","category"],
	];
	
	//Required algorithm
	foreach($required[ $device ] as $req){
		if(!$$req)
			DF\Response::printFailure("<span class='capitalize'>'$req'</span> was not set", 1);
	}
	
	//Algorithm for new category
	if($category == "new"){
		$category = $newCategory;
	}
	
	//Process tags before insertion
	$tags = trim($tags);
	$tags = preg_replace("/\s+/"," ", $tags);

	//Insert data
	$values = compact($allowed);
	$output = DF\DB::update("dev_serv", $values);
	
	DF\Response::printSuccess(["msg"=>"Devices updated successfully", "reload"=> true]);
}

function edit($values){
	create($values);
}

function delete($values){
	$output = DF\DB::delete("dev_serv", $values);
	DF\Response::printSuccess(["msg"=>"Entry has been deleted", "reload"=> true]);
}

return route_api;