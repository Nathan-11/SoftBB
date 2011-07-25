<?php
 
/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de suppression d'un groupe (traitement)
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
 
$sql = 'SELECT idm FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($_GET['idg']).' AND stat = 1';
$req = $db->query($sql);
while($data = $req->fetch())
{
	$sql2 = 'SELECT idm FROM '.$prefixtable.'groupemembre WHERE idm = '.$data['idm'].' AND stat = 1';
	$req2 = $db->query($sql2);
	if($req2->rowCount() <= 1) 
	{
		$sql3 = 'SELECT rang FROM '.$prefixtable.'membres WHERE id = "'.$data['idm'].'"';
		$req3 = $db->query($sql3);
		$data3 = $req3->fetch();
		if($data3['rang'] == 3)
		{							
			$sql4 = 'UPDATE '.$prefixtable.'membres SET rang = 0  WHERE id = '.$data['idm'];
			$req4 = $db->query($sql4) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 
		}
	}
}

$sql = 'DELETE FROM '.$prefixtable.'groupemembre WHERE idg = "'.$_GET['idg'].'"';
$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 

$sql = 'DELETE FROM '.$prefixtable.'groupe WHERE id = "'.$_GET['idg'].'"';
$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 

$sql4 = 'UPDATE '.$prefixtable.'forum SET groupe = -3  WHERE groupe = '.$_GET['idg'];
$req4 = $db->query($sql4) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo())); 

include('valid_group_conf.php');
	
?>
