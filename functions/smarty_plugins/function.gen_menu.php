<?php

function smarty_function_gen_menu(array $args, Smarty_Internal_Template $smarty){
	$id = $args["id"];
	$menu = [];
	
	//TODO: Generate Menu items
	
	//Apply filters
	Events::apply_filter("gen_menu", $menu);
	
	//Convert to HTML 
	$output = menu2markup($menu, $args);
	return $output;
}

function menu2markup($menu, $attribs=[]){
	//Set Attributes
	if(isset($attribs["id"]))
		$attribs["id"] .= "-menu";
	foreach($attribs as $key=>$value)
		$attrib .= "$key=\"$value\" ";
	
	//Start generating markup
	$output = "<ul $attrib>";
	foreach($menu as $title=>$url){
		$title=Filter::escape($title);		
		if(gettype($url)=="array"){
			$children = menu2markup($url, ["class"=>"sub-menu" ]);
			$output.="<li><a>$title</a>$children</li>";
		}
		else{
			$url=Filter::escape($url);
			$output.="<li><a href=\"$url\">$title</a></li>";
		}
	}
	$output .= "</ul>";
	
	return $output;
}