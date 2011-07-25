<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de resynchronisation du nombre de messages d'un sujet
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

if(empty($_SESSION['pseudo'])) 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
$pseudo = $_SESSION['pseudo'];

$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
$sql = 'SELECT id,rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
$req = $db->query($sql) or die('Erreur SQL !<br />'.print_r($db->errorInfo()));
if($req->rowCount() == 0) 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
$data = $req->fetch();
$rang = $data['rang'];
$idmembre = $data['id'];

if(empty($pseudo)) 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
	
// Vérifie si ça vaut la peine d'aller plus loin
elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['id2'])) 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
	
// Si ça en vaut la peine
else
{
	// Si c'est un chef de groupe qui veut modifier
	if($rang == 3)
	{
		// On cherche le forum de ce sujet
		$sql = 'SELECT idsfa FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
		$req = $db->query($sql) or die('Erreur SQL !<br />'.print_r($db->errorInfo()));
		if($req->rowCount() != 0)
		{
			$data = $req->fetch();
			// On cherche le groupe de ce forum
			$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.$data['idsfa'];
			$req = $db->query($sql) or die('Erreur SQL !<br />'.print_r($db->errorInfo()));
			if($req->rowCount() != 0)
			{
				$data = $req->fetch();
				// Si c'est pas un groupe particulier, on arrete là
				if($data['groupe'] == 0 || $data['groupe'] == -1 || $data['groupe'] == -2 || $data['groupe'] == -3) $modifier = false;
				else
				{ 
					// Si c'est un groupe particulier, on vérifie s'il en est chef
					$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.$idmembre.'" AND idg = "'.$data['groupe'].'" AND stat = "1"';
					$req = $db->query($sql);
					$modifier = (($req->rowCount() == 0) ? false : true );
				}
			}
			else
				$modifier = false;
		}
		else
			$modifier = false;
	}
	// Les modos et admins sont d'office acceptés
	else $modifier = true;
	// On va faire ce qu'il faut
	if($modifier)
	{
		//////////////////////////////////////////////////////////////////////////////////////
		$sql = 'SELECT idsfa,idfa,idsa,nbr FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		if($req->rowCount() == 0) exit('1'); //Pas trop logique que ça bug
		$data = $req->fetch();
		if($data['idsa'] == 0)
		{
			$sql = 'SELECT tmppost,pseudode FROM '.$prefixtable.'post WHERE idsa = '.intval($_GET['id2']).' ORDER BY tmppost DESC';
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
			$data3 = $req->fetch();
			$nbr = $req->rowCount();
			$sql = 'UPDATE '.$prefixtable.'post SET nbr = '.$nbr.' , tmppost = '.$data3['tmppost'].' , pseudodernier = "'.addslashes($data3['pseudode']).'" WHERE id2 = '.intval($_GET['id2']);

			if($nbr == 0)
			{
				$sql = 'SELECT tmpsave,pseudode,tmpdernierpost FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']).' ORDER BY tmppost DESC';
				$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
				$data3 = $req->fetch();
				$sql = 'UPDATE '.$prefixtable.'post SET nbr = 0 , tmppost = '.$data3['tmpsave'].' , pseudodernier = "'.addslashes($data3['pseudode']).'" WHERE id2 = '.intval($_GET['id2']);
			}
					
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
			header('Location: index.php?page=notifs&aff=resynchok&ids='.intval($_GET['id2']));
		}
		else
			header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
		////////////////////////////////////////////////////////////////////////////////////////
	}
	else
		header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
}
?>    
