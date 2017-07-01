<?php
class User extends Model  {
	public  $login;
	public  $pwd;
	public  $nom;
	public  $prenom;
	public  $mail;
	
	public function __construct($log,$mdp,$email,$name,$surname){
		$this->login =$log;
		$this->pwd=$mdp;
		$this->nom=$name;
		$this->mail=$email;
		$this->prenom=$surname;
	}
	
	public static function createUser($login, $password,$mail,$nom,$prenom){
		parent::doRequest("INSERT INTO UTILISATEUR (login, password, mail,nom,prenom) VALUES ('$login','$password','$mail','$nom','$prenom')");
		$user = new User($login, $password,$mail,$nom,$prenom);
		$idjoueur=User::getElementbyLogin($login,"ID_JOUEUR");
		parent::doRequest("INSERT INTO INFOPARTIE (ID_JOUEUR,NB_PARTIE_G,NB_PARTIE_J) VALUES (".$idjoueur.",0,0)");
		return $user;
	}
	
	public static function isLoginUsed($login){
		$isloginused = false;
		$user= User::getElementbyLogin($login,'login');
	  	if(!empty($user)){
			$isloginused=true;
		}
	    return $isloginused;
	}
	
	public static function isPassWordRight($login,$password){
		$ispasswordright= false;
		$pwd=User::getElementbyLogin($login,'password');
		if($password==$pwd)
  			  $ispasswordright=true;
  	    return $ispasswordright;
	}
	
	public static function getElementbylogin($login, $elm){
		$recup= User::selectElmFromWhere($elm,'UTILISATEUR',"LOGIN='".$login."'");
		//$recup= User::selectElmFromWhere($elm,'UTILISATEUR','LOGIN',$login);
		if (isset($recup[0]))
			return $recup[0];
	}
	
	public static function getElementbyID($id, $elm){
		$recup= User::selectElmFromWhere($elm,'UTILISATEUR',"ID_JOUEUR=".$id."");
		if (isset($recup[0]))
			return $recup[0];
	}
	
	public static function selectElmFromWhere($elm,$table,$condition=NULL){ //elm doit etre en majuscule
		
			$element = NULL;
			if($condition == NULL)
				//$request = $pdo->prepare("select ".$elm." from ".$table."");
				$stockage=parent::stockRequest("select ".$elm." from ".$table."");
			else 
				//$request = $pdo->prepare("select ".$elm." from ".$table." where ".$condition."");
				$stockage=parent::stockRequest("select ".$elm." from ".$table." where ".$condition."");
			
			
			if ($elm =='DISTINCT REJOINDRE.ID_PARTIE' || $elm=='REJOINDRE.ID_PARTIE')
				$elm='ID_PARTIE'; 
			if($elm == 'rangee.ID_RANGEE')
				$elm='ID_RANGEE';
	    	
			$recup= array();
			$i=0; //a vérif si le i sert a quelque chose 
			foreach($stockage as $s){
				$var=$s->$elm;
				$recup[]= $var;
				$i++;
			 }
			
			return $recup;
	}
		
	public static function rejoindrePartie($login,$partie){
		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
    	parent::doRequest("INSERT INTO TEST.REJOINDRE (ID_PARTIE,ID_JOUEUR,SCORE) VALUES  (".$partie.",".$idJoueur.",0) ; UPDATE PARTIE SET NB_JOUEURS=NB_JOUEURS+1 WHERE ID_PARTIE=".$partie."");
		parent::doRequest("UPDATE INFOPARTIE SET NB_PARTIE_J=NB_PARTIE_J+1 WHERE ID_JOUEUR=".$idJoueur."");
    	
		
	}
	
	public static function showpartielancee($args=NULL){
		$idjoueur=User::getElementbylogin($_SESSION['login'],'ID_JOUEUR');
		//On vérifie d'abord si la partie est terminée 
		//récuperation du nombre de joueurs 
		$stockagenb=Model::stockRequest("SELECT NB_JOUEURS FROM PARTIE WHERE ID_PARTIE=".$_GET['partie'].""); 
		$paramnb='NB_JOUEURS';
		$nbjoueurs=$stockagenb[0]->$paramnb;
		
		$stock = parent::stockRequest("SELECT NB_CARTES FROM MAIN WHERE ID_PARTIE=".$_GET['partie']."");
		//print_r($stock);
		$param='NB_CARTES';
		$cpt=0;
		foreach($stock as $nbcarte){
			//print_r($nbcarte->$param);
			if($nbcarte->$param==0){
				$cpt+=1;
			}
		}
		if($cpt == $nbjoueurs){
			parent::doRequest("UPDATE PARTIE SET ETAT='TERMINEE' WHERE ID_PARTIE=".$_GET['partie']."");
		}
		
		
		//on récupère l'état
		$etat=User::selectElmFromWhere('ETAT','PARTIE',"ID_PARTIE=".$_GET['partie']."");;
		$_GET['etat']=$etat[0]; // 3 types d'etat : CREATION, EN COURS, TERMINEE
		//$args=array(); // a voir avec agrs['coupinterdit'];
		$args['createur']=User::getcreateur($_GET['partie']);
		
		
		if($_GET['etat']=='TERMINEE'){
			//récuperation du score du joueur
			$stock=Model::stockRequest("SELECT SCORE FROM REJOINDRE WHERE ID_JOUEUR=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')." AND ID_PARTIE=".$_GET['partie']."");
			$param1='SCORE';
		
			$args['scorejoueur'.$idjoueur.'partie'.$_GET['partie']]=$stock[0]->$param1;
			$args['scorefinal']=$stock[0]->$param1;
		
			
			//récuperation du gagnant
			$stock=parent::stockRequest("SELECT ID_JOUEUR FROM REJOINDRE WHERE ID_PARTIE=".$_GET['partie']." ORDER BY SCORE ASC LIMIT 1");
			$paramj='ID_JOUEUR';
			$idgagnant=$stock[0]->$paramj;
			
			$stock = parent::stockRequest("SELECT LOGIN FROM UTILISATEUR WHERE ID_JOUEUR=".$idgagnant."");
			$paramj='LOGIN';
			
			$args['gagnantpartie'.$_GET['partie']]= $stock[0]->$paramj;
			
			//On archive la partie pour tous les joueurs 
			parent::doRequest("INSERT INTO PARTIEARCHIVEE (ID_JOUEUR,ID_PARTIE,SCORE) SELECT ID_JOUEUR,ID_PARTIE,SCORE FROM REJOINDRE WHERE ID_PARTIE=".$_GET['partie']."");
			parent::doRequest("DELETE FROM REJOINDRE WHERE ID_PARTIE=".$_GET['partie']."");
			
			//Update la partie gagnée pour le vainqueur
			parent::doRequest("UPDATE INFOPARTIE SET NB_PARTIE_G=NB_PARTIE_G+1 WHERE ID_JOUEUR=".$idgagnant."");
			
			
			$view = new UserView('userController','partieTerminee',$args);
			$view->render();
		}
		
		
		if($_GET['etat']=='EN COURS'){
			
		;	
		//on récupère les id des rangées de la partie
		$stockage=parent::stockRequest("SELECT ID_RANGEE FROM RANGEE WHERE ID_PARTIE=".$_GET['partie']."");
		$param='ID_RANGEE';
		$i=1;
		foreach($stockage as $id){
			$args['rangee'.$i]=$id->$param;
			//on récupère les cartes de la rangée
			$stockagecarte=parent::stockRequest("SELECT ID_CARTE FROM POSSEDE WHERE ID_RANGEE=".$id->$param."");
			
			$param2='ID_CARTE';
			$k=1;
			foreach($stockagecarte as $idcarte){
				
				$args['rangee'.$i.'carte'.$k]=$idcarte->$param2;
			
				$k=$k+1;
			}
			
			
			$i=$i+1;
		}
		
		//on recupère les cartes du joueur
		$j=1;
		$stockage=parent::stockRequest("SELECT ID_MAIN FROM MAIN WHERE ID_JOUEUR=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')." AND ID_PARTIE=".$_GET['partie']."");
		
		$paramM='ID_MAIN';
		//print_r($request);
		$idMain=$stockage[0]->$paramM;
		
		$stockage=parent::stockRequest("SELECT ID_CARTE FROM CONTIENT WHERE ID_MAIN=".$idMain."");
		$param='ID_CARTE';
		foreach($stockage as $id){
			$args['carte'.$j]=$id->$param;
			$j=$j+1;
		}
		//on récupère les noms des autres joueurs 
		$k=1;
	
		$stockid= parent::stockRequest("SELECT ID_JOUEUR FROM REJOINDRE WHERE ID_JOUEUR!=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')." AND ID_PARTIE=".$_GET['partie']."");
		
		$param='ID_JOUEUR';
		foreach($stockid as $id){
			$args['joueur'.$k]=User::getElementbyID($id->$param,'LOGIN');
			$k=$k+1;
		}
		$args['nbjoueurs']=$k;
		
		//on récupère le score du joueur
		$stockscore=parent::stockRequest("SELECT SCORE FROM REJOINDRE WHERE ID_PARTIE=".$_GET['partie']." AND ID_JOUEUR=".User::getElementbylogin($_SESSION['login'],'ID_JOUEUR')."");
		$params='SCORE';
		$args['score']=$stockscore[0]->$params;
		
		
		
		}
		$view = new UserView('userController','Partie',$args);
		$view->render();
		
		
	}
	
	public static function getpartiescreees($login){
		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
		$idPartie=User::selectElmFromWhere('ID_PARTIE','PARTIE',"ETAT='CREATION' AND ID_JOUEUR=".$idJoueur[0]."");
		
		User::showPartie($idPartie);
	}
	
	public static function getcreateur($partie){
		$id=User::selectElmFromWHere('ID_JOUEUR','PARTIE',"ID_PARTIE='".$partie."'");
		$joueur=User::selectElmFromWHere('LOGIN','UTILISATEUR',"ID_JOUEUR='".$id[0]."'");
		
		return $joueur[0];
	}
	
	public static function getPartiespubliques($login){
		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
		$idPartie=User::selectElmFromWhere('DISTINCT REJOINDRE.ID_PARTIE','PARTIE, REJOINDRE',"REJOINDRE.ID_JOUEUR!=".$idJoueur[0]." AND PARTIE.STATUT='PUBLIQUE' AND REJOINDRE.ID_PARTIE=PARTIE.ID_PARTIE AND REJOINDRE.ID_PARTIE NOT IN (SELECT ID_PARTIE FROM REJOINDRE WHERE ID_JOUEUR='".$idJoueur[0]."')");
		User::showPartie($idPartie);
	}
	
	public static function getPartiesEncours($login){
		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
		print_r($idJoueur[0]);
		$idPartie=User::selectElmFromWhere('REJOINDRE.ID_PARTIE','REJOINDRE, PARTIE',"REJOINDRE.ID_PARTIE=PARTIE.ID_PARTIE AND ETAT='EN COURS' AND REJOINDRE.ID_JOUEUR=".$idJoueur[0]."");
		
		User::showPartie($idPartie);
		
	}
	
	public static function gethistoriqueparties($login){
		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
		$idPartie=User::selectElmFromWhere('ID_PARTIE','REJOINDRE',"ID_JOUEUR=".$idJoueur[0]."");
		$idPartieArchivee= User::selectElmFromWhere('ID_PARTIE','PARTIEARCHIVEE',"ID_JOUEUR=".$idJoueur[0]."");
		//$args=array();
		//$args['nbarchive']=sizeof($idPartie);
		$nonarchive=array();
		$stock=sizeof($idPartie);
		$nonarchive['nb']=$stock;
		$total=array_merge($idPartie,$idPartieArchivee,$nonarchive);
		User::showPartie($total,'historique');
	}
	
	public static function showPartie($partie,$fonction=NULL){
		$args=array();
		$args['nonarchive']=sizeof($partie);
		if($fonction=='historique'){
			$nonarchive=array_pop ($partie);
			$args['nonarchive']=$nonarchive;
		}
		
		$i=1;
		
		foreach($partie as $p){
			$args[$i]= $partie[$i-1];
			$args['createur'.$i]=User::getcreateur($partie[$i-1]);
			
			$stock=User::selectElmFromWhere('ETAT','PARTIE',"ID_PARTIE=".$partie[$i-1]."");
			$args['etat'.$i]=$stock[0];
			$i= $i+1;
		}
		$args[0]=$i-1;
		
		
		$view = new UserView('userController','showParties',$args);
		$view->render();
	}
	
	public static function showProfile($login = NULL){
		if($login ==NULL)
			$login = $_SESSION['login'];

			$args=array();
			
			
			$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
		
			$args['login']= $login;
			$args['prenom']=User::getElementbyLogin($login,'prenom');
			$args['nom']=User::getElementbyLogin($login,'nom');
			$args['id']=User::getElementbyLogin($login,'ID_JOUEUR');
			$args['mail']=User::getElementbyLogin($login,'mail');
			$PG=User::selectElmFromWhere('NB_PARTIE_G','INFOPARTIE',"ID_JOUEUR=".$idJoueur[0]."");
			$PJ=User::selectElmFromWhere('NB_PARTIE_J','INFOPARTIE',"ID_JOUEUR=".$idJoueur[0]."");
			$PEC=User::selectElmFromWhere('COUNT(ID_PARTIE)','PARTIE',"ID_JOUEUR=".$idJoueur[0]." AND ETAT='EN COURS'");
			//$PEC=User::selectElmFromWhere('NB_PARTIE_ENCOURS','INFOPARTIE',"ID_JOUEUR=".$idJoueur[0]."");
			
			$scoremoyen=0;
			$nbscore=0;
			$stockscore=parent::stockRequest("SELECT SCORE FROM PARTIEARCHIVEE WHERE ID_JOUEUR=".$idJoueur."");
			$paramscore="SCORE";
			
			
			foreach($stockscore as $score ){
				$scoremoyen+=$score->$paramscore;
				$nbscore+=1;	
			}
			if($nbscore !=0){
				$scoremoyen= $scoremoyen/$nbscore;
				$args['scoremoyen']=$scoremoyen;
			}
			else 
				$args['scoremoyen']="Aucun score enregistré";
				
			if (isset($PG[0]))
				$args['partieG']=$PG[0];
			else
				$args['partieG']="0";
			if (isset($PJ[0]))
				$args['partieJ']=$PJ[0];
			else
				$args['partieJ']="0";
			/*if (isset($PEC[0]))
				$args['partieEnCours']=$PEC[0];
			else
				$args['partieEnCours']="";*/
			//print_r($args);
			
		
			$view = new UserView('userController','showProfile',$args); // a affiner en fonction de connecté ou pas ou a redefinir dans UserController
			$view->render();	
	}
	
	public static function creerpartie($login){
		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
    
		parent::doRequest("INSERT INTO PARTIE (ETAT, NB_JOUEURS, ID_JOUEUR, STATUT) VALUES ('CREATION', '0','".$idJoueur[0]."', 'PUBLIQUE')");
		$partie = User::selectElmFromWhere('MAX(ID_PARTIE)','PARTIE','ID_JOUEUR='.$idJoueur[0].'');
		
		if(isset($_POST['partieprivee'])){
			if($_POST['partieprivee']=='on'){
				$request=$pdo->prepare("UPDATE PARTIE SET STATUT='PRIVEE' WHERE ID_PARTIE=".$_SESSION['partie']."");
				$request->execute();
			}
		}
		$_SESSION['partie']=$partie[0];
		User::rejoindrepartie($login,$partie[0]);

	}
	
	public static function deleteinvitation($login,$partie){
		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
		
		parent::doRequest("DELETE FROM TEST.INVITER WHERE ID_PARTIE=".$partie." AND ID_JOUEUR=".$idJoueur."");
		
	}
	
	public static function inviterjoueur($login){
		$param = 'ID_JOUEUR';

		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');

		$stockage=parent::doRequest("SELECT ID_JOUEUR FROM TEST.INVITER WHERE ID_JOUEUR=".$idJoueur." AND ID_PARTIE=".$_SESSION['partie']."");
		
		$partie = $_SESSION['partie'];
		if(empty($stockage)){
    		
			parent::doRequest("INSERT INTO TEST.INVITER (ID_PARTIE,ID_JOUEUR) VALUES (".$partie.",".$idJoueur.")");
		}
			$_GET['partie']=$partie;
			$args['createur']=$_SESSION['login'];
		
		User::showpartielancee($args);
	}
	
	public static function showinvitationlogin($login){
		$idJoueur=User::getElementbyLogin($login,'ID_JOUEUR');
		$parties= 		User::selectElmFromWhere('ID_PARTIE','INVITER','ID_JOUEUR='.$idJoueur.'');

		User::showPartie($parties);
	}
	
	public static function updateUser($pwd=NULL,$nom=NULL,$prenom=NULL,$mail=NULL){
		
		if($pwd!=NULL){
			parent::doRequest("update utilisateur set PASSWORD='".$pwd."' where LOGIN='".$_SESSION['login']."'");
			
		}
		if($nom!=NULL){
			parent::doRequest("update utilisateur set NOM='".$nom."' where LOGIN='".$_SESSION['login']."'");
		}
		if($prenom!=NULL){
			parent::doRequest("update utilisateur set PRENOM='".$prenom."' where LOGIN='".$_SESSION['login']."'");
		}
		if($mail!=NULL){
			parent::doRequest("update utilisateur set MAIL='".$mail."' where LOGIN='".$_SESSION['login']."'");
		}
		//faire requete recupmail.
		User::envoyerMail($mail);
	}

}

	