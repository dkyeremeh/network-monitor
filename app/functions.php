<?php

include __DIR__ . "/fn/general.php";
include __DIR__ . "/fn/smarty.php";


//App related
Events::add_action("init", "app_init");

//Pages
Events::add_action("page_customer_login", "admin_redirect");
Events::add_action("page_merchant_register", "admin_redirect");
Events::add_action("page_merchant_register", "merchant_qn_redirect");
Events::add_action("page_customer_register", "admin_redirect");
Events::add_action("page_delivery_register", "admin_redirect");

Events::add_filter("before_template", "append_template_vars");

function append_template_vars($vars){
	$vars["ACCOUNT"] = $_SESSION["account"];
	$vars["CLIENT"] = $_SESSION["client"];
	return $vars;
}

Events::add_action("admin_login", "load_admin_access");
function load_admin_access($user){
	//Load tools for the module
	$table = "admin_tools";
	$query = "SELECT tools.title, tools.tool, tools.icon_class FROM access_control RIGHT JOIN tools
		on access_control.tool = tools.tool AND access_control.module = tools.module
		WHERE `role`= :role AND tools.`module` = :module ORDER BY `order`";
	$values = [
		":role"=> $_SESSION['account']["role"],
		":module"=> $_SESSION['account']["module"],
	];
	
	//Load tools from DB
	$tools = DF\DB::prepared_query($query, $values);
	
	//load the tools into session
	$_SESSION["admin_tools"] = [];
	foreach($tools as $tool){
		$_SESSION["admin_tools"][$tool["tool"]] = $tool;
	}

}
