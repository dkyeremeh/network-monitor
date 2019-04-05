<?php

class Events{
	protected static $actions = [];
	protected static $filters = [];
	
	//Used for adding acion
	
	
	static function add_action($hook, $func, $priority=10){
		if( isset(self::$actions[$hook]))
			$key = array_search($func,self::$actions[$hook]);
		else
			$key = false;
		if($key === false)
			self::$actions[$hook][] = $func;
	}
	static function remove_action($hook, $func){
		if($actions = self::$actions[$hook]){
			$key = array_search($func, $actions);
			if($key !== false)
				unset(self::$actions[$hook][$key]);
		}
	}
	static function call_action($hook){
		if($actions = self::$actions[$hook]){
			$args = func_get_args();
			unset($args[0]);
			foreach($actions as $func)
				call_user_func_array($func, $args);
			self::clear_action($hook);
		}
	}
	static private function clear_action($hook){
		if( isset(self::$actions[$hook]) )
			unset(self::$actions[$hook]);
	}
	
	//Filters
	static function add_filter($hook, $func, $priority=10){
		if( isset(self::$filters[$hook]))
			$key = array_search($func, self::$filters[$hook]);
		else
			$key = false;
		if($key === false)
			self::$filters[$hook][] = $func;
	}
	
	static function remove_filter($hook, $func){
		if($filters = self::$filters[$hook]){
			$key = array_search($func, $filter);
			if($key !== false)
				unset(self::$filters[$hook][$key]);
		}
	}
	static function apply_filter($hook, &$input){
		if($filters = self::$filters[$hook]){
			foreach($filters as $func)
				$input = call_user_func($func, $input);
			self::clear_filter($hook);
		}
	}
	static private function clear_filter($hook){
		if( isset(self::$filters[$hook]) )
			unset(self::$filters[$hook]);
	}
}