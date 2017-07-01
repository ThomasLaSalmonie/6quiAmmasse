<!doctype html>
<html>
<h1>Invitation</h1>
<?php
 $nbuti=$args['nbuti'];

 $i=0;

 //print_r($args['utilisateur0']);
 // print_r($args['utilisateur1']);
 
if(isset($inscErrorText))
   echo '<span class="error">' . $inscErrorText . '</span>';
?>
<form action="index.php?controller=user&action=inviter" method="post">
  <table>
<tr>
  <th>Utilisateur 1 :</th>
  <td><select name="utilisateur1">
	  
<?php
do{
	
  echo "<option value='".$args['utilisateur'.$i]."' selected>".$args['utilisateur'.$i]."</option>";
  $i=$i+1;
}while($i<$nbuti);
 $i=0;
?>
  <option value='nul' selected></option>
  </select></td>
</tr>
<tr>
  <th>Utilisateur 2 :</th>
  <td><select name="utilisateur2">
<?php
do{
	
  echo "<option value='".$args['utilisateur'.$i]."' selected>".$args['utilisateur'.$i]."</option>";
  $i=$i+1;
}while($i<$nbuti);
 $i=0;
?>
  <option value='nul' selected></option>
  </select></td>
</tr>
<tr>
  <th>Utilisateur 3 :</th>
  <td><select name="utilisateur3">
<?php
do{
	
  echo "<option value='".$args['utilisateur'.$i]."' selected>".$args['utilisateur'.$i]."</option>";
  $i=$i+1;
}while($i<$nbuti);
 $i=0;
?>
  <option value='nul' selected></option>
  </select></td>
</tr>
<tr>
  <th>Utilisateur 4 :</th>
  <td><select name="utilisateur4">
<?php
do{
	
  echo "<option value='".$args['utilisateur'.$i]."' selected>".$args['utilisateur'.$i]."</option>";
  $i=$i+1;
}while($i<$nbuti);
 $i=0;
?>
  <option value='nul' selected></option>
  </select></td>
</tr>
<tr>
  <th>Utilisateur 5 :</th>
  <td><select name="utilisateur5">
<?php
do{
	
  echo "<option value='".$args['utilisateur'.$i]."' selected>".$args['utilisateur'.$i]."</option>";
  $i=$i+1;
}while($i<$nbuti);
 $i=0;
?>
  <option value='nul' selected></option>
  </select></td>
</tr>
<tr>
<td><input type='checkbox' name='partieprivee' value='on'>Partie privée<br></td>
	<td><input type="submit" value="Inviter mes amis" /></td>
</tr>
</table>
</form>

<form action="index.php?controller=user&action=creersansinviter" method="post">
 <table>
<tr>
	<td><input type="submit" value="Je n'ai pas d'ami a inviter" /></td>
</tr>
</table>
</form>
</html>

