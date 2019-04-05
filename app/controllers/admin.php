<?php

preg_match("/(\w+)/", DF\Request::$url, $matches);
$request_module = $matches[1];
$module = $_SESSION['account']["module"];

if($_SESSION['account']["module"] != $request_module){
	header("location: /$request_module/login");
	exit("Redirecting to /$request_module/login");
}

//Set other template variable
$page["tools"] = $_SESSION["admin_tools"];
$page["module"] = $_SESSION['account']["module"];

if($module=="merchant")
	$page["menu"] = [
		["title"=>"Visit Store", "href"=>"/{{account.shop}}", "target"=>"_blank"],
	];

return $page;