<?php

class UserController extends Controller{
	
	public function __construct($request){
		session_start();
		parent::__construct($request);
		if(isset($_SESSION['login']))
			$sessionlogin = $_SESSION['login'];
		if(isset($_POST['inscLogin']))
			$sessionlogin = $_POST['inscLogin'];
		if(isset($_POST['connectLogin']))
			$sessionlogin = $_POST['connectLogin'];
		//startsession($sessionlogin);
		$_SESSION[ 'login' ] = $sessionlogin;
		
	} 

	public function defaultAction($request){
		$view = new UserView($request->getControllerName(),'user'); 
		//$view->setArg('menu', 'menuUser');
		$view->render();
	}

	public function deconnexion($request){
		session_destroy();
		$newRequest = new Request();
		$newRequest->write('controller','anonymous');
		$newRequest->write('action','defaultAction');
		$userController = Dispatcher::dispatch($newRequest);
		$userController->execute();
		
	}
	
	public function accueil($request) {
		$view = new UserView($request->getControllerName(),'user');// a affiner en fonction de connecté ou pas 
		$view->render();		
	}
	
	public function afficherecherche() {
		
		$view = new UserView($this,'rechercher'); // a affiner en fonction de connecté ou pas ou a redefinir dans UserController
		$view->render();	
	}
		
	public function showProfile(){
			User::showProfile();
	}
	
	public function rechercher() {
		$loginR = $_POST['loginR'];
		if (User::isLoginUsed($loginR)){
			User::showProfile($loginR);
		}	
		else {
			$view = new UserView($this,'rechercher'); 
			$view->setArg('RechErrorText','This login does not exist'); 
			$view->render();
		}
	}
	
	public function play(){
		$view = new UserView($this,'menuPlay');
		$view->render();
	}
	
	public function creerpartie(){
		$args=array();
		$stockage=Model::stockRequest("SELECT LOGIN FROM UTILISATEUR WHERE LOGIN!='".$_SESSION['login']."'");
		$param='LOGIN'; 
		$i=0;
		foreach($stockage as $user){
			$args['utilisateur'.$i]=$user->$param;
			$i=$i+1;
		}
		$args['nbuti']=$i;
		$view = new UserView($this,'invitation',$args); 
		$view->render();
	}
	
	public function listerpartiesencours(){
		User::getPartiesEncours($_SESSION[ 'login' ]);
	}
	
	public function listerpartiespublic(){
		User::getPartiespubliques($_SESSION[ 'login' ]);
		
	}
	
	public function listehistoriqueparties(){
		User::gethistoriqueparties($_SESSION[ 'login' ]);
	}
	
	public function listerpartiesencreation(){
		User::getpartiescreees($_SESSION[ 'login' ]);
	}
	
	public function rejoindrepartie(){
		User::rejoindrePartie($_SESSION[ 'login' ],$_GET['partie']);
		User::deleteinvitation($_SESSION[ 'login' ],$_GET['partie']);
		$nbjoueurs = User::selectElmFromWhere('NB_JOUEURS','PARTIE',"ID_PARTIE=".$_GET['partie']."");
		if($nbjoueurs[0]==10){
			$this->demarrerpartie();
		}
		
		else
			User::showpartielancee();
	}
	
	public function showinvitation(){
		User::showinvitationlogin($_SESSION[ 'login' ]);
	}
	
	public function inviter(){
		User::creerpartie($_SESSION[ 'login' ]);
		$i=1;
		do{	
			
			User::inviterjoueur($_POST['utilisateur'.$i]);
			$i++;
			
		}while($_POST['utilisateur'.$i]!='nul' && $i<6);
	}
	
	public function creersansinviter(){
		User::creerpartie($_SESSION[ 'login' ]);
		$_GET['partie']=$_SESSION['partie'];
		$args['createur']=$_SESSION['login'];
		User::showpartielancee($args);
	}
	
	public function accepterinvitation(){
		User::rejoindrePartie($_SESSION[ 'login' ],$_GET['partie']);
		User::deleteinvitation($_SESSION[ 'login' ],$_GET['partie']);
		User::showpartielancee();
	}
	
	public function refuserinvitation(){
		User::deleteinvitation($login = $_SESSION[ 'login' ],$_GET['partie']);
		User::showinvitationlogin($_SESSION[ 'login' ]);
	}
	
	public function poursuivrepartie(){
		$args['joueur']=$_SESSION['login'];
		$args['userPlayed']='false';
		User::showpartielancee($args);
	}
	
	public function quitterpartie(){
		
		
		Model::doRequest("DELETE FROM REJOINDRE WHERE ID_JOUEUR=".User::getElementbyLogin($_SESSION['login'],'ID_JOUEUR')." AND ID_PARTIE=".$_GET['partie']."");
		
		Model::doRequest("INSERT INTO PARTIEARCHIVEE(ID_JOUEUR,ID_PARTIE,SCORE) VALUES (".User::getElementbyLogin($_SESSION['login'],'ID_JOUEUR').",".$_GET['partie'].",150)");
		Model::doRequest("UPDATE PARTIE SET NB_JOUEURS=NB_JOUEURS-1 WHERE ID_PARTIE=".$_GET['partie']."");
		
		//si il n'y a plus qu'un joueur dans la partie la partie se supprime 
		//recuperation du nombre de joueurs 
		$stockagenb=Model::stockRequest("SELECT NB_JOUEURS FROM PARTIE WHERE ID_PARTIE=".$_GET['partie'].""); 
		$paramnb='NB_JOUEURS';
		$nbjoueurs=$stockagenb[0]->$paramnb;
		
		if($nbjoueurs==1){
			Model::doRequest("UPDATE PARTIE SET ETAT='SUPRIMEE' WHERE ID_PARTIE=".$_GET['partie']."");
		}
		
		//affiche les parties en cours
		$newRequest = new Request();
		$newRequest->write('controller','user');
		$newRequest->write('action','listerpartiesencours');
		
		User::getPartiesEncours($_SESSION[ 'login' ]);

	}
	
	public function supprimerpartie(){
		Model::doRequest("UPDATE PARTIE SET ETAT='SUPRIMEE' WHERE ID_PARTIE=".$_GET['partie']."");
		$this->listerpartiespublic();	
	}
	
	public function demarrerpartie(){
		//On démarre uniquement si il y a plus de 2 joueurs
		$idjoueur=User::getElementbyLogin($_SESSION['login'],'ID_JOUEUR');
		$stockagenb=Model::stockRequest("SELECT NB_JOUEURS FROM PARTIE WHERE ID_PARTIE=".$_GET['partie'].""); 
		$paramnb='NB_JOUEURS';
		$nbjoueurs=$stockagenb[0]->$paramnb;
		
		if($nbjoueurs>=2){
		//set le nombre de coups à 0
		Model::doRequest("INSERT INTO JOUERCOUP (ID_PARTIE,NB_COUPS) VALUES (".$_GET['partie'].",0)");
		
		Model::doRequest("UPDATE PARTIE SET STATUT='PRIVEE', ETAT='EN COURS' WHERE ID_PARTIE=".$_GET['partie'].""); //la partie devient privée on ne peut plus la rejoindre
		
		Model::doRequest("DELETE FROM INVITER WHERE ID_PARTIE=".$_GET['partie'].""); //on supprime toutes les invitations
		
		$stockagecartes=Model::stockRequest("SELECT ID_CARTE FROM CARTE ORDER BY RAND() LIMIT 4"); // récup 4 cartes aléatoires pour les rangées
		$param='ID_CARTE';
		
		foreach($stockagecartes as $s){ // Je rempli les rangée
			
			Model::doRequest("INSERT INTO RANGEE (ID_PARTIE,NB_CARTES) VALUES (".$_GET['partie'].",1)"); //créer une nouvelle rangée
			
			$stockageID=Model::stockRequest("SELECT MAX(ID_RANGEE) FROM RANGEE"); //je récupère l'ID de la rangée créée
			
			$param2='MAX(ID_RANGEE)';
			$ID=($stockageID[0]->$param2);
			
			Model::doRequest("INSERT INTO POSSEDE (ID_RANGEE,ID_CARTE) VALUES(".$ID.",".$s->$param.")");
			
			Model::doRequest("INSERT INTO CARTEDISTRIBUEES (ID_PARTIE,ID_CARTE) VALUES (".$_GET['partie'].",".$s->$param.")"); // on les met dans carte distribuées pour pas les redistribuer
			
		}
		
		// MTN je vais distribuer les cartes
		$stockjoueurs=Model::stockRequest("SELECT ID_JOUEUR FROM REJOINDRE WHERE ID_PARTIE=".$_GET['partie'].""); //récupère les joueurs de la partie
		
		$paramJ='ID_JOUEUR';
		
		foreach($stockjoueurs as $joueur){
			
			$stockagecartes=Model::stockRequest("SELECT ID_CARTE FROM CARTE WHERE ID_CARTE NOT IN (SELECT ID_CARTE FROM CARTEDISTRIBUEES WHERE ID_PARTIE=".$_GET['partie'].") ORDER BY RAND() LIMIT 10"); // selectiionne 10 cartes qui sont ni dans contient ni dans possede
			
			Model::doRequest("INSERT INTO MAIN (ID_PARTIE,NB_CARTES,ID_JOUEUR) VALUES (".$_GET['partie'].",10,".$joueur->$paramJ.")"); //creer une nouvelle main
			
			$stockageID=Model::stockRequest("SELECT MAX(ID_MAIN) FROM MAIN"); //je récupère l'ID de la main créée
			
			$param3='MAX(ID_MAIN)';
			$ID=($stockageID[0]->$param3);
			
			foreach($stockagecartes as $s){
				Model::doRequest("INSERT INTO CONTIENT (ID_MAIN,ID_CARTE) VALUES(".$ID.",".$s->$param.")"); //insert les cartes dans la main
				
				Model::doRequest("INSERT INTO CARTEDISTRIBUEES (ID_PARTIE,ID_CARTE) VALUES (".$_GET['partie'].",".$s->$param.")"); //insert les cartes dans la main
				
			}
			
			}
		}
	$args['selectionner_rangee']=FALSE;
	$args['nbjoueurs']=$nbjoueurs;
	User::showpartielancee($args);
	}
	
