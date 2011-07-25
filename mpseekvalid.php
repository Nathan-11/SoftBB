<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de validation du pseudo envoyeur
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
include('info_options.php');
include('fonctions.php');
include('info_bdd.php');
$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);

if(isset($_POST['pseudo']))
{
	$sql = 'SELECT id FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($_POST['pseudo']).'"';
	$req = $db->query($sql);
	if($req->rowCount() >0)
	{
		$data = $req->fetch();
		header('Location: '.((!$url_rewriting)
			? 'index.php?page=mpsend&id='.$data['id']
			: 'mpsend-'.$data['id'].'-'.casse($_POST['pseudo']).'.html'));
	}
	else
		header('Location: '.((!$url_rewriting) ? 'index.php?page=mpseek&bad=&pseudo='.$_POST['pseudo'] : 'mpseekbad-'.$_POST['pseudo'].'.html'));
}
else
	header('Location: '.((!$url_rewriting) ? 'index.php?page=mpseek&bad=&pseudo='.$_POST['pseudo'] : 'mpseekbad-'.$_POST['pseudo'].'.html'));
	
?>
