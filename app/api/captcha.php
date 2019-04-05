<?php

//Remove all previous captcha
$captcha = Captcha::generate();

DF\Response::printSuccess(["data"=>$captcha]);