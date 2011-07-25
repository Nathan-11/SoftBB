<?php 

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de gestion des droits d'un forum (affichage)
 *   Version : 1.x
 *   
 *   Copyright            : (C) 2005-201x - Équipe SoftBB.net
 *   Site-web             : http://www.softbb.net/
 *   Em@il                : Voir sur le site
 *   Développement        : Equipe SoftBB - ouverte - (voir sur le site)
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique Générale GNU.
 *   Plus d'infos sur /index.php
 *
 ***************************************************************************/

include_once('log.php');
echo '<h1>'.$lg_fea['fe0'].'</h1>';

$tgroupe = array();
$tidg = array();
$increm = 0;
$sql = 'SELECT * FROM '.$prefixtable.'groupe ORDER BY id DESC';
$req = $db->query($sql);
while($data = $req->fetch())
{
	$tgroupe[$increm] = $data['nom'];
	$tidg[$increm] = $data['id'];
	$increm++;
}

$sql = 'SELECT groupe,m,v,mg,nom FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt != 0';
$req = $db->query($sql);
$data = $req->fetch();
if($req->rowCount() == 0) 
	echo '<p>'.$lg_fea['fe1'].'</p>';
else 
	echo '
<p>
	'.$lg_fea['fe2'].'<strong>'.htmlentities($data['nom']).'</strong><br />
	'.$lg_fea['fe2b'].'
</p>
<form name="form1" method="post" action="index.php?page=auto&id='.intval($_GET['id']).'">
	<p>'.$lg_fea['fe3'].'</p>
	<select name="rang">
		<option value="0"'; if($data['groupe'] == 0) echo ' selected '; echo'>'.$lg_fea['fe4'].'</option>
		<option value="-2"'; if($data['groupe'] == -2) echo ' selected '; echo'>'.$lg_fea['fe5'].'</option>
		<option value="-1"'; if($data['groupe'] == -1) echo ' selected '; echo'>'.$lg_fea['fe6'].'</option>
		<option value="-3"'; if($data['groupe'] == -3) echo ' selected '; echo'>'.$lg_fea['fe7'].'</option>
		<option value="-4"'; if($data['groupe'] == -4) echo ' selected '; echo'>'.$lg_fea['fe8'].'</option>
	';
for($po=0;$po<$increm;$po++)
{
	echo'<option value="'.$tidg[$po].'"'; 
	if($data['groupe'] == $tidg[$po]) 
		echo ' selected ';
	echo'>'.htmlentities($tgroupe[$po]).'</option>';
}
echo'
	</select>
	
	<p>'.$lg_fea['fe9'].'</p>
	
	<p>
		<select name="v" class="sbouton">
			<option value="0" '; if($data['v'] == 0) { echo ' selected ';} echo' class="red">'.$lg_fea['fe10'].'</option>
			<option value="1" '; if($data['v'] == 1) { echo ' selected ';} echo' class="modo">'.$lg_fea['fe11'].'</option>
		</select>
	</p>
	
	<p>'.$lg_fea['fe12'].'</p>
	<p>
		<select name="m" class="sbouton">
			<option value="0" '; if($data['m'] == 0) { echo ' selected ';} echo' class="red">'.$lg_fea['fe13'].'</option>
			<option value="1" '; if($data['m'] == 1) { echo ' selected ';} echo' class="modo">'.$lg_fea['fe14'].'</option>
			<option value="2" '; if($data['m'] == 2) { echo ' selected ';} echo' class="admin">'.$lg_fea['fe15'].'</option>
			<option value="3" '; if($data['m'] == 3) { echo ' selected ';} echo' class="admin">'.$lg_fea['fe16'].'</option>
			<option value="4" '; if($data['m'] == 4) { echo ' selected ';} echo' class="admin">'.$lg_fea['fe17'].'</option>
		</select>
	</p>
	
	<p>'.$lg_fea['fe18'].'</p>
	<p>
		<select name="mg" class="sbouton">
			<option value="0" '; if($data['mg'] == 0) { echo ' selected ';} echo' class="red">'.$lg_fea['fe13'].'</option>
			<option value="1" '; if($data['mg'] == 1) { echo ' selected ';} echo' class="modo">'.$lg_fea['fe14'].'V</option>
			<option value="2" '; if($data['mg'] == 2) { echo ' selected ';} echo' class="admin">'.$lg_fea['fe15'].'</option>
			<option value="3" '; if($data['mg'] == 3) { echo ' selected ';} echo' class="admin">'.$lg_fea['fe16'].'</option>
			<option value="4" '; if($data['mg'] == 4) { echo ' selected ';} echo' class="admin">'.$lg_fea['fe17'].'</option>
		</select>
	</p>
	
	<p><input type="submit" name="Submit" value="'.$lg_fea['fe18b'].'" class="bouton" /></p>
</form>

<p>'.$lg_fea['fe19'].'<br />
	<strong>'.$lg_fea['fe4'].'</strong> : 
		'.$lg_fea['fe22'].'<br />
	<strong>'.$lg_fea['fe5'].'</strong> : 
		'.$lg_fea['fe23'].'<br />
	<strong>'.$lg_fea['fe6'].'</strong> : 
		'.$lg_fea['fe24'].'<br />
	<strong>'.$lg_fea['fe7'].'</strong> : 
		'.$lg_fea['fe25'].'<br />
	<strong>'.$lg_fea['fe8'].'</strong> : 
		'.$lg_fea['fe26'].'<br />
	<strong>'.$lg_fea['fe20'].'</strong> : '.$lg_fea['fe21'].'
		'.$lg_fea['fe27'].'
</p>
';


?>
