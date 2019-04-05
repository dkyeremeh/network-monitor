<?php

class Captcha {
	static function generate(array $params=[]){
		$wordLen = 4;
		extract($params);

		if($_SESSION[__ZF])
			foreach($_SESSION[__ZF] as $key=>$val){
				unset($_SESSION[$key]);
			}
		$_SESSION[__ZF] = [];

		$captcha = new Zend\Captcha\Figlet(array(
		    'name' => 'captcha',
		    'wordLen' => $wordLen,
		    'timeout' => 300,
		));

		$data["id"] = $captcha->generate();

		//this will output a Figlet string
		$data["figlet"] = $captcha->getFiglet()->render($captcha->getWord());

		return $data;
	}
	static function verify($input){
				//validate captcha
		$captcha = new Zend\Captcha\Figlet(array(
			'name' => 'captcha',
		  	  'timeout' => 300,
		));

		return $captcha->isValid($input);
	}
}