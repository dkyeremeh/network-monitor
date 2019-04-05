<?php

//Templating functions
function get_template($target){
	$target = str_replace(".php","", $target);
	if(file_exists("templates/$target.php")){
		return include("templates/$target.php");
	}
}
function get_sidebar($name){
	return get_template("sidebars/$name.php");
}
function get_header(){
	return get_template("header.php");
}
function get_footer(){
	return get_template("footer.php");
}