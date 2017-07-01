<?php

class AnonymousController extends Controller{
	private $args;
	
	public function defaultAction($request){
		$view = new AnonymousView($request->getControllerName(),'accueil'); //appelle accueilview
		$view->render();
	}
	
	public function afficheinscription($request) {
		$view = new AnonymousView($this,'inscription');
		$view->render();		
	}
	
	public function connexion($request) {
		$view = new AnonymousView($this,'connexion');
		$view->render();		
	}
	
	public function connexionencours($args){
		
		$login = $args->read('connectLogin');
		if(User::isLoginUsed($login)){
			$password = $args->read('connectPassword');
			if(User::isPassWordRight($login,$password)){
				$newRequest = new Request();
				$newRequest->write('controller','user');
				$newRequest->write('action','defaultAction');
				$newRequest->write('user',$login);
				$userController = Dispatcher::dispatch($newRequest);
				$userController->execute();
				
			}
			else {
				
				$view = new AnonymousView($this,'connexion'); 
				$view->setArg('connectErrorText','Password is incorrect'); 
				$view->render();
			}
		}
		else {
			
			$view = new AnonymousView($this,'connexion'); 
			$view->setArg('connectErrorText','This login does not exist'); 
			$view->render();
		}
	}

	
	public function accueil($request) {
		$view = new AnonymousView($request->getControllerName(),'accueil'); // a affiner en fonction de connecté ou pas 
		$view->render();		
	}

	
	public function inscription($args) { //args est une requête
		$login = $args->read('inscLogin');
		if(User::isLoginUsed($login)) {
			$view = new AnonymousView($this,'inscription'); //réaffiche la page inscription
			$view->setArg('inscErrorText','This login is already used'); //affiche un message d'erreur
			$view->render(); //render est abstract peut être un pb
		} else {
			$password = $args->read('inscPassword');
			$nom = $args->read('nom');
			$prenom = $args->read('prenom');
			$mail = $args->read('mail');
		
			$user = User::createUser($login, $password,$mail,$nom,$prenom);
			
			//$this->startsession($login);
			//session_start();
			//$_SESSION[ 'login' ] = $login;
			
			if(!isset($user)) {	
				$view = new AnonymousView($this,'inscription');
				$view->setArg('inscErrorText', 'Cannot complete inscription');
				$view->render();
			} else {
				$newRequest = new Request();
				$newRequest->write('controller','user');
				$newRequest->write('action','defaultAction');
				$newRequest->write('user',$login);
				$userController = Dispatcher::dispatch($newRequest);
				$userController->execute();
			} 
		}
	}	
}
	
?>