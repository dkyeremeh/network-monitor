<?php

class Filter{
	static private $filters = [
		"alphanumeric" => [Filter, "sanitize_alphanumerals"],
		"name" => [Filter, "sanitize_name"],
		"prettyurl" => [Filter, "sanitize_url"],
	];
	
	static private $valid_rules = [
	];
	
	static private $escape_rules =[
		"html" => "htmlspecialchars",
		"url" => "urlencode",
	];
		
		
	static function sanitize($input, $type){
		$TYPE = strtoupper($type);
		$type = strtolower($type);
		//Use inbuilt sanitizer
		if(defined("FILTER_SANITIZE_$TYPE"))
			return filter_var($input, constant("FILTER_SANITIZE_$TYPE"));
		//User custom sanitizer
		else if(isset(self::$filters[$type]))
			return call_user_func(self::$filters[$type], $input);
		//No sanitizer found
		else{
			trigger_error("Undefined sanitizing algorithm '$type'");
			return $input;
		}
			
	}
	static function validate($input, $type){
		$TYPE = strtoupper($type);
		$type = strtolower($type);
		
		if(defined("FILTER_VALIDATE_$TYPE"))
			return filter_var($input, constant("FILTER_VALIDATE_$TYPE"));
		else if($rule = self::$valid_rules[$type])
			return preg_match($rule, $input);
		
		//No validation_candidate found
		else{
			trigger_error("Undefined validation algorithm specified");
			return false;
		}
		
	}
	static function escape($input, $type="html"){
		if( isset(self::$escape_rules[$type]) )
			return call_user_func(self::$escape_rules[$type], $input);
		else
			trigger_error("No escape rule was found");
	}
	
	
	static public function sanitize_name($url){
		$url = trim($url);
		return preg_replace("/[^\w -]/", "", $url);
	}
	static public function sanitize_alphanumerals($url){
		return preg_replace("/\W/", "", $url);
	}
	static public function sanitize_url($url){
		$url = strtolower($url);
		$url = preg_replace("/[^\w]/", "-", $url);
		$url = preg_replace("/[_ ]/", "-", $url); 
		$url = preg_replace("/-+/", "-", $url);
		
		return trim($url, "-\t\n\r\0\x0B" );
	}
}