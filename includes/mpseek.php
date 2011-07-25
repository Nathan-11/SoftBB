<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de sélection d'un membre pour envoie d'un mp
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

	
$form = '<form name="form1" method="post" action="mpseekvalid.php">
			<label for="utilisateur">'.$lg_mps['mp1'].'</label> 
			<input type="text" id="utilisateur" name="pseudo" size="32" maxlength="64" /><br />
			<input type="submit" name="Submit" value="'.$lg_mps['mp2'].'" />
		</form>';

if(!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) 
	exit();

// s'il le membre est introuvable on propose	
if(isset($_GET['bad']) && isset($_GET['pseudo']) && !empty($_GET['pseudo']))
{
	// on va d'abord lister les occurences de pseudo ressemblant à ce pseudo pour faciliter l'utilisateur
	$aff = 0;
	if(strlen($_GET['pseudo']) >= 3){
		$sql = 'SELECT id, pseudo FROM '.$prefixtable.'membres WHERE pseudo LIKE "%'.add_gpc($_GET['pseudo']).'%"';
		$req = $bdd->query($sql);
		$requse++;
		$recher = true;
	}
	if(isset($recher) && $req->rowCount() > 0){
		
		$msg = '
			<p>'.$lg_mps['mp3'].'<br >
				'.$lg_mps['mp4'].'</p>
			<ul>';
		while($data = $req->fetch())
			$msg .= '<li><a onclick="document.getElementById(\'utilisateur\').value = \''.$data['pseudo'].'\'">'.$data['pseudo'].'</a></li>';
		$msg .= '</ul>';
		$msg .= $form;
		display_error($lg_mps['mp5'], $msg);
	}
	else
		display_error($lg_mps['mp6'], 
		'<p>'.$lg_mps['mp7'].'"'.$_GET['pseudo'].'" '.$lg_mps['mp8'].'</p>'.$form);
}
// sinon, on affiche le formulaire dans tous les cas
else
	display_error($lg_mps['mp9'], 
		$form);

$bdd = null;

?>
