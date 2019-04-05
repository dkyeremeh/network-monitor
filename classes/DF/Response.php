<?php

    namespace DF;
    
/*
	INTRODUCTION
	
	This class creates a JSON response from an array or other inputs
	
	METHODS
	
	static functon printSuccess(mixed $data, boolean $exit=false)
		This method is for printing a response which a status of true which indicates a success
		
	static functon printFailure(multi $data, boolean $exit=false)
		This method is for printing a response with a status of false which indicates a failure
		
	
*/
class Response{
	static protected function printResponse($data, $exit=false){
		echo json_encode($data);
		if($exit)
			exit();
	}
	static function printSuccess($data, $exit=false){
		if(gettype($data) != "array")
			$data = ["msg" => $data];
		$data["status"] = true;

		if(class_exists("Events")){
			\Events::call_action("df_response_success", $data);
			\Events::apply_filter("df_response_success", $data);
		}

		self::printResponse($data, $exit);
	}
	static function printFailure($data, $exit=false){
		if(gettype($data) != "array")
			$data = ["msg" => $data];
		$data["status"] = false;

		if(class_exists("Events")){
			\Events::call_action("df_response_failure", $data);
			\Events::apply_filter("df_response_failure", $data);
		}
		self::printResponse($data, $exit);
	}
	
}
