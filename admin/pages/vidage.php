<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Vider une catgéorie (traitement)
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

$sql = 'UPDATE '.$prefixtable.'forum SET temps = 0 , adernier = "-" , dernier = "-" , nbsujet = 0 , nbmessage = 0  WHERE id = "'.intval($_GET['id']).'"';
$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
							
$sql = 'DELETE FROM '.$prefixtable.'sondage WHERE sforumatt = '.intval($_GET['id']);
$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

$sql = 'DELETE FROM '.$prefixtable.'voter WHERE sfofo = '.intval($_GET['id']);
$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

$sql = 'DELETE FROM '.$prefixtable.'post WHERE idsfa = "'.intval($_GET['id']).'"';
$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

include('../langue/'.$langue.'/admin/langue_conf_forum.php');
include('conf_forum.php');

?>
