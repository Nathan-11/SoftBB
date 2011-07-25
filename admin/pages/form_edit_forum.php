<?php 

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de modification d'un forum (affichage)
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
echo '<h1>Modification d\'un forum </h1>';

$sql = 'SELECT nom,description FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt != 0';
$req = $db->query($sql);
$data = $req->fetch();
if($req->rowCount() == 0) 
	echo '<p>Il semble que la cat&eacute;gorie dans laquelle vous voulez ajouter un forum n\'existe pas/plus</p>';
else 
	echo '
<form name="form1" method="post" action="index.php?page=rensforum&id='.$_GET['id'].'">
	<p>
		Vous allez modifier le forum suivant : <strong>'.htmlentities($data['nom']).'</strong><br />
		Ces informations seront toujours modifiables par la suite si besoin est.
	</p>
	<p><input name="nom" type="text" class="bouton" value="'.htmlentities($data['nom']).'" size="20" maxlength="128" /></p>
	<p><textarea name="description" cols="" rows="" class="tbouton">'.htmlentities($data['description']).'</textarea></p>
	<input type="submit" name="Submit" value="Ajouter ce forum" class="bouton" />
</form>';

?>
