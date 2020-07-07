<?php
    namespace DF;
	class Cache{
		public static $dir = "cache";
		
		public static function get($file, $age=0){ //Retrieve a cache
			if(file_exists(self::$dir ."/$file"))
				if(time() - filectime(self::$dir ."/$file") < $age)
					return file_get_contents(self::$dir ."/$file");
		}
		public static function set($file,$content){ //Create Cache
			if(strlen($content)<10)
				return;
			$path = explode("/",$file);
			array_pop($path);
			$path=implode("/", $path);
			if(!file_exists(self::$dir ."/$path"))
				mkdir(self::$dir ."/$path");
			file_put_contents(self::$dir . "/$file",$content);
		}
		public static function invalidate($ids){ //Delete cache
			if(gettype($ids)=="array")
				$args = $ids;
			else
				$args = func_get_args();
			foreach($args as $id){
				if(file_exists(self::$dir ."/$id"))
					unlink(self::$dir ."/$id");
			}
		}
		public function start($id, $expire){ //Put at the beginning of the cache stream
			$this->id = strpos($id, ".")? $id : "$id.cache";
			if($cache = self::get($this->id, $expire)){
				print $cache;
				$this->id = null;
				exit();
			}
			ob_start();
		}
		public function end($id=false){ //Put at the end of cache stream
			$id = $id ? $id : $this->id;
			Cache::set($id,ob_get_flush());
			$this->id = null;
		}
		
		
		//
		public function __construct($id=false, $expire=0, $set_headers = false){
			$max_age = $expire/2;
			if($set_headers){
				header_remove("Expires");
				header_remove("Pragma");
				header("Cache-Control: no-transform, public, max-age=$max_age");
			}
			if($id)
				self::start($id, $expire);
		}
		public function __destruct(){
			if($this->id)
				self::end();
		}
	}

	//set cache directory
	global $APP_ROOT;
	Cache::$dir = "$ROOT/data/cache";