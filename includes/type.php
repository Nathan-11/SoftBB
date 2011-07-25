<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de déplacement de changement de type du sujet
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
 
if(!defined('IN_SOFTBB')) exit('Not in SoftBB');
 
if(empty($pseudo)) 
	include('./includes/notifs.php');
elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['ids']) || $_GET['stat'] != 0 && $_GET['stat'] != 1 && $_GET['stat'] != 2)
	include('./includes/notifs.php');
else
{
	// Si c'est un chef de groupe qui veut modifier
	if($rang == 3)
	{
		// On cherche le forum de ce sujet
		$sql = 'SELECT idsfa FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']);
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
		$requse++;
		if($req->rowCount() != 0)
		{
			$data = $req->fetch();
			// On cherche le groupe de ce forum
			$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.$data['idsfa'];
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
			$requse++;
			if($req->rowCount() != 0)
			{
				$data = $req->fetch();
				// Si c'est pas un groupe particulier, on arrete là
				if($data['groupe'] == 0 || $data['groupe'] == -1 || $data['groupe'] == -2 || $data['groupe'] == -3) $modifier = false;
				else
				{ 
					// Si c'est un groupe particulier, on vérifie s'il en est chef
					$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.$idmembre.'" AND idg = "'.$data['groupe'].'" AND stat = "1"';
					$req = $bdd->query($sql);
					$requse++;
					if($req->rowCount() == 0) 
						$modifier = false;
					else 
						$modifier = true;
				}
			}
			else
				$modifier = false;
		}
		else
			$modifier = false;
	}
	// Les modos et admins sont d'office acceptés
	else 
		$modifier = true;
	//On va faire ce qu'il faut
	if($modifier)
	{
		$sql = 'UPDATE '.$prefixtable.'post SET sondage = "'.$_GET['stat'].'"  WHERE id2 = "'.$_GET['ids'].'"';
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo())); 
		$requse++;
		$bdd = null;
		display_error($l_types['ty1'], '
		<p>'.$l_types['ty2'].
			(($_GET['stat'] == 0) ? $l_types['ty3']
				: (($_GET['stat'] == 1) ? $l_types['ty4'] : $l_types['ty5'])).'.
		</p>
		<p>
			<a href="'.((!$url_rewriting) ? 'index.php?page=post&amp;ids='.$_GET['ids'] : 'post-'.$_GET['ids'].'.html').'">
				'.$l_types['ty6'].'
			</a>
		</p>');
	}
	else
	{
		echo $modifier;
		include('./includes/notifs.php');
	}
}
?>
