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
	class Crud{
		//Load
		static function query($query, $callback=false, $assoc=false){
			$con=new MysqlO;
			$res = $con->query($query) or die($con->error);
			$con->close();
			
			//What to do with the result
			//execute callback function if present
			if(gettype($callback)=="object")
				$callback($res);
			//return associative array if requested
			else if(gettype($callback)=="boolean")
				$assoc = $callback;
			if($assoc)
				return $res;
		}
		static function load($table, $id=false){
			if(gettype($id)=="array"){
				foreach ($id as $key=>$val){
					$c++ == 0 or $and = " AND";
					$MATCH .= "$and $key = '$val'";
				}
				$MATCH = " WHERE $MATCH";
			}
			$query="SELECT * FROM `" . $table . "`" . $MATCH;
			$res = self::query($query,true);
			
			$return = [];
			while($arr = $res->fetch_assoc()){
				$temp = [];
				foreach($arr as $key=>$val){
					if(!is_numeric($key))
						$temp[$key]=$val;
				}
				$return[] = $temp;
			}
			return $return;
		}
		static function update($table, $data){
			foreach($data as $key=>$val){
				$comma=($count>0)?",":"";
				$keys .= "$comma $key";
				$vals .= "$comma '$val'";
				$pairs .= "$comma $key='$val'";
				$count ++;
			}
			$query="INSERT INTO `". $table ."`($keys) VALUES($vals)
			ON DUPLICATE KEY UPDATE $pairs ";
			self::query($query);
		}
		static function delete($table, $data){
			foreach($data as $key=>$val){
				$sep=($count++>0)?" AND" : "";
				$pairs .= "$sep $key='$val'";
			}
			$query="DELETE FROM `". $table ."` WHERE $pairs ";
			self:: query($query);
		}
	}
?>