<?php

$data["tools"] = $_SESSION["admin_tools"];
$data["account"] = $_SESSION['account'];

if($_SESSION['account'])
	DF\Response::printSuccess(["data"=>$data]);
else
	DF\Response::printFailure("You are logged out");