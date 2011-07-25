<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'ajout/ modification d'un message/ post
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


function ippp ()
{
	$ipe = explode('.',$_SERVER['REMOTE_ADDR']);
	$j = count($ipe); $ip = "";
	for($i=0;$i<$j;$i++){
		$ip .= dechex($ipe[$i]);
		if($i != $j-1) $ip .='$';
	}
	return $ip;
}

if(!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) 
	exit('1');

if(isset($_GET['idf']))
{ 
	if(!is_numeric($_GET['idf'])) 
		exit('-1');

	$act = 'post';
	$sql = 'SELECT groupe,nom,fatt,m,mg FROM '.$prefixtable.'forum WHERE fatt != 0 AND id = '.intval($_GET['idf']);
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$requse++;
	$numreq = $req->rowCount();
	if(empty($numreq))
	{
		include('./includes/notifs.php');
		exit();
	} 

	$data3 = $req->fetch();
	$nomsujetforum = $data3['nom'];
			
	if($data3['groupe'] == -1  && $rang != 1 && $rang != 2 || $data3['groupe'] == -3 && $rang != 1 && $rang != 2)
		exit('2'); 
			
	if($data3['groupe'] == -4   && $rang != 1 && $rang != 2 && $data3['m'] != 4 && $data3['m'] != 2) 
		exit('5');
			
	if($data3['groupe'] != 0 && $data3['groupe'] != -2)
	{
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.$data3['groupe'].' AND idm = '.$idmembre;
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
		$requse++;
		$autorisation = $req->rowCount();
		$d = $req->fetch(); 
		////////////////////////////////////////
		if($autorisation == 0 && $rang != 1 && $rang != 2)
			if($data3['m'] != 2 && $data3['m'] != 4) 
				exit('6');
		if($autorisation == 1 && $d['stat'] == 0 && $rang != 1 && $rang != 2)
			if($data3['mg'] != 2 && $data3['mg'] != 4) 
				exit('7');
	}

}
elseif(isset($_GET['edit']))
{
	if(!is_numeric($_GET['edit'])) exit();
		 
	$act = 'edit';
	$sql = 'SELECT * FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['edit']);
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$requse++;
	$numreq = $req->rowCount();
					
	if(empty($numreq)) exit();
	
	$dat = $req->fetch();
	$idsfa = $dat['idsfa'];
	$titresujet = $dat['titre'];
	$editlock = $dat['lock'];
	$nedittitre = $dat['idsa'];
					
					
	if($dat['idde'] == $_SESSION['idlog'] && $rang != 2 && $rang != 1)
	{
		$rang = 1;
		$editaffmod = 1;
		$reptimeplus = true;
	}
					
	$sql = 'SELECT groupe,nom,m,mg FROM '.$prefixtable.'forum WHERE id = '.intval($idsfa);
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$requse++;
	$data3 = $req->fetch();
	$nomsujetforum = $data3['nom'];
					
	if($data3['groupe'] == 0  && $rang != 1 && $rang != 2 || $data3['groupe'] == -1  && $rang != 1 && $rang != 2 || $data3['groupe'] == -3 && $rang != 1 && $rang != 2)
		exit('3');
					
	if($data3['groupe'] != 0   && $rang != 1 && $rang != 2)
	{
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($data3['groupe']).' AND idm = '.intval($idmembre).' AND stat = 1';
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
		$requse++;
		$autorisation = $req->rowCount();

		if(empty($autorisation)  && $rang != 1 && $rang != 2)
			exit('6');
		else
			$rang = 1;
	}
}
else
{
	if(!is_numeric($_GET['ids'])) 
		exit('a');
	if(isset($_GET['cit']))
		if(!is_numeric($_GET['cit'])) 
			exit('b');
			
	$act = 'rep';
	$sql = 'SELECT * FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']).' AND `lock` < 1 AND idsa = 0';
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$requse++;
	$numreq = $req->rowCount();
	if(empty($numreq)) 
		exit('Erreur');
	
	$dat = $req->fetch();
	$idsfa = $dat['idsfa'];
	$titresujet = $dat['titre'];
	$editlock = $dat['lock'];
		
	$sql = 'SELECT groupe,nom,m,mg FROM '.$prefixtable.'forum WHERE id = '.intval($idsfa);
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$requse++;
	$data3 = $req->fetch();
	$nomsujetforum = $data3['nom'];
	if($data3['groupe'] == -4   && $rang != 1 && $rang != 2 && $data3['m'] != 4 && $data3['m'] != 3) exit('1');

	if($data3['groupe'] == -1  && $rang != 1 && $rang != 2 || $data3['groupe'] == -3 && $rang != 1 && $rang != 2)
		exit('3');
	if($data3['groupe'] > 0)
	{
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($data3['groupe']).' AND idm = '.intval($idmembre);
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
		$requse++;
		$autorisation = $req->rowCount();
		$d = $req->fetch();
		
		////////////////////////////////////////
		if($autorisation == 0 && $rang != 1 && $rang != 2)
			if($data3['m'] != 3 && $data3['m'] != 4) 
				exit('2');

		if($autorisation == 1 && $d['stat'] == 0 && $rang != 1 && $rang != 2)
			if($data3['mg'] != 3 && $data3['mg'] != 4) 
				exit(3);
		else 
			$rang = 1;

		$data2 = $req->fetch();
		$statgroupe = $data2['stat'];
		if($statgroupe == 1) $rang = 1;
	}
	
	// si le topic est verrouillé ou surverrouillé et membre non modérateur ni admin
	if( ($editlock == -1 || $editlock == -2)  && $rang != 1 && $rang != 2) 
		exit('4');
}

