<?php
class UserView extends View{
	
	public function render() //inclu les template dans la page
	{ 
	//$this->setArg('menu','menuUser');

	
		
	$this->setArg('head','headUser');
	$this->loadTemplate($this->args['head'], $this->args); 		
	$this->loadTemplate($this->templateNames['top'], $this->args); 
	if(isset($_GET['play'])){
		if($_GET['play']=='play'){
			$this->setArg('menu','menuPlay');
			$this->loadTemplate($this->args['menu'], $this->args); 		
		}
	}
	$this->loadTemplate($this->templateNames['content'], $this->args); //usertemplate	
	$this->loadTemplate($this->templateNames['foot'], $this->args);
	}
	
	
}