<!doctype html>
<html>
<body>
  
	   <h1>PARTIE TERMINEE <br/>  </h1>
<?php
//$score = $args['scorejoueur'.$_SESSION['login'].'partie'.$_GET['partie']];
$score=$this->args['scorefinal'];
//print_r($score);
//print_r($args['gagnantpartie'.$_GET['partie']]);
if($args['gagnantpartie'.$_GET['partie']]==$_SESSION['login'])
	echo "FELICITATION ! VOUS AVEZ GAGNE LA PARTIE ! <br/>";
else 
	echo " Dommage... Vous avez perdu, n'hésitez pas à rejouer ! <br/>";

echo "Votre score :".$score;
	
?> 
	   
</body>

</html>