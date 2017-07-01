<!doctype html>
<html>
	<head>
    	<title>Laura & Thomas WEBSITE</title>
    	<meta charset="utf-8">
    	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
	</head>
	<hr />
	<h1>INSCRIPTION</h1>
	<?php
	if(isset($inscErrorText))
	   echo '<h2 class="error">!  ' . $inscErrorText . '  !</h2>';
	?>
	<hr />
 <form action="index.php?action=inscription" method="post">
  <table>
	<tr>
		<th>Login* :</th>
		<td><input type="text" name="inscLogin" placeholder="Mon login"/></td>
	</tr>
	<tr>
		<th>Mot de passe* :</th>
		<td><input type="password" name="inscPassword" placeholder="*****"/></td>
	</tr>
	<tr>
		<th>Mail :</th>
		<td><input type="email" name="mail" /></td>
	</tr> 
	<tr>
		<th>Nom :</th>
		<td><input type="text" name="nom" /></td>
	</tr> 
	<tr>
		<th>Prenom :</th>
		<td><input type="text" name="prenom"/></td>
	</tr> 
	<tr>
		<th />
		<td><input type="submit" value="Creer mon compte..." /></td>
	</tr>
	
  </table>
 </form>
</html>