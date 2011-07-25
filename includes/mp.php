<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Boîte de réception/ envoie de mp
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
if(isset($_SESSION['mprec'])) 
	unset($_SESSION['mprec']);
if(!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) 
	exit('8');

echo '
<div class="top_infos">
	<div class="mptop_left">
		<a href="'.((!$url_rewriting) ? 'index.php?page=mpseek' : 'mpseek.html') .'">
			<img src="'.$design.'actions/'.$langue.'/nouveau.gif" alt="'.$langue_mp['mp1'].'" title="'.$langue_mp['mp1'].'" />
		</a>
	</div>
	<div class="mptop_arbo">
		<a href="'.((!$url_rewriting) ? 'index.php' : 'index.html').'">
			'.$langue_index['index21'].' '.htmlentities($nomduforum).'
		</a> -&gt; 
		<a href="'.((!$url_rewriting) ? 'index.php?page=mp' :'mp.html') .'"> '
			.((!isset($_GET['send'])) ? '<span class="admin">' : '')
				.$langue_mp['mp2']
			.((!isset($_GET['send'])) ? '</span>' : '') .'
		</a>
	</div>
	<div style="clear:both;"></div>
</div>';


if($rang == -1)
	include('./includes/notifs.php');
else
{
	
	$sql = 'SELECT p.*,m.pseudo AS psde, m2.pseudo AS psa FROM '.$prefixtable.'mp AS p 
		LEFT JOIN '.$prefixtable.'membres AS m2 ON p.ida=m2.id 
		LEFT JOIN '.$prefixtable.'membres AS m ON p.idde=m.id 
		WHERE rep = 0 AND ((ida = '.$idmembre.' AND del2 = 0) OR (idde = '.$idmembre.' AND del1 = 0))
		ORDER BY dernier DESC, temps DESC';
	
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$requse++;
	
	echo '
	<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
		<tr class="titreforum">
			<td colspan="2" class="titreforumstart texte_base_titrespec">'.$langue_mp['mp3'].'</td>
			<td class="titreforum texte_base_titre">'.$langue_mp['mp4'].'</td>
			<td class="titreforum texte_base_titre">'.$langue_mp['mp5'].'</td>
			<td class="titreforum texte_base_titre">'.$langue_mp['mp6'].'</td>
			<td class="titreforumend texte_base_titre">'.$langue_mp['mp7'].'</td>
		</tr>
	';
	if($req->rowCount() == 0)
	{
		echo '
		<tr>
			<td colspan="6" class="cadre_fonce_end mp_nomsg">'.$langue_mp['mp8'].'</td>
		</tr>
		';
	}
	
	$unread = 0;
	while($data = $req->fetch())
	{
		echo '
		<tr>
			<td class="cadre_fonce cell1_mp_logo">';
			// Champ `Lu` pour l'auteur (idde), `lu2` pour le "destinaire" (ida)
			if(($data['lu'] == 0 && $data['idde'] == $idmembre) || ($data['lu2'] == 0 && $data['ida'] == $idmembre)){
				echo '<img src="'.$design.'statut/n_sujet.gif" alt="'.$langue_mp['mp9'].'" title="'.$langue_mp['mp9'].'" />';
				$unread++;
			}
			else
				echo '<img src="'.$design.'statut/sujet.gif" alt="'.$langue_mp['mp10'].'" title="'.$langue_mp['mp10'].'" />';
			echo'
			</td>
			<td class="cadre_fonce cell2_mp_title">
				<a href="'.((!$url_rewriting)
					? 'index.php?page=mpread&amp;idm='.$data['id'].(isset($_GET['send']) ? '&send=' : '')
					: 'mpread'.(isset($_GET['send']) ? 'send' : '').'-'.$data['id'].'-'.casse($data['titre']).'.html');
				;
			echo'">'.sit(htmlentities($data['titre'])).'
				</a>
			</td>
			<td class="cadre_clair cell3_mp_author">';
				if($data['idde'] == $idmembre){
					$_ps = $data['psa'];
					$_id  = $data['ida'];
				}
				else{
					$_ps = $data['psde'];
					$_id  = $data['idde'];
				}
				echo '
				<a href="'.((!$url_rewriting) 
					? 'index.php?page=affprofil&amp;id='.$_id
					: 'affprofil-'.$_id.'-'.casse($_ps).'.html'
					).'">
					'.htmlentities($_ps).'
				</a>';
			echo '
			</td>
			<td class="cadre_fonce cell4_mp_date">'.datefct($data['temps'],$gmt).'</td>
			<td class="cadre_fonce cell5_mp_nb">'.($data['nb']+1).'</td>
			<td class="cadre_fonce_end cell6_mp_del">'; 
				if(!isset($_GET['send'])) echo'<a onclick="decision(\''.$langue_mp['mp11'].'\',\'delmp.php?id='.$data['id'].'\')">[Supprimer]</a>';      
				else echo '-';
			echo'
			</td>
		</tr>
		';
	}
	
	// Si tous les sujets sont lu alors on arrêt de prévenir le membre de l'extérieur
	if($mp > 0 && $unread == 0)
	{
		$sql = 'UPDATE '.$prefixtable.'membres SET mp = 0 WHERE id = "'.intval($_SESSION['idlog']).'"';
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
		$requse++;
	}
	$bdd = null;
	
	echo '
	</table>
	
	<div class="bottom_infos">
	</div>
	';
}
?>
