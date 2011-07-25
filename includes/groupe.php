<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de choix de listing des groupes
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

echo '
<div class="top_infos">
	<a href="'.( (!$url_rewriting) ? 'index.php' : 'index.html' ) .'">
		'.$langue_index['index21'].' '.htmlentities($nomduforum).'
	</a>
</div>';

$toDisplay = '';
$sql = 'SELECT * FROM '.$prefixtable.'groupe ORDER BY id DESC';
$req = $bdd->query($sql);
$requse++;
$bdd = null;

if($req->rowCount() != 0) {
	$toDisplay .= '
	<form name="form1" method="post" action="'.((!$url_rewriting)?'index.php?page=affgroupe' : 'affgroupe.html').'">
		<select name="groupe">';
		
		while($data = $req->fetch())
			$toDisplay .= '
			<option value="'.$data['id'].'">'.htmlentities($data['nom']).'</option>';
		$toDisplay .= '
		</select>
		<input type="submit" name="Submit" value="'.$langue_groupe['groupe2'].'" />
	</form>';
}
else 
	$toDisplay .= '<p>'.$langue_groupe['groupe3'].'</p>';
	
// affichage du résultat
display_error($langue_groupe['groupe1'], $toDisplay);
				
?>
