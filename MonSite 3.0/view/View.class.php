<?php

abstract class View extends MyObject { 
	
	protected $args;
	protected $templateNames;
	
	public function __construct($controller, $templateName, $args = array()) { 
	$this->templateNames = array();
	$this->templateNames['head'] = 'headAnonymous';
	$this->templateNames['top'] = 'top'; 
	$this->templateNames['menu'] = 'menuAnonyme'; 
	$this->templateNames['foot'] = 'foot'; 
	$this->templateNames['content'] = $templateName; 
	$this->args = $args;
	$this->args['controller'] = $controller; }
	
	public function setArg($key, $val) {
		$this->args[$key] = $val; 
	}
			
	public function loadTemplate($name,$args=NULL, $prop=NULL) //charge les template
	{ 
		$templateFileName = __ROOT_DIR  . '/templates/'. $name . 'Template.php';
			if(is_readable($templateFileName)) { 
				if(isset($args))
				foreach($args as $key => $value) //{
						$$key = $value;
				//print_r($templateFileName);}
				require_once($templateFileName); 
				}
			else
				throw new Exception('undefined template "' . $name .'"'); 
	}
			
	public abstract function render();
	/*public function render() //inclu les template dans la page
		{ 	
		$this->loadTemplate($this->templateNames['head'], $this->args); 		
		$this->loadTemplate($this->templateNames['top'], $this->args); 		
		$this->loadTemplate($this->templateNames['menu'], $this->args); 		
		$this->loadTemplate($this->templateNames['content'], $this->args); 	
		$this->loadTemplate($this->templateNames['foot'], $this->args);
		}*/
} 