<?php 

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Formulaire d'ajout d'un forum (affichage)
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

echo '<h1>'.$lg_fadd['fa0'].'</h1>';
	
$sql = 'SELECT nom FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['ida']).' AND fatt = 0';
$req = $db->query($sql);
$data = $req->fetch();
if($req->rowCount() == 0) 
	echo '<p>'.$lg_fadd['fa1'].'</p>';
else 
	echo '
<form name="form1" method="post" action="index.php?page=addforum&id='.$_GET['ida'].'">
	<p>
		'.$lg_fadd['fa2'].'<strong>'.htmlentities($data['nom']).'</strong><br />
		'.$lg_fadd['fa3'].'
	</p>
	<p>'.$lg_fadd['fa4'].'
		
	</p>
	<p>
		<input name="nom" type="text" class="bouton" value="'.$lg_fadd['fa5'].'" size="20" maxlength="128" onFocus="if(value==\''.$lg_fadd['fa5'].'\') value=\'\';" />
	</p>
	<p>
		<textarea name="description" cols="" rows="" class="tbouton" onFocus="if(value==\''.$lg_fadd['fa6'].'\') value=\'\';">'.$lg_fadd['fa6'].'</textarea>
	</p>
	<input type="submit" name="Submit" value="'.$lg_fadd['fa7'].'" class="bouton" />
</form>';

?>
