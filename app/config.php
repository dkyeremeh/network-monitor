<?php

$AUTOLOAD[] = dirname (__FILE__) . "/classes";

$GEN_ROUTES = [
	'Admin Login' => [
		'pattern' => '$',
		'controller' => 'login',
		'template' => 'pages/login',
		'priority' => '5'
	],
	'System Login' => [
		'pattern' => 'system\/login',
		'controller' => 'login',
		'template' => 'pages/login',
		'priority' => '5'
	],
	'API' => [
		'pattern' => 'api\\/(\\w+)\\/?(\\w+)?',
		'controller' => 'api',
		'template' => 'api',
		'priority' => '1'
	],
	'system' => [
		'pattern' => '*',
		'controller' => 'admin',
		'template' => 'admin',
		'priority' => '3'
	],
	'pwd_reset' => [
		'pattern' => '(\w+)\/reset\/(.+)',
		'controller' => 'pwd_reset',
		'template' => 'pages/pwd_reset',
		'priority' => '3'
	],
];


if( file_exists("$ROOT/options.json") ){
	
	$OPTIONS = json_decode(
		file_get_contents("$ROOT/options.json")
	);
}