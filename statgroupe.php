<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de modification de groupe
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

 
ini_set("register_globals","off"); 
session_start();
include('info_bdd.php');
include('info_options.php');


if(isset($_SESSION['pseudo']))
	$pseudo = $_SESSION['pseudo'];
else 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' )); 

$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);

$sql = 'SELECT rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"  AND valid = "1"';
$req = $db->query($sql);

if($req->rowCount() == 1) 
{
	$data = $req->fetch(); 
	if($data['rang'] == 2 || $data['rang'] == 1)
	{
		$sql = 'SELECT rang,id FROM '.$prefixtable.'membres WHERE id = "'.intval($_GET['idm']).'"  AND valid = "1"';
		$req = $db->query($sql);
		if($req->rowCount() == 1)
		{
			$data = $req->fetch();
			//-1-------------------------------------------------------------
			// Montée en rang
			if($_GET['act'] == "up")
			{
				// Si rang déjà suffisant
				if($data['rang'] == 1 || $data['rang'] == 2 || $data['rang'] == 3)
				{
					$sql = 'UPDATE '.$prefixtable.'groupemembre SET stat = 1  WHERE idm = '.intval($_GET['idm']).' AND idg = '.intval($_GET['idg']);
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
				}
				// Autrement
				else 
				{
					$sql = 'UPDATE '.$prefixtable.'groupemembre SET stat = 1  WHERE idm = "'.intval($_GET['idm']).'"  AND idg = '.intval($_GET['idg']);
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
					$sql = 'UPDATE '.$prefixtable.'membres SET rang = 3  WHERE id = "'.intval($_GET['idm']).'"';
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
				}
			}
			// Descente en rang
			//-2-------------------------------------------------------------
			else
			{
				// Si rang suffisant
				if($data['rang'] == 1 || $data['rang'] == 2)
				{
					$sql = 'UPDATE '.$prefixtable.'groupemembre SET stat = 0  WHERE idm = '.intval($_GET['idm']).' AND idg = '.intval($_GET['idg']);
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
				}
				// Autrement
				else 
				{
					$sql = 'UPDATE '.$prefixtable.'groupemembre SET stat = 0  WHERE idm = '.intval($_GET['idm']).' AND idg = '.intval($_GET['idg']);
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
					$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.intval($_GET['idm']).'" AND stat = 1';
					$req = $db->query($sql);
					// Si chef dans autres groupes
					if($req->rowCount() == 0)
					{
						$sql = 'UPDATE '.$prefixtable.'membres SET rang = 0  WHERE id = "'.intval($_GET['idm']).'"';
						$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
					}
				}
			}
			//-3-------------------------------------------------------------
			header('Location: '.((!$url_rewriting) 
				? 'index.php?page=affgroupe&groupe='.intval($_GET['idg'])
				: 'affgroupe-'.intval($_GET['idg']).'.html'));
		}	
 		else
			header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));			
	}
	else 
		header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' )); 
}
else 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' )); 
?>