// Vérification du délai minimum entre deux messages
$timemin = time()-$tmpfreepost;
if($tempspostlast >= $timemin && $rang != 1 && $rang != 2 || $tempspostlast >= $timemin && isset($reptimeplus) && $rang != 2)
	display_error($lg_posta['p0'], $lg_posta['p1a'].$tmpfreepost. $lg_posta['p1b']);
else // formulaire --> jusqu'à la fin du fichier
{
	if(empty($_POST['texte']) || empty($_POST['titre']) && isset($_GET['idf']) 
		|| (!empty($_POST['quest_sondage']) && empty($_POST['option_1'])) 
		|| !isset($_POST['sendage']))
	{
		echo'
<form action="';
		if(!$url_rewriting){
			echo 'index.php?page=postadd';
			if(isset($_GET['idf']))
				echo '&amp;idf='.$_GET['idf'];
			elseif(isset($_GET['edit'])) 
				echo '&amp;edit='.$_GET['edit'].'&amp;pg='.$_GET['pg'].'&amp;ids='.$_GET['ids'];
			else 
				echo '&amp;ids='.$_GET['ids'];
		}
		else{
			if(isset($_GET['idf']))
				echo 'addtopic-'.$_GET['idf'];
			elseif(isset($_GET['edit'])) 
				echo 'postedit-'.$_GET['edit'].'-'.$_GET['ids'].(($_GET['pg'] != 0) ? '-p'.$_GET['pg'] : '');
			else 
				echo 'postadd-'.$_GET['ids'];	
			echo ((!empty($titresujet)) ? '-'.casse($titresujet) : '').'.html';
		}
		echo'
		" method="post" enctype="multipart/form-data" name="post">
<div class="top_infos">
	<a href="'.((!$url_rewriting) ? 'index.php' : 'index.html').'">
		Index : '.htmlentities($nomduforum).'
	</a>
</div>
		';
		
		if( isset($_POST['previsu']) && !empty($_POST['texte']))
		{
			echo '
<div class="postadd_prev contour_cadre">
	<div class="titreforumunique texte_base_titrespec">'.$lg_posta['p2'].'</div>
	<div class="text_prev">
		'.bbcode(nl2br(sit(($_POST['texte'])))).'
	</div>
</div>
			';
		}
		
		echo'
<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumstart texte_base_titrespec" colspan="2">
			';(($act == "post") ? $lg_posta['p3'] : $lg_posta['p4']) .
		'</td>
	</tr>';
		if(( (!empty($_POST['quest_sondage']) && empty($_POST['option_1'])) || empty($_POST['texte']) || (empty($_POST['titre']) && isset($_GET['idf']))) && isset($_POST['sendage'])  )
		{
			echo '
	<tr>
		<td class="cadre_clair">
			<span class="red">'.$lg_posta['p5'].'</span>
		</td>
		<td class="cadre1_bas">';
			if(!empty($_POST['quest_sondage']) && empty($_POST['option_1'])) echo $lg_posta['p6'].'<br />';
			if(empty($_POST['titre']) && isset($_GET['idf']) ) echo $lg_posta['p7'].'<br />';
			if(empty($_POST['texte'])) echo $lg_posta['p8'];
				echo '
		</td>
	</tr>
				';
		}
		echo '
	<tr>
		<td class="cadre_clair">'.(($act == "post") ? $lg_posta['p9'] : $lg_posta['p10']).'</td>
		<td class="cadre1_bas">
			';
			if($act == "post") { echo bbcode(htmlentities(($nomsujetforum))); } 
			else { echo '<span class="admin">'.bbcode(htmlentities(($nomsujetforum))).'</span> '.sit((htmlentities('   ||    '.$titresujet))); }
			echo'
		</td>
	</tr>
	<tr>
		<td class="cadre_clair">
			<label for="titre">'.$lg_posta['p11'].'</label>
		</td>
		<td class="cadre1_bas">
			<input id="titre" maxlength="64" type="text"
			'; 
			if(isset($_POST['titre'])) 
				echo ' value="'.sit(htmlentities(strip_gpc($_POST['titre']))).'" ';
			elseif(isset($_GET['edit']))
			{
				$sql = 'SELECT titre,pseudo,texte,idsa FROM '.$prefixtable.'post LEFT JOIN '.$prefixtable.'membres AS m ON pseudode=m.id WHERE id2 = '.intval($_GET['edit']);
				$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
				$requse++;
				$dated = $req->fetch();
				$nedittitre = $dated['idsa'];
				echo 'value="'.sit(htmlentities(($dated['titre']))).'"';
			}
			else { echo''; }
			echo' class="post_input_title" name="titre" tabindex="80" />
		</td>
	</tr>
			';
			if(isset($_GET['edit'])  && !isset($_POST['texte']))
			{
				echo'
	<tr>
		<td class="cadre_clair">'.$lg_posta['p12'].'</td>
		<td class="cadre1_bas">'.htmlentities(($dated['pseudo'])).'</td>
	</tr>
	<tr>
				';
			}
			echo'
		<td width="160" valign="top" class="cadre_clair">
			<label for="texte">'.$lg_posta['p13'].'</label>
			';
			
			if($emoticonnb != 0)	// affichage émoticones
				afficher_emoticones("texte");
				
			echo'
		</td>
		<td class="cadre1_bas">
			<div class="cadre_bbcode">
				'.afficher_panneau_bbcode('texte').'
			</div>
			<textarea name="texte" id="texte" tabindex="90" class="post_textarea">';
				if(isset($_GET['cit']))
				{
					$sql = 'SELECT m.pseudo,texte FROM '.$prefixtable.'post  LEFT JOIN '.$prefixtable.'membres AS m ON pseudode=m.id WHERE id2 = '.intval($_GET['cit']).' AND idsa = '.intval($_GET['ids']);
					if($_GET['cit'] == $_GET['ids'])
					$sql = 'SELECT m.pseudo,texte FROM '.$prefixtable.'post  LEFT JOIN '.$prefixtable.'membres AS m ON pseudode=m.id WHERE id2 = '.intval($_GET['cit']);
					$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
					$requse++;
					if($req->rowCount() == 0 && $_GET['cit'] != $_GET['ids'] ) echo $lg_posta['p14'];
					else
					{
						$dat1 = $req->fetch();
						echo '[quote="'.htmlentities(($dat1['pseudo'])).'"]'.(sit(htmlentities($dat1['texte']))).'[/quote]';
					}
				}
				elseif(isset($_GET['edit']) && !isset($_POST['texte'])) { echo sit(htmlentities(($dated['texte']))); }
				else { if(isset($_POST['texte'])) echo sit(htmlentities(strip_gpc($_POST['texte']))); }
				echo'</textarea>';
			echo'
		</td>
	</tr>
</table>
			';
			
	// Modification d'un sondage
	if(isset($_GET['idf']) && $nbsondage > 1)
	{
		echo'
<table class="texte_base_normal" width="100%" class="postadd_table" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumstart texte_base_titrespec" colspan="2">'.$lg_posta['p15'].'</td>
	</tr>
	<tr>
		<td class="cadre_clair" height="30" width="160">
			<label for="q_sond">'.$lg_posta['p16'].'</label>
		</td>
		<td class="cadre1_bas">
			<input id="q_sond" type="text" maxlength="48" class="input_sondage_question" name="quest_sondage" '; 
				if(isset($_POST['quest_sondage'])) echo ' value="'.strip_gpc($_POST['quest_sondage']).'" '; 
			echo' tabindex="100" />
		</td>
	</tr>
		';
		for($sond=0 ; $sond<$nbsondage ; $sond++)
		{
			if(isset($_POST['option_'.$sond]) && !empty($_POST['option_'.$sond]))
			{
				if(!isset($_POST[$sond]) && !isset($moinsun))
				{
					echo '
	<tr>
		<td class="cadre_clair" height="30" width="160">
			<label for="o_sond'.$sond.'">'.$lg_posta['p17'].'</label>
		</td>
		<td class="cadre1_bas">
			<input id="o_sond'.$sond.'" maxlength="48" type="text" name="option_'.$sond.'" class="input_sondage_reponse" value="'.strip_gpc($_POST['option_'.$sond]).'" tabindex="110" />
			<input type="submit" name="'.$sond.'" class="sondage_bouton_supprimer" value="'.$lg_posta['p18'].'" tabindex="120" />
		</td>
	</tr>
					';
				}
				else
				{
					if(!isset($moinsun)) $sond++;
					$moinsun = true;
					if(isset($_POST['option_'.($sond)]) && !empty($_POST['option_'.($sond)]))
						echo '
	<tr>
		<td class="cadre_clair" height="30" width="160">
			<label for="o_sond">'.$lg_posta['p19'].'</label>
		</td>
		<td class="cadre1_bas">
			<input type="text" id="o_sond" maxlength="48" name="option_'.($sond-1).'" class="input_sondage_reponse" value="'.strip_gpc($_POST['option_'.($sond)]).'" tabindex="130" /> 
			<input type="submit" name="'.($sond-1).'" class="sondage_bouton_supprimer" value="'.$lg_posta['p18'].'" tabindex="140" />
		</td>
	</tr>
					';
					else 
						$sond += -1;
				}
			}
			else break;
		}
		$_POST = array();
		if(isset($moinsun)) $sond += -1;
		if($sond < $nbsondage)
			echo'
	<tr>
		<td class="cadre_clair" height="30" width="160">
			<label for="o_sond">'.$lg_posta['p21'].'</label>
		</td>
		<td class="cadre1_bas">
			<input maxlength="48" id="o_sond" type="text" name="option_'.$sond.'" class="input_sondage_reponse" tabindex="150" /> 
			<input type="submit" name="option" class="sondage_bouton_ajouter" value="'.$lg_posta['p22'].'" tabindex="160" />
		</td>
	</tr>
	<tr>
		';
		echo'
		<td class="cadre_clair" height="30" width="160">
			<label for="d_sondage">'.$lg_posta['p23'].'</label>
		</td>
		<td class="cadre1_bas">
			<input id="d_sondage" type="text" name="temps_sondage" class="input_sondage_jours" '.((isset($_POST['temps_sondage'])) ? ' value="'.strip_gpc($_POST['temps_sondage']).'" ' : '' ) . ' tabindex="170" /> 
			'.$lg_posta['p24'].' 
		</td>
	</tr>
</table>
		';
	}
	
	echo'        
<p align="center">
	<input type="submit" name="previsu" class="bouton_previsualiser" value="'.$lg_posta['p25'].'" tabindex="180" />
	<input type="submit" name="sendage" class="bouton_envoyer" value="'.$lg_posta['p26'].'" tabindex="190" />
</p>
</form>
	';
	
	
		///////////////////////////////////////////////
		// Récapitulatif des messages si option active et réponse à un sujet existant
		// pas de problème d'autorisation de lecture (déjà vu avant)
		if(isset($_GET['ids']))
		{
			echo'
		<div class="review_maindiv">
			<fieldset class="review_fieldset">
				<legend class="review_legend">'.$lg_posta['p27'].'</legend>
					<div id="preview" class="review_indiv">';
			$sql = '
				SELECT id2,rangspec,titre,sign,signaff,edit,ip,texte,pseudode,idsa,tmpsave,pseudo,nbpost,idde,rang,id,avatar,tmppost,www FROM '.$prefixtable.'post
				LEFT JOIN '.$prefixtable.'membres ON '.$prefixtable.'membres.id = '.$prefixtable.'post.idde
				WHERE '.$prefixtable.'post.id2 = '.intval($_GET['ids']).' OR '.$prefixtable.'post.idsa = '.intval($_GET['ids']).' ORDER BY '.$prefixtable.'post.id2 DESC LIMIT 0,20';
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
			$requse++;
			$nbentree = $req->rowCount();
			echo'
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="2" class="texte_base_normal">          
					<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
					<tr class="titreforum">
						<td width="150" class="titreforumstart texte_base_titrespec">'.$lg_posta['p28'].'</td>
						<td class="titreforumend texte_base_titre">'.$lg_posta['p29'].'</td>
					</tr>
					';
			$cmt = 0;
			$color = '';
			while ($data = $req->fetch()) 
			{
				++$cmt;
				$color = 'alternate'.($color == 'alternate1') ? '1' : '2';
				echo'
					<tr>
						<td width="150" height="50" align="center" valign="top" class="cadre_clair" style="padding:10px">
							<b>'.htmlentities($data['pseudo']).'</b>
							<br />
						';
				$idavatar = $data['id'];
				if(!empty($data['avatar']) && $data['avatar'] != "http://") 
					echo '<img src="'.$data['avatar'].'" alt="'.$data['pseudo'].'" />';
				echo'
						<br />
						Messages: '.$data['nbpost'].'<br />
					</td>
					<td valign="top" class="'.$color.'" style="padding:10px">
						<table width="100%" cellpadding="0" cellspacing="0" style="padding-bottom:5px">
						<tr>
							<td class="posthaut">'.$lg_posta['p30'].
							(($data['idsa'] == 0)
								? datefct($data['tmpsave'],$gmt)
								: datefct($data['tmppost'],$gmt)).
								' || '.$lg_posta['p31'].sit(htmlentities(($data['titre']))).'
							</td>
							<td width="200" align="right" class="posthaut"></td>
						</tr>
						</table>
						'.bbcode(nl2br(sit(($data['texte'])))).'</td>
					</tr>
					<tr>
						<td colspan="2" class="espace"><img src="'.$design.'space.gif" alt="" /></td>
					</tr>';
			}
			echo'
					</table>
				</td>
			</tr>
			</table>
			';
			echo '
		</div>
		</fieldset>
		<br />';
		}
	}
	
	else 	// ENVOI DU TEXTE
	{ 
		if(isset($_GET['idf']))
		{

			$sql = 'INSERT INTO '.$prefixtable.'post (`titre` , `texte` , `idfa` , `idsfa` , `idsa` , `pseudode` , `idde` , `sondage` , `nbr` , `tmppost` , `pseudodernier` , `ip` , `edit` , `tmpdernierpost` , `lock` , `tmpsave` ) VALUES ("'.add_gpc($_POST['titre']).'","'.add_gpc($_POST['texte']).'","'.intval($data3['fatt']).'","'.intval($_GET['idf']).'","0","'.intval($idmembre).'","'.intval($idmembre).'","0","0","'.time().'","'.intval($idmembre).'","'.ippp().'",0,0,0,"'.time().'")';
			$qs = trim($_POST['quest_sondage']);
			// DEBUT DU SONDAGE
			if(!empty($qs) && !empty($_POST['option_1']) && !empty($_POST['option_0']))
			{
				$option_1 = trim($_POST['option_1']);
				$option_0 = trim($_POST['option_0']);
				if(!empty($option_1) && !empty($option_0))	
					
				$sql = 'INSERT INTO '.$prefixtable.'post (`titre` , `texte` , `idfa` , `idsfa` , `idsa` , `pseudode` , `idde` , `sondage` , `nbr` , `tmppost` , `pseudodernier` , `ip` , `edit` , `tmpdernierpost` , `lock` , `tmpsave` ) VALUES ("'.add_gpc($_POST['titre']).'","'.add_gpc($_POST['texte']).'","'.intval($data3['fatt']).'","'.intval($_GET['idf']).'",0,"'.intval($idmembre).'","'.intval($idmembre).'",0,0,"'.time().'","'.intval($idmembre).'","'.ippp().'",0,"1",0,"'.time().'")';
			}
			else{
				$option_1 = '';
				$option_0 = '';
		}
		
		// petit out
		$req = $bdd->query($sql) or die('Erreur SQL !'.print_r($bdd->errorInfo())); 
		$requse++;
		$idretour = $bdd->lastInsertId();
		// petit out

		if(!empty($option_1) && !empty($option_0))
		{
			$sqlsond = 'INSERT INTO `'.$prefixtable.'sondage` (`idpost` , `forumatt` , `sforumatt` , `texte` , `nboption` , `nbvote` , `tmpvote` ) VALUES';
			for($sondinsert=0;$sondinsert<$nbsondage;$sondinsert++)
			{
				if(!empty($_POST['option_'.$sondinsert])) ${'option_'.$sondinsert} = trim($_POST['option_'.$sondinsert]); else ${'option_'.$sondinsert} = '';
				if(!empty(${'option_'.$sondinsert}))
					$sqlsond .= ' ("'.$idretour.'","'.intval($data3['fatt']).'","'.intval($_GET['idf']).'","'.add_gpc(${'option_'.$sondinsert}).'",0,0,0) , ';
				else
					break;
			}

			if(!empty($_POST['temps_sondage'])) $tempsondage = time()+(3600*24*abs(intval($_POST['temps_sondage'])));
			else $tempsondage = 0;
			$sqlsond .= ' ("'.$idretour.'","'.intval($data3['fatt']).'","'.intval($_GET['idf']).'","'.add_gpc($qs).'","'.$sondinsert.'",0,"'.$tempsondage.'");';
			if(empty($sondinsert)) 
				$sondinsert = 0;
			$reqsond = $bdd->query($sqlsond) or die('Erreur SQL !'.print_r($bdd->errorInfo())); $requse++;
		}
		// FIN DU SONDAGE
		
		$sql = 'UPDATE '.$prefixtable.'forum SET nbsujet = nbsujet+1 , adernier = "'.intval($idmembre).'" , dernier = "" , temps = '.time().' WHERE id = "'.intval($_GET['idf']).'"';
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
		$requse++;
		$sql = 'UPDATE '.$prefixtable.'membres SET nbpost  = nbpost +1 , tempspost = '.time().' WHERE id = "'.intval($idmembre).'"';
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
		$requse++; 
		$bdd = null;
			
			display_error(
				''.$lg_posta['p32'], 
				'<p>'.$lg_posta['p33'].'</p>
				<p>
					'.$lg_posta['p35'].'
					<a href="'.((!$url_rewriting) 
						? 'index.php?page=forum&amp;idf='.$_GET['idf'] 
						: 'forum-'.$_GET['idf'].'-'.casse($titresujet).'.html').'">
						'.$lg_posta['p36'].'
					</a>
					<script type="text/javascript">window.setTimeout("location=(\''.((!$url_rewriting) ? 'index.php?page=forum&amp;idf='.$_GET['idf'] : 'forum-'.$_GET['idf'].'.html') . '\');",5000)</script>
				</p>');
		}
		elseif(isset($_GET['edit']))
		{
			$tt = trim($_POST['titre']);
			if($nedittitre > 0  || !empty($tt))
			{
				$sql = 'UPDATE '.$prefixtable.'post SET titre = "'.add_gpc($_POST['titre']).'" , texte = "'.add_gpc($_POST['texte']).'"  WHERE id2 = "'.intval($_GET['edit']).'" OR `lock` = "'.intval($_GET['edit']).'"';
				if(isset($editaffmod)) $sql = 'UPDATE '.$prefixtable.'post SET titre = "'.add_gpc($_POST['titre']).'" , texte = "'.add_gpc($_POST['texte']).'" , edit = "'.time().'" WHERE id2 = "'.intval($_GET['edit']).'"';
			}
			else
			{
				$sql = 'UPDATE '.$prefixtable.'post SET  texte = "'.add_gpc($_POST['texte']).'"  WHERE id2 = "'.intval($_GET['edit']).'" OR `lock` = "'.intval($_GET['edit']).'"';
				if(isset($editaffmod)) $sql = 'UPDATE '.$prefixtable.'post SET  texte = "'.add_gpc($_POST['texte']).'" , edit = "'.time().'" WHERE id2 = "'.intval($_GET['edit']).'"';

			}
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); $requse++;
			
			$sql = 'UPDATE '.$prefixtable.'membres SET tempspost = '.time().' WHERE id = "'.intval($_SESSION['idlog']).'"';
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); $requse++; 
			$bdd = null;
			
			display_error(
				$lg_posta['p37'],
				'<p>'.$lg_posta['p38'].'</p>
				<p>'.$lg_posta['p35'].'
					<a href="'.((!$url_rewriting)
						? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$_GET['pg'].'#'.$_GET['edit']
						: 'post-'.$_GET['ids'].'-p'.$_GET['pg'].'-'.casse($titresujet).'.html#'.$_GET['edit']).'">
						'.$lg_posta['p36'].'
					</a>
					<script type="text/javascript">
						window.setTimeout("location=(\'index.php?page=post&ids='.$_GET['ids'].'&pg='.$_GET['pg'].'#'.$_GET['edit'].'\');",5000)
					</script>
				</p>');
		}
		elseif(isset($_GET['ids']))
		{
			$sql = 'INSERT INTO '.$prefixtable.'post (`titre` , `texte` , `idfa` , `idsfa` , `idsa` , `pseudode` , `idde` , `sondage` , `nbr` , `tmppost` , `pseudodernier` , `ip` , `edit` , `tmpdernierpost` , `lock` , `tmpsave` ) VALUES("'.add_gpc($_POST['titre']).'","'.add_gpc($_POST['texte']).'","'.intval($dat['idfa']).'","'.intval($dat['idsfa']).'","'.intval($_GET['ids']).'","'.intval($idmembre).'","'.intval($idmembre).'",0,0,"'.time().'","","'.ippp().'",0,0,0,"'.time().'");';
			$req = $bdd->query($sql) or die('Erreur SQL !'.print_r($bdd->errorInfo())); $requse++;
			$idretour = $bdd->lastInsertId();

			$sql = 'UPDATE '.$prefixtable.'forum SET nbmessage = nbmessage+1 , adernier = "'.intval($idmembre).'" , dernier = "" , temps = '.time().' WHERE id = "'.intval($dat['idsfa']).'"';
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); $requse++;

			$sql = 'UPDATE '.$prefixtable.'post SET nbr = nbr+1 , pseudodernier = "'.intval($idmembre).'" , tmppost = '.time().' WHERE id2 = "'.intval($_GET['ids']).'"';
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); $requse++;

			$sql = 'UPDATE '.$prefixtable.'membres SET nbpost  = nbpost +1 , tempspost = '.time().' WHERE id = "'.intval($idmembre).'"';
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); $requse++;
			
			////////////////////

			$sql = 'SELECT id2 FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']).' OR idsa = '.intval($_GET['ids']);
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); $requse++;
			$nbentree2 = $req->rowCount();
			$req->closeCursor();
			$nbpage = ceil($nbentree2/$postparpageaff)-1; 		
			$bdd = null;
			
			///////////////////// 
			
			display_error($lg_posta['p41'],'
				<p>'.$lg_posta['p42'].'</p>
				<p>
					'.$lg_posta['p39'].' 
					<a href="'.((!$url_rewriting)
						? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$nbpage.'#'.$idretour
						: 'post-'.$_GET['ids'].'-p'.$nbpage.'-'.casse($titresujet).'.html#'.$idretour).'">
						'.$lg_posta['p36'].'
					</a>				
					<script type="text/javascript">window.setTimeout("location=(\'index.php?page=post&ids='.$_GET['ids'].'&pg='.$nbpage.'#'.$idretour.'\');",5000)</script>
				</p>');
		}
	}
}
?>
