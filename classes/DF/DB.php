<?php

	namespace DF;
	/*
		A simple class for reading and writing data
		
		METHODS
		
		static query(string sql, [function $callback], [boolean $assoc])
			The query function is used to execute an sql statement from the database. It use a child class of MysqlO
			This function is used by all other methods to perform operations
			* $callback function can be used to execute a specific function with the result of the query;
			* $assoc = true will return an associative array after executing the query
			
		static read(string $table, array $match)
		
		static update(string $table, array $match)
			This function can be used for creating new entries and also for updating existing ones
			It uses entry update if a Duplicate key is found
			
		static delete(string $table, array $match)
			just as the name sounds
			
			
		static read
	*/
	class DB{
		static $con = [];
		
       	static private $db = [
			"host"=> DB_HOST,
			"user"=> DB_USER,
			"database"=> DB_NAME,
			"password"=> DB_PASS
		];
		
		static function connect(){
			if(!self::$con){
				extract (self::$db);
				self::$con = new \PDO("mysql:host=$host;dbname=$database", $user, $password);
				
				self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
				self::$con->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
			}
			return self::$con;
		}
		static function fetch_data($res){
			return $res->fetchAll();
		}
		static function prepare($query){

			try{
				$con = self::connect();
				return $con->prepare($query);
			}
			catch(\PDOException $e){
				trigger_error($e->getMessage());
			}
		}
		static function prepared_query($query, $values=[], $assoc = false){
			//create connection
			if(!$assoc && gettype($values)!="array"){
				$assoc = $values;
				$values = [];
			}
			try{
				$con = self::connect();
				$stmt = $con->prepare($query);
				$stmt->execute($values);
				
				if($assoc)
					return self::fetch_data($stmt);
				else
					return $stmt;
			}
			catch(\PDOException $e){
				trigger_error($e->getMessage());
			}
		}
		static function query($query,$assoc=false){
			try{
				$con = self::connect();
				$res = $con->query($query);
				if($assoc)
					return self::fetch_data($res);
				else
					return $res;
			}
			catch(\PDOException $e){
				trigger_error($e->getMessage());
			}
		}
		static function load($table, $values=false, $mods = ""){
			if(gettype($values)=="array"){
				foreach ($values as $key=>$val){
					$c++ == 0 or $and = " AND";
					$MATCH .= "$and `$key` = :$key";
					$values[":$key"] = $val;
				}
				$MATCH = " WHERE $MATCH";
			}
			if(gettype($mods)=="integer")
				$mods = "LIMIT $mods";
				
			$query="SELECT * FROM `" . $table . "`" . $MATCH . " $mods";
			$res = self::prepared_query($query, $values);
			return self::fetch_data($res);
		}
		static function update($table, $data){
			foreach($data as $key=>$val){
				$comma = $count++ == 0 ? "" : ",";
				$keys .= "$comma `$key`";
				$vals .= "$comma :$key";
				$pairs .= "$comma `$key`=:$key";
				$values[":$key"] = $val;
			}
			$query="INSERT INTO `". $table ."`($keys) VALUES($vals)
			ON DUPLICATE KEY UPDATE $pairs ";
			return self::prepared_query($query, $values);
		}
		static function delete($table, $data){
			foreach($data as $key=>$val){
				$sep=($count++>0) ? " AND" : "";
				$pairs .= "$sep `$key`=:$key";
				$values[":$key"] = $val;
			}
			$query="DELETE FROM `". $table ."` WHERE $pairs ";
			return self:: prepared_query($query, $values);
		}
	}
?>