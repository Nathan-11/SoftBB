<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Indique les messages comme tous lu et redirige vers l'accueil
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
include('info_options.php');
if(isset($_SESSION['pseudo']))
{
	$pseudosave = $_SESSION['pseudo'];
	$logsave = $_SESSION['idlog'];
	// $_SESSION = array();
	$_SESSION['pseudo'] = $pseudosave;
	$_SESSION['idlog'] = $logsave;
	$_SESSION['lastvisit'] = time();
}
header('Location: '.((!$url_rewriting) ? 'index.php' : 'index.html'));
?>
