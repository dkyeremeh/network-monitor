<?php

// Check status of all services
$cron->add( 'check-status', [
	'schedule' => '* * * * *',
	'enabled' => true,
	'function' => function() {

		$statusChanges = [];	// List of divice id and their new stutus'
		$message = "";

		$dev_serv = DF\DB::load("dev_serv");

		// Check running services and save status changes to $statusChanges
		foreach ($dev_serv as $s){

			// Check if service is running
			if( !$s["device"] ){
				$sock = @fsockopen($s["address"], $s["port"]);
				$status = $sock ? 1 : 0;
				fclose($sock);
			}
			else{
				// TODO: Different variant for windows
				@exec("ping -c1 $s[address]", $output, $error);
				$status = $error ? 0 : 1;
			}

			//Add service $statusChanges
			if( $status != $s["status"] ){
				$statusChanges[ $s["id"] ] = $status;
				$message .= "$s[name] is now " . ( $status ? "*online*" : "*offline*") . "\n";
			}
		}
		

		// Prepare queries to update DB
		$updateStatus = DF\DB::prepare("UPDATE dev_serv SET `status` = :status WHERE `id` = :id");
		$logStatusChange = DF\DB::prepare("INSERT INTO status_changes SET `status` = :status, `dev_serv` = :id");

		// Update DB tables
		foreach( $statusChanges as $id => $status){
			$values = compact([ "id", "status"] );
			$updateStatus->execute( $values );
			$logStatusChange->execute( $values );
		}

		// Return if $OPTIONS are not set
		if( ! (isset($OPTIONS) && $OPTIONS["notify"] ) ){
			return;
		}

		// Send notification for services which just changed status
		if( $statusChanges && $OPTIONS["notify"]["email"] ){
			Notification\Email::send( $OPTIONS["notify"]["alertEmail"], "Device & Services Update", $message );
			echo "Email Sent";
		}

		if( $statusChanges && $OPTIONS["notify"]["sms"] ){
			Notification\SMS::send( $OPTIONS["notify"]["alertPhone"], $message );
			echo "SMS Sent";
		}

	},
]);

