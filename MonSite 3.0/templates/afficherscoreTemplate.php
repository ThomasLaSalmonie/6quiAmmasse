<!doctype html>
<html>
	<head>
    	<title>Laura & Thomas WEBSITE</title>
    	<meta charset="utf-8">
    	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
	</head>
	<body>
		
			
			<?php
			if(!isset($this->args['joueur0']))
				echo "<h1>Il n'y a pas encore de score enregistré</h1>";
			
			else if(isset($this->args['joueur0']) ){
				echo "<h1>Tableau des meilleurs scores</h1>";
			
				for($i=0;$i<$this->args['nb'];$i++){
				if(isset($this->args['joueur'.$i]));
					echo("<h1 class='joueur'> Nom :".$this->args['joueur'.$i]."	score :".$this->args['score'.$i]."</h1>");
				}
			}
			
			?>
		
	</body>
	<style>
		.joueur{
			text-align :center;
			color:red;
			font-weight: bold;
		}
	</style>
</html>