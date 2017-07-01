<?php
class Request extends MyObject  {
	//permet de ne pas manipuler trop de chaines de caractères -> simplification
	private static $current=NULL;
	
	public static function getCurrentRequest(){
		if(self::$current==null){
			self::$current = new Request();
		}
		return self::$current;
	}
	
	
	public static function getControllerName(){
		if(!isset($_GET['controller']))
			return 'Anonymous';
		else
			return $_GET['controller'];
	} //faudrait tester get post cookie
	
	
	public static function getActionName(){
		if(!isset($_GET['action']))
			return 'defaultAction';
		else
			return $_GET['action'];
	}
	
	public function read($toread){
		if(!isset($_POST[$toread]))
			throw new Exception ("$toread is not define");
		else
			return $_POST[$toread];
	}
	
	public function write($key, $value){
		$_GET[$key]=$value;
	}

}

//après on pourra appeler Request::getCurrentRequest()->getControllerName()