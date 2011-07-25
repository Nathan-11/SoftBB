<?php
 
/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de mise à jour d'un forum (traitement)
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

$sql = 'UPDATE '.$prefixtable.'forum SET m='.add_gpc($_POST['m']).', mg='.intval($_POST['mg']).', v='.intval($_POST['v']).', groupe="'.$_POST['rang'].'"  
		WHERE id = "'.intval($_GET['id']).'"';
$req = $db->query($sql) or die('Erreur SQL !<br />'.print_r($db->errorInfo())); 

include('./pages/valid_foru_conf.php');

?>
