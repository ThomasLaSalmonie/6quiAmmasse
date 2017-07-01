<?php
class Dispatcher extends MyObject {
	
	public static function dispatch($request){
		
		$controllername = ucfirst($request::getControllerName().'Controller'); //créer une classe de controller
		
		$controller = new $controllername($request); //ici on instancie une classe dont le nom est contenu dans la variable
	
		return $controller;
	}

}
