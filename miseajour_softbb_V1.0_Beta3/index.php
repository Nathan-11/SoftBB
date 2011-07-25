<?php

// inclusions
set_time_limit ( 3600 ); 	// plus d'une heure ?!
include('../info_bdd.php');
include('../info_options.php');
include('../version.php');


try{
	$bdd = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
} catch (Exception $e){
        die('Erreur connexion PDO : ' . $e->getMessage());
}


?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SoftBB - mise à jour</title>
<link rel="stylesheet" href="install.css" type="text/css" />
</head>

<body>
<form name="Formulaire">
<div id="titre"><img src="./install.jpg" alt="SoftBB - mise à jour" /></div>
<div id="install">
	<div id="right">Mise à jour de la base de donnée du forum.</div>
	
	<p>Bonjour et bienvenue dans le script de mise à jour de SoftBB V1.0 Beta2 vers SoftBB V1.0 Beta3</p>
	<p>Lorsque vous avez termin&eacute; la mise à jour, supprimez le dossier miseajour_softbb_V1.0_Beta3, puis rendez-vous sur <a href="../">l'index du forum </a></p>
<?php

if(isset($_GET['proceed']))
{
		$depot = unserialize(file_get_contents('../depot.conf'));
		if($_POST['depot'] == 'stable') {
			$depot = $depot['stable'];
		} else {
			$depot = $depot['beta'];
		}
		file_put_contents('../depot.conf', serialize($depot));
	
	//////////////////////////////////////////////////////////////
	// Ajout de la date d'inscription et de dernière connexion ///
	//////////////////////////////////////////////////////////////
	$table = 'ALTER TABLE `'.$prefixtable.'_membres` ADD `date_register` INT NOT NULL , ADD `date_login` INT NOT NULL; UPDATE `'.$prefixtable.'_membres` SET `date_register` = \''.time().'\', `date_login` = \''.time().'\'';

	$table = explode(';',$table);
	echo '<p class="titrevert">Creation des tables</p>';

	for($i=0 ; $i<count($table) ; $i++) {
		if($bdd->query($table[$i])) 
			echo '<p><font color="green">Requête ex&eacute;cut&eacute;e :</font></p><p>'.$table[$i].'</p>';
		else 
			echo '<p><font color="red">/!\ Requête non-ex&eacute;cut&eacute;e :</font></p><p>'.$table[$i].'</p>';
	}

	echo '<p class="titrevert">Actions terminés</p>
	<p>Vous pouvez supprimer le dossier <b>miseajour_softbb_V1.0_Beta3</b> et <b>install</b> si ce n\'est pas déjà fait<br />
	et <a href="../">retourner sur votre forum mis à jour</a> !</p>';
	
} else{
	echo '
	<form method="post" action="index.php?proceed=" name="update">
		<p><b>Depot de mise à jour de SoftBB</b><br />
				<span stype="padding-left: 20px"><input type="radio" name="depot" value="stable" /><b>Stable :</b> Vous ne disposez que des mises &agrave; jour de s&eacute;curit&eacute; avant la sortie de ma version 1.0 stable</span><br />
				<span stype="padding-left: 20px"><input type="radio" name="depot" value="beta" checked="checked" /><b>Beta :</b> Vous disposez des toutes dernières nouveauté de SoftBB notamment les avancée du système de mods...</span>
				</p>
		<p>
			<input type="submit" name="maj" value="Débuter la mise à jour !" />
		</p>
	</form>';
}

?></div>
  </form></body>
</html>
