<?php

$ROOT = __DIR__;

//Load config files
require_once "$ROOT/config.php";

//Load user-defined(contents) functions
if (file_exists("$APP_ROOT/app/functions.php")) {
    require_once "$APP_ROOT/app/functions.php";
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