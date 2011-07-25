<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'affichage de profil et recherche
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
 
echo '
<div class="top_infos">
	<a href="' . ((!$url_rewriting) ? 'index.php' : 'index.html' ).'">Index : '.$nomduforum.'</a>
</div>';

$aff = 1;
if(isset($_POST['pseudo']))
{
	$sql = 'SELECT id,www,avatar,localisation,rang,valid,nbpost,pseudo,mail FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($_POST['pseudo']).'"';
	$req = $bdd->query($sql);
	$requse++;
	if($req->rowCount() >0)
		$data = $req->fetch();
	else
	{
		// on va d'abord lister les occurences de pseudo ressemblant à ce pseudo pour faciliter l'utilisateur
		$aff = 0;
		if(strlen($_POST['pseudo']) >= 3){
			$sql = 'SELECT id, pseudo FROM '.$prefixtable.'membres WHERE pseudo LIKE "%'.add_gpc($_POST['pseudo']).'%"';
			$req = $bdd->query($sql);
			$requse++;
			$recher = true;
		}
		if(isset($recher) && $req->rowCount() > 0){
			$msg = '
				<p>Le nom exacte du pseudo n\'a pas été trouvé,<br >
					peut-être que l\'utilisateur que vous recherchez se trouve dans cette liste ?</p>
				<ul>';
			while($data = $req->fetch())
				$msg .= '
					<li>
						<a href="'.((!$url_rewriting) 
							? 'index.php?page=affprofil&id='.$data['id'] 
							: 'affprofil-'.$data['id'].'-'.casse($data['pseudo']).'.html').'">'.$data['pseudo'].'</a>
					</li>';
			$msg .= '</ul>';
			display_error('Quelques possibilités', $msg);
		}
		else
			display_error($langue_affprofil['affprofil1'], 
				$langue_affprofil['affprofil2'].'<br />
				'.$langue_affprofil['affprofil3'].'<br /><br />
				<a href="' . ((!$url_rewriting) ? 'index.php?page=membre' : 'membre.html').'">
					'.$langue_affprofil['affprofil4'].'
				</a>'
			);
		
	}
}
else
{ 
	$sql = 'SELECT id,www,avatar,localisation,rang,valid,nbpost,pseudo,mail,date_register,date_login FROM '.$prefixtable.'membres WHERE id = "'.intval($_GET['id']).'"';
	$req = $bdd->query($sql);
	$requse++;
	$bdd = null;
	$data = $req->fetch();
	$aff=1;
}

if($aff == 1)
{
	echo '
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_normal">
		<tr class="titreforum">
			<td width="150" class="titreforumstart texte_base_titrespec">'.$langue_affprofil['affprofil5'].'</td>
			<td class="titreforumend texte_base_titre">'.$langue_affprofil['affprofil6'].'</td>
		</tr>
		<tr>
			<td align="center" class="cadre_clair" style="padding:10px">
			';
			if($data['avatar'] != "http://" && $data['avatar'] != "")
				echo '<img src="'.$data['avatar'].'" alt="" />';
			else
				echo'-';
			if($rang == -1)
				$row = 1;
			else
				$row=3;
				
			echo'
			</td>
			<td rowspan="'.$row.'" valign="top" class="cadre1_bas" style="padding:10px">
				<span class="admin">'.$langue_affprofil['affprofil7'].' </span>';
			echo htmlentities($data['pseudo']);
			echo'
				<br /><span class="admin">'.$langue_affprofil['affprofil8'].' </span>
				'; 
				if($data['valid'] == 0)
				echo $langue_affprofil['affprofil9']; 
					elseif($data['rang'] == 2) echo $langue_affprofil['affprofil10'];
					elseif($data['rang'] == 1) echo $langue_affprofil['affprofil11'];
					elseif($data['rang'] == 3) echo $langue_affprofil['affprofil12'];
				else echo $langue_affprofil['affprofil13'];
				echo'
					<br />
					<br />
					<span class="modo">'.$langue_affprofil['affprofil14'].' </span>'.htmlentities($data['localisation']).'<br /><span class="modo">'.$langue_affprofil['affprofil15'].'. </span>';
					if(!empty($data['www']) && $data['www'] != "http://")
						echo'<a href="'.htmlentities($data['www']).'">'.htmlentities($data['www']).'</a>';
					else
						echo '-';
					
					if(date('d/m/Y') == date('d/m/Y', $data['date_register'])) {
						echo '<br /><span class="modo">' . $langue_affprofil['affprofil21'] . '</span>' . $langue_affprofil['affprofil23'] . date('H', $data['date_register']), 'h', date('i', $data['date_register']);
					} elseif(date('m/Y') == date('m/Y', $data['date_register']) && (intval(date('d'))-1) == intval(date('d', $data['date_register']))) {
						echo '<br /><span class="modo">' . $langue_affprofil['affprofil21'] . '</span>' . $langue_affprofil['affprofil24'] . date('H', $data['date_register']), 'h', date('i', $data['date_register']);
					} else {
						echo '<br /><span class="modo">' . $langue_affprofil['affprofil21'] . '</span>' . date('d/m/Y', $data['date_register']);
					}
					if(date('d/m/Y') == date('d/m/Y', $data['date_login'])) {
						echo '<br /><span class="modo">' . $langue_affprofil['affprofil21'] . '</span>' . $langue_affprofil['affprofil23'] . date('H', $data['date_login']), 'h', date('i', $data['date_login']);
					} elseif(date('m/Y') == date('m/Y', $data['date_login']) && (intval(date('d'))-1) == intval(date('d', $data['date_login']))) {
						echo '<br /><span class="modo">' . $langue_affprofil['affprofil21'] . '</span>' . $langue_affprofil['affprofil24'] . date('H', $data['date_login']), 'h', date('i', $data['date_login']);
					} else {
						echo '<br /><span class="modo">' . $langue_affprofil['affprofil21'] . '</span>' . date('d/m/Y', $data['date_login']);
					}
					
					echo' <br /><br /><span class="admin">'.$langue_affprofil['affprofil16'].' </span> ';
					echo $data['nbpost'];
					if($rang == 2)
						echo'<br /><br />'.$langue_affprofil['affprofil17'].' </span>'.$data['mail'];
				echo'
			</td>
		</tr>
		';
		if($rang == 2) 
		echo '
		<tr class="titreforum">
			<td width="230" class="titreforumstart texte_base_titrespec">'.$langue_affprofil['affprofil18'].'</td>
		</tr>
		<tr>
			<td class="cadre_clair" style="padding:10px">
				<a href="'.((!$url_rewriting) ? 'index.php?page=profil&amp;id='.$data['id'] : 'profil-'.$data['id'].'.html').'">
					<span class="modo">['.$langue_affprofil['affprofil19'].']</span>
				</a>
				<br />
				<a href="' . ((!$url_rewriting) ? 'index.php?page=mpsend&amp;id='.$data['id'] : 'mpsend-'.$data['id'].'.html').'">
					<span class="modo">['.$langue_affprofil['affprofil20'].']</span>
				</a>
				<br />
			</td>
		</tr>
		';
		$req->closeCursor(); 
		if($rang != -1 && $rang != 2){
			echo'
			<tr>
				<td width="250" class="titreforum">'.$langue_affprofil['affprofil18'].'</td>
			</tr>
			<tr>
				<td class="cadre_clair" style="padding:10px" align="center">
					<a href="'.((!$url_rewriting) ? 'index.php?page=mpsend&amp;id='.$data['id'] : 'mpsend-'.$data['id'].'.html').'">
						<span class="modo">['.$langue_affprofil['affprofil20'].']</span>
					</a>
					<br />
				</td>
			</tr>
			';
		}
	echo '</table>';
}
?>
