<?php

global $CAPTURE;
$module = "system";

//Redirect when user is already logged in
if($_SESSION['account']["module"] == $module){
	header("location: /$module/");
	exit("Redirecting to /$module/");
}

//Check if module is allowed to use this login
$allowed_modules = ["merchant","system", "delivery"];
if (!in_array($module, $allowed_modules))
	return false;

//Titles table
$titles = [
	"merchant"=>"Store Login",
];

//Set variables;
$title = $titles[$module];
$page["title"] = $title ? $title : "Login";
$page["module"] = $module;
return $page;