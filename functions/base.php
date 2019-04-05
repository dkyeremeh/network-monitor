<?php

//apcu to apc emulator
if(! function_exists("apc_add") )
	require_once dirname( __FILE__ ) . "/apc_shim.php";

if(!$GEN_ROOT)
	require_once dirname( __FILE__ ) . "/../config.php";
function gen_autoload($class){
	global $AUTOLOAD;
	$_class = strtolower($class);
	$class = str_ireplace("\\","/", $class);
	foreach($AUTOLOAD as $dir){
		if(file_exists("$dir/$class.php"))
			$file = "$dir/$class.php";
		else if(file_exists("$dir/$_class.php"))
			$file = "$dir/$_class.php";
		
		if (isset($file)){
			require_once($file);
			return;
		}
	}
}
spl_autoload_register("gen_autoload");

//Templating functions
function load_smarty(){
	global $GEN_OPTIONS, $GEN_ROOT, $GEN_VARS;
	if(!isset($GEN_VARS["smarty"])){
		
		$smarty = new Smarty;
		$smarty->setTemplateDir("$GEN_ROOT/app/templates/");
		$smarty->setCompileDir("$GEN_ROOT/compiled");
		$smarty->setCacheDir("$GEN_ROOT/data/cache/smarty");
		$smarty->setConfigDir("$GEN_ROOT/modules/smarty/config");
		$smarty->addPluginsDir("$GEN_ROOT/functions/smarty_plugins");
		$GEN_VARS["smarty"] = $smarty;
	}
	return $GEN_VARS["smarty"];
}
function get_template($target, $vars=[]){
	global $SITE_INFO;
	//Hooks
	Events::call_action("before_template", $vars, $target);
	Events::apply_filter("before_template", $vars);
	
	//Load smarty
	$smarty = load_smarty($vars); //$vars may contain some parameters for configuring smarty

	//Assign Values
	$smarty->assign("_GET", $_GET);
	$smarty->assign("SITE_INFO", $SITE_INFO);
	$smarty->assign($vars);
	
	//if template target is set in controller, change target to the value from the controller
	$target = $vars["template"] ? $vars["template"] : $target;

	//if caching is enabled in controller
	if(defined("CACHE_ID") && $vars["caching"]){
		//Save cache id to db
		apc_add("CACHE_" .  CACHE_ID,  $target);

		//Display template
		$smarty->display("$target.tpl",  CACHE_ID);
	}
	//Disable cache if it's not enabled in controller
	else {
		$smarty->setCaching(0);
		$smarty->display("$target.tpl");
	}
	

	Events::call_action("after_template", $target, $vars);
}
function get_sidebar($name){
	return get_template("sidebars/$name");
}

//controller Logic
function get_controller($target, $CAPTURE=[]){
	global $GEN_OPTIONS, $GEN_ROOT, $ROOT;
	$dirs = [];
	$dirs[] = "$GEN_ROOT/app/controllers";
	$dirs[] = "$GEN_ROOT/controllers";
	
	$target = str_replace(".php","", $target);
	foreach($dirs as $dir){		
		if(file_exists("$dir/$target.php")){
			return include("$dir/$target.php");
		}
	}
}

function check_cache($cacheOptions, $template){
	global $route;
	$cacheOptions[0] = $route["controller"];
	
	//Load item from cache if it exist else disable cache
	define("CACHE_ID",  crc32 ( serialize($cacheOptions ? $cacheOptions : DF\Request::$url) ) );
	
	$smarty = load_smarty();
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
	
	if(apc_exists("CACHE_" .  CACHE_ID)){
		$cache_template = apc_fetch("CACHE_" .  CACHE_ID);
		 if( $smarty->isCached("$cache_template.tpl", CACHE_ID) ){
			$smarty->display("$cache_template.tpl",  CACHE_ID);
			exit();
		 }
	}
}

function create_password($password){
	global $create_password_count;
	
	$salt = '$2x$07$' . md5( mcrypt_create_iv(10) );
	$pwd = crypt($password, $salt);

	if(strlen($pwd) < 6 && $create_password_count++ < 10){
		$pwd = create_password($password);
	}
	return $pwd;
}

function trim_recursive($r){
	if(!is_array($r))
		return $r;
	foreach($r as $key=>$val){
		if(gettype($val) == "string" )
			$r[$key] = trim($val);
		else if(gettype($val) == "string" )
			$r[$key] = trim_recursive($val);
	}
	return $r;
}