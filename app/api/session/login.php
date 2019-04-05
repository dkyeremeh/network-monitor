<?php

//extract variable
extract($_POST);


//if user is logged in
if($session_module = $_SESSION["account"]["module"]){
	//return an error if another module is logged into
	if($session_module != $module)
		DF\Response::printFailure("You are logged into another part of this system.<br/> Please logout first", 1);
	else
		//Redirect when user is logged into the right module
		DF\Response::printSuccess(["redirect","/$session_module/"], 1);
}

if($module == "customer"){
	call_api("/customer/account/login");
	exit();
}

//Captcha validation
$valid_captcha = Captcha::verify($_POST["captcha"]) ;

if($client == "ionic")
	$valid_captcha = true;

if ( !$valid_captcha)
	DF\Response::printFailure("Invalid CAPTCHA Code! Try reloading the CAPTCHA", 1);

//Captcha validation
//Bypass for ionic apps
if($client != "ionic")
	$valid_captcha = Captcha::verify($_POST["captcha"]) ;

//Table to be loaded for the requested module
$module_tables=[
	"merchant" => "merchants",
	"system" => "users",
	"delivery" => "users",
];

//Check database
$user = DF\DB::load($module_tables[$module], ["username"=>$username])[0];
if(!count($user))
	DF\Response::printFailure("Invalid Username and/or password", 1);

//Match password
if($user["password"] == crypt ($password, $user["password"])){ //login successful
	
	unset($user["password"]);
	$user["module"] =  $module;
	
	$_SESSION["account"] = $user;
	$_SESSION["client"] = $client;

	//Call event actions
	Events::call_action("admin_login", $user);
		
	
	//Redirect
	$redirect = $redirect ? $redirect : "/$module/";
	
	$data = [
		"account" => $_SESSION["account"],
		"tools" => $_SESSION["admin_tools"],
	];
	
	//Register mobile device
	if($push_id)
		Notification\Push::update_devices($push_id, Account::get_id());
		
	DF\Response::printSuccess([
		"msg"=>"Login Successful",
		"data"=>$data,
		"redirect" => $redirect,
		"notification" => [],
	]);
}
else
	DF\Response::printFailure("Invalid Username and/or password", 1);

