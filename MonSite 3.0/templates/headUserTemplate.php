<!doctype html>
<html>
	<head>
    	<title>Laura & Thomas WEBSITE</title>
    	<meta charset="utf-8">
    	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
	</head>
	<body>
		<header>
			<div data-position="fixed" id="bandeauBas">
				<span class="brand">Vous êtes connecté en tant que : </span>
				<a><?php
						if(isset($_SESSION['login']))
							echo $_SESSION['login'];
					?></a>
			</div>
			<nav>
				<hr />
				<ul> 
					<li><a class="btn" href='index.php?controller=user&action=accueil'>Accueil</a></li>
					<li><a class="btn" href='index.php?controller=user&action=showProfile'>Profil</a></li>
					<li><a class="btn" href='index.php?controller=user&action=afficherecherche'>Rechercher</a></li>
					<li><a class="btn" href='index.php?controller=user&action=play&play=play'>Play</a></li>
					<li><a class="btn" href='index.php?controller=user&action=meilleurscore'>Meilleurs Scores</a></li>					
					<li><a class="btn" href='index.php?controller=user&action=deconnexion'>Deconnexion</a></li>
				</ul>
				<hr />
			</nav>
		</header>
	</body>

</html>