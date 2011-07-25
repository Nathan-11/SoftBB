<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page dde lecture d'un mp
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

if($rang == -1 || !isset($_GET['idm']) || !is_numeric($_GET['idm']))
	include('./includes/notifs.php');
else
{
	$sql = 'SELECT m.*,s.pseudo AS sender, s.avatar FROM '.$prefixtable.'mp AS m 
		LEFT JOIN '.$prefixtable.'membres AS s ON m.idde=s.id
		WHERE (m.id = '.intval($_GET['idm']).' OR m.rep = '.intval($_GET['idm']).')
			AND ( ( idde = '.$idmembre.' AND del1 <> 1) OR ( ida = '.$idmembre.' AND del2 <> 1) )
		ORDER BY temps ASC';
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$requse++;

	// $req->closeCursor();
	if($req->rowCount() > 0)
	{
		$premierPost = true;
		$envoyeur = $receveur = 0;
		$topinfos = $reponserapide = '';
		
		while($data = $req->fetch())
		{
			// Premier post = préparation champs réponse rapide, non lu/lu pour profiter des données de l'entrée principale du sujet
			if($premierPost)
			{
				if($data['rep'] != 0){
					include('includes/notifs.php');
					die();
				}
				$envoyeur = $data['idde'];
				$receveur = $data['ida'];
				
				// réponse rapide
				if($data['del1'] == 0 && $data['del2'] == 0)
				{
					$reponserapide = '
					<div class="mpr_endbloc"></div>
					<div id="mpr_mainbloc">
						<a href="'.((!$url_rewriting) ? 'index.php?page=affprofil&id='.$idmembre : 'affprofil-'.$idmembre.'-'.casse($pseudo).'.html' ).'">
							<div class="mpr_infomb '.(($idmembre == $envoyeur) ? 'mpr_infomb_e' : 'mpr_infomb_r' ).'">
								'.$pseudo.'<br />
								'.((!empty($avatar)) ? '<img src="'.$avatar.'" alt="Avatar de '.$pseudo.'" class="mpr_avatarimg" />' : '').'
							</div>
						</a>
						<div class="mpr_infos '.(($data['idde'] == $envoyeur) ? 'mpr_infos_e' : 'mpr_infos_r' ).'">'.$lg_mpr['mp1'].'</div>
						<div class="mpr_txt '.(($idmembre == $envoyeur) ? 'mpr_txt_e' : 'mpr_txt_r' ).'">
							<form action="'.((!$url_rewriting)
								? 'index.php?page=mpsend&amp;id='.$_GET['idm'].'&rep='.(($idmembre == $receveur) ? $envoyeur : $receveur )
								: 'mprep-'.(($idmembre == $receveur) ? $envoyeur : $receveur ).'-'.$_GET['idm'].'-'.casse($data['titre']).'.html').'" 
								method="post" enctype="multipart/form-data" name="post">
								
								<textarea name="texte" id="texte" class="speedrep_textarea"></textarea>
								<input maxlength="64" type="hidden" value="RE: '.$data['titre'].'" class="speedrep_titre" name="'.$lg_mpr['mp2'].'" />
								<input type="submit" name="previsu" value="'.$lg_mpr['mp3'].'" />
								<input type="submit" name="Submit" value="'.$lg_mpr['mp4'].'" />
								<input type="hidden" name="repto" value="'.$_GET['idm'].'" />
							</form>
						</div>
					</div>';
				}
				
				// espace supérieur
				$topinfos = '
				<div class="top_infos">
					<div class="mptop_left">
						<a href="'.((!$url_rewriting) ? 'index.php?page=mpseek' : 'mpseek.html' ).'">
							<img src="'.$design.'actions/'.$langue.'/nouveau.gif" alt"'.$lg_mpr['mp5'].'" />
						</a>
						'.(($data['del1'] == 0 && $data['del2'] == 0) ? '
								<a href="'.((!$url_rewriting)
										? 'index.php?page=mpsend&amp;id='.(($idmembre == $receveur) ? $envoyeur : $receveur ).'&amp;rep='.$_GET['idm']
										: 'mprep-'.(($idmembre == $receveur) ? $envoyeur : $receveur ).'-'.$_GET['idm'].'-'.casse($data['titre']).'.html' ).'">
									<img src="'.$design.'actions/'.$langue.'/repondre.gif" alt="'.$lg_mpr['mp6'].'" />
								</a>'
							: 
								'<img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$lg_mpr['mp7'].'" title="'.$lg_mpr['mp8'].'" />'
						  ).'
					</div>
					<div class="mptop_arbo">
						<a href="index.php">'.$lg_mpr['mp9'].''.htmlentities($nomduforum).'</a>-&gt; 
						<a href="index.php?page=mp">'.$lg_mpr['mp10'].'</a> - 
						<a href="index.php?page=mp&amp;send">'.$lg_mpr['mp11'].'</a>
						<span class="mptop_titre"> - '.stripslashes(htmlentities($data['titre'])).'</span>
					</div>
					<div style="clear:both;"></div>
				</div>';
				echo $topinfos;
				$premierPost = false;
				
				
				// Non lu -> lu
				if(($data['lu'] == 0 && $envoyeur == $idmembre) || ($data['lu2'] == 0 && $receveur == $idmembre)) 
				{
					$sql = 'UPDATE '.$prefixtable.'mp SET '.(($envoyeur == $idmembre) ? 'lu' : 'lu2' ).' = 1 WHERE id = '.intval($data['id']);
					$req2 = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
					$requse++;
					
					// dernier message non lu ?
					$sql = 'SELECT id FROM '.$prefixtable.'mp 
						WHERE rep = 0 AND (
							('.$envoyeur.' = '.$idmembre.' AND lu = 0) 
							OR ('.$receveur.' = '.$idmembre.' AND lu2 = 0)) ';
					$req2 = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
					$requse++;
					if($req->rowCount() == 0){
						$sql = 'UPDATE '.$prefixtable.'membres SET mp = 0 WHERE id = "'.intval($_SESSION['idlog']).'"';
						$req2 = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));	$requse++;
					}
				}
			}
			else 
				echo '<div class="mpr_entremsg"></div>';
			
			echo'
			<div id="mpr_mainbloc">
				<a href="'.((!$url_rewriting) ? 'index.php?page=affprofil&id='.$data['idde'] : 'affprofil-'.$data['idde'].'-'.casse($data['sender']).'.html' ).'">
					<div class="mpr_infomb '.(($data['idde'] == $envoyeur) ? 'mpr_infomb_e' : 'mpr_infomb_r' ).'">
						'.$data['sender'].'<br />
						'.((!empty($data['avatar'])) ? '<img src="'.$data['avatar'].'" alt="'.$lg_mpr['mp15'].$data['sender'].'" class="mpr_avatarimg" />' : '').'
					</div>
				</a>
				<div class="mpr_infos '.(($data['idde'] == $envoyeur) ? 'mpr_infos_e' : 'mpr_infos_r' ).'">
					'.$lg_mpr['mp16'].''.datefct($data['temps'],$gmt).', sujet : '.stripslashes(htmlentities($data['titre'])).'
				</div>
				<div class="mpr_txt '.(($data['idde'] == $envoyeur) ? 'mpr_txt_e' : 'mpr_txt_r' ).'">
					'.bbcode(nl2br(htmlentities($data['texte']))).'
				</div>
				<div class="mpr_endbloc"></div>
			</div>
			';
		}
		
		echo $reponserapide;
		echo $topinfos;
	}
	else
		include('./includes/notifs.php');
}
?>

