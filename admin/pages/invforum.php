<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de modification d'un forum (traitement)
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

$sql = 'SELECT nbsf,positionf FROM '.$prefixtable.'forum WHERE id = "'.intval($_GET['id']).'" AND fatt = 0';
$req = $db->query($sql);
$data = $req->fetch();

if($_GET['act'] == 'up' && $req->rowCount() != 0)
{
	$sql = 'SELECT nbsf,id FROM '.$prefixtable.'forum WHERE positionf = "'.(intval($data['positionf'])-1).'" AND fatt = 0';
	$req = $db->query($sql);
	$data2 = $req->fetch();
	if($req->rowCount() != 0)
	{
		$sql = 'UPDATE '.$prefixtable.'forum SET position = position-'.($data2['nbsf']+1).'  WHERE id = "'.intval($_GET['id']).'" OR  fatt = "'.intval($_GET['id']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
		
		$sql = 'UPDATE '.$prefixtable.'forum SET position = position+'.($data['nbsf']+1).'  WHERE id = "'.$data2['id'].'" OR  fatt = "'.$data2['id'].'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		
		$sql = 'UPDATE '.$prefixtable.'forum SET positionf = positionf+1  WHERE id = "'.$data2['id'].'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		
		$sql = 'UPDATE '.$prefixtable.'forum SET positionf = positionf-1  WHERE id = "'.intval($_GET['id']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
	}
}
elseif($_GET['act'] == 'down' && $req->rowCount() != 0)
{
	$sql = 'SELECT nbsf,id FROM '.$prefixtable.'forum WHERE positionf = "'.(intval($data['positionf'])+1).'" AND fatt = 0';
	$req = $db->query($sql);
	$data2 = $req->fetch();
	if($req->rowCount() != 0)
	{
		$sql = 'UPDATE '.$prefixtable.'forum SET position = position+'.($data2['nbsf']+1).'  WHERE id = "'.intval($_GET['id']).'" OR  fatt = "'.intval($_GET['id']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		
		$sql = 'UPDATE '.$prefixtable.'forum SET position = position-'.($data['nbsf']+1).'  WHERE id = "'.$data2['id'].'" OR  fatt = "'.$data2['id'].'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		
		$sql = 'UPDATE '.$prefixtable.'forum SET positionf = positionf-1  WHERE id = "'.$data2['id'].'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		
		$sql = 'UPDATE '.$prefixtable.'forum SET positionf = positionf+1  WHERE id = "'.intval($_GET['id']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
	}
}

include('../langue/'.$langue.'/admin/langue_conf_forum.php');
include('conf_forum.php'); 
?>
