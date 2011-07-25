<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Ajout d'un forum dans une catégorie (traitement)
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
 
$sql = 'SELECT position FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt = 0';
$req = $db->query($sql);
$data = $req->fetch();
$nom = trim($_POST['nom']);

if($req->rowCount() == 0) 
	echo '<p>'.$lg_fad['fa0'].'</p>';

else
{ 
	$temps = time();
	
	$sql = 'UPDATE '.$prefixtable.'forum SET position = position+1  WHERE position > "'.$data['position'].'"';
	if(!empty($nom)) $req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
	
	$sql = 'UPDATE '.$prefixtable.'forum SET nbsf = nbsf+1  WHERE id = "'.intval($_GET['id']).'"';
	if(!empty($nom)) $req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
	
	if( strlen(add_gpc($_POST['description'])) > 255) $description = substr($_POST['description'],0,255);
	else $description = $_POST['description'];

	$sql = 'INSERT INTO '.$prefixtable.'forum (`nom`, `description`, `groupe`, `nbsujet`, `nbmessage`, `dernier`, `adernier`, `temps`, `fatt`, `position`, `nbsf`, `positionf`, `v`, `m`, `mg`) VALUES ("'.add_gpc($_POST['nom']).'","'.add_gpc($description).'","0","0","0","-","-","'.$temps.'","'.intval($_GET['id']).'","'.($data['position']+1).'","0","0","0","0","0")';
	if(!empty($nom)) $req = $db->query($sql) or die('Erreur SQL !'.$db->print_r($db->errorInfo())); 

	include('../langue/'.$langue.'/admin/langue_auto.php');
	include('./pages/valid_foru_conf.php'); 
}
?>
