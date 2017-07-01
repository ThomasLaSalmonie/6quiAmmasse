<!doctype html>
<html>
	<head>
    	<title>Laura & Thomas WEBSITE</title>
    	<meta charset="utf-8">
    	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
	</head>
	<body>
		<hr />
		<h1>EDITION DU PROFIL</h1>
		<?php
		if(isset($inscErrorText))
		   echo '<h2 class="error">!  ' . $inscErrorText . '  !</h2>';
		?>
		<hr />
		<form action="index.php?controller=user&action=validerEdition" method="post" <form method="post" action="page.php" enctype="multipart/form-data">
		  <table>
			<tr>
				<th>Nouveau le mot de passe :</th>
				<td><input type="text" name="password1" placeholder="Mon mot de passe"/></td>
			</tr>
			<tr>
				<th>Confirmer le mot de passe :</th>
				<td><input type="password" name="password2" placeholder="*****"/></td>
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
				<th>Mon avatar :</th>
				<td><input type="file" name="icone" id="icone"/></td>
			</tr>
		  
			<th />
			<tr>
				<th />
				<td><input type="submit" value="Valider modification" /></td>
			</tr>
		  </table>
		</form>
	</body>

</html>