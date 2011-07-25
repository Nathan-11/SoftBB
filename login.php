<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de vérification d'authentification
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
 
session_start();
$_SESSION = array();

include('info_bdd.php');
include('info_options.php');
include('./includes/gpc.php');

if(!isset($_POST['pseudolog'])) 
	exit('Il n\'y a pas de formulaire');

$pseudolog = trim($_POST['pseudolog']);
$sql = 'SELECT id,temps,pseudo FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($pseudolog).'" AND `mdp` = "'.md5($_POST['mdp']).'" AND valid = "1"';
$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);


$req = $db->query($sql);

if($req->rowCount() == 1) 
{
		$data = $req->fetch(); 
		$_SESSION['pseudo'] = $data['pseudo'];
		$_SESSION['idlog'] = $data['id'];
		$_SESSION['ip_anti_vol'] = $_SERVER['REMOTE_ADDR'];
		
		$req->closeCursor();
		
		$sql = 'UPDATE '.$prefixtable.'membres SET temps = "'.time().'" , co = "1", date_login = "'.time().'" WHERE id = "'.intval($data['id']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		 
		if(isset($_POST['souvenir']) && $_POST['souvenir'] == "auto") 
		{
			$expire = 365*24*3600;
			setcookie("idlog",$data['id'],time()+$expire);
			setcookie("mdp",md5($_POST['mdp']),time()+$expire);
		}

		$redir = 'Location: '.((!$url_rewriting) ? 'index.php?page=indexforum' : 'index.html');
		$_SESSION['lastvisit'] = $data['temps'];

	$db = null;
	header($redir);
}
else
{
	session_unset();
	session_destroy();
	$req->closeCursor();
	$db = null;
	header('Location: '.((!$url_rewriting) ? 'index.php?page=connexion&erreur=3' : 'connexion-erreur.html'));
}
?>
