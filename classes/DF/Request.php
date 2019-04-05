<?php

namespace DF;
    
class Request{
	public static $path = "";
	public static $referer = "";
	public static $url = "";
	public static $query = "";
}

Request::$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if(!Request::$path) //Set path for nginx
	Request::$path = $_GET["_p_"] ? $_GET["_p_"] : "/";	

Request::$url = $_SERVER["REQUEST_URI"];
Request::$query = $_SERVER["QUERY_STRING"];
Request::$referer = $_SERVER["HTTP_REFERER"];