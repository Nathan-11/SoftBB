<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Affichage d'une liste d'émoticones
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
include('info.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Ajouter un smilies</title>
	<link href="<?php echo $design; ?>/styles/general.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="post_fct.js"></script>
</head>
<body>
<table class="texte_base_gras" width="100%" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumstart texte_base_titre" colspan="2">Ajouter un smiley</td>
	</tr>
	<tr>
		<td width="50%" align="center" class="cadre_clair">Code</td>
		<td width="50%" height="30" align="center" class="cadre_clair">Image</td>
	</tr> 
	<?php
	include('info_bdd.php');
	include('info_emote.php');
	
	for($i=0;$i<$emoticonnb;$i++)
	{
	echo'
	<tr>
		<td align="center" class="cadre_fonce">
			<a href="javascript:emoticon(\''.$emoticonc[$i].'\', \'texte\')">'.htmlentities($emoticonc[$i]).'</a>
		</td>
		<td height="30" align="center" class="cadre_clair" style="padding:3px">
			<a href="javascript:emoticon(\''.$emoticonc[$i].'\', \'texte\')"><img src="'.$emoticonv[$i].'" alt="Emoticone" /></a>
		</td>
	</tr>
	';
	}
?>
</table>
</body>
</html>
