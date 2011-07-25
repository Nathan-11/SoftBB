<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de redirection vers le dernier message à partir de l'id du sujet
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
include('info_bdd.php');
include('info.php');
include('info_options.php');
$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);



	$sql = 'SELECT nbr,titre FROM '.$prefixtable.'post WHERE id2 = '.$_GET['post'];
	$req = $db->query($sql)  or die('Erreur SQL !'.$db->print_r($db->errorInfo()));
	$data = $req->fetch(); 
	
	$sql2 = 'SELECT id2 FROM '.$prefixtable.'post WHERE idsa = '.$_GET['post'].' order by tmppost DESC ';
	$req2 = $db->query($sql2)  or die('Erreur SQL !'.$db->print_r($db->errorInfo()));
	$data2 = $req2->fetch(); 

$page = ceil(($data['nbr']+1)/$postparpageaff)-1;

header('Location: '.((!$url_rewriting)
	? 'index.php?page=post&ids='.$_GET['post']. (($page != 0)?'&pg='.$page : '').'#'.$data2['id2']
	: 'post-'.$_GET['post'] . (($page != 0)?'-p'.$page : '') .'-'.casse($data['titre']).'.html#'.$data2['id2'])
	);

?>
