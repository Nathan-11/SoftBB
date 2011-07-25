<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de listing des forums
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

if(!is_numeric($_GET['idf']))
{
	include('./includes/notifs.php'); 
	exit();
}

// si le forum existe
$numreq = $req->rowCount();
$nbentree = $data3['nbsujet'];
$tempslastv = $data3['temps'];


/* Fonction affichant la pagination */
function page_par_page(){
	global $nbentree, $p2, $langue_forum, $postparpage, $url_rewriting, $data3;
	
	if($nbentree != 0) 		echo $langue_forum['forum6'].' '; 
	if($p2 > 1)				echo'... ';
	
	$nbpage = ceil($nbentree / $postparpage); 
	if($p2 > 0){ 			$p = $p2 - 1; $pc = 0; }
	elseif($nbpage == 2) 	$p = $pc=0;
	else{					$p = 0;  $pc = 1; }
	
	if($p2 < $nbpage-1)		$pmax = $p2 + 1 + $pc;
	else 					$pmax = $nbpage-1;
		
	for($p ; $p<=$pmax ; $p++)
	{
		echo '<a href="'.((!$url_rewriting)
			?'index.php?page=forum&amp;idf='.$_GET['idf'].'&amp;pg='.$p 
			: 'forum-'.$_GET['idf'].'-p'.$p.'-'.casse($data3['nom']).'.html').'">';
		if($p2 == $p)
			echo'<span class="admin">';
		echo $p+1;
		if($p2 == $p)
			echo'</span>';
			
		echo '</a>';
		if($p != $nbpage-1)
			echo',';
	}
	if($p2 < $nbpage -2 -$pc)
		echo'... ';
}

////////////////////////////////////////////////////////
// VÉRIFICATION DES DROITS DE VISIONNAGE DU FORUM
$autorisation = 1;		// on procède par élimination
// Cas 1 : Forum pour membre et user pas membre
if($data3['groupe'] == -2 && $rang == -1)
{
	display_error(bbcode(htmlentities($data3['nom'])), $langue_forum['forum1']);	// pas autorisé à chaque display_error()
	$autorisation = -1;
}
elseif($data3['groupe'] == -4)
{
	if($rang != 1  && $rang != 2)
	{
		if(!isset($_SESSION['pseudo']))
		{
			$autorisation = (($data3['v'] == 1) ? 1 : -1);
			$vofpost = $data3['v'];
		}
		else
		{
			$vofpost = $data3['m'];
			$autorisation = (($data3['m'] > 0) ? 1 : -1);
		}
		if($autorisation == -1)
			display_error(bbcode(htmlentities($data3['nom'])), $langue_forum['forum2']);
	}
	else
	{
		$vofpost = 4; 
		$autorisation = 1;
	}
}
elseif($data3['groupe'] == -3 && $rang != 1 && $rang != 2)
{
	display_error(bbcode(htmlentities($data3['nom'])), $langue_forum['forum2']);
	$autorisation = -1;
}

// Cas 2 : Forum pas locké et pas normal
elseif($data3['groupe'] != -1 && $data3['groupe'] != 0 && $data3['groupe'] != -2  && $data3['groupe'] != -4)
{
	// Cas 2.1 : Groupe particulier et pas membre
	if($rang == -1 && $data3['v'] == 0)
	{
		$autorisation = 0; 
		$vofpost = $data3['v'];
	}
	elseif($rang == -1 && $data3['v'] == 1)
	{
		$autorisation = 1; 
		$vofpost = $data3['v'];
	}
	
	// Cas 2.3 : Groupe particulier et membre
	elseif($rang != 2 && $rang != 1)
	{
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($data3['groupe']).' AND idm = '.intval($idmembre);
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
		$requse++;
		$goupas = $req->rowCount();
		$data2 = $req->fetch();
		if($goupas == 1 && $data2['stat'] == 0)
		{
			$vofpost = $data3['mg'];
			$autorisation = ($data3['mg'] > 0) ? 1 : 0;
		}
		elseif($goupas == 1 && $data2['stat'] == 1)
		{
			$vofpost = 4;
			$autorisation = 1;
			$rang = 1;
		} 
		else
		{
			$vofpost = $data3['m'];
			$autorisation = ($data3['m'] > 0) ? 1 : 0;
		}
	}
	// Rien de spécial (pas forcément logique)
	else{ 
			$autorisation = 1; 
			$vofpost = 4;
	}
}
// sinon $autorisation = 1 (prévu en haut)


