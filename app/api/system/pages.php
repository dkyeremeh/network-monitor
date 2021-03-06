<?php

function load($post, $params)
{
    $data          = [];
    $data["pages"] = DF\DB::load("pages", ["controller" => ""]);

    DF\Response::printSuccess(["data" => $data]);
}

function create($values)
{
    extract($values);
    $table    = "pages";
    $allowed  = ["id", "title", "content", "url", "slug"];
    $required = ["content", "title", "url", "slug"];

    //Required algorithm
    foreach ($required as $req) {
        if (!$$req) {
            DF\Response::printFailure("<span class='capitalize'>'$req'</span> was not set", 1);
        }

    }

    //Insert data
    $values = compact($allowed);
    $output = DF\DB::update($table, $values);

    DF\Response::printSuccess(["msg" => "Pages has been updated", "reload" => true]);
}

function edit($values)
{
    create($values);
}

function delete($values)
{
    $output = DF\DB::delete("pages", $values);
    DF\Response::printSuccess(["msg" => "Entry has been deleted", "reload" => true]);
}

return route_api;
