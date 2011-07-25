<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Post-traitement avant affichage d'une page admin
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
$time = date("l, j F Y [h:i a]");

// pas pass� par index.php (non s�curis�)
if(!defined('administration'))
	exit('Vous n\'�tes pas dans l\'administration');

$ip = ((!empty($_SESSION['pseudo'])) 
	? $_SESSION['pseudo'].':' 
	: '')
	.$_SERVER['REMOTE_ADDR'];


$phrase_erreur = '<p>/!\ Seuls les administrateurs peuvent avoir acc�s � l\'administration.</p>
	<p>Si vous �tes administrateur, passez pr&eacute;alablement sur le forum pour vous connecter</p>';

if(empty($_SESSION['idlog'])) exit($phrase_erreur);
include('../info_bdd.php');
include('../info_options.php');
$design = '../'.$design;	// remonter d'un dossier pou l'acc�s aux images


$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
$sql = 'SELECT rang,langue,pseudo FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"  AND valid = "1" AND rang= "2"';
$req = $db->query($sql) or die('Erreur SQL : '.print_r($db->errorInfo()));
if($req->rowCount() == 0) 
	exit($phrase_erreur); 



$req = $req->fetch();
$langue1 = $req['langue'];
$pseudo = $req['pseudo'];

$quotes_gpc = get_magic_quotes_gpc();

// Langue
if(empty($langue1) || !is_dir('../langue/'.$langue1.'/admin/'))
	$langue = 'fr';
else
	$langue = $langue1;

// Pages pouvant �tre inclues
$inclauto = array('conf_forum', 'gest_group', 'gest_opt', 'gest_langue', 'gest_rang', 'gest_emotes', 'del', 'possforum', 'form_edit_aut', 'adforum', 'delforum', 'invforum', 'vidage', 'form_edit_forum','form_edit_cat','form_add_forum','auto','renforum','rensforum','addgroupe','delgroupe','save_opt','addforum');
include_once('../langue/'.$langue.'/admin/langue_index.php');

?>
