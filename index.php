<?php

$ROOT = __DIR__;

//Load config files
require_once "$ROOT/config.php";
require_once "$ROOT/app/config.php";

//Composer AutoLoad
require_once "$ROOT/vendor/autoload.php";

//Load basic functions
require_once "$ROOT/functions/base.php";
//Load user-defined(contents) functions
if (file_exists("$ROOT/app/functions.php")) {
    require_once "$ROOT/app/functions.php";
}

//Execute init event functions
Events::call_action("init");

//Load Routes

foreach ($ROUTES as $route) {
    if ($route["pattern"] == DF\Request::$path || @preg_match("/^\/" . $route["pattern"] . "/", DF\Request::$path, $CAPTURE)) {
        //Load Business Logic
        $data = get_controller($route["controller"], $CAPTURE);
        //Load template

        if ($data) {
            $data = gettype($data) == "array" ? $data : [];
            get_template($route["template"], $data);
            return;
        }
    }
}
http_response_code(404);
get_template("404");