<?php
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

//$path is used to handle request from other aspects of the app

//Router handler
$curpath = $_SERVER["PATH_INFO"] ? $_SERVER["PATH_INFO"]: parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$curpath = dirname(__FILE__)  . ( $path ? $path : $curpath );

if(file_exists($curpath . ".html")){
	$INCLUDE = $curpath . ".html";
}
else if(file_exists($curpath . ".php")){
	$INCLUDE =  $curpath . ".php";
}

if($INCLUDE){
	//header("Cache-Control: no-transform, public, max-age=3600");
	include($INCLUDE);
	return;
}

//File not found: Send 404 error if request came from browser
if(!$path)
	header("HTTP/1.0 404 Not Found");