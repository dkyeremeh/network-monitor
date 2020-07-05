<?php
	
//Restrict access to login related function (Access control)
	//Capture the module and feature being accessed
	//Prevent access if account's module does not match the module being accessed
	//but allow access if the feature is whitelisted


//Set Variables. R denotes request
$Rmodule = $CAPTURE[1];
$Rtool = $CAPTURE[2];
$module = $_SESSION["account"]["module"];

//Restricted modules
$restricted = ["merchant","system","customer","delivery"];

//Tools 
$free = [ //No login regquired
	"merchant"=>["account"],
	"customer"=>["account"],
	"system"=>[],
	"delivery"=>[],
];
$module_free = [	//For everyone logged in
	"merchant"=>["dashboard","load"],
	"system"=>["dashboard","load"],
	"delivery"=>["dashboard","load"],
	"customer"=>[],
];

//Do some checks;
if(in_array($Rmodule, $restricted)){ //module is restricted

	$access_tool = isset($_SESSION["admin_tools"][$Rtool] ) !== false;
	$module_free_access = $Rmodule == "customer" || in_array($Rtool, $module_free[$Rmodule]) !== false;
	$free_access = in_array($Rtool, $free[$Rmodule] ) !== false;
	$right_module = $Rmodule == $module;

	
	//Perform access Control		
	if( !($free_access || ($right_module && ( $access_tool || $module_free_access) ) ) ){	//no access to the requested tool

		$response = ["msg" => "You do not have permission to access this feature"];
		$response["redirect"] = "/$Rmodule/login";

		//Send response data
		DF\Response::printFailure($response, 1);
	}
}


if(!$_POST){
	$_POST = json_decode(	file_get_contents("php://input"), true	);
}


Events::call_action("before_api_call", $module, $Rtool, $_POST);

$path = substr(DF\Request::$path, 5); //Remove preceding "/api/"
$res = call_api($path, $_POST);

if($res === false){
	DF\Response::printFailure("The requested action does not exist", 1);
}
exit();

/*
chdir ($APP_ROOT);

$ROUTES = [];
$curPath = "." . DF\Request::$path;
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
	//configure AutoLoad
	if(!$_POST)
		$_POST = json_decode(	file_get_contents("php://input"), true	);
	$ROUTES = array_reverse($ROUTES);
	$FN = include($INCLUDE);
	if(is_callable($FN))
		$FN($ROUTES);
	exit();
}
else{
	DF\Response::printFailure("The requested action does not exist", 1);
}
*/
