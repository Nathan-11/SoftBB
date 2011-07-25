<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de connexion (html) 
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
 
if(!defined('IN_SOFTBB')) 
	exit('Not in SoftBB');

$_SESSION['token'] = true;
	
echo '<div class="notif_cadre">';
echo '<div class="notif_titre texte_base_titrespec">'.$langue_lgout['titre'].'</div>';
echo '<div class="notif_msg texte_base_normal"><p>'.$langue_lgout['question'].'<br /><a href="'.((!$url_rewriting) ? 'index.php' : 'index.html' ). '">'.$langue_lgout['reponse_non'].'</a> || <a href="logout.php">'.$langue_lgout['reponse_oui'].'</a></p></div></div>';
?>
