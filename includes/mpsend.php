<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'envoie de mp
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
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) 
	exit();
if(!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) 
	exit();
if($rang == -1) 
	include('./includes/notifs.php');
else
{
	$sql = 'SELECT pseudo FROM '.$prefixtable.'membres WHERE id = "'.intval($_GET['id']).'" AND valid = 1';
	$req = $bdd->query($sql);
	$requse++;
	$numreq = $req->rowCount();
	if(empty($numreq)){
		include('includes/notifs.php');
		die();
	}

	$data = $req->fetch();
	$pseudosend = $data['pseudo'];
	$req->closeCursor();
	if(isset($_POST['texte'])){
		$titre = trim(strip_gpc($_POST['titre'])); 
		$texte = trim(strip_gpc($_POST['texte']));
	}
	else
		$titre = $texte = '';
	
	$timemin = time() - $tmpfreepost;
	if($idmembre == $_GET['id'])
		display_error($lg_mps['mp1'], $lg_mps['mp2']);
	elseif(!isset($_POST['previsu']) && $tempspostlast >= $timemin && $rang != 1 && $rang != 2)
		display_error($lg_mps['mp3'], $lg_mps['mp4'].$tmpfreepost.$lg_mps['mp5']);
	elseif(empty($titre) || empty($texte) || isset($_POST['previsu']))
	{
		echo '
		<div class="top_infos">
			<a href="'.((!$url_rewriting) ? 'index.php' : 'index.html').'">
				'.$lg_mps['mp6'].htmlentities($nomduforum).'
			</a> -&gt; 
			<a href="'.((!$url_rewriting) ? 'index.php?page=mp' :'mp.html') .'">
				'.$lg_mps['mp7'].'
			</a> - 
			<a href="'.((!$url_rewriting) ? 'index.php?page=mp&send' : 'mp-send.html').'">
				'.$lg_mps['mp8'].'
			</a>
		</div>
		';
		
		if(isset($_POST['previsu'])){
			echo '
		<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
			<tr class="titreforum">
				<td class="titreforumstart texte_base_titrespec">'.$lg_mps['mp9'].'</td>
			</tr>
			<tr>
				<td class="alternate1">
					<div class="text_prev">
						'.bbcode(nl2br(sit(($_POST['texte'])))).'
					</div>
				</td>
			</tr>
		</table>';
		}
		
		echo '
		<form action="'.((!$url_rewriting)
			? 'index.php?page=mpsend&amp;id='.$_GET['id']
			: 'mpsend-'.$_GET['id'].'-'.casse($pseudosend).'.html').'" method="post" enctype="multipart/form-data" name="post">
        <table class="texte_base_normal table_mpsend" width="100%" cellspacing="0" cellpadding="0">
			<tr class="titreforum">
				<td class="titreforumunique texte_base_titrespec table_mpsend_top" colspan="2">'.$lg_mps['mp10'].'</td>
			</tr>
			<tr>
				<td class="cadre_clair cell_mpsend_author">'.$lg_mps['mp11'].' </td>
				<td class="cadre1_bas cell_mpsend_authordisplay">'.htmlentities($pseudosend).'</td>
			</tr>
			';
		
			if(isset($_POST['titre']))
			{
				if(empty($titre) || empty($texte))
				{
					echo '
			<tr>
				<td class="cadre_clair cell_mpsend_error" style="padding:4px">
					<span class="red">'.$lg_mps['mp12'].'</span>
				</td>
				<td class="cadre1_bas cell_mpsend_errordisplay" style="padding:4px">'
					.((empty($titre)) ? $lg_mps['mp13'].'<br />' : '')
					.((empty($texte)) ? $lg_mps['mp14'] : '').
				'</td>
            </tr>
			';
				}
			}
		echo'
			<tr>
				<td class="cadre_clair cell_mpsend_subject">'.$lg_mps['mp15'].'</td>
				<td class="cadre1_bas cell_mpsend_subjectdisplay">
					<input  maxlength="64" type="text" value="';
				if(isset($_GET['rep']))
				{
					$sql = 'SELECT titre FROM '.$prefixtable.'mp WHERE id = '.intval($_GET['rep']);
					$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
					$bdd = null;
					$requse++;
					$dat1 = $req->fetch();
					if(substr($dat1['titre'],'0','3') != 'RE:')
						echo 'RE: '.htmlentities($dat1['titre']);
					else
						echo htmlentities($dat1['titre']);
				}
				else
					echo htmlentities($titre);
				echo'" class="post_input_title" name="titre" /></td>
			</tr>
			<tr>
				<td class="cadre_clair cell_mpsend_contain">'.$lg_mps['mp16'];
			
			if($emoticonnb != 0)	// affichage émoticones
				afficher_emoticones("texte");
			
				echo'
				</td>
				<td class="cadre1_bas cell_mpsend_containdisplay">
					'.afficher_panneau_bbcode('texte').'
					
	               <br />
				   
				<textarea name="texte" id="texte" class="post_textarea">'.htmlentities($texte).'</textarea>';
				echo'
				</td>
			</tr>
			<tr>
				<td colspan="2" class="mpsend_send_buttons">
					<input type="submit" name="previsu" value="'.$lg_mps['mp17'].'" />
					<input type="submit" name="Submit" value="'.$lg_mps['mp18'].'" />
					<input type="hidden" name="repto" value="'.((!isset($_GET['rep'])) ? -1 : $_GET['rep']).'" />
				</td>
			</tr>
		</table>
		</form>
		';
	}
	else
	{
		if(isset($_POST['repto']) && is_numeric($_POST['repto']))
		{
			// Post message
			if($_POST['repto'] >= 0)	// réponse
			{
				// on vérifie que l'envoyeur et le destinataire est le bon
				$sql = 'SELECT ida FROM '.$prefixtable.'mp WHERE 
					(
						   (ida="'.intval($_GET['id']).'" AND idde="'.intval($idmembre).'") 
						OR (idde="'.intval($_GET['id']).'" AND ida="'.intval($idmembre).'")
					) AND id="'.$_POST['repto'].'"';
				$req = $bdd->query($sql) or die('Erreur SQL !'.print_r($bdd->errorInfo()));	$requse++;
				if($req->rowCount() == 1){
					$data = $req->fetch();
					$ida = $data['ida'];	// id receveur
					
					// insert réponse
					$sql = 'INSERT INTO '.$prefixtable.'mp (`rep` , `ida` , `idde` , `titre` , `texte` , `temps` ) VALUES ("'.$_POST['repto'].'","'.intval($_GET['id']).'","'.intval($idmembre).'","'.add_gpc(trim($_POST['titre'])).'","'.add_gpc(trim($_POST['texte'])).'","'.time().'")';
					$req = $bdd->query($sql) or die('Erreur SQL !'.print_r($bdd->errorInfo()));	$requse++;
					
					// puis update non lu pour le "destinataire" sur le premier topic
					$sql = 'UPDATE '.$prefixtable.'mp SET '.(($idmembre == $ida) ? 'lu' : 'lu2' ).' = 0, nb=nb+1, dernier='.time().' WHERE id = '.intval($_POST['repto']);
					$req = $bdd->query($sql) or die('Erreur SQL !'.print_r($bdd->errorInfo()));	$requse++;
				}
				else{	// La discution n'existe pas pas entre les deux membres désignés !
					include('includes/notifs.php');
					$afterreg = true;		// après les autres requètes
				}
			}
			else{	// nouveau sujet
				$sql = 'INSERT INTO '.$prefixtable.'mp (`lu` , `ida` , `idde` , `titre` , `texte` , `temps` ) VALUES (0,"'.intval($_GET['id']).'","'.intval($idmembre).'","'.add_gpc(trim($_POST['titre'])).'","'.add_gpc(trim($_POST['texte'])).'","'.time().'")';
				$req = $bdd->query($sql) or die('Erreur SQL !'.print_r($bdd->errorInfo()));	$requse++;
			}
			
			if(!isset($afterreg))
			{
				// Prévenir destinataire
				$sql = 'UPDATE '.$prefixtable.'membres SET mp = mp+1  WHERE id = "'.intval($_GET['id']).'"';
				$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
				$requse++;
				
				// Temps dernier post (antiflood)
				$sql = 'UPDATE '.$prefixtable.'membres SET tempspost = '.time().' WHERE id = "'.intval($idmembre).'"';
				$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
				$requse++;
				
				if(isset($_POST['repto']))
				{
					var_dump($_POST['repto']);
					display_error($lg_mps['mp19'], $lg_mps['mp20'].'
					Message envoyé -> <a href="'.((!$url_rewriting)
						? 'index.php?page=mpread&idm='.$_POST['repto']
						: 'mpread-'.$_POST['repto'].'.html').'">'.$lg_mps['mp21'].'</a>');
				}
				else{
					display_error(''.$lg_mps['mp22'], '
					'.$lg_mps['mp22'].' -> <a href="'.((!$url_rewriting)
						? 'index.php?page=mp'
						: 'mp.html').'">'.$lg_mps['mp23'].'</a>');
				
				}
			}
		}
		else
			include('includes/notifs.php');
		
		$bdd = null;
	}
	
}


?>
