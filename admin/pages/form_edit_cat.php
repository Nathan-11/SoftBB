<?php 

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de modification d'une catégorie (affichage)
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

echo '
	<h1>Modification du nom d\'une cat&eacute;gorie</h1>';
	
$sql = 'SELECT nom FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt = 0';
$req = $db->query($sql);
$data = $req->fetch();
if($req->rowCount() == 0) echo '<p>Il semble que la cat&eacute;gorie que vous voulez modifier n\'existe pas/plus</p>';
else echo '
<form name="form1" method="post" action="index.php?page=renforum&id='.intval($_GET['id']).'">
	<p>
		Vous allez modifier le nom de la cat&eacute;gorie suivante : <strong>'.htmlentities($data['nom']).'</strong><br />
		Ces informations seront toujours modifiables par la suite si besoin est.</p>
	<p>
		<input name="nom" type="text" class="bouton" value="'.htmlentities($data['nom']).'" size="20" maxlength="128" />
		<input type="submit" name="Submit" value="Modifier cette cat&eacute;gorie" class="bouton" />
	</p>
</form>
	';
	
?>