	public function ramasserRangee(){
		
		$pdo= DatabasePDO::CurrentDatabase();
		//recuperation de la carte jouée
		$stock=Model::stockRequest("SELECT ID_CARTE FROM CARTEJOUEE WHERE ID_JOUEUR=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')." AND ID_PARTIE=".$_GET['partie']."");
		$paramcarte='ID_CARTE';
		$cartejouee=$stock[0]->$paramcarte;
		print_r($cartejouee);
		$idpartie=$_GET['partie'];
		$idjoueur=User::getElementbyLogin($_SESSION['login'],'ID_JOUEUR');
		$nbjoueur= User::selectElmFromWhere("NB_JOUEURS",'PARTIE',"ID_PARTIE='".$idpartie."'");
		$nbcoups= User::selectElmFromWhere("NB_COUPS",'jouercoup',"ID_PARTIE='".$idpartie."'");
		
		//récuperation du numero de la main du joueur
		$stock=Model::stockRequest("SELECT ID_MAIN FROM MAIN WHERE ID_JOUEUR=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')." AND ID_PARTIE=".$_GET['partie']."");
		$parammain='ID_MAIN';
		$idmain=$stock[0]->$parammain;
		
		//récuperation du score du joueur
		$stock=Model::stockRequest("SELECT SCORE FROM REJOINDRE WHERE ID_JOUEUR=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')." AND ID_PARTIE=".$idpartie."");
		$param1='SCORE';
		$score=$stock[0]->$param1;
		
		//on recupere les cartes de la rangée
		$param='NB_CARTES';
		$nbcartes=Model::stockRequest("SELECT NB_CARTES FROM RANGEE WHERE ID_RANGEE=".$_GET['range']."")[0]->$param;
		
		$stockagecartes=Model::stockRequest("SELECT ID_CARTE FROM POSSEDE WHERE ID_RANGEE=".$_GET['range']." ORDER BY ID_CARTE ASC");
		
		$param2='NB_POINTS';
		$i=1;
		foreach($stockagecartes as $carte){
			$value=Model::stockRequest("SELECT NB_POINTS FROM CARTE WHERE ID_CARTE=".$carte->$paramcarte."")[0]->$param2;
			Model::doRequest("DELETE FROM POSSEDE WHERE ID_CARTE=".$carte->$paramcarte."");
			$i += 1;
			$score += $value;
			
		}
		 
		//MAJ du score
		Model::doRequest("UPDATE REJOINDRE SET SCORE=".$score." WHERE ID_JOUEUR=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')."");
		
		
		//ajoute la carte à la rangée
		Model::doRequest("INSERT INTO POSSEDE (ID_RANGEE,ID_CARTE) VALUES (".$_GET['range'].",".$cartejouee.")");
		
		//supprimer la carte jouée
		Model::doRequest("DELETE FROM CARTEJOUEE WHERE ID_PARTIE=".$idpartie." AND ID_JOUEUR=".$idjoueur."");
		$args['userPlayed']='true';
		
		$stockagenb=Model::stockRequest("SELECT NB_JOUEURS FROM PARTIE WHERE ID_PARTIE=".$_GET['partie'].""); 
		$paramnb='NB_JOUEURS';
	
		$args['selectionner_rangee']=FALSE;
		$args['nbjoueurs']=$stockagenb[0]->$paramnb;
		
		if($nbcoups[0]==$nbjoueur[0]){
			$request=$pdo->prepare("UPDATE jouercoup SET NB_COUPS=0 WHERE ID_PARTIE=".$idpartie."");
			$request->execute();
			$request->closeCursor();
			UserController::placementCarte();
			
			//=> jouer les carte sur les bonnes rangées au fur et à mesure (carte > à la carte de la rangée && ecart(cartejouée et carterangée) minimum)
			//=> si la rangée atteint 6 cartes alors les points des cartes sont attribué au joueur de la carte
				//=> supprimé la rangée puis ajouter une nouvelle carte
	
		}
		
		User::showpartielancee($args);
	}
	
	public function jouercarte(){
			$args['userPlayed']='false';
			$pdo= DatabasePDO::CurrentDatabase();
			$cartejouee=$_GET['cartejouee'];
			$idjoueur=User::getElementbyLogin($_SESSION['login'],'ID_JOUEUR');
			$idpartie=$_GET['partie'];
			$args['selectionner_rangee']=False;
	
			//ajout de cartejouée
			Model::doRequest("INSERT INTO CARTEJOUEE (ID_PARTIE,ID_JOUEUR,ID_CARTE) VALUES (".$idpartie.",".$idjoueur.",".$cartejouee.")");
		
			//MAJ du nombre de coups joués 
			Model::doRequest("UPDATE jouercoup SET NB_COUPS=NB_COUPS+1 WHERE ID_PARTIE=".$idpartie."");
	
			$nbjoueur= User::selectElmFromWhere("NB_JOUEURS",'PARTIE',"ID_PARTIE='".$idpartie."'");
			$nbcoups= User::selectElmFromWhere("NB_COUPS",'jouercoup',"ID_PARTIE='".$idpartie."'");
	
	
			//récuperation du numero de la main du joueur
			$stock=Model::stockRequest("SELECT ID_MAIN FROM MAIN WHERE ID_JOUEUR=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')." AND ID_PARTIE=".$_GET['partie']."");
			$parammain='ID_MAIN';
			$idmain=$stock[0]->$parammain;
	
			//supprimer la carte de la main du joueur
			Model::doRequest("DELETE FROM CONTIENT WHERE ID_CARTE=".$cartejouee." AND ID_MAIN=".$idmain."");
			Model::doRequest("UPDATE MAIN SET NB_CARTES=NB_CARTES-1 WHERE ID_MAIN=".$idmain."");
			
			$nbcarte=User::selectElmFromWhere("COUNT(ID_CARTE)",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE");
	
			$rangee= User::selectElmFromWhere("ID_CARTE",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE");
			$id_rangee= User::selectElmFromWhere("rangee.ID_RANGEE",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE");
			$id=104;
			
			for($i=0;$i<$nbcarte[0];$i++){
				$max_carte=User::selectElmFromWhere("MAX(ID_CARTE)",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE AND rangee.ID_RANGEE='".$id_rangee[$i]."'");
				if($cartejouee<$max_carte[0] && $cartejouee<$id){
					$id=$max_carte[0];
					$args['selectionner_rangee']=True;
				}
				else{
					$args['selectionner_rangee']=False;
					break;
				}
			}
	
			if($nbcoups[0]==$nbjoueur[0] && $args['selectionner_rangee']!=True){
				$request=$pdo->prepare("UPDATE jouercoup SET NB_COUPS=0 WHERE ID_PARTIE=".$idpartie."");
				$request->execute();
				$request->closeCursor();
				UserController::placementCarte();
				
			}
			
		
		
			$request=$pdo->prepare("SELECT NB_JOUEURS FROM PARTIE WHERE ID_PARTIE=".$_GET['partie']."");
			$request->execute();
			$stockagenb = $request->fetchAll(PDO::FETCH_OBJ); 
			$paramnb='NB_JOUEURS';
			$nbjoueurs=$stockagenb[0]->$paramnb;
			$request->closeCursor();
	
			$args['nbjoueurs']=$nbjoueurs;
	
			User::showpartielancee($args);
	
	}
	
	public static function placementCarte(){
		$args['userPlayed']='false';
		$idpartie=$_GET['partie'];
		$nbcarteR=User::selectElmFromWhere("COUNT(ID_CARTE)",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE");
		$nbCarte_jouee=Model::stockRequest("SELECT COUNT(ID_CARTE) FROM CARTEJOUEE WHERE ID_PARTIE=".$idpartie."");
		$param1='COUNT(ID_CARTE)';
		$nbCarte_jouee=$nbCarte_jouee[0]->$param1;
		print_r($nbCarte_jouee);
		$id_rangee= User::selectElmFromWhere("rangee.ID_RANGEE",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE");
		$idCarte=Model::stockRequest("SELECT ID_CARTE FROM CARTEJOUEE WHERE ID_PARTIE=".$idpartie." ORDER BY ID_CARTE ASC");
		$param2='ID_CARTE';
		
		for($i=0;$i<$nbCarte_jouee;$i++){
			$idrangee=0;
			$id_carteMax=0;
			$joueeCarte=$idCarte[$i]->$param2;
			for($j=0;$j<$nbcarteR[0];$j++){
				$max_carte=User::selectElmFromWhere("MAX(ID_CARTE)",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE AND rangee.ID_RANGEE='".$id_rangee[$j]."'");
		
				if($joueeCarte>$max_carte[0] && $max_carte[0]>$id_carteMax){
					$idrangee=$id_rangee[$j];
					$id_carteMax=$max_carte[0];
				}
			}
			Model::doRequest("INSERT INTO possede (ID_RANGEE,ID_CARTE) VALUES (".$idrangee.",".$joueeCarte.")");
			Model::doRequest("UPDATE rangee SET NB_CARTES=NB_CARTES+1 WHERE ID_RANGEE='".$idrangee."'");
			
			//si il y a 6 carte dans la rangée ça recupere la rangee pour le joueur de la 
			
			$nbCarte_rangee=User::selectElmFromWhere("COUNT(ID_RANGEE)","possede","ID_RANGEE=".$idrangee."");
			if($nbCarte_rangee[0]==6){
				//on recupere les cartes de la rangée
				$param='NB_CARTES';
				$nbcartes=Model::stockRequest("SELECT NB_CARTES FROM RANGEE WHERE ID_RANGEE=".$idrangee."")[0]->$param;
				
				$stockagecartes=Model::stockRequest("SELECT ID_CARTE FROM POSSEDE WHERE ID_RANGEE=".$idrangee." ORDER BY ID_CARTE ASC");
				$paramcarte='ID_CARTE';
				$param2='NB_POINTS';
				$i=1;
				
				
				//récupération de l'id du joueur ayant la carte
				$cartejouee=User::selectElmFromWhere("MAX(ID_CARTE)","possede","ID_rangee=".$idrangee."")[0];
				$idjoueur=User::selectElmFromWhere("ID_JOUEUR","cartejouee","ID_PARTIE=".$idpartie." AND ID_CARTE=".$cartejouee."")[0];
				
				//récuperation du score du joueur
				$stock=Model::stockRequest("SELECT SCORE FROM REJOINDRE WHERE ID_JOUEUR=".$idjoueur." AND ID_PARTIE=".$idpartie."");
				$param1='SCORE';
				$score=$stock[0]->$param1;
		
				foreach($stockagecartes as $carte){
					
					$value=Model::stockRequest("SELECT NB_POINTS FROM CARTE WHERE ID_CARTE=".$carte->$paramcarte."")[0]->$param2;
					$i += 1;
					$score += $value;
					
				}
				 
				//MAJ du score
				Model::doRequest("UPDATE REJOINDRE SET SCORE=".$score." WHERE ID_JOUEUR=".$idjoueur."");
				Model::doRequest("DELETE FROM POSSEDE WHERE ID_RANGEE=".$idrangee."");

				Model::doRequest("INSERT INTO possede (ID_RANGEE,ID_CARTE) VALUES (".$idrangee.",".$cartejouee.")");
			}
			
			
		}
		
		Model::doRequest("DELETE FROM CARTEJOUEE WHERE ID_PARTIE=".$idpartie."");
		Model::doRequest("UPDATE jouercoup SET NB_COUPS=0 WHERE ID_PARTIE=".$idpartie."");
	}
	
	public function editProfil(){
			$view = new UserView($this,'editProfil'); 
			$view->render();
	}
	
	public function validerEdition($args){
		if($_FILES['icone']['name']!=''){
			if($_FILES['icone']['error'] > 0){
				$view = new UserView($this,'editProfil'); //réaffiche la page inscription
				$view->setArg('inscErrorText','image trop grosse comme thomas'); //affiche un message d'erreur
				$view->render();
			}
			if($_FILES['icone']['size']>1048576){
				$view = new UserView($this,'editProfil'); //réaffiche la page inscription
				$view->setArg('inscErrorText','image trop grosse'); //affiche un message d'erreur
				$view->render();
			}
			$login=$_SESSION['login'];
			$chemin = 'fonts/'.$login.'.png';
			$resultat = move_uploaded_file($_FILES['icone']['tmp_name'],$chemin);
			if (!$resultat){
				$view = new UserView($this,'editProfil'); //réaffiche la page inscription
				$view->setArg('inscErrorText','echec du chargement de l image'); //affiche un message d'erreur
				$view->render();
			}
		}
		$pwd1=$args->read('password1');
		$pwd2=$args->read('password2');
		if($pwd1!=$pwd2){
			$view = new UserView($this,'editProfil'); //réaffiche la page inscription
			$view->setArg('inscErrorText','Les mots de passe sont différents'); //affiche un message d'erreur
			$view->render();
		}
		if($args->read('nom')!='')
			$nom = $args->read('nom');
		else
			$nom=NULL;
		if($args->read('prenom')!='')
			$prenom = $args->read('prenom');
		else
			$prenom=NULL;
		if($args->read('mail')!='')
			$mail = $args->read('mail');
		else
			$mail=NULL;
	
		$user= User::updateUser($pwd1,$nom,$prenom,$mail);
		$this->showProfile();
	}
	
	
}
	
?>