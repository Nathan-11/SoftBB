<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'écriture du fichier info_options.php (traitement & affichage)
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


include_once('log.php'); 

function addslashes2 ($chaine) {
	return str_replace("'","\'",str_replace("\\","\\\\",$chaine));
}

if(isset($_POST['nomduforum']))
{
	echo '<h1>'.$lg_saveOpt['so0'].'</h1>';

$insert =  '<?php

$nomduforum = \''.addslashes2(strip_gpc($_POST['nomduforum'])).'\'; 
$mailadmin = \''.addslashes2(strip_gpc($_POST['mailadmin'])).'\'; 
$smtp = \''.addslashes2(strip_gpc($_POST['smtp'])).'\'; 
$nbsondage = '.intval($_POST['nbsondage']).'; 

$cache_forum = '.$_POST['cache_forum'].';
$url_rewriting = '.$_POST['url_rewriting'].';
$languedef = \''.$_POST['languedef'].'\';
$design = \'design/'.$_POST['designdef'].'/\';

$mailconf = '.$_POST['mailconf'].'; 
$gzip  = '.$_POST['gzip'].'; 
$autmodpseudo  = '.$_POST['autmodpseudo'].'; 
$afflistdelauto = '.$_POST['afflistdelauto'].'; 
$autorisationsign  = '.$_POST['autorisationsign'].'; 
$bbcodesign = '.$_POST['bbcodesign'].'; 
$ipaff  = '.$_POST['ipaff'].'; 
$affreprapide  = '.$_POST['affreprapide'].'; 

$lmax = '.intval($_POST['lmax']).'; 
$hmax = '.intval($_POST['hmax']).'; 
$pmax = '.intval($_POST['pmax']).'; 
$tmpfreepost = '.intval($_POST['tmpfreepost']).'; 

$membreparpage = '.intval($_POST['membreparpage']).'; 
$postparpage = '.intval($_POST['postparpage']).'; 
$postparpageaff = '.intval($_POST['postparpageaff']).';
$adresse = \''.addslashes2(strip_gpc($_POST['url'])).'\';

$lockforum = '.$_POST['lockforum'].';
$message_de_lock = \''.addslashes2(strip_gpc($_POST['message_de_lock'])).'\';
$upavatar = "1";

?>';

	$fp = fopen('../info_options.php','w+');
	fseek($fp,0);
	fputs($fp,$insert);
	fclose($fp);
		
	echo '<p>'.$lg_saveOpt['so1'].'</p>';
}
else{
	echo '<h1>'.$lg_saveOpt['so2'].'</h1>
	<p>'.$lg_saveOpt['so3'].'</p>';
}

echo '<p><a href="index.php?page=gest_opt">'.$lg_saveOpt['so4'].'</a></p>';

?>
