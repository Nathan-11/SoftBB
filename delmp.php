<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Suppression d'un message privé
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
include('info_bdd.php');
include('info_options.php');
include('includes/gpc.php');

if(!isset($_SESSION['pseudo']) || !isset($_SESSION['idlog']) || !isset($_GET['id']) || !is_numeric($_GET['id']))
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreure.html' ));

$_SESSION['idlog'] = intval($_SESSION['idlog']);
$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);

// vérification existance du membre
$sql = 'SELECT id FROM '.$prefixtable.'membres WHERE id = "'.$_SESSION['idlog'].'" AND mdp="'.add_gpc($_COOKIE['mdp']).'" AND valid = "1" ';
$req = $db->query($sql) or die('Erreur SQL !<br />'.print_r($db->errorInfo()));
if($req->rowCount() == 1)
{
	// on recherche si le membre fait partie de la discution en envoyeur ou en receveur
	$sql = 'SELECT ida, idde FROM '.$prefixtable.'mp 
			WHERE id = "'.intval($_GET['id']).'" AND rep=0 AND (ida = "'.$_SESSION['idlog'].'" OR idde = "'.$_SESSION['idlog'].'") ';
	$req = $db->query($sql) or die('Erreur SQL !<br />'.print_r($db->errorInfo()));
	if($req->rowCount() == 1)
	{
		$data = $req->fetch();
		$sql = 'UPDATE '.$prefixtable.'mp SET '.(($data['idde'] == $_SESSION['idlog']) ? 'del1' : 'del2' ).' = 1 WHERE id="'.intval($_GET['id']).'" ';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.print_r($db->errorInfo()));
	}
	else
		header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreurrrrr.html' ));
}
else
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreurrrrr.html' ));

// OK -> redir vers mp immédiate
header('Location: '.((!$url_rewriting) ? 'index.php?page=mp' : 'mp.html'));	
?>
