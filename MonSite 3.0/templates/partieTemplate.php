<!doctype html>
<html>
	<head>
    	<title>Laura & Thomas WEBSITE</title>
    	<meta charset="utf-8">
    	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
	</head>
	<hr />
	
	
	<?php
	//print_r($this->args['createur']);
	//print_r($_GET['statut']);
	$partie=$_GET['partie'];
	echo "<h1>PARTIE NUMERO : ".$partie."</h1>";
	//print_r($_GET['etat']);
	if(isset($this->args['createur'])){
		//print_r($this->args['createur']);
		if($_SESSION['login']==$this->args['createur'] && $_GET['etat']=='CREATION'){
	 	   echo "<form action=\"index.php?controller=user&action=demarrerpartie&play=play&partie=".$partie."\"  method=\"post\" > ";
	 	  echo "<td><input type=\"submit\" value=\"Démarrer la partie\" /></td>";
 	 	  echo"</form>";
		}
		
		if(isset($this->args['nbjoueurs'])){
			if($this->args['nbjoueurs']==1){
				echo"Veuillez attendre qu'il y ait au moins un autre participant";
			}
		}
	}	
	
	
	if(isset($_GET['etat'])){
		if($_GET['etat']=='CREATION' && $_SESSION['login']!=$this->args['createur']){
			echo "Veuillez attendre que la partie commence";
		}	
	
		
		if($_GET['etat']=='EN COURS'){
			//afficher les noms des joueurs
			$k=1;
			echo "<nav><ul><li>Participants :";
			do{
				
				echo " ".$this->args['joueur'.$k]."";
				$k=$k+1;
			}while($k<($this->args['nbjoueurs']));
			echo "</li></ul></nav>";
			echo "<nav><ul><li><a class=\"btn\" href='index.php?controller=user&action=poursuivrepartie&partie=".$_GET['partie']."'>Rafraichir</a></li></ul></nav>";
			
			
			
			//afficher les rangee
			$j=1;
			do{	
				echo"<nav>";
				echo"<div>";
				$k=1;
				echo"<li>";
				do{
					
					if(isset($this->args['rangee'.$j."carte".$k]) && $this->args['rangee'.$j."carte".$k]!=" "){
						echo "<img class='panda' src=\"images/carte".$this->args['rangee'.$j."carte".$k].".jpg\" >";
						//print_r($this->args['rangee'.$j."carte".$k]);
					}$k=$k+1;
					
				}while($k<=6);
				if(isset($args['selectionner_rangee'])){
					if($args['selectionner_rangee']==TRUE){
						
						//print_r($this->args['rangee'.$j]);
						echo "<form action=\"index.php?controller=user&action=ramasserRangee&partie=".$partie."&range=".$this->args['rangee'.$j]."\" method=\"post\" > ";
						echo "<input type=\"submit\" value=\"Selectionner \" />";
						echo"</form>";
						
					}
				}
				echo"</li>";
				echo"</div>";
				echo"</nav>";
				$j=$j+1;
			}while($j<=4);
			
			
			//afficher les cartes du joueur
			$i=1;
			echo"<nav>";
			echo"<ul>";
	
			$idpartie=$_GET['partie'];
			$idjoueur=User::getElementbyLogin($_SESSION['login'],'ID_JOUEUR');
			
			$coupjoue= User::selectElmFromWhere("ID_CARTE",'cartejouee',"ID_PARTIE='".$idpartie."' AND ID_JOUEUR='".$idjoueur."'");
			
			$nbcartes=User::selectElmFromWhere("NB_CARTES",'main',"ID_PARTIE='".$idpartie."' AND ID_JOUEUR='".$idjoueur."'");
			do{
				if($this->args['carte'.$i]!=""){
					echo"<li>";
					echo "<form action=\"index.php?controller=user&action=jouercarte&partie=".$partie."&cartejouee=".$this->args['carte'.$i]."\" method=\"post\" > ";
					echo "<div><img class='panda' src=\"images/carte".$this->args['carte'.$i].".jpg\" ></div>";
					if(!isset($coupjoue[0])){
						if($args['userPlayed']=='false')
							echo "<input type=\"submit\" value=\"Jouer cette carte \" />";
					}
					echo"</form>";
					echo"</li>";
				}
				$i=$i+1;
			}while($i<=$nbcartes[0]);
	
			echo"</ul>";
			echo"</nav>";
			
			//afficher le score du joueur
			echo "VOTRE SCORE:".$this->args['score']."";
			
			echo "<form action=\"index.php?controller=user&action=quitterpartie&play=play&partie=".$partie."\"  method=\"post\" > ";
			echo "<td><input type=\"submit\" value=\"Quitter la partie\" /></td>";
			echo "</form>";
		  
			if($_SESSION['login']==$this->args['createur']){
				echo "<form action=\"index.php?controller=user&action=supprimerpartie&play=play&partie=".$partie."\"  method=\"post\" > ";
				echo "<td><input type=\"submit\" value=\"Supprimer la partie\" /></td>";
				echo "</form>";
			}
			
			$rangee= User::selectElmFromWhere("ID_CARTE",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE");
			
			$id_rangee= User::selectElmFromWhere("rangee.ID_RANGEE",'possede,rangee',"rangee.ID_PARTIE='".$idpartie."' AND rangee.ID_RANGEE=possede.ID_RANGEE");

		
		}
		
		
	}	
	
	
		?>
		

 
 <style>

 	.panda{
 		width:100px;
 		}
 </style>
</html>