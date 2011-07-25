<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'affichage des groupes (affichage)
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
	return str_replace("'","\'",$chaine);
}

echo '<h1>'.$lg_gestGrp['gg0'].'</h1>
	<p>'.$lg_gestGrp['gg1'].'
	</p>
	<p><strong>'.$lg_gestGrp['gg2'].'</strong></p>';

	
$sql = 'SELECT nom,id FROM '.$prefixtable.'groupe';
$req = $db->query($sql);


if($req->rowCount() == 0) 
	echo '<p>'.$lg_gestGrp['gg3'].'</p>';
else {
	while($data = $req->fetch()) 
		echo '. '.htmlentities($data['nom']).' - <a href="#" onclick="decision(\''.$lg_gestGrp['gg4'].'('.addslashes2(htmlentities($data['nom'])).')\',\'index.php?page=delgroupe&idg='.intval($data['id']).'\')">['.$lg_gestGrp['gg7'].']</a><br />';
}

echo'
<p><strong>'.$lg_gestGrp['gg5'].'</strong></p>
	<form name="form1" method="post" action="index.php?page=addgroupe">
		<input name="groupe" type="text" class="bouton" size="30" maxlength="64"> 
		<input type="submit" name="Submit" class="bouton" value="'.$lg_gestGrp['gg6'].'">
	</form>';
	
?>
