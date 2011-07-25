<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de notification d'erreur de groupe
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
 
if(!defined('IN_SOFTBB')) exit('Not in SoftBB');
include_once('./langue/'.$langue.'/langue_erreurgroup.php');


// Préparation du texte en affichage
$txt = '';
if(isset($_GET['type']))
{
	if($_GET['type'] == 'membreban')
		$txt .= '<p>'.$langue_erreurgroup['erreurgroup2'].'</p>';
	if($_GET['type'] == 'deja')
		$txt .= '<p>'.$langue_erreurgroup['erreurgroup3'].'</p>';
}
$txt .= '
<p>
	<a href="'.((!$url_rewriting) ? 'index.php?page=affgroupe&amp;groupe='.$_GET['retour'] : 'affgroupe-'.$_GET['retour'].'.html').'">
		'.$langue_erreurgroup['erreurgroup4'].'
	</a>
</p>';

display_error(
	$langue_erreurgroup['erreurgroup1'],
	$txt
);

?>
