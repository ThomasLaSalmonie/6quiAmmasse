<!doctype html>
<html>
	<head>
    	<title>Laura & Thomas WEBSITE</title>
    	<meta charset="utf-8">
    	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
	</head>
<?php
if(isset($RechErrorText))
   echo '<span class="error">' . $RechErrorText . '</span>';
?>
<form action="index.php?controller=user&action=rechercher" method="post">
	<table>
		<tr>
			<th />
			<td><input type="search" name="loginR" placeholder="Entrez login"/></td>
		</tr>
		<th />
		<td><input type="submit" value="Rechercher" /></td>
	</table>
</form>	
</html>