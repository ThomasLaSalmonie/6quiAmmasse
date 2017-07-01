<!doctype html>
<html>
    <head>
      <title>Liste des Parties</title>
      <meta charset="utf-8">
    </head>

    <body>
        <div id="titre">
          <h1>Liste des parties :</h1>
        </div>
        <div class="formP">
          <?php 
		  //print_r($this->i);
		 
		 $nonarchive= $this->args['nonarchive'];
		 $limit=$this->args[0];
			  //print_r($limit);
			  if($limit!=0){
			  	$j=1;
			  
			  do{
				
				  ?>				  
				  	  
          
		  <a name='partie'>Partie numero:<?php echo $this->args[$j];?></a>  
		  
		  <?php 
		 
		  
		  //print_r($this->args['etat'.$j]);
		  
		  		  if($this->args['etat'.$j]=='SUPRIMEE'){
		  			  echo "(Cette partie a été suprimée par le créateur)";
		  		  }
				  else 
					  echo strtolower("(".$args['etat'.$j].")");
				  
				  if($j>$nonarchive){
				  	echo "(Vous avez quitté cette partie)";
				  }
				  
				  
				  
				  if($_GET['action']=='listerpartiespublic' ){
					  echo "<form action=\"index.php?controller=user&action=rejoindrepartie&play=play&partie=".$this->args[$j]."\"  method=\"post\" > ";
					  echo "<td><input type=\"submit\" value=\"Rejoindre\" /></td>";
				  	  echo "</form>";
				  }
				  
				  if($_GET['action']=='listerpartiesencreation'){
					  
					  echo "<form action=\"index.php?controller=user&action=poursuivrepartie&play=play&partie=".$this->args[$j]."\" method=\"post\" > ";
					  echo "<td><input type=\"submit\" value=\"Demarrer la partie\" /></td>";
				  	  echo "</form>";
					  echo "<form action=\"index.php?controller=user&action=supprimerpartie&play=play&partie=".$this->args[$j]."\" method=\"post\" > ";
					  echo "<td><input type=\"submit\" value=\"Supprimer la partie\" /></td>";
				  	  echo "</form>";
					  $realise='oui';
				  }
				  
				  if($_GET['action']=='listerpartiesencours'){
					  echo "<form action=\"index.php?controller=user&action=poursuivrepartie&play=play&partie=".$this->args[$j]."\"  method=\"post\" > ";
					  echo "<td><input type=\"submit\" value=\"Continuer\" /></td>";
				  	  echo "</form>";
					  echo "<form action=\"index.php?controller=user&action=quitterpartie&play=play&partie=".$this->args[$j]."\"  method=\"post\" > ";
					  echo "<td><input type=\"submit\" value=\"Quitter\" /></td>";
				  	  echo "</form>";
				  }
				  
				  
				 
				  if($_GET['action']=='showinvitation' || $_GET['action']=='refuserinvitation' ){
					  echo "<form action=\"index.php?controller=user&action=accepterinvitation&play=play&partie=".$this->args[$j]."\"  method=\"post\" > ";
					  echo "<td><input type=\"submit\" value=\"Accepter\" /></td>";
				 	  echo "</form>";
					  echo "<form action=\"index.php?controller=user&action=refuserinvitation&play=play&partie=".$this->args[$j]."\"  method=\"post\" > ";
					  echo "<td><input type=\"submit\" value=\"Refuser\" /></td>";
				      echo"</form>";
	  }?>
		 <p></p> 
		  <?php
		  $j=$j+1;
	  }while(!empty($this->args[$j]) && $j<=$limit); // a corriger y a un GROS pb 
		 } 
		  ?>
          </form>
        </div>
    </body>
</html>