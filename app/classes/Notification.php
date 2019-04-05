<?php

class Notification{
	static function send($user, $title, $msg, $options){
		$allowed_types = ["auto", "app", "email","push","sms"];

		//Set notification type
		if( is_array($options) ){
			$type = $options["type"];
			unset( $options["type"] );
		}
		else{
			$type =$options ? $options : "auto";
			$options = [];
		}

		//check if type is allowed
		if( !in_array($type, $allowed_types) )
			trigger_error("Invalid notification type '$type' ", E_USER_ERROR);

		//Notification period
		$period = $options["period"] ? $options["period"] : 10;
		unset($options["period"]);
		

		//check if data already exists
		$exists = DF\DB::prepared_query(
		 	"SELECT id, msg FROM notifications
			WHERE
				account = :account AND title = :title AND type = :type AND status = '0' ",
			[ ":account" => $user, ":title" => $title, ":type" => $type ] ,
			true
		)[0];

		//Append new message to existing if it exist
		if($exists){
			$exists["msg"] .= "\n\n$msg";
			DF\DB::update("notifications", $exists);
			
			$options["notice_id"] = $exists["id"];
		}
		else{
			//New message
			DF\DB::prepared_query(
				"INSERT INTO notifications(account, msg, title, type, delivery_time, extra)
				 VALUES( :account, :msg, :title, :type, CURRENT_TIMESTAMP + INTERVAL :period MINUTE , :extra) ",
				 [ 
					":account" => $user, 
					":msg"=>$msg,
					 ":title" => $title,
					 ":type" => $type,
					 ":period" => $period,
					 ":extra" => json_encode($options),
				 ]
			);

			$options["notice_id"] = DF\DB::$con->lastInsertID();
		}
		
		//Send push notification
		Notification\Push::to_user($user, $title, $msg, $options);
	}


	static function send_inactive(){
		if(! function_exists("get_ids")){
			function get_ids($row){return $row["id"];}
		}

		//Get all unsent notices
		$notices = DF\DB::query( "SELECT * FROM notifications WHERE delivery_time <= CURRENT_TIMESTAMP AND status = '0'  AND type != 'app' " , true );

		if(!$notices){
			return;
		}

		//Get id of all notices
		$id_r = array_map( "get_ids", $notices );
		$ids = join(",", $id_r);

		//Set status to sent (1)
		DF\DB::query( "UPDATE notifications SET status = '1' WHERE id IN ( $ids ) ;" );

		foreach($notices as $notice){
			switch($notice["type"]){
				case "auto":
					Notification\Auto::send( $notice["account"], $notice["title"], $notice["msg"], json_decode( $notice["extra"], true) );

				break;

				case "email":
					//Send the mail
					Notification\Email::to_user( $notice["account"], $notice["title"], $msg, json_decode($extra) );
				break;

				case "sms":
					Notification\SMS::to_user( $notice["account"], $notice["title"] ."\n\n" . $notice["msg"], json_decode($extra) );

				break;

				case "push":
					Notification\Push::to_user( $notice["account"] , $notice["title"] , $notice["msg"], json_decode($extra) );
				break;
			}
		}

	}

	static function fetch($get_all = false){
		$account = Account::get_id();

		//Return response if account info cannot be found
		if(!$account)
			return;

		if( $get_all !== true )
			$query_status = "AND status = '0' ";

		//Get notifications belonging to the account 
		$notices = DF\DB::query("SELECT * FROM notifications WHERE account = '$account' $query_status;", true);
		if( !$notices )
			return;

		if( !function_exists("get_ids") ){
			function get_ids($row){return $row["id"];}
		}

		//Get id of all notices
		$id_r = array_map( "get_ids", $notices );
		$ids = join(",", $id_r);

		//Set status of retrieved notifications to 1 (sent)
		DF\DB::query( "UPDATE notifications SET status = '1' WHERE id IN ( $ids )  AND status = '0' ;" );

		return $notices;
	}
	static function send_active($response){
		$notification = [];

		//return if $response already has notification. This is to prevent loading of data twice
		if( isset($response["notification"]) )
			return $response;

		//Fetch all unseen notification
		$notification["data"] = static::fetch();

		//Append notification to response
		if($notification["data"])
			$response["notification"] = $notification;

		//Response
		return $response;
	}
}