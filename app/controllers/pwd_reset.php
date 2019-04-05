<?php

global $CAPTURE;
$module = $CAPTURE[1];
$token = $CAPTURE[2];


if($_POST){
	$tables =[
		"customer" => "customers",
		"merchant" => "merchants",
		"system" => "users",
		"delivery" => "users"
	];
	//Check password length
	if(strlen($_POST["password"]) < 6 ){
		DF\Response::printFailure("Passwords is too short", true);
	}
	//ensure passwords match
	if($_POST["password"] != $_POST["repassword"]){
		DF\Response::printFailure("Passwords do not match", true);
	}

	//Fetch info from DB
	$account = DF\DB::load(
		"pwd_reset", 
		[
			"email" => $_POST["email"], 
			"token"=>$token, 
			"module"=> $module
		]
	 )[0];

	if( !$account)
		DF\Response::printFailure("Invalid email and/or reset link", true);

	//Reset password
	$email = $_POST["email"];
	$password = create_password( $_POST["password"] );
		DF\DB::update( $tables[$module], compact("email","password") );

	//Delete reset info from database
		DF\DB::delete( "pwd_reset", compact("email") );

	//Success
	DF\Response::printSuccess("Password reset complete. You can login now", true);
}

$account = DF\DB::load(
	"pwd_reset", 
	[
		"token"=>$token, 
		"module"=> $module
	]
 )[0];

$page["title"] = "Set New Password";

if(!$account)
	$page["content"] = "This password reset link is no longer valid";

return $page;