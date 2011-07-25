<?php  

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Index administration - Main frames
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

define('administration', true);
include_once('../includes/gpc.php');
include_once('../info_options.php');
include_once('log.php');

// voirforum : on n'affiche que l'iframe, mieux interprété par l'attribut height
if((isset($_GET['page']) && $_GET['page'] != 'index') && $_GET['page'] == 'voirforum')
	die('<iframe src="../index.php" style="width:100%; height:100%;" frameborder="0"></iframe>');
	
// Affichage de l'en-tête et importation des style
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>'.$lg_ind['i0'].'</title>
		<link rel="stylesheet" href="./install.css" type="text/css" />
		<script type="text/javascript" src="../global_fct.js"></script>
	</head>
	';
$img = 'style="padding-right:5px; "';	// commun à toutes les images du menu (forum, smileys, plug-ins, ...) <img $img src=""...>

// Si on ne demande ni le menu, ni la page c'est qu'on demande la structure principale
if(!isset($_GET['menu']) && !isset($_GET['page'])){
	echo ' 
	<FRAMESET rows="104,*" frameborder="no" scrolling="no" framespacing="0" name="frame">	
		<FRAME name="sommaire" target="page" src="index.php?menu=" scrolling="auto">
		<FRAME name="page" src="index.php?page=index" scrolling="auto">
	</FRAMESET>';
}

// Demande du menu
elseif(isset($_GET['menu'])){
	echo '
	<div id="topzone">
		<div class="tz_left">
			<a href="index.php?page=index&index" target="page">
				<img src="design/logo_admin.png" alt="'.$lg_ind['i0'].'" />
			</a><br />
			<div style="margin-left:20px;">
				<a href="../index.php?page=admin" target="page" title="'.$lg_ind['i1'].'">
					<img src="'.$design.'moderation/annonce.gif" style="width:11px;" />
					Vérifier mises à jour
				</a><br />
				<a href="./index.php?page=voirforum" target="page" title="'.$lg_ind['i2'].'">
					<img src="'.$design.'moderation/synchroniser.gif" style="width:11px;" /> Apercu du forum
				</a> | 
				<a href="../index.php" target="frame" title="'.$lg_ind['i3'].'">
					<img src="'.$design.'moderation/suppr_sujet.gif" style="width:11px;" /> Quiter admin
				</a><br />
			</div>
		</div>
		<div style="white-space:nowrap; margin-left:280px; text-align:center;">
			<table class="logo_tab">
				<tr>
					<td class="icon"><a href="./index.php?page=conf_forum" target="page"><img '.$img.' src="'.$design.'design/admin_forums.png" alt="img" title="'.$lg_ind['i4'].'" /><div class="txt">'.$lg_ind['i4b'].'</div></a></td>
					<td class="icon"><a href="./index.php?page=gest_opt" target="page"><img '.$img.' src="'.$design.'design/admin_options.png" alt="img" title="'.$lg_ind['i5'].'" /><div class="txt">'.$lg_ind['i5b'].'</div></a></td>
					<td class="icon"><a href="./index.php?page=gest_group" target="page"><img '.$img.' src="'.$design.'design/admin_groupe.png" alt="img" title="'.$lg_ind['i6'].'" /><div class="txt">'.$lg_ind['i6b'].'</div></a></td>
					<td class="icon"><a href="./index.php?page=gest_emotes" target="page"><img '.$img.' src="'.$design.'design/admin_smiley.png" alt="img" title="'.$lg_ind['i7'].'" /><div class="txt">'.$lg_ind['i7b'].'</div></a></td>
					<td class="icon"><a href="./index.php?page=gest_rang" target="page"><img '.$img.' src="'.$design.'design/admin_rang.png" alt="img" title="'.$lg_ind['i7c'].'" /><div class="txt">'.$lg_ind['i7d'].'</div></a></td>
					<td class="icon_notxt"><a href="./index.php?page=mods_index" style="cursor:pointer;" target="page"><img '.$img.' src="'.$design.'design/admin_mod.png" alt="img" title="'.$lg_ind['i8'].'" /></a></td>
					<td class="icon_notxt"><a href="./index.php?page=gest_opt_mod" target="page"><img '.$img.' src="'.$design.'design/admin_mod_opt.png" alt="img" title="'.$lg_ind['i9'].'" /></a></td>
				</tr>
			</table>
		</div>
	</div>';
}

// Demande d'une page
elseif(isset($_GET['page']) && $_GET['page'] != 'index')
{
	echo '
<body>
	';
	if(file_exists('pages/'.$_GET['page'].'.php'))
	{
		if(file_exists('../langue/'.$langue.'/admin/langue_'.$_GET['page'].'.php'))
			require_once('../langue/'.$langue.'/admin/langue_'.$_GET['page'].'.php');
		
		echo '<div id="install">';
			include('pages/'.$_GET['page'].'.php');
		echo '</div>';
	}
	else
		die($lg_ind['i12'].' ('.$_GET['page'].')');
}

// La page d'accueil
else{
	echo '<div id="install">
	<div id="right">'.$lg_ind['i10'].'</div>
	<div class="clear"></div>
	'.$lg_ind['i11'];
}

?>

</body>
</html>
