<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de modification de position d'un forum (traitement)
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

$temps = time();

$sql = 'SELECT fatt,position FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt != 0';
$req = $db->query($sql);
$data = $req->fetch();

$sql = 'SELECT nbsf,position FROM '.$prefixtable.'forum WHERE id = '.$data['fatt'];
$req1 = $db->query($sql);
$data2 = $req1->fetch();

if($req->rowCount() != 0 && ($_GET['act'] == 'up' && $data['position'] > $data2['position']+1 || $_GET['act'] == 'down' && $data['position'] < $data2['position']+$data2['nbsf']) )
{
	if($_GET['act'] == 'up')
	{
		$pos1 = $data['position']-1;
		$pos2 = $data['position'];
	}
	elseif($_GET['act'] == 'down')
	{
		$pos1 = $data['position']+1;
		$pos2 = $data['position'];
	}

	if($_GET['act'] == 'down' || $_GET['act'] == 'up')
	{
		$sql = 'UPDATE '.$prefixtable.'forum SET position = '.$pos2.'  WHERE position = "'.$pos1.'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		
		$sql = 'UPDATE '.$prefixtable.'forum SET position = '.$pos1.'  WHERE id = "'.intval($_GET['id']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
	}
}

include('../langue/'.$langue.'/admin/langue_conf_forum.php');
include('conf_forum.php'); 

?>
