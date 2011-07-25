<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Validation du compte d'un membre
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
 
include('includes/gpc.php');
include('info_options.php');
include('info_bdd.php');

$pseudo3 = $_GET['pseudo'];
$code = $_GET['pass'];
$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);

$sql = 'SELECT code FROM '.$prefixtable.'membresvalid WHERE pseudo = "'.add_gpc($pseudo3).'"';
$req = $db->query($sql);
$data = $req->fetch();

if($code ==  $data['code'])
{
	$req->closeCursor();
	$sql = 'UPDATE '.$prefixtable.'membres SET valid = "1" WHERE id = "'.add_gpc($pseudo3).'"';
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
	header('Location: index.php?page=notifs&aff=regok2');
	$sql = 'DELETE FROM '.$prefixtable.'membresvalid WHERE pseudo = "'.add_gpc($pseudo3).'"';
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
}
else
{
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
	$req->closeCursor();
}
?>
