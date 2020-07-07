<?php

namespace Notification;

// Load Composer's autoloader
require 'vendor/autoload.php';

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;


class Email{
	
	static function send($d, $t, $m, $options = []){

		global $OPTIONS;
		
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
		
		if(!count($d)){
			trigger_error("No valid email was specified", E_USER_ERROR);
		}
		
		$options["title"] = $t;
		$options["message"] = \Parsedown::instance()->text( $m );;
		
		
		//Fetch template
		$smarty = load_smarty();
		$template = $options["template"] ? $options["template"] : "email.tpl";
		$msg = $smarty->fetch($template, $options);

		//Send the mail
		// return @mail($d, $t, $msg, $headers );
		
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);
		
		try {
			//Server settings
			// $mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = $OPTIONS["smtp"]["server"];  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $OPTIONS["smtp"]["username"];                     // SMTP username
			$mail->Password   = $OPTIONS["smtp"]["password"];                               // SMTP password
			$mail->SMTPSecure = $OPTIONS["smtp"]["cert"];                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = $OPTIONS["smtp"]["port"];                                    // TCP port to connect to
		
			//Recipients
			$mail->setFrom( $OPTIONS["smtp"]["username"], $_ENV["SITE_NAME"]);

			foreach($d as $a){
				$mail->addAddress($a);               // Name is optional
			}
		
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $t;
			$mail->Body    = $msg;
			$mail->AltBody = $m;
		
			$mail->send();
		} catch (Exception $e) {
			$LOG->error($mail->ErrorInfo);
		}
	}
	
	static function to_user($account, $title, $msg, $extra=[]){
		
		if( !($email = $extra["email"]) ){
			$email = \Account::get_users_info( [$account], "email" )  [0]["email"];
		}
		$msg = \Parsedown::instance()->text( $msg );
		
		if($extra["url"])
			$msg .= "<p><br/><a href='$_ENV[SITE_URL]/$extra[url]'>Open Manager</a></p>";
		
		//Send the mail
		return static::send( $email, $title, $msg, $extra );
	}
	
}