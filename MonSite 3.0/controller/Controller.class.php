<?php
abstract class Controller extends MyObject {
	private $myrequest;
	private $currentAction;

	
	public function __construct($request){
		$this->myrequest=$request;
	}
	
	abstract function defaultAction($request);
	
	abstract function accueil($request);
	
	public function execute(){
		$currentAction = $this->myrequest->getActionName();
		
		if(!method_exists($this,$currentAction))
			throw new Exception ("$currentAction does not exist");
		
		return $this->$currentAction($this->myrequest);
		
	}
	
	public function meilleurscore(){
		$pdo= DatabasePDO::CurrentDatabase();
		$request = $pdo->prepare("SELECT utilisateur.login,SCORE FROM partiearchivee,utilisateur WHERE utilisateur.ID_JOUEUR=partiearchivee.ID_JOUEUR ORDER BY SCORE ASC LIMIT 5");
		$request->execute();
		$stockage=$request->fetchAll(PDO::FETCH_OBJ);
		$param1="login";
		$param2="SCORE";
		$i=0;
		$j=0;
		
		do{
			if(isset($stockage[$i])){
				$args['joueur'.$i]=$stockage[$i]->$param1;
				$args['score'.$i]=$stockage[$i]->$param2;
				$j++;
			}
			$i++;
		} while ($i<5);
		$args['nb']=$j;
		if(isset($_SESSION['login'])){
			$view = new UserView($this,'afficherscore',$args);
			$view->render();			
		}
		else{
			$view = new AnonymousView($this,'afficherscore',$args);
			$view->render();
		}
	}
	
	
	
}
	
?>