<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de suppression d'un forum (traitement)
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

$temps = time();

$sql = 'SELECT nbsf,position FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt = 0';
$req = $db->query($sql);

if($req->rowCount() != 0)
{
	$data = $req->fetch();

	$sql = 'DELETE FROM '.$prefixtable.'forum WHERE id = "'.$_GET['id'].'" OR fatt = "'.$_GET['id'].'"';
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
	
	$sql = 'UPDATE '.$prefixtable.'forum SET positionf = positionf-1  WHERE position > "'.$data['position'].'" AND fatt = 0';
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

	$sql = 'UPDATE '.$prefixtable.'forum SET position = position-'.($data['nbsf']+1).'  WHERE position > "'.$data['position'].'"';
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
	
	$sql = 'DELETE FROM '.$prefixtable.'post WHERE idfa = "'.$_GET['id'].'"';
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
	
	$sql = 'DELETE FROM '.$prefixtable.'sondage WHERE forumatt = '.intval($_GET['id']);
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
	
	$sql = 'DELETE FROM '.$prefixtable.'voter WHERE fofo = '.intval($_GET['id']);
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
}

include('../langue/'.$langue.'/admin/langue_conf_forum.php');
include('conf_forum.php');

?>
