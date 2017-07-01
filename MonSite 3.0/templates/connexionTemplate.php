<!doctype html>
<html>

	<head>
    	<title>Laura & Thomas WEBSITE</title>
    	<meta charset="utf-8">
    	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
	</head>
	<hr />
	<h1>CONNEXION</h1>
	<?php
		if(isset($connectErrorText))
	   echo '<h2 class="error">!  ' . $connectErrorText . '  !</h2>';
	?>
	<hr />
	<form action="index.php?action=connexionencours" method="post">
		<table>
		
			<tr>
				<th>Login* :</th>
				<td><input type="text" name="connectLogin"/></td>
			</tr>
			<tr>
				<th>Mot de passe* :</th>
				<td><input type="password" name="connectPassword"/></td>
			</tr>
			<tr>
				<th />
				<td><input type="submit" value="Je me connecte" /></td>
			</tr>
		
		</table>
	</form>
</html>