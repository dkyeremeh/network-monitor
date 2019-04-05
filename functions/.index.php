<?php
if(!$AUTOLOAD)
	require_once dirname( __FILE__ ) . "/../config.inc";

function __autoload($class){
	global $AUTOLOAD;
	$class = str_ireplace("\\","/", $class);
	foreach($AUTOLOAD as $dir){
		if(file_exists("$dir/$class.php")){
			require_once("$dir/$class.php");
			return;
		}
	}
} 
