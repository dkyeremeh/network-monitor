<?php

$default_vars = ["admin_tools","account"];
$module_vars = [
	"system"=> [],	
	"delivery"=> [],	
	"merchant"=> [],		
	"customer"=> ["cart"],
];
$module = $_SESSION["account"]["module"];
if($module){
	$vars = array_merge($default_vars, $module_vars[$module]);

	foreach($vars as $var)
		unset($_SESSION[$var]);
}
DF\Response::printSuccess([
	"msg"=>"Logout Successful",
	"redirect"=>"/"
]);
Events::call_action("session_logout");
Events::call_action("session_logout_$module");