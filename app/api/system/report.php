<?php

function fetch($values){

	extract( $values );

	$now = time();
	$startTime = 0;
	$endTime = 0;

	if(!$dev_serv){
		return DF\Response::printFailure("No device or service was specified");
	}
	//Check if item exists
	$devData = DF\DB::load("dev_serv",["id"=> $dev_serv ]);
	if( !$devData ){
		return DF\Response::printFailure("No device or service does not exist");
	}
	$devData = $devData[0];


	// Fetch data
	$sql = "SELECT status, at FROM status_changes WHERE dev_serv = :dev_serv ";
	$params = ["dev_serv"];

	if($from){
		$sql .= "AND at >= :from ";
		$params[] = "from";

		$startTime = max( strtotime($from), strtotime( $devData["at"] ) );
		$startTime = min( $startTime, $now );
	} 
	if($to){
		$sql .= "AND at <= :to ";
		$params[] = "to";

		$endTime = min( strtotime($to), $now );
	} 
	$data = DF\DB::prepared_query($sql, compact($params), true );

	// Set data for the $from time
	// Fetch last known data if no data was found
	if( $from && !$data ){
		$data = DF\DB::prepared_query(
			"SELECT status, at FROM FROM status_changes WHERE dev_serv = :dev_serv AND at < :from ORDER BY id DESC LIMIT 1",
			compact(["from","dev_serv"]),
			true
		);

		// Set 
		if($data){
			$data[0]["at"] = $from;
		}
	}

	else if( !$data ){
		return DF\Response::printFailure("Device has been offline");
	}

	else{
		array_unshift( $data, [
			"at" => $from ? $from : $devData["at"],
			"status" => !$data[0]["status"]
		]);
	}

	//Append the data for the $to time
	$data[] = [
		"at" => $to ? $to : date("Y-m-d H:i:s"),
		"status" => $data[ count($data) - 1]["status"]
	];

	// Calculate total duration
	$startTime = $startTime ? $startTime : strtotime( $data[0]["at"]);
	$endTime = $endTime ? $endTime : strtotime( $data[ count($data) - 1 ]["at"]);

	// Calculate total downtime and uptime
	$downtime = 0;
	$uptime = 0;
	$downtimes = [];

	for( $x = 1; $data[$x]; $x++ ){
		$xTime = strtotime( $data[$x]["at"] );
		$prevTime = strtotime( $data[$x-1]["at"] );

		// Downtime
		$downtime += !$data[$x-1]["status"] * ( $xTime - $prevTime);

		if( !$data[ $x-1 ]["status"]){
			$downtimes[] = [
				"start" => $prevTime,
				"end" => $xTime,
			];
		}

		// Downtime
		$uptime += $data[$x-1]["status"] * ( $xTime - $prevTime );
	}

	// Response data
	$resData = [
		"downtimes" => $downtimes,
		"downtime" => $downtime,
		"uptime" => $uptime,
		"start" => $startTime,
		"end" => $endTime,
		"dev_serv" => $devData,
	];



	DF\Response::printSuccess(["data"=>$resData]);
}


return route_api;