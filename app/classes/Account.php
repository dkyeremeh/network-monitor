<?php

//Customer ids are numeric
//module are organisations. Their ids begin with _
//Anonumous users have their accounts ending with +
//module_users are users in an organisation. ids begin with *
//Merchant ids are alphanumeric text


class Account{
	protected static $accountTables= [
		"customer" => "customers",
		"module" => "users",
		"module_user" => "users",
		"anonymous" => "anonymous_users",
		"merchant" => "shops",
	];
	
	protected static $prefixes = [
		"module_user"=>"*",
		"module" => "_",
	];
	
	protected static $suffixes = ["anonymous"=>"+"];
	
	protected static $key_cols = [
		"customer"=>"id",
		"merchant"=>"id",
		"anonymous"=>"id",
		"module"=>"organisation",
		"module_user"=>"username"
	];
	
	function get(){
		return $_SESSION["account"];
	}
	function get_id(){
		//Exit with error response if user is not logged in
		if($_SESSION["account"]["shop"])
			return $_SESSION["account"]["shop"];
		else if($_SESSION["account"]["id"])
			return $_SESSION["account"]["id"];
		else if($_SESSION["account"]["organisation"])
			return "_" . $_SESSION["account"]["organisation"];
		else
			return false;
	}
	function get_type($account=false){
		if(!$account)
			$account = static::get_id();

		if(is_numeric($account)) //Customer
			return "customer";

		else if(substr($account, 0,1) == "_")
			return "module";
		
		else if(substr($account, 0,1) == "*")
			return "module_user";

		else if(substr($account,-1) =="+")
			return "anonymous";
		else
			return "merchant";
	}
	function get_table($account=false){
		if(!$account)
			$account = static::get_id();

		$type = static::get_type($account);
		return static::$accountTables[$type];
	}
	function get_users_info(array $users, $fields="*", $accCol="account"){
		//$users must be a list of ids of a list of account info array
		$accounts = [];
		$info = [];

		//Arrange accounts accourding to account types
		if( is_array($users[0]) && $users[0][$accCol] )
			foreach ($users as $user){
				$type = static::get_type($user[$accCol]);
				$accounts[$type][] = $user[$accCol];
			}
		else{
			foreach ($users as $user){
				$type = static::get_type($user);
				$accounts[$type][] = $user;
			}
		}
		unset ($users);		
		
		//Convert $fields into a string if its an array
		if(gettype($fields)=="array"){
			$fields = array_map( function($f){return "`$f`"; }, $fields );
			$fields = join(',', $fields);
		}

		//Prepare and perform queries
		foreach($accounts as $key=>$type){
			
			//Remove the suffixes from accounts
			if(static::$suffixes[$key]){
				$suf = array_fill(0, count($type), static::$suffixes[$key] );
				$type = array_map( function($val, $suf){
					return str_replace( $suf,"", $val); 
				} , $type, $suf);
			}
			

			//Remove the prefixes from accounts
			if(static::$prefixes[$key]){
				$pre = array_fill(0, count($type), static::$prefixes[$key] );
				$type = array_map( function($val, $pre){
					return str_replace( $pre,"", $val); 
				} , $type, $pre);
			}
			
			$table = static::$accountTables[$key];	//Table to fetch data from
			$key_col = static::$key_cols[$key];	//column data from
			
			//ids to be used for info
			$ids = array_map(function( $id ){ return "'$id'";}, $type);
			$ids = join(",", $ids);

			$accountData = DF\DB::query("SELECT $fields FROM `$table` WHERE `$key_col` IN ( $ids )", true);
			
			//var_dump(static::$prefixes[$key],static::$suffixes[$key],$type);
			
			foreach($accountData as $account){
				if($account[$key_col]){
					$id =  static::$prefixes[$key] . $account[$key_col] . static::$suffixes[$key] ;
					if($key == "module"){
						foreach($account as $k=>$v){
							$info[$id][$k][] = $v;
						}
					}
					else
						$info[$id] = $account;
				}
				else
					$info[] = $account;
			}
		}

		return $info;
		//return call_user_func_array( 'array_merge', $info );
	}
	function get_names(array $users, $accCol = "account"){
		$accounts = [];	
		$names = [];	//Container for the names output
		$columns = [
			"anonymous"=> "nickname",
			"customer"=>"extra",
			"merchant" =>"name",
			"module_user" =>"details",
		];	//Columns containing the info we need from each table
		$accountMapFn = [
			"anonymous" => function( $r ){return $r["nickname"]; },
			"customer" => function( $r ){
				$r = json_decode( $r["extra"], true ); 
				return $r["nickname"] ? $r["nickname"] : $r["name"];
			},
			"merchant" => function( $r ){return $r["name"]; },
			"module" => function( $r ){return $r["organisation"]; },
			"module_user" => function( $r ){
				$d = json_decode( $d["details"], true ); 
				return $d["name"] ? $d["name"] : $r["username"]; 
			},
		];

		//Arrange accounts accourding to account types
		if( is_array($users[0]) && $users[0][$accCol] )
			foreach ($users as $user){
				$type = static::get_type($user[$accCol]);
				$accounts[$type][] = $user[$accCol];
			}
		else{
			foreach ($users as $user){
				$type = static::get_type($user);
				$accounts[$type][] = $user;
			}
		}
		unset ($users);

		//Prepare and perform queries
		foreach($accounts as $key=>$type){
			
			//Remove the suffixes from accounts
			if(static::$suffixes[$key]){
				$suf = array_fill(0, count($type), static::$suffixes[$key] );
				$type = array_map( function($val, $suf){
					return str_replace( $suf,"", $val); 
				} , $type, $suf);
			}
			

			//Remove the prefixes from accounts
			if(static::$prefixes[$key]){
				$pre = array_fill(0, count($type), static::$prefixes[$key] );
				$type = array_map( function($val, $pre){
					return str_replace( $pre,"", $val); 
				} , $type, $pre);
			}			
			
			$table = static::$accountTables[$key];	//Table to fetch data from
			$key_col = static::$key_cols[$key];	//Table to fetch data from
			
			//Create a list of ids
			$ids = array_map(function( $id ){ return "'$id'";}, $type);
			$ids = join(",", $ids);

			$column = $columns[ $key ];	//Column to fetch data from
			$column = $column ? ",`$column`" : "";

			//Fetch Columns containing the account names
			$accountData= DF\DB::query(" SELECT `$key_col` $column FROM `$table` WHERE `$key_col` IN ( $ids ) ", true);

			//Create An associative array having the id as key and account names as values
			foreach ($accountData as $account){
				$id =  static::$prefixes[$key] . $account[$key_col] . static::$suffixes[$key] ;
				$names[ $id ] = $accountMapFn[$key]($account);
			}
		}

		return $names;
	}
}