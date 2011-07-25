<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Ajout d'un membre dans un groupe
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
include('./includes/gpc.php');
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
		$sql2 = 'SELECT pseudo,id FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($_POST['pseudo']).'"  AND valid = "1"';
		$req2 = $db->query($sql2);
		if($req2->rowCount() == 1)
		{
			$data = $req2->fetch();	
			$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.$data['id'].'" AND idg ='.$_GET['groupe'];
			$req3 = $db->query($sql);
			if($req3->rowCount() == 0)
			{
				$sql = 'INSERT INTO '.$prefixtable.'groupemembre (`idm`, `idg`, `pseudom`, `stat`) VALUES ("'.$data['id'].'","'.$_GET['groupe'].'",0,0)';
				$req = $db->query($sql) or die('Erreur SQL !'.$db->print_r($db->errorInfo()));
			}  
			else
				$redir = 'index.php?page=erreurgroup&type=deja&retour='.$_GET['groupe'];  
		}  
		else 
			$redir = 'index.php?page=erreurgroup&type=membreban&retour='.$_GET['groupe'];
			  
 		if(!isset($redir))
			$redir = 'index.php?page=affgroupe&groupe='.$_GET['groupe'];
	} 
 	else 
		$redir = ((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' );
}
else
	$redir = ((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' );
	
header('Location: '.$redir);
?>
