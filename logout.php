<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de d�connexion - suppression de cookie
 *   Version : 1.x
 *   
 *   Copyright            : (C) 2005-201x - �quipe SoftBB.net
 *   Site-web             : http://www.softbb.net/
 *   Em@il                : Voir sur le site
 *   D�veloppement        : Equipe SoftBB - ouverte - (voir sur le site)
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique G�n�rale GNU.
 *   Plus d'infos sur /index.php
 *
 ***************************************************************************/
 
 
 
session_start();
include('info_bdd.php');
if(isset($_SESSION['token']) && $_SESSION['token'] == true) {
	if(isset($_SESSION['idlog']))
	{
		$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
		
		$sql = 'UPDATE '.$prefixtable.'membres SET co = "0" WHERE id = "'.intval($_SESSION['idlog']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
		$_SESSION = array();
	}

	$_SESSION['idlog'] = "";

	if(isset($_COOKIE));
	{
		setcookie("idlog","",time()-(365*24*3600));
		setcookie("mdp","",time()-(365*24*3600));
	}
	setcookie("lastvisit","",time()-(365*24*3600));

	$db = null;
}
header('Location: index.php');
?>
