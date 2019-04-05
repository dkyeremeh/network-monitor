<?php

namespace Notification;

class Email{
	
	static function send($d, $t, $m, $options = []){
		
		//prepare and sanitize email list
		if(gettype($d)!=="array"){
			$d = explode(",",$d);
		}
		foreach($d as $key=>$D){
		
			$D = \Filter::sanitize($D, "email");	//Sanitize Email
			
			if( ! \Filter::validate($D,"email") ){	//Validate Email
				unset($d[$key]);
			}
		}
		
		if(!count($d))
			trigger_error("No valid email was specified", E_USER_ERROR);
		
		$d = join(",", $d);
		
		$options["title"] = $t;
		$options["message"] = $m;
		
		
		//Fetch template
		$smarty = load_smarty();
		$template = $options["template"] ? $options["template"] : "email.tpl";
		$msg = $smarty->fetch($template, $options);
		

		//Set headers
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= "From: " . SITE_NAME . " <".SITE_EMAIL ."> \r\n";
		
		//Send the mail
		return @mail($d, $t, $msg, $headers );
	}
	
	static function to_user($account, $title, $msg, $extra=[]){
		global $SITE_INFO;
		
		if( !($email = $extra["email"]) ){
			$email = \Account::get_users_info( [$account], "email" )  [0]["email"];
		}
		$msg = \Parsedown::instance()->text( $msg );
		
		if($extra["url"])
			$msg .= "<p><br/><a href='$SITE_INFO[base]/$extra[url]'>Open Manager</a></p>";
		
		//Send the mail
		return static::send( $email, $title, $msg, $extra );
	}
	
}