<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Suppression d'un sujet
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
if(empty($_SESSION['pseudo'])) 
	exit();

$pseudo = $_SESSION['pseudo'];
include('info_bdd.php');
include('info_options.php');

$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
$sql = 'SELECT id,rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
if($req->rowCount() == 0) 
	exit();

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
	$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		if($req->rowCount() != 0)
		{
			$data = $req->fetch();
			// On cherche le groupe de ce forum
			$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.$data['idsfa'];
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
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
					if($req->rowCount() == 0) $modifier = false;
					else $modifier = true;
				}
			}
			else
				$modifier = false;
		}
		else
			$modifier = false;
	}
	// Les modos et admins sont d'office acceptés
	else 
		$modifier = true;
	
	// On va faire ce qu'il faut
	if($modifier)
	{
		//////////////////////////////////////////////////////////////////////////////////////
		$sql = 'SELECT idsfa,idfa,idsa,nbr FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		if($req->rowCount() == 0) // Si ça plante c'est que le message a déjà été supprimée, ne devrait pas arriver...
			header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' )); 
		$data = $req->fetch();
		if($data['idsa'] > 0)
		{ 
			$sql = 'DELETE FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
			$req = $db->query($sql) or die('Erreur SQL !'.$db->print_r($db->errorInfo())); 
		
			$sql = 'SELECT tmppost,pseudode FROM '.$prefixtable.'post WHERE idsa = '.$data['idsa'].' ORDER BY tmppost DESC';
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
			$data2 = $req->fetch();
		
			$sql = 'UPDATE '.$prefixtable.'post SET tmppost = '.$data2['tmppost'].', pseudodernier = "'.addslashes($data2['pseudode']).'"  , nbr = nbr-1 WHERE id2 = '.$data['idsa'];
			if($req->rowCount() == 0)
			{
				$sql = 'SELECT tmppost,pseudode,tmpsave FROM '.$prefixtable.'post WHERE id2 = '.$data['idsa'].' ORDER BY tmppost DESC';
				$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
				$data2 = $req->fetch();
			
				$sql = 'UPDATE '.$prefixtable.'post SET tmppost = '.$data2['tmpsave'].', pseudodernier = "'.addslashes($data2['pseudode']).'" , nbr = nbr-1 WHERE id2 = '.$data['idsa'];
			}
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

			$sql = 'SELECT tmppost,pseudodernier,tmpdernierpost FROM '.$prefixtable.'post WHERE idsfa = '.$data['idsfa'].' AND `lock` < 1 AND idsa <1 ORDER BY tmppost DESC';
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
			$data3 = $req->fetch();
		
			$sql = 'UPDATE '.$prefixtable.'forum SET temps = '.$data3['tmppost'].', adernier = "'.addslashes($data3['pseudodernier']).'" , dernier = "'.addslashes($data3['tmpdernierpost']).'" , nbmessage = nbmessage-1 WHERE id = '.$data['idsfa'];
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
			header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=delvalid&id2='.$data['idsa'] : 'notif-delvalid-'.$data['idsa'].'.html'));
		}
		else
		{
			$sql = 'DELETE FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']).' OR `lock` = '.intval($_GET['id2']).' OR idsa = '.intval($_GET['id2']);
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		
			$sql = 'DELETE FROM '.$prefixtable.'sondage WHERE idpost = '.intval($_GET['id2']);
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

			$sql = 'DELETE FROM '.$prefixtable.'voter WHERE idpost = '.intval($_GET['id2']);
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

			$sql = 'SELECT tmppost,pseudodernier,tmpdernierpost FROM '.$prefixtable.'post WHERE idsfa = '.$data['idsfa'].' AND `lock` < 1 AND idsa <1 ORDER BY tmppost DESC';
			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
			$data3 = $req->fetch();
		
			$sql = 'UPDATE '.$prefixtable.'forum SET temps = '.$data3['tmppost'].', adernier = "'.addslashes($data3['pseudodernier']).'" , dernier = "'.addslashes($data3['tmpdernierpost']).'" , nbsujet = nbsujet-1 , nbmessage = nbmessage-'.$data['nbr'].' WHERE id = '.$data['idsfa'];
			
			if($req->rowCount() == 0)
				$sql = 'UPDATE '.$prefixtable.'forum SET temps = 0, adernier = "-" , dernier = "-" , nbsujet = "0" , nbmessage = "0" WHERE id = '.$data['idsfa'];

			$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
			
			header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=delvalid2&id2='.$data['idsfa'] : 'notif-delvalid2-'.$data['idsfa'].'.html'));
		}
	}
	else
		header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
}
?>     
