<?php

function app_init(){
	//Load application options into memory
	// TODO: Load options
}
function evaluate ($exp, $vars = []){
	$exp = strtr($exp, $vars);	//Replace variables
	$exp = preg_replace("/[;\s{}\[\]$\"\']/","", $exp);	//replace some special characters to invalidate a function or command
	$return = @eval("return $exp;");	//Evaluate expression
	return $return ? $return : 0;	//output
}

function dir_count($dir){
	return (count(scandir($dir)) - 2);
}

function call_api($path, $data=[]){
	global $APP_ROOT;

	chdir ($APP_ROOT);

	$data = trim_recursive($data);
	$ROUTES = [];
	$curPath = "./api/$path";
	$curPath = dirname($curPath) . "/" . basename($curPath);
	$max = substr_count($curPath, '/') - 1; //-1 ensures that we only operate in the api directory


	for($i=0; $i < $max ; $i++){ //check if file exists
		if(file_exists($curPath . ".php")){
			$INCLUDE = $curPath . ".php";
			break;

		}
		else if(file_exists($curPath . "/index.php")){
			$INCLUDE = $curPath . "/index.php";
			break;
		}
		//if not remove the last index
		else{
			$ROUTES[]=basename($curPath);
			$curPath = dirname($curPath);
		}
	}
	if($INCLUDE){
		$ROUTES = array_reverse($ROUTES);
		$FN = include($INCLUDE);
		if(is_callable($FN))
			return $FN($ROUTES, $data);
		return true;
	}

	return false;
}


function admin_redirect(){
	if(isset($_SESSION['account']["module"])){
		
		$module=$_SESSION['account']["module"];
		header("location: /$module/");
		exit("Redirecting to /$module/");
	}
}
function parse_headers($header){
	$h = [];
	preg_match_all( "/((.+): (.+)\n?)/", $header, $o );

	foreach( $o[2] as $key=>$val ){
		$h[$val] = $o[3][$key];
	}
	return $h;
}

function url_status( $url ){
	$browser = new Browser;
	@$browser->head( $url );

	$status = parse_headers( $browser->getHeaders() );
	$status["code"] = $browser->getResponseCode();

	return $status;
}

function filesize_format($size, $precision = 2){
	$size = max(0, (int)$size);
	$units = array( ' B', ' kB', ' MB', ' GB', ' TB', ' PB', ' EB') ;
	$power = $size > 0 ? floor(log($size, 1024)) : 0;
	return number_format($size / pow(1024, $power), $precision , '.', ',') . $units[$power] ;
} 

function route_api( $ROUTES, $data ){
	if(function_exists($ROUTES[0])){
		$fn = $ROUTES[0];
		array_splice( $ROUTES ,0, 1);
		$fn( $data, $ROUTES );
	}

	else
		DF\Response::printFailure("No candidate exists for this request");

}

function array_get_col($col, array $arr){
	$col_r = array_fill(0, count($arr), $col ); 
	return array_map(function($r, $col){
		return $r[$col];
	}, $arr, $col_r);
}

function array_get_col_value($col, array $arr){
	return array_get_col($col, $arr);
}

function array_assign_key($col, array $arr){
	$return = [];
	foreach($arr as $r){
		$return[$col] = $r;
	}
	return $return;
}