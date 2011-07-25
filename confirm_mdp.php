<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de changement de mot de passe
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
 
include('./includes/gpc.php');
include('info_bdd.php');
include('info_options.php');


if(isset($_GET['pseudo']) && isset($_GET['psw'])){
	$pseudo = $_GET['pseudo'];
	$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
	$sql = 'SELECT date, mdp FROM '.$prefixtable.'oubli WHERE pseudo = "'.add_gpc($pseudo).'" AND mdp="'.addslashes($_GET['psw']).'" AND date > "'.( time() - 24*60*60 ).'"';
	$req = $db->query($sql);
	$data = $req->fetch();
	$new_mdp = $data['mdp'];

	// on modifie le mot de passe du membre
	if($req->rowCount() == 1)
	{
		$req->closeCursor();
		$sql = 'UPDATE '.$prefixtable.'membres SET mdp = "'.$new_mdp.'" WHERE pseudo = "'.add_gpc($pseudo).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />' . $db->print_r($db->errorInfo())); 
		
		$sql = 'DELETE FROM '.$prefixtable.'oubli WHERE pseudo = "'.add_gpc($pseudo).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />' . $db->print_r($db->errorInfo())); 
		header('Location: '.(($url_rewriting) ? 'index.php' : 'indexforum.html' ));
		$valide = true;
	}
}

if(!isset($valide)){
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
	$req->closeCursor();
}

?>
