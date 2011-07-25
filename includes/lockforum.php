<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de (sur)vérrouillage d'un forum (+notif)
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

if(empty($pseudo))
	include('./includes/notifs.php');

// Vérifie s'il vaut la peine d'aller plus loin.
elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['ids']) || $_GET['stat'] != 0 && $_GET['stat'] != -1 && $_GET['stat'] != -2) 
	include('./includes/notifs.php');
else
{
	// Si c'est un chef de groupe qui veut modifier
	if($rang == 3)
	{
		// On cherche le forum de ce sujet
		$sql = 'SELECT idsfa FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']);
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo())); $requse++;
		if($req->rowCount() != 0)
		{
			$data = $req->fetch();
			// On cherche le groupe de ce forum
			$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.intval($data['idsfa']);
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo())); $requse++;
			if($req->rowCount() != 0)
			{
				$data = $req->fetch();
				// Si c'est pas un groupe particulier, on arrête là
				if($data['groupe'] == 0 || $data['groupe'] == -1 || $data['groupe'] == -2 || $data['groupe'] == -3) $modifier = false;
				else
				{ 
					// Si c'est un groupe particulier, on vérifie s'il en est chef
					$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.intval($idmembre).'" AND idg = "'.intval($data['groupe']).'" AND stat = "1"';
					$req = $bdd->query($sql); $requse++;
					if($req->rowCount() == 0) $modifier = false;
					else $modifier = true;
				}
			}
			else
				$modifier = false;
		}
		else
			$modifier = false;
	}
	else 	// Les modos et admins sont d'office acceptés 
		$modifier = true;
	
	// On procède
	if($modifier)
	{
		$sql = 'UPDATE '.$prefixtable.'post SET `lock` = "'.intval($_GET['stat']).'"  WHERE id2 = "'.intval($_GET['ids']).'" OR  idsa = "'.intval($_GET['ids']).'"';
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));  $requse++;
		$bdd = null;
		
		display_error($langue_lockforum['lockforum1'], '
			<p>
				'.$langue_lockforum['lockforum2'].' '. 
					( ($_GET['stat'] == 0) ? $langue_lockforum['lockforum3'] : 
						(($_GET['stat'] == -1) ? $langue_lockforum['lockforum4'] 
							: $langue_lockforum['lockforum5'])).'.
			</p>
			<p>
				<a href="index.php?page=post&amp;ids='.intval($_GET['ids']).'">
					&gt;&gt; '.$langue_lockforum['lockforum6'].' &lt;&lt;
				</a>
			</p>');
	}
	else{
		echo $modifier;
		include('./includes/notifs.php');
	}
}
?>
