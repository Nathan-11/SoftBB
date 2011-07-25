<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - 
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
$nom = trim($_POST['nom']);
if(!empty($nom))
{
	
	if( strlen($_POST['description']) > 255) $description = substr($_POST['description'],0,255);
	else $description = $_POST['description'];

	$sql = 'UPDATE '.$prefixtable.'forum SET nom = "'.add_gpc($_POST['nom']).'" , description = "'.add_gpc($description).'" WHERE id = "'.$_GET['id'].'"';
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
}
include('valid_foru_conf.php'); 
?>
