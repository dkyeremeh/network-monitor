<?php

namespace Notification;

class SMS{
	function send($to, $text){
		global $OPTIONS;

		//InfoBib
		if(gettype($to)=="array")
			$url = "https://api.infobip.com/sms/1/text/multi";
		else
			$url = 'https://api.infobip.com/sms/1/text/single';
		
		$data = compact("to","text","from");
		$data_json = json_encode($data);
		$auth = base64_encode("$OPTIONS[infobip][username]:$OPTIONS[infobip][username]");

		$headers = [
			"Accept: application/json",
			"Content-type: application/json",
			"Authorization: Basic $auth"
		];

		//open connection to server
		$ch = curl_init($url);

		//set  headers, data
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data_json);

		//execute post
		ob_start();
		curl_exec($ch);
		$result = ob_get_clean();
		
		print_r( $result);

	}
	static function to_user($account, $message, $extra=[]){
		//Check if user's phone was set as an extra option, get user's phone.
		//Else use Account::get_users_info to get account's phone
		if( !($phone = $extra["phone"]) ){
			$phone = \Account::get_users_info( [ $account ], "phone" )[0]["phone"];
		}
		if(!$phone)
			return false;
		
		$phone = preg_replace("/^\+?233/", "0", $phone);
		$phone = preg_replace("/^0/", "233", $phone);

		
		//Send the mail
		static::send( $phone, $msg );
		
		return true;
	}
}