<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Ajout d'une nouvelle catégorie de forum (traitement)
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

$sql = 'SELECT position FROM '.$prefixtable.'forum ORDER BY position DESC LIMIT 0,1';
$req = $db->query($sql);
$req = $req->fetch();
$row = $req['position'];

$sql = 'SELECT count(id) FROM '.$prefixtable.'forum WHERE fatt = 0';
$req = $db->query($sql);
$row2 = $req->rowCount();

$sql = 'INSERT INTO '.$prefixtable.'forum (`nom`, `description`, `groupe`, `nbsujet`, `nbmessage`, `dernier`, `adernier`, `temps`, `fatt`, `position`, `nbsf`, `positionf`, `v`, `m`, `mg`) VALUES ("'.add_gpc($_POST['nom']).'","","0","0","0","-","-","'.time().'","0","'.($row[0]+1).'","0","'.($row2[0]+1).'","0","0","0")';

$nom = trim($_POST['nom']);

if(!empty($nom)) $req = $db->query($sql) or die('Erreur SQL !'.$db->print_r($db->errorInfo())); 

include('../langue/'.$langue.'/admin/langue_auto.php');
include('./pages/valid_foru_conf.php'); 

?>
