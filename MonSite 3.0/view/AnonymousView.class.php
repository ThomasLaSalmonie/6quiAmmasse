<?php
class AnonymousView extends View{
	
	public function render() //inclu les template dans la page
		{ 	
		$this->setArg('menu', 'menuAnonyme');
		$this->setArg('head', 'headAnonymous');
		
		$this->loadTemplate($this->args['head'], $this->args); 		
		$this->loadTemplate($this->templateNames['top'], $this->args); 		
		$this->loadTemplate($this->args['menu'], $this->args); 		
		$this->loadTemplate($this->templateNames['content'], $this->args); 	
		$this->loadTemplate($this->templateNames['foot'], $this->args);
		}
}	
	
?>