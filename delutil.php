<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Supprime d'un utilisateur
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
 
include('./includes/gpc.php');

session_start();
include('info_bdd.php');
include('info_options.php');
$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);


if(isset($_SESSION['pseudo']))
	$pseudoa = $_SESSION['pseudo'];
else 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' )); 

if(isset($_GET['id']))
{ 
	$sql1 = 'SELECT rang FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($pseudoa).'"';
	$req1 = $db->query($sql1);
	$data = $req1->fetch(); 
	$rang = $data['rang'];

	if($rang == 2)
	{
		$sql = 'DELETE FROM '.$prefixtable.'membres WHERE id = "'.intval($_GET['id']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
	}
}
$db = null;
header('Location: index.php?page=membre');
?>
