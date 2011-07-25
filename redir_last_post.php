<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de redirection vers le dernier message à partir de l'id du forum
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
include('info_options.php');
include('fonctions.php');
$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);


$sql = 'SELECT id2,idsa, titre FROM '.$prefixtable.'post WHERE idsfa = "'.intval($_GET['forum']).'" AND (idsa !=0 OR idsa = 0 AND nbr = 0) ORDER BY tmppost DESC';
$req = $db->query($sql)  or die('Erreur SQL !'.$db->print_r($db->errorInfo()));
$data = $req->fetch(); 

if($req->rowCount() == 0) 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));

if($data['idsa'] != 0)
{
	$sql = 'SELECT nbr, titre FROM '.$prefixtable.'post WHERE id2 = '.$data['idsa'];
	$req = $db->query($sql)  or die('Erreur SQL !'.$db->print_r($db->errorInfo()));
	$data2 = $req->fetch(); 
	$page = ceil(($data2['nbr']+1)/$postparpageaff)-1;
	header('Location: '.((!$url_rewriting) 
		? 'index.php?page=post&ids='.$data['idsa']. (($page != 0)?'&pg='.$page : '').'#'.$data['id2']
		: 'post-'.$data['idsa']. (($page != 0)?'-p'.$page : '').'-'.casse($data2['titre']).'.html#'.$data['id2'])
	);
}
else 
	header('Location: '.((!$url_rewriting)
		? 'index.php?page=post&ids='.$data['id2']
		: 'post-'.$data['id2'].'-'.casse($data['titre']).'.html'));
?>
