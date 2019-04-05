<?php

//Smarty Plugins
function init_smarty_plugins(){
	$smarty = load_smarty();
	$smarty->registerPlugin("modifier","sanitize", [Filter, "sanitize"] );
	$smarty->registerPlugin("modifier","base64", "base64_encode" );
	$smarty->registerPlugin("modifier","strtotime", "strtotime" );
	
	$smarty->registerPlugin("function","clientTemplate", "getClientTemplate");
	$smarty->registerPlugin("function","insertController", "insertController");
	$smarty->registerPlugin("function","accountStatusJS", "accountStatusJS");
	$smarty->registerPlugin("function","widget", "display_widget");
}

Events::add_action("before_template", "init_smarty_plugins");

function insertController($params){
	extract($params);
	$data = get_controller($name, $capture);
	if($template)
		$data["template"] = $template;
	get_template($template, $data);
}
function accountStatusJS($params){
	$account = $_SESSION["account"] ;
	if(!$account)
		return;
	$account["extra"] = json_decode($account["extra"], true);
	$account = json_encode( $account );
	echo "<script type='text/javascript' >account = $account</script>";
}

function getClientTemplate($id){
	global $ROOT;
	if(gettype($id) == "array" )
		$id = $id["id"];
	$path = "/templates/$id";
	include "$ROOT/static/index.php";
}
function display_widget($params){}