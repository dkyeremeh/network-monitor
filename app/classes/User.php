<?php

class User{
	function load(){
		//Show all users for system Admin, else show only users from Organisation
		
		$account = $_SESSION["account"];
		
		if($account["module"] != "system"){
			$constraints = [
				"module" => $account["module"],
				"organisation" => $account["organisation"],
			];
			$module_restrict = "WHERE module='$account[module]'";
		}
		
		//Load modules for the user admin
		$data["tools"] = DF\DB::query("SELECT module,role AS role FROM access_control $module_restrict GROUP BY module, role", 1);
		
		$list = DF\DB::load("users", $constraints);
		
		//Remove password 
		foreach($list as $user){
			unset($user["password"]);
			$data["users"][] = $user;
		}
		unset($list);
		
		//Response
		DF\Response::printSuccess(["data" => $data]);
	}
	function create($data, $edit=false){
		extract($data);
		$module="system";
		//Set variables
		$account = $_SESSION["account"];
		$required = ["module", "username","role"];
		$allowed = ["module", "username","password","role","email","details"];

		//Restrict cross module/organisational user adding; except for system admin
		if($account["module"] != "system"){
			$module = $account["module"];
			$organisation = $account["organisation"];
		}
		
		//Check existing user (For editing)
		if($edit){
			//Check if user exists
			$exists = DF\DB::load("users",compact("username","module","organisation"));
			
			if(!$exists) 	//User doesn't exist
				DF\Response::printFailure("Specified user does not exist", 1);
		}
		
		//check existing user (For Adding)
		else{
			$exists = DF\DB::load("users",compact("username"));
			
			if($exists) 	//User doesn't exist
				DF\Response::printFailure("Another user exists with this username", 1);
		}
		
		//Required algorithm
		foreach($required as $key)
			if(!isset($$key))
				DF\Response::printFailure("Invalid field '$key'",1);
		
		//Sanitize Data
		$username = Filter::sanitize($username, "alphanumeric");
		
		//Password logic for new users and when editing password
		if(!$edit || $editPassword){
			if($password != $repassword) //No password was set Or paswords don't match
				DF\Response::printFailure("Passwords do not match", 1);
				
			if(strlen($password)<7)
				DF\Response::printFailure("Password is too short", 1);
			$password = create_password($password);
		}
		else{
			unset($password);
		}
		
		//Sanitize and validate Email if it has been set
		if($email){
			$email = Filter::sanitize($email,"email");
			if(! Filter::validate($email, "email"))
				DF\Response::printFailure("Invalid email address", 1);
		}
		
		//New organisation algorithm
		if($organisation == 'new')
			$organisation = $newOrg;
		
		//Insert Data
		DF\DB::update("users", compact($allowed) );
		
		//Response
		DF\Response::printSuccess(["msg"=>"Account was processed successfully", "reload"=> true]);
	}
	function delete($data){
		//Set variables
		extract($data);
		$account = $_SESSION["account"];
		$constraint = [];
		$username = $id;
		
		//Prevent User from deleting his own account
		if($username == $account["username"])
			DF\Response::printFailure("You cannot delete your account", 1);
		
		//Restrict cross module/organisational user deleting; except for system admin
		if($account["module"] != "system"){
			$constraints = [
				"module" => $account["module"],
				"organisation" => $account["organisation"],
			];
		}
		//Remove User
		$trans = DF\DB::delete("users", compact("username", $constraint));
		
		//Response
		if ($trans->rowCount()>0) //Deleted successfully
			DF\Response::printSuccess(["msg"=>"User deleted","reload"=>true]);
		else	//Unsuccessful
			DF\Response::printFailure("Specified user does not exist", 1);
			
	}
	function edit($data){
		$account = $_SESSION["account"];
		
		//prevent user from changing own important entries
		if($account["username"]==$data["username"]){
			$data["role"]= $account["role"];
		}
		self::create($data, true);
	}
}