<?php
 
/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'ajout d'une nouveau groupe (traitement)
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

$groupe = trim($_POST['groupe']);
$sql = 'SELECT id FROM '.$prefixtable.'groupe WHERE nom = "'.add_gpc($groupe).'"';
$req = $db->query($sql);		
	
$sql = 'INSERT INTO '.$prefixtable.'groupe (`nom`) VALUES ("'.add_gpc($groupe).'")';
if(!empty($groupe) && $req->rowCount() == 0) $req = $db->query($sql) or die('Erreur SQL !'.$db->print_r($db->errorInfo())); 

include('./pages/valid_group_conf.php'); 

?>