if(!isset($vofpost)) 
	$vofpost = 4; 
if($autorisation == 0)	// pas le droit
	display_error(bbcode(htmlentities($data3['nom'])), $langue_forum['forum2']);
else
{
	// Tentative 1
	if(isset($_SESSION['pseudo']))
	{	
		if(isset($_SESSION['forumtime'.$_GET['idf']]) && $tempslastv  < $_SESSION['forumtime'.$_GET['idf']]) 
			$revoir = false;
		elseif($tempslastv < $_SESSION['lastvisit']) 
			$revoir = false;
		else 
			$revoir = true;
			
		if($revoir)
		{
			if(isset($_SESSION['forumtime'.$_GET['idf']])) 
				$sql = 'SELECT id2,tmppost,`lock` FROM '.$prefixtable.'post WHERE idsfa = '.intval($_GET['idf']).' AND idsa = 0 AND tmppost > '.intval($_SESSION['forumtime'.intval($_GET['idf'])]);
			else 
				$sql = 'SELECT id2,tmppost,`lock` FROM '.$prefixtable.'post WHERE idsfa = '.intval($_GET['idf']).' AND idsa = 0 AND tmppost > '.intval($_SESSION['lastvisit']);
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
			$requse++;
			$nbneue = 0;
			while ($data = $req->fetch())
			{
				if($data['tmppost'] > $_SESSION['lastvisit'] && $data['lock'] < 1)
				{
					$nbneue++;
					if(isset($_SESSION['post'.'-'.$data['id2'].'-'.$_GET['idf']]) 
						&& ($_SESSION['post'.'-'.$data['id2'].'-'.$_GET['idf']] >= $data['tmppost']))
							$nbneue += -1; 
				}
			}
			$_SESSION['kk'.$_GET['idf']] = $nbneue;
			$_SESSION['forumtime'.$_GET['idf']] = time();
			$req->closeCursor();
		}
	}
	// Fin de tentative
	
	
	if(isset($_GET['pg']))
	{
		$de = $_GET['pg'] * $postparpage; 
		$p2 = $_GET['pg'];
	}
	else
		$de = $p2 = 0;
	
	////////////////////////////////
	// Affichage partie supérieure
	echo '
	<div class="top_infos texte_base_normal">
	';
	
	$affichage_boutons = '';		// variable contenant le html à poster en haut
	if($rang != -1 && $vofpost > 1 && $vofpost != 3)
	{
		$affichage_boutons .= '
			<div class="f_top_g">';
			if($data3['groupe'] == -1 || $data3['groupe'] == -3)
			{
				if($rang == 2 || $rang == 1)
					$affichage_boutons .= '
					<a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;idf='.$_GET['idf']
						: 'addtopic-'.$_GET['idf'].'-'.casse($data3['nom']).'.html').'">
						<img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$langue_forum['forum3'].'" />
					</a>';
				else
					$affichage_boutons .= '<img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$langue_forum['forum3'].'" />'; 
			}
			else
				$affichage_boutons .= '
				<a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;idf='.$_GET['idf']
						: 'addtopic-'.$_GET['idf'].'-'.casse($data3['nom']).'.html').'">
					<img src="'.$design.'actions/'.$langue.'/nouveau.gif" alt"'.$langue_forum['forum4'].'" />
				</a>';
			$affichage_boutons .= '
			</div>
		';
	}

	echo $affichage_boutons;
	echo '
		<div class="f_top_m">
			<a href="'.((!$url_rewriting) ? 'index.php' : 'index.html').'">
				'.$langue_forum['forum5'].' '.htmlentities($nomduforum).'
			</a> -> '.bbcode(htmlentities($data3['nom'])).'
		</div>
		<div class="f_top_d">
	';
	page_par_page();
	
	echo'
		</div>
		<div style="clear:both;"></div>
	</div>';
	// fin affichage partie supérrieure

	$sql = 'SELECT p.id2,p.titre,p.sondage,p.`lock`,p.tmppost,p.nbr,p.idde,p.pseudode,p.pseudodernier,p.tmpdernierpost,m.pseudo AS pseudoposter,r.pseudo AS pseudoreponder FROM '.$prefixtable.'post AS p LEFT JOIN '.$prefixtable.'membres AS m ON p.idde=m.id LEFT JOIN '.$prefixtable.'membres AS r ON p.pseudodernier=r.id  WHERE idsfa = '.intval($_GET['idf']).' AND idsa = 0 ORDER BY sondage DESC,tmppost DESC LIMIT '.intval($de).','.intval($postparpage);
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$bdd = null;
	$requse++;
	$nbentree = $req->rowCount();

	if($nbentree != 0)
	{
		echo'<table class="texte_base_normal"  width="100%"  border="0" cellspacing="0" cellpadding="0">';
		echo'<tr class="titreforum">
				<td colspan="2" class="titreforumstart texte_base_titrespec" style="text-align:left;">'.$langue_forum['forum7'].'</td>
				<td width="80" class="titreforum texte_base_titre">'.$langue_forum['forum8'].'</td>
				<td width="130" class="titreforum texte_base_titre">'.$langue_forum['forum9'].'</td>
				<td width="150" class="titreforumend texte_base_titre">'.$langue_forum['forum10'].'</td>
			</tr>
		';
		while ($data = $req->fetch()) 
		{
			if(isset($savetype) && $savetype != $data['sondage'])
				echo'<tr>
						<td colspan="5" class="espace"></td>
					</tr>
				';
				
			$savetype = $data['sondage'];
			echo'
			<tr>
				<td width="25" height="25" class="cadre_fonce">';
				// Affichage des Lus / Non Lus
				if(($data['lock'] == -1 || $data['lock'] == -2) && $data['sondage'] != 2 && $data['sondage'] !=1 )
					echo '<img src="'.$design.'statut/sujet_clos.gif" alt="Sujet Clot" />';
				elseif($data['lock'] > 0) 
					echo '<img src="'.$design.'statut/deplace.gif" alt="'.$langue_forum['forum11'].'" />';
				elseif(isset($_SESSION['post'.'-'.$data['id2'].'-'.$_GET['idf']]) && !empty($pseudo))
				{
					if($_SESSION['post'.'-'.$data['id2'].'-'.$_GET['idf']] <  $data['tmppost'])
					{
						if($data['nbr']/$postparpageaff >= 3)
						{ 
							if($data['sondage'] == 2) echo '<img src="'.$design.'statut/n_annonce.png" alt="'.$langue_forum['forum12'].'" />';
							elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/n_post_it.gif" alt="'.$langue_forum['forum13'].'" />';
							else echo '<img src="'.$design.'statut/n_sujet_pop.gif" alt="'.$langue_forum['forum14'].'" />';
						}
						else
						{
							if($data['sondage'] == 2) echo '<img src="'.$design.'statut/n_annonce.png" alt="'.$langue_forum['forum12'].'" />';
							elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/n_post_it.gif" alt="'.$langue_forum['forum13'].'" />';
							else echo '<img src="'.$design.'statut/n_sujet.gif" alt="'.$langue_forum['forum15'].'" />';
						}
					}
					else 
					{
						if($data['nbr']/$postparpageaff >= 3)
						{
							if($data['sondage'] == 2) echo '<img src="'.$design.'statut/annonce.png" alt="'.$langue_forum['forum16'].'" />';
							elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/post_it.gif" alt="'.$langue_forum['forum17'].'" />';
							else echo '<img src="'.$design.'statut/sujet_pop.gif" alt="'.$langue_forum['forum18'].'" />';
						}
						else
						{
							if($data['sondage'] == 2) echo '<img src="'.$design.'statut/annonce.png" alt="'.$langue_forum['forum16'].'" />'; 
							elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/post_it.gif" alt="'.$langue_forum['forum17'].'" />'; 
							else echo '<img src="'.$design.'statut/sujet.gif" alt="'.$langue_forum['forum19'].'" />';
						}
					}
				}
				elseif(isset($_SESSION['lastvisit'])  && !empty($pseudo))
				{	
					if($_SESSION['lastvisit'] <  $data['tmppost'])
					{
						if($data['nbr']/$postparpageaff >= 3)
						{
							if($data['sondage'] == 2) echo '<img src="'.$design.'statut/n_annonce.png" alt="'.$langue_forum['forum12'].'" />';
							elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/n_post_it.gif" alt="'.$langue_forum['forum13'].'" />';
							else echo '<img src="'.$design.'statut/n_sujet_pop.gif" alt="'.$langue_forum['forum14'].'" />';
						}
						else
						{
							if($data['sondage'] == 2) echo '<img src="'.$design.'statut/n_annonce.png" alt="'.$langue_forum['forum12'].'" />';
							elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/n_post_it.gif" alt="'.$langue_forum['forum13'].'" />';
							else echo '<img src="'.$design.'statut/n_sujet.gif" alt="'.$langue_forum['forum15'].'" />';
						} 
					}
					else 
					{
						if($data['nbr']/$postparpageaff >= 3)
						{
							if($data['sondage'] == 2) echo '<img src="'.$design.'statut/annonce.png" alt="'.$langue_forum['forum16'].'" />';
							elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/post_it.gif" alt="'.$langue_forum['forum17'].'" />';
							else echo '<img src="'.$design.'statut/sujet_pop.gif" alt="'.$langue_forum['forum18'].'" />';
						}
						else
						{
							if($data['sondage'] == 2) echo '<img src="'.$design.'statut/annonce.png" alt="'.$langue_forum['forum16'].'" />';
							elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/post_it.gif" alt="'.$langue_forum['forum17'].'" />';
							else echo '<img src="'.$design.'statut/sujet.gif" alt="'.$langue_forum['forum19'].'" />';
						}
					}
				}
				else 
				{
					if($data['sondage'] == 2) echo '<img src="'.$design.'statut/annonce.png" alt="'.$langue_forum['forum16'].'" />';
					elseif($data['sondage'] == 1) echo '<img src="'.$design.'statut/post_it.gif" alt="'.$langue_forum['forum17'].'" />';
					else echo '<img src="'.$design.'statut/sujet.gif" alt="'.$langue_forum['forum19'].'" />';
				}
				// FIN Affichage des Lus / Non Lus
				/// FIN SESSION LU/PAS LU
					echo'
					</td>
					<td class="cadre_clair" style="padding:5px">';
					if($data['lock'] > 0)
						echo'<a href="'.((!$url_rewriting)
							? 'index.php?page=post&amp;ids='.$data['lock']
							: 'post-'.$data['lock'].'-'.casse($data['titre']).'.html' );
					else 
						echo'<div class="messagevisite"><a href="'.((!$url_rewriting)
							? 'index.php?page=post&amp;ids='.$data['id2']
							: 'post-'.$data['id2'].'-'.casse($data['titre']).'.html');
					echo '">';
					if($data['lock'] > 0) 
						echo '['.$langue_forum['forum20'].']';
					if($data['tmpdernierpost'] == 1) 
						echo '['.$langue_forum['forum21'].'] ';
					echo sit(htmlentities($data['titre']));
					if($rang == 1 || $rang == 2)
					{
						if($data['lock'] == -1) 
							echo' - <span class="admin">['.$langue_forum['forum22'].']</span> ';
						if($data['lock'] == -2) 
							echo' - <span class="admin">['.$langue_forum['forum23'].']</span> ';
					}
					echo'</a></div>';

					$nbdepage = ceil(($data['nbr']+1)/$postparpageaff);
					if($nbdepage > 1)
					{
						echo '<span class="allera">[ '.$langue_forum['forum24'].'';
						if($nbdepage <= 4)
						{
							for($p=0; $p<$nbdepage; $p++)
							{
								echo '
									<a href="'.((!$url_rewriting)
										? 'index.php?page=post&amp;ids='.$data['id2'].'&amp;pg='.$p
										: 'post-'.$data['id2'].'-p'.$p.'-'.casse($data['titre']).'.html').'">
										'.($p+1).'
									</a>';
								if($p != ($nbdepage-1)) 
									echo ',';
							}
						}
						else
						{
							echo '<a href="'.((!$url_rewriting)
								? 'index.php?page=post&amp;ids='.$data['id2'].'&amp;pg=0'
								: 'post-'.$data['id2'].'-p0-'.casse($data['titre']).'.html').'">1</a>,...,';
							for($p=($nbdepage-3);$p<$nbdepage;$p++)
							{
								echo '<a href="'.((!$url_rewriting)
									? 'index.php?page=post&amp;ids='.$data['id2'].'&amp;pg='.$p
									: 'post-'.$data['id2'].'-p'.$p.'-'.casse($data['titre']).'.html').'">'.($p+1).'</a>';
								if($p != ($nbdepage-1)) 
									echo ',';
							}
						}
						echo ' ]</span>';
					}	
					echo'
					</td>
			    	<td width="80" class="cadre_fonce" align="center">';
					if($data['lock'] > 0) echo'-';
					else echo $data['nbr'];
					echo'
					</td>
			    	<td width="130" class="cadre_clair" align="center">
						<a href="'.((!$url_rewriting)
							? 'index.php?page=affprofil&amp;id='.htmlentities($data['pseudode'])
							: 'affprofil-'.htmlentities($data['pseudode']).'-'.casse($data['pseudoposter']).'.html').'">
							'.htmlentities($data['pseudoposter']).'
						</a></td>
				    <td width="150" class="cadre_fonce_end" align="center" style="padding:3px">
						'.datefct($data['tmppost'],$gmt).'<br />
						par 
						<a href="'.((!$url_rewriting)
							? 'index.php?page=affprofil&amp;id='.htmlentities($data['pseudodernier'])
							: 'affprofil-'.htmlentities($data['pseudodernier']).'-'.casse($data['pseudoreponder']).'.html').'">
							'.htmlentities($data['pseudoreponder']).'
						</a>
						<a href="redir_last_post_list.php?post='.$data['id2'].'">
							<img src="'.$design.'statut/icon_latest_reply.gif" border="0" />
						</a>
					</td>
				</tr>
			';
			}	// Fin while
			echo'</table>';
			
			// Affichage partie inférieure
			echo '
			<div class="bottom_infos">
				'.$affichage_boutons.'
				<div style="clear:both;"></div>
			</div>
			
			<!-- légende icones -->
			<table class="table_legende_icones">
				<tr>
					<td><img src="'.$design.'statut/annonce.png" alt="'.$langue_forum['forum16'].'" /></td>
					<td class="texte_base_normal">'.$langue_forum['forum16'].'</td>
					<td><img src="'.$design.'statut/post_it.gif" alt="'.$langue_forum['forum17'].'" /></td>
					<td class="texte_base_normal">'.$langue_forum['forum17'].'</td>
				</tr>
				<tr>
					<td><img src="'.$design.'statut/sujet_pop.gif" alt="'.$langue_forum['forum18'].'" /></td>
					<td class="texte_base_normal">'.$langue_forum['forum18'].'</td>
					<td><img src="'.$design.'statut/n_sujet.gif" alt="'.$langue_forum['forum15'].'" /></td>
					<td class="texte_base_normal">'.$langue_forum['forum15'].'</td>
					<td><img src="'.$design.'statut/sujet.gif" alt="'.$langue_forum['forum19'].'" /></td>
					<td class="texte_base_normal">'.$langue_forum['forum19'].'</td>
				</tr>
			</table>
			';
			
		}
		else 	// pas de messages
			display_error(bbcode(htmlentities($data3['nom'])), $langue_forum['forum25']);
	}

	$req->closeCursor();
?> 
