<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de suppression de sondage
 *   Version : 1.x
 *   
 *   Copyright            : (C) 2005-201x - �quipe SoftBB.net
 *   Site-web             : http://www.softbb.net/
 *   Em@il                : Voir sur le site
 *   D�veloppement        : Equipe SoftBB - ouverte - (voir sur le site)
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique G�n�rale GNU.
 *   Plus d'infos sur /index.php
 *
 ***************************************************************************/
if(!defined('IN_SOFTBB')) 
	exit('Not in SoftBB');
 
if(empty($pseudo))
	include('./includes/notifs.php');
// Non modo ni admins, ni id valide
elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['ids'])) 
	include('./includes/notifs.php');
// OK, reste � v�rifier pour le groupe
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
			$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.$data['idsfa'];
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo())); $requse++;
			if($req->rowCount() != 0)
			{
				$data = $req->fetch();
				// Si c'est pas un groupe particulier, on arrete l�
				if($data['groupe'] == 0 || $data['groupe'] == -1 || $data['groupe'] == -2 || $data['groupe'] == -3) $modifier = false;
				else
				{ 
					// Si c'est un groupe particulier, on v�rifie s'il en est chef
					$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.$idmembre.'" AND idg = "'.$data['groupe'].'" AND stat = "1"';
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
	else 	// Les modos et admins sont d'office accept�s
		$modifier = true;
	
	// On va faire ce qu'il faut
	if($modifier)
	{
		$sql = 'DELETE FROM '.$prefixtable.'sondage WHERE idpost = '.intval($_GET['ids']);
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));  $requse++;
	
		$sql = 'DELETE FROM '.$prefixtable.'voter WHERE idpost = '.intval($_GET['ids']);
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));  $requse++;
	
		$sql = 'UPDATE '.$prefixtable.'post SET tmpdernierpost = "0" WHERE id2 = '.intval($_GET['ids']);
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));  $requse++;
		$bdd = null;

		display_error($langue_delsonde['delsonde1'], '
			<p>'.$langue_delsonde['delsonde2'].'.</p>
			<p>
				<a href="'.((!$url_rewriting) ? 'index.php?page=post&ids'.$_GET['ids'] : 'post-'.$_GET['ids'].'.html').'">
					&gt;&gt; '.$langue_delsonde['delsonde3'].' &lt;&lt;
				</a>
			</p>');
	}
	else 	// chef de groupe refus�
	{
		echo $modifier;
		include('./includes/notifs.php');
	}
}
?>
