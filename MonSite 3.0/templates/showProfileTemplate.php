<!doctype html>
<html>
    <head>
      <title>Profil</title>
      <meta charset="utf-8">
    </head>

    <body>
        <div id="titre">
		<hr />
          <h1>Synthèse du profil:</h1>
		<hr />
        </div>
		<nav>
			<ul>
			<?php
			$name=NULL;
			if($_POST['action']='showProfile')
				$name=$_SESSION['login'];
			if($_POST['action']='rechercher'){
				if(isset($_POST['loginR']))
					$name=$_POST['loginR'];
			}
			if(!file_exists("fonts/".$name.".png"))
				$name="avatar_defaut";			
			?>
			<img class="imgProfil" src="fonts/<?php echo $name?>.png" alt="photo de profil" />
			</ul>
		</nav>
        <div>
          <h1><?php echo $this->args['prenom']."  ".$this->args['nom'];  ?></h1> 
          <h3>Login:<?php echo $this->args['login'];?></h3> 
          <h3>Mail: <?php echo $this->args['mail']; ?></h3>
			<br />
			<h3>Nombre de parties jouées: <?php echo $this->args['partieJ']; ?></h3>
			<h3>Nombre de parties gagnées: <?php echo $this->args['partieG']; ?></h3>
			<h3>Score Moyen: <?php echo $this->args['scoremoyen']; ?></h3>
        </div>
		<form action="index.php?controller=user&action=editProfil" method="post">
		<?php 
			
			if(!isset($_POST['loginR'])){
			?>
				<table>
					<tr>
						<th />
						<td><input type="submit" value="Editer mon profil"></td>
					</tr>
				</table>
			<?php
			}?>
		</form> 
    </body>
</html>