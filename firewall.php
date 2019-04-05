<?php

/*
*	All non-static requests will be passed to this file. This file implements some security measures and acts as a firewall of a sort
*	Functions:	CSRF control, DDOS prevention, Multi-request filter
*/

require_once "config.php";
$REQUEST_PATH = $_SERVER["PATH_INFO"]? $_SERVER["PATH_INFO"]: $_SERVER["ORIG_PATH_INFO"];

//MULTI-REQUEST CONTROL (MRC)
session_start();
return;
/*
if($_SESSION["MRC"]["LAST_REQUEST_PATH"] == $REQUEST_PATH && time() - $_SESSION["MRC"]["LAST_REQUEST_TIME"] < 2 ){
	http_response_code(405);
	exit("Duplicate request detected");	//Multi-request shielding action
}

$_SESSION["MRC"]["LAST_REQUEST_TIME"] = time();
*/

//DDOS prevention
if($_SESSION["account"])
	return;

if($_SESSION["DDOS"]["ACCESS_BLOCKED"]){	//When requests have exceeded the allowed level and blocked
	if(time() - $_SESSION["DDOS"]["LAST_REQUEST_TIME"] < DDOS_BLOCK_DURATION){ //if block duration is not up
		//exit with error;
		http_response_code(403);
		exit("<h1>Access Blocked</h1><p>You have exceeded the maximum requests per minutes. \n<br/><br/> Please wait for (1) minute to gain access</p>");
	} else
		unset ($_SESSION["DDOS"]["ACCESS_BLOCKED"]);
}

$_SESSION["DDOS"]["LAST_REQUEST_TIME"] = time ();

if ($_SESSION["DDOS"]["LAST_REQUEST_TIME"] - $_SESSION["DDOS"]["FIRST_RPM_TIME"] > 60){ //reset request-per-minute counter and reference timer
	$_SESSION["DDOS"]["FIRST_RPM_TIME"] = $_SESSION["DDOS"]["LAST_REQUEST_TIME"];
	$_SESSION["DDOS"]["REQUESTS_PER_MINUTE"] = 0;
}
if(++$_SESSION["DDOS"]["REQUESTS_PER_MINUTE"] > REQUESTS_PER_MINUTE){	//if request exceed the allowed limit
	$_SESSION["DDOS"]["ACCESS_BLOCKED"] = true;	//set session to block access
	$_SESSION["DDOS"]["FIRST_RPM_TIME"] = $_SESSION["DDOS"]["LAST_REQUEST_TIME"];
	//exit with error;
	http_response_code(403);
	exit("<h1>Access Blocked</h1><p>You have exceeded the maximum requests per minutes. \n<br/><br/> Please wait for (1) minute to gain access</p>");	
}
