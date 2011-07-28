<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de lecture d'un sujet
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
 
// [1] Expulsé si pas connecté ou GET bidouillé
if(!isset($pseudo) || !is_numeric($_GET['ids'])){ 
	include('./includes/notifs.php');
	exit('2');
}


// [2] On sélectionne les donées relatives au post dont l'id est GET['ids'], en y joignant les données du groupe
$idsfa = $data3['idsfa'];
$sondage = $data3['sondage'];
$titresujet = $data3['titre'];
$editlock = $data3['lock'];
$nbentree2 = $data3['nbr']+1;

// /!\ Petit eexplication : le champ tmpdernierpost correspond à l'id du sondage (désolé pour la bidouille)
$sondageaff = $data3['tmpdernierpost'];
	
// [5] Si le membre est connecté, il a droit au système d'affichage post lu/non lu
if(isset($_SESSION['idlog']))
{
	// [5.1] Si le temps de dernière vue du post est déjà en cache
	if(isset($_SESSION['post-'.$_GET['ids'].'-'.$idsfa]))
	{ 
		// [5.1.1] Alors on vérifie si le temps du post est suppérieur
		if($_SESSION['post-'.$_GET['ids'].'-'.$idsfa] <= $data3['tmppost']) $revoir = true;
		// [5.1.2] Cas contraire
		else $revoir = false;
	}
	
	// [5.2] Autrement si le temps du post est suppérieur au temps de dernière visite
	elseif($_SESSION['lastvisit'] <= $data3['tmppost'])
	{
		$revoir = true;
	}
		
	// [5.3] Autrement, on ne fait rien
	else {
		$revoir = false;
	} 

	// [5.A] Si les conditions sont là pour vérifier
	if($revoir)
	{	
		// [5.A.1] on met à jour le temps du post actuel, puisque le membre y est
		$_SESSION['post-'.$_GET['ids'].'-'.$idsfa] = time();
	
		// [5.A.2] on sélectionne les post susceptibles d'etre non lu
		$sql = 'SELECT id2,tmppost,`lock` FROM '.$prefixtable.'post WHERE tmppost > '.intval($_SESSION['lastvisit']).' AND idsfa = '.intval($idsfa).' AND idsa = 0';
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
		$requse++;
		
		// [5.A.3]  On va compter le nombre de post réellement non-lu, donc pas ceux dont le temps est mit en cache
		$nbneue = 0;
		while ($data = $req->fetch())
		{
			// [5.A.3.1] On ajoute 1 au nombre de post non lu 
			$nbneue++;
			// [5.A.3.2] On vérifie si un temps en cache existe
			if(isset($_SESSION['post-'.$data['id2'].'-'.$idsfa]))
				// [5.A.3.2.1] Si, c'est le cas, on regarde si le temps en cache est supérieur ou pas au temps du post, si ce n'est pas le cas, on retire 1
				if($_SESSION['post-'.$data['id2'].'-'.$idsfa] >= $data['tmppost'])
					$nbneue--;
		}
		// [5.A.4]  On met à jour le nombre de post et le temps du cache du forum
		$_SESSION['kk'.$idsfa] = $nbneue;
		$_SESSION['forumtime'.$idsfa] = time();
	}  // Fin de [5.A]
} //Fin de [5]

// [6] on commence par vérifier les autorisations
// [6.1] Si le groupe vaut -2 (membre seul) et que c'est un membre qui veut visioner
if($data3['groupe'] == -2 && $rang == -1)
{
	display_error(bbcode(htmlentities(($data3['nom']))), $langue_post['post1']);
	
	// [6.1.1] On lui refuse l'accès pour plus tard
	$autorisation = -1;
}

elseif(!empty($data3['idsa']) || $editlock > 0)
{
	include('./includes/notifs.php');
	$autorisation = -1;
}

// [6.2] Si le groupe vaut -4 (personalisé)
elseif($data3['groupe'] == -4)
{
	// [6.2.1] Si ce n'est ni un admin ni modo qui veut visioner
	if($rang != 1  && $rang != 2)
	{
		// [6.2.1.1] Si c'est un non membre
		if(!isset($_SESSION['idlog']) || empty($_SESSION['idlog']))
		{
			// [6.2.1.1.A] On vérifie si il peut visioner
			$autorisation = ($data3['v'] == 1) ? 1 : -1;
						
			// [6.2.1.1.B] $vofpost, mise en cache de l'autorisation de visionage
			$vofpost = $data3['v'];
		}
		
		// [6.2.1.2] Si c'est un membre
		else
		{
			// [6.2.1.2.A] On vérifie si il peut visioner
			$vofpost = $data3['m'];
			if($data3['m'] > 0) $autorisation = 1; 
			// [6.2.1.2.B] Cas contraire
			else $autorisation = -1;
		}
		
		// [6.2.1.A] On affiche le message d'erreur si le membre ne peut pas aller plus loin
		if($autorisation == -1)
			display_error(bbcode(htmlentities($data3['nom'])), $langue_post['post2']);
	}
	
	// [6.2.2] On met les autorisations maximums pour un admin ou modo
	else
	{
		$vofpost = 4; 
		$autorisation = 1;
	}
}

// [6.3] Si le groupe vaut -3 (surverouillage), seul les admins ou modos (total, pas chef de groupe) peuvent visioner
elseif($data3['groupe'] == -3 && $rang != 1 && $rang != 2)
{
	display_error(bbcode(htmlentities($data3['nom'])), $langue_post['post3']);
	$autorisation = -1;
}

elseif($editlock == -2 && $rang != 1 && $rang != 2 && ($data3['groupe'] <= 0 || $rang == -1) )
{
	display_error(bbcode(htmlentities($data3['nom'])), $langue_post['post4']);
	$autorisation = -1;
}

// [6.4] Si le groupe est un groupe créé, donc, un groupe particulié
elseif($data3['groupe'] != -1 && $data3['groupe'] != 0 && $data3['groupe'] != -2 && $data3['groupe'] != -4)
{
	// [6.4.1] Si le groupe est un groupe particulié et que le membre est non connecté
	if($rang == -1 && $data3['v'] == 0)
	{
		$autorisation = 0; 
		$vofpost = $data3['v'];
	}
	
	// [6.4.1.false] Cas contraire
	else
	{
		$autorisation = 1; 
		$vofpost = $data3['v'];
	}
	
	// [6.4.1] Si le groupe est un groupe particulié et que le membre est connecté
	if($rang != 2 && $rang != 1 && $rang != -1)
	{
		// [6.4.1.1] On récupère les infos relatives au groupes : le membre en fait partie? grade spécial?
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.$data3['groupe'].' AND idm = '.$idmembre;
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
		$requse++;
		$goupas = $req->rowCount();
		$data2 = $req->fetch();
		
		// [6.4.1.2] Si c'est un membre du groupe, on reprend les infos relatives au groupe
		if($goupas == 1 && $data2['stat'] == 0 && $editlock != -2)
		{
			// [6.4.1.2.1] On récupère le information relative au visionage pour le membre du groupe
			$vofpost = $data3['mg'];
			$autorisation = ($data3['mg'] > 0) ? 1 : 0;
		}
		
		// [6.4.1.3] Si c'est un chef de ce groupe, on le considère comme un modérateur temporairement (juste sur cette page)
		elseif($goupas == 1 && $data2['stat'] == 1)
		{
			// [6.4.1.3.1] On met toutes les autorisations au max (sauf admin, mais, meme, ça changerai rien ici)
			$vofpost = 4;
			$autorisation = $rang = 1;
		} 
		
		// [6.4.1.4] Ici, il ne reste que les membres, non membres du groupe
		elseif($goupas == 0)
		{
			// [6.4.1.4.1] On récupère le information relative au visionage pour le simple membre
			$vofpost = $data3['m'];
			if($data3['m'] >0 && $editlock != -2) $autorisation = 1; else $autorisation = 0;
		}
		
		else
		{
	// [6.4.1.4] Ici, il ne reste que les membres, non membres du groupe		// [6.4.1.4.1] On récupère le information relative au visionage pour le simple membre
			$autorisation = 0;
		}
	}
	// [6.4.2] Cas où rien prévu, donc admin ou md
	if($rang == 1 || $rang == 2)
	{ 
		$autorisation = 1; 
		$vofpost =4;
	}
}

// [6.5] Cas où rien n'est prévu, c'est qu'on a donc, l'autorisation
else
	$autorisation = 1;


// [7] Si les autorisations, ne sont pas fixées, on les mets au max (logiquement, il ne reste que les dm ou admins)
if(!isset($vofpost)) $vofpost = 4; 
// [8] On commence l'affichage
	// [8.1] Cas où on refuse l'accès
	if($autorisation == 0 || $autorisation == -1)
	{
		// [8.1.1] Cas où on a pas afficher de message d'erreur
		if($autorisation == 0)
			display_error(bbcode(htmlentities($data3['nom'])), $langue_post['post5']);
		// [8.1.false] autrement, on a déjà affiché toutes les infos relatives à la restriction
	}
	
	// [8.2] Cas où on autorise l'accès
	else
	{
		// [8.2.1] Calcule du système d'affichage page par page
		
		//  /!\ Mini avertissement !!
		//  Les pages sont comme suit : la page un correspond à la variable GET 0 et ansi de suite, 
		//  on commce à numéroter depuis 0
		// [8.2.1.1] On a une page en GET
		if(isset($_GET['pg']))
		{
			$de = intval($_GET['pg']) * $postparpageaff; 
			$p2=$_GET['pg']; 
		}
		// [8.2.1.2] Autrement, on considère qu'on commence à zéro
		else
			$de = $p2 = 0;

		// [8.2.1.pause] On utilisera ça plus tard
		// [8.2.2] Affichage des boutons répondre/nouveau
		
		echo '
		<div class="texte_base_normal top_infos">
		';

		// [8.2.2.1] On vérifie si il y a lieu d'afficher, l'espace pour les boutons
		if($rang != -1 && ($vofpost == 3 || $vofpost == 4 || $vofpost == 2))
		{
			echo'
			<div class="top_boutons">';
	
			// [8.2.2.1.1] Le membre peut-il poster?
			if($vofpost == 2 || $vofpost == 4)
			{
				// [8.2.2.1.1.A] On vérifie si il n'y a pas de contre indication
				if($data3['groupe'] != -1 && $data3['groupe'] != -3)
					echo ' <a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;idf='.$idsfa
						: 'addtopic-'.$idsfa.'-'.casse($data3['nom']).'.html').'">
							<img src="'.$design.'actions/'.$langue.'/nouveau.gif" alt"'.$langue_post['post6'].'" />
						</a>';
				
				// [8.2.2.1.1.B] Cas des admin ou modo, ils sont autorisés à poster
				elseif($rang == 2 || $rang == 1)
					echo ' <a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;idf='.$idsfa
						: 'addtopic-'.$idsfa.'-'.casse($data3['nom']).'.html').'">
							<img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$langue_post['post7'].'" />
						</a> ';
				else
					echo ' <img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$langue_post['post7'].'" /> ';
			}
		
			// [8.2.2.1.false] Cas contraire, on affiche un bouton du type verouillé
			else 
				echo ' <img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$langue_post['post7'].'" />';
	
			// [8.2.2.1.2] Le membre peut-il répondre?
			if($vofpost == 3 || $vofpost == 4)
			{
				// [8.2.2.1.2.A] On vérifie si il n'y a pas de contre indication
				if($editlock != -1 && $editlock != -2 && $data3['groupe'] != -1 && $data3['groupe'] != -1 && $data3['groupe'] != -3)
					echo' <a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;ids='.$_GET['ids']
						: 'postadd-'.$_GET['ids'].'-'.casse($titresujet).'.html').'">
							<img src="'.$design.'actions/'.$langue.'/repondre.gif" alt="'.$langue_post['post8'].'" />
						</a> ';
				
				// [8.2.2.1.2.B] Cas des admin ou modo, ils sont autorisés à poster
				elseif($rang == 2 || $rang == 1)
					echo ' <a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;ids='.$_GET['ids'].'&amp;idfret='.$idsfa
						: 'postadd-'.$_GET['ids'].'-'.casse($titresujet).'.html').'">
							<img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$langue_post['post7'].'" />
						</a> ';
					
				// [8.2.2.1.1.C] Il y a contre indication
				else
					echo ' <img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$langue_post['post7'].'" /> '; 
			}
		
			// [8.2.2.1.false] Cas contraire, on affiche un bouton du type verouillé
			else echo ' <img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="'.$langue_post['post7'].'" /> ';
			echo'</div>
			';
		}
		
		// [8.2.3] Affichage du nom du forum
		echo'
		<div class="top_titre">
			<a href="'.((!$url_rewriting) ? 'index.html' : 'index.php').'">'.htmlentities($nomduforum).'</a>
			 > 
			<a href="'.((!$url_rewriting)
				? 'index.php?page=forum&amp;idf='.$idsfa
				: 'forum-'.$idsfa.'-'.casse($data3['nom']).'.html' ).'">
					'.(bbcode(htmlentities($data3['nom']))).'
			</a>
			 > 
			'.$titresujet.'
		</div>
		<div class="top_pagination">
		'; 
	
		// [8.2.4] Creation de la fonction d'affichage du page par page
		function page_par_page ()
		{
			// [8.2.4.0] importation de variable
			global $nbentree2, $postparpageaff, $de, $p2, $url_rewriting, $titresujet;
	
			// [8.2.4.1] Si il y a des entrées
			if($nbentree2 != 0) 
				echo'Page : ';
				 
			$p3 = $p2-1;

			// [8.2.4.2] Si on est pas sur la première page, on peut donc mettre le lien vers la page précedente
			if($p2 != 0)
				echo '<a href="'.((!$url_rewriting) 
					? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p3
					: 'post-'.$_GET['ids'].'-p'.$p3.'-'.casse($titresujet).'.html').'">'.$langue_post['post9'].'</a>,';
		
			// [8.2.4.3] Si on est pas sur la première page, on peut donc mettre le lien vers la page précedente
			if($p2 > 1)
				echo '<a href="'.((!$url_rewriting) 
					? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg=0'
					: 'post-'.$_GET['ids'].'-'.casse($titresujet).'.html').'">1,</a>';

			// [8.2.4.etc] Enfin, c'est de la logique, pas besoin d'y toucher, je passe ça
			if($p2 > 2)
				echo'...,';
				
			$nbpage = ceil($nbentree2/$postparpageaff); 
			if($p2 > 0)
			{
				$p = $p2 - 1; 
				$pc = 0;
			}
			elseif($nbpage == 2)
				$p = $pc = 0;
			else
			{
				$p = 0; 
				$pc = 1;
			}
			if($p2 < $nbpage-1)
				$pmax = $p2 + 1 + $pc;
			else
				$pmax = $nbpage-1;
				
			for($p;$p<=$pmax;$p++)
			{ 
				echo '<a href="'.((!$url_rewriting) 
					? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p
					: 'post-'.$_GET['ids'].(($p != 0) ? '-p'.$p : '').'-'.casse($titresujet).'.html').'">';
				if($p2 == $p)
					echo'<span class="admin">';
					
				echo $p+1; 
				if($p2 == $p)
					echo'</span>';
					
				echo '</a>';
				if($p != $nbpage-1)
					echo',';
			}
			if($p2 < $nbpage-3-$pc)
				echo'...,';
				
			$p5 = $nbpage-1;
			if($p2 < $nbpage-2 && $nbpage > 3)
				echo '<a href="'.((!$url_rewriting) 
					? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p5
					: 'post-'.$_GET['ids'].(($p5 != 0) ? '-p'.$p5 : '').'-'.casse($titresujet).'.html').'">'.$nbpage.'</a>';
					
			if($p2 < $nbpage-3 && $nbpage <= 3)
				echo '<a href="'.((!$url_rewriting) 
					? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p5
					: 'post-'.$_GET['ids'].(($p5 != 0) ? '-p'.$p5 : '').'-'.casse($titresujet).'.html').'">'.$nbpage.'</a>';
					
			$p4 = $p2+1;
			if($p2 != $nbpage-1)
				echo '<a href="'.((!$url_rewriting) 
					? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p4
					: 'post-'.$_GET['ids'].(($p4 != 0) ? '-p'.$p4 : '').'-'.casse($titresujet).'.html').'">'.$langue_post['post11'].'</a>';
		}
		// [8.2.4.fin] Fin de la fonction d'affichage du page par page

		echo page_par_page().'	
			</div>
			<div style="clear:both;"></div>
		</div>
		';

		// [8.2.5] Affichage du nom du post et de ses réponses
		// /!\ Petit explication le champ sondage correspond au type de post (annonce,post-it,...) (dsl pour la bidouille)
		// [8.2.5.1] On sélectionne ce qu'on affichera, en tenant compte des limites
		$sql = '
			SELECT id2,rangspec,titre,sign,signaff,edit,ip,texte,pseudode,idsa,tmpsave,pseudo,nbpost,idde,rang,id,avatar,tmppost,www FROM '.$prefixtable.'post
			LEFT JOIN '.$prefixtable.'membres ON '.$prefixtable.'membres.id = '.$prefixtable.'post.idde
			WHERE '.$prefixtable.'post.id2 = '.intval($_GET['ids']).' OR '.$prefixtable.'post.idsa = '.intval($_GET['ids']).' ORDER BY '.$prefixtable.'post.id2 LIMIT '.$de.','.$postparpageaff;

	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
	$requse++;
	$nbentree = $req->rowCount();
	
	
		// sondage dans la partie supérieure de la disscution
		if($sondageaff == 1)
		{
			$sqls = 'SELECT s.idsond,s.texte,s.nbvote,s.nboption,s.tmpvote,v.idvoteur  FROM '.$prefixtable.'sondage AS s LEFT JOIN '.$prefixtable.'voter AS v ON (s.idpost=v.idpost) AND (v.idvoteur=0) WHERE s.idpost = '.$_GET['ids'].' ORDER BY s.nboption DESC, s.idsond ASC';
			if(!empty($_SESSION['idlog'])) $sqls = 'SELECT s.idsond,s.texte,s.nbvote,s.nboption,s.tmpvote,v.idvoteur  FROM '.$prefixtable.'sondage AS s LEFT JOIN '.$prefixtable.'voter AS v ON (s.idpost=v.idpost) AND (v.idvoteur='.$_SESSION['idlog'].') WHERE s.idpost = '.$_GET['ids'].' ORDER BY s.nboption DESC,s.idsond ASC';
			$reqs = $bdd->query($sqls) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
			$requse++;
			echo '
		<div class="post_sondage contour_cadre">	
			<div class="titreforumunique texte_base_titrespec">'.$langue_post['post12'].'</div>
			<div class="post_sond_in texte_base_normal">'; 
			$datas = $reqs->fetch();
			$sur = $datas['nbvote'];
			$nbentreevote = $datas['nboption'];
			$timeend = $datas['tmpvote'];
			$iddejaopost= $datas['idvoteur'];
			if(($timeend > time() || $timeend == 0) 
				&& empty($iddejaopost) 
					&& ($vofpost == 3 || $vofpost == 4) 
						&& !empty($_SESSION['idlog']) 
							&& !isset($_GET['affsond']) 
								&& $editlock != -1) 
					$nbcols = 2; 
			else 
				$nbcols = 3;
			
			echo '
					<form action="index.php?page=voteadd&amp;ids='.$_GET['ids'].'" method="post">
					<table class="sond_table">
					<tr>
			';
	
			if($nbcols == 2)
				echo '
					';
				echo'
						<td colspan="'.$nbcols.'" align="center" style="padding-bottom:8px">
					';
				echo (htmlentities($datas['texte'])).'</td>
					</tr>
					';
				while($datas = $reqs->fetch())
				{
					if(($timeend > time() || $timeend == 0) && empty($iddejaopost) && ($vofpost == 3 || $vofpost == 4) && !empty($_SESSION['idlog']) && !isset($_GET['affsond'])  && $editlock != -1)
					{
					echo '
					<tr>
						<td width="5"><input type="radio" name="id_option" value="'.$datas['idsond'].'"></td>
						<td>'.(htmlentities($datas['texte'])).'</td>
					</tr>
						';
					}
					else
					{
						if($datas['nbvote'] > 0 && $sur > 0) 
							$size = round(($datas['nbvote']/$sur)*150); 
						else 
							$size = 1;
						if($datas['nbvote'] > 0 && $sur > 0) 
							$pourc = round(($datas['nbvote']/$sur)*100); 
						else 
							$pourc = 0;
			
						echo '
					<tr>
						<td align="right" style="padding-right:4px">'.(htmlentities($datas['texte'])).'</td>
						<td align="left"><img src="'.$design.'sondeleft.gif" alt="" /><img src="'.$design.'sondecentre.gif"  width="'.$size.'" height="13" alt="" /><img src="'.$design.'sonderight.gif" alt="" /></td>
						<td style="padding-right:6px" align="left">'.$pourc.'% ['.$datas['nbvote'].']</td>
					</tr>
						';
					}
				}
				echo '
					<tr>
						<td colspan="'.$nbcols.'" align="center" style="padding-top:8px">
				';
			if($nbcols == 2)
				echo '
							<input type="submit" name="Submit" value="'.$langue_post['post13'].'" />
							<br />
							<br />
							<a href="'.((!$url_rewriting)
								? 'index.php?page=post&amp;ids='.$_GET['ids'].'&amp;affsond='
								: 'postsond-'.$_GET['ids'].'-'.casse($titresujet).'.html').'">
									'.$langue_post['post14'].'
							</a><br />
						';
			else
				echo $langue_post['post15'].''.$nbentreevote.' ['.$sur.']
						</td>
					';
			echo'
					</tr>
					</table>
				</form>
			</div>
		</div>
		';
		}
		
		
		//////////////////////////////
		// Lecture du SUJET
		$bdd = null;
		echo' 
		<div class="contour_cadre post_global">	
			';
		if(isset($_GET['pg']))	$cmt = ($_GET['pg'] == 0) ? 0 : 1;
		else 					$cmt = 0;
		$color = 'alternate1';
		
		while ($data = $req->fetch()) 
		{
			$cmt++;
			$color = 'alternate'.($color == 'alternate1') ? '1' : '2';
			$rang = array('', ' class="modo"', ' class="admin"');
			echo'
			<div class="post_new">
				<!-- Début zone fiche du membre -->
				<a href="../">
				<div class="post_fiche">
					<a name="'.$data['id2'].'"></a>
					<div class="p_pseudo"><span'. $rang[$data['rang']] . '>' . htmlentities($data['pseudo']).'</span></div>
				';
			if($data['rangspec'] > 0)
			{
				$kk = $data['rangspec']-1;

				if(!empty($rangimage[$kk])) {
					echo '<img src="'.$design.'rang/'.$rangimage[$kk].'" class="p_imgrang" alt="image du rang" /><br />';
				}
				echo '<span style="color: '.$rangcouleur[$kk].'" class="p_nomrang">'.$rangnom[$kk].'</span>';
			}
			else
			{
				$cont = 0;
				for($kk=0 ; $cont<1 ; $kk++)
				{
					if($rangpostmin[$kk] <= $data['nbpost']) 
					{
						if(!empty($rangimagem[$kk])) 
							echo '<img src="'.$design.'rang/'.$rangimagem[$kk].'" class="p_imgrang" alt="'.$langue_post['post18'].'" />';
						$cont = 1;
						echo '<span class="p_nomrang">'.$rangmembre[$kk].'</span>';
					}
				}
			}
			echo '<br />';
			
			$idavatar = $data['id'];
			if(!empty($data['avatar']) && $data['avatar'] != "http://") 
				echo '<img src="'.$data['avatar'].'" alt="'.$data['pseudo'].'" class="p_avatar" />';
			
			echo'
				<div class="p_msg">'.$langue_post['post19'].$data['nbpost'].'</div>';
			if($rang == 2 && $ipaff) 	// affichage ip
				echo 'ip : '.professordekodor($data['ip']);
			
			// affichage infos membres + (site, mp...)
			echo '<a href="' . ((!$url_rewriting) ? '?page=affprofil&id=' . $data['id'] : 'affprofil-'.$data['id'].'-'.casse($data['pseudo']).'.html') . '">
						<img src="'.$design.'actions/'.$langue.'/profil.gif" alt="'.$langue_post['post20'].'" /></a>';
			if($rang != -1 && $data['id'] != $idmembre) 
				echo' 
					<a href="'.((!$url_rewriting)
						? 'index.php?page=mpsend&amp;id='.$data['id']
						: 'mpsend-'.$data['id'].'-'.casse($data['pseudo']).'.html').'">
						<img src="'.$design.'actions/'.$langue.'/mp.gif" alt="'.$langue_post['post21'].'" />
					</a>';
			if($data['www'] != "" && $data['www'] != "http://") 
				echo' 
					<a href="'.htmlentities($data['www']).'">
						<img src="'.$design.'actions/'.$langue.'/www.gif" alt="'.$langue_post['post22'].'" />
					</a>';

			echo'
				</div>
				<!-- fin fiche membre -->

				<div class="post_msg">
					<div class="posthead_buttons">';
						if($rang != -1 && $editlock != -1 || $rang == 1 || $rang == 2) 
							echo' 
						<a href="'.((!$url_rewriting)
							? 'index.php?page=postadd&amp;ids='.$_GET['ids'].'&amp;cit='.$data['id2']
							: 'postcit-'.$_GET['ids'].'-'.$data['id2'].'-'.casse($titresujet).'.html' ).'">
							<img src="'.$design.'actions/'.$langue.'/citer.gif" alt="'.$langue_post['post25'].'" title="'.$langue_post['post26'].'" />
						</a>';
						if($data['id'] == $idmembre || $rang == 2 || $rang == 1) 
							echo' 
						<a href="'.((!$url_rewriting)
							? 'index.php?page=postadd&amp;edit='.$data['id2'].'&amp;pg='.$p2.'&amp;ids='.$_GET['ids']
							: 'postedit-'.$data['id2'].'-'.$_GET['ids'].(($p2 != 0) ? '-p'.$p2 : '').'-'.casse($titresujet).'.html').'"> 
							<img src="'.$design.'actions/'.$langue.'/editer.gif" alt="'.$langue_post['post27'].'" title="'.$langue_post['post28'].'" />
						</a>';
						if($rang == 2 && $cmt > 1 || $rang == 1 && $cmt > 1) 
							echo' <input name="delpost" type="image" src="'.$design.'actions/'.$langue.'/supprimer.gif" onclick="decision(\''.addslashes($langue_post['post30']).'\',\'delpost.php?id2='.$data['id2'].'\')" />';
						echo'
					</div>
					<div class="posthead_infos">
						<a href="#'.$data['id2'].'"><img src="'.$design.'/actions/ancre.gif" /></a>
						'.$langue_post['post31'].
						(($data['idsa'] == 0)
							? datefct($data['tmpsave'],$gmt)
							: datefct($data['tmppost'],$gmt)).
						((!empty($data['titre']))
							? ' || '.$langue_post['post32'].sit(htmlentities(($data['titre'])))
							: '').'
					</div>
					
					<div class="post_msgtxt">
				'.bbcode(nl2br(sit(($data['texte']))));
				if(!empty($data['edit']))
					echo'<p class="edit">'.$langue_post['post35'].''.datefct($data['edit'],$gmt).']</p>';
				
				if($bbcodesign && !empty($data['sign']) && $data['signaff'] == 1 && $autorisationsign) 
					echo '<br />________________<br />'.bbcode(nl2br(sit(($data['sign']))));
				elseif(!empty($data['sign']) && $data['signaff'] == 1 && $autorisationsign) 
					echo '<br />________________<br />'.nl2br(htmlentities(($data['sign'])));
			echo'
					</div>
				</div>
				<!-- fin bloc message -->
				<div class="post_undermsg"></div>
			</div>	<!-- fin bloc fiche message -->
			';
		}	// fin de boucle msg
		
		echo'
		</div> <!-- fin lecture du sujet -->';
	
	
	
	echo'
		<div class="texte_base_normal bottom_infos">
	';
		// [8.2.2.1] On vérifie si il y a lieu d'afficher, l'espace pour les boutons
		if($rang != -1 && ($vofpost == 3 || $vofpost == 4 || $vofpost == 2))
		{
			echo'
			<div class="bottom_boutons">
			';
		
			// [8.2.2.1.1] Le membre peut-il poster?
			if($vofpost == 2 || $vofpost == 4)
			{
				// [8.2.2.1.1.A] On vérifie si il n'y a pas de contre indication
				if($data3['groupe'] != -1 && $data3['groupe'] != -3)
					echo'
					<a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;idf='.$idsfa
						: 'addtopic-'.$idsfa.'-'.casse($data3['nom']).'.html').'">
						<img src="'.$design.'actions/'.$langue.'/nouveau.gif" alt"Nouveau Sujet" />
					</a>';
				
				// [8.2.2.1.1.B] Cas des admin ou modo, ils sont autorisés à poster
				elseif($rang == 2 || $rang == 1)
					echo '
					<a href="'.((!$url_rewriting)
						? 'index.php?pagse=postadd&amp;idf='.$idsfa
						: 'postadd-'.$idsfa.'-'.casse($titresujet).'.html').'">
						<img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="Vérouillé" />
					</a> ';
					
				// [8.2.2.1.1.C] Il y a contre indication
				else
					echo ' <img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="Vérouillé" />'; 
			}
			
			// [8.2.2.1.false] Cas contraire, on affiche un bouton du type verouillé
			else 
				echo ' <img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="Vérouillé" />';
		
			// [8.2.2.1.2] Le membre peut-il répondre?
			if($vofpost == 3 || $vofpost == 4)
			{
				// [8.2.2.1.2.A] On vérifie si il n'y a pas de contre indication
				if($editlock != -1 && $editlock != -2 && $data3['groupe'] != -1 && $data3['groupe'] != -1 && $data3['groupe'] != -3)
					echo ' 
					<a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;ids='.$_GET['ids']
						: 'postadd-'.$_GET['ids'].'-'.casse($nomduforum).'.html').'">
						<img src="'.$design.'actions/'.$langue.'/repondre.gif" alt="Répondre" />
					</a> ';
				
				// [8.2.2.1.2.B] Cas des admin ou modo, ils sont autorisés à poster
				elseif($rang == 2 || $rang == 1)
					echo ' 
					<a href="'.((!$url_rewriting)
						? 'index.php?page=postadd&amp;ids='.$_GET['ids']
						: 'postadd-'.$_GET['ids'].'-'.casse($nomduforum).'.html').'">
						<img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="Vérouillé" />
					</a>';
				
				// [8.2.2.1.1.C] Il y a contre indication
				else
					echo ' <img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="Vérouillé" /> '; 
			}
			
			// [8.2.2.1.false] Cas contraire, on affiche un bouton du type verouillé
			else echo ' <img src="'.$design.'actions/'.$langue.'/verrouille.gif" alt="Vérouillé" /> ';
	  
			echo'
		</div>
			';
		}
	////

	if($rang == 1 || $rang == 2) 
	{
		echo'
		<div class="bottom_admin">
			';
			if($editlock == 0)
			{
				echo'
				<a href="index.php?page=lockforum&amp;ids='.$_GET['ids'].'&amp;stat=-1">
					<img src="'.$design.'moderation/verrouiller.gif" alt="'.$langue_post['post40'].'" title="'.$langue_post['post40'].'" />
				</a> 
				<a href="index.php?page=lockforum&amp;ids='.$_GET['ids'].'&amp;stat=-2">
					<img src="'.$design.'moderation/surverrouiller.gif" alt="'.$langue_post['post41'].'" title="'.$langue_post['post41'].'" />
				</a>';
			}
			else
			{
				echo'
				<a href="index.php?page=lockforum&amp;ids='.$_GET['ids'].'&amp;stat=0">
					<img src="'.$design.'moderation/deverrouiller.gif" alt="'.$langue_post['post42'].'" title="'.$langue_post['post42'].'" />
				</a>';
			}
			echo'
				<a href="index.php?page=type&amp;ids='.$_GET['ids'].'&amp;stat=0">
					<img src="'.$design.'moderation/post.gif" alt="'.$langue_post['post43'].'" title="'.$langue_post['post43'].'" />
				</a> 
				<a href="index.php?page=type&amp;ids='.$_GET['ids'].'&amp;stat=1">
					<img src="'.$design.'moderation/postit.gif" alt="'.$langue_post['post44'].'" title="'.$langue_post['post44'].'" />
				</a> 
				<a href="index.php?page=type&amp;ids='.$_GET['ids'].'&amp;stat=2">
					<img src="'.$design.'moderation/annonce.gif" alt="'.$langue_post['post45'].'" title="'.$langue_post['post45'].'" />
				</a> 
				<a href="" onclick="window.open(\'moveto.php?ids='.$_GET['ids'].'&amp;f='.$idsfa.'\', \'\', \'HEIGHT=380,resizable=yes,scrollbars=yes,WIDTH=400\');return false;">
					<img src="'.$design.'moderation/deplacer.gif" alt="'.$langue_post['post46'].'" title="'.$langue_post['post46'].'" />
				</a> 
				<input name="delall" type="image" src="'.$design.'moderation/suppr_sujet.gif" border="0" title="'.$langue_post['post47'].'" onclick="decision(\''.addslashes($langue_post['post48']).'\',\'delpost.php?id2='.$_GET['ids'].'\')" />';
			
			echo'
				<a href="resinchr.php?id2='.$_GET['ids'].'">
					<img src="'.$design.'moderation/synchroniser.gif" alt="'.$langue_post['post49'].'" title="'.$langue_post['post49'].'Synchroniser" />
				</a>
				';
			echo'
				<input name="delpost" type="image" src="'.$design.'moderation/suppr_sondage.gif" border="0" title="'.$langue_post['post50'].'" onclick="decision(\''.addslashes($langue_post['post51']).'\',\'index.php?page=delsonde&amp;ids='.$_GET['ids'].'\')" />';
			echo '
			</div>
			';
	}

		echo'
		<div class="bottom_pagination">';
			echo page_par_page().'
		</div>
		<div style="clear:both;"></div>
	</div>
	';
}
if($autorisation!= -1 && $rang != -1 && ($vofpost == 3 || $vofpost == 4 || $vofpost == 2))
{
	if($affreprapide && ($vofpost == 3 || $vofpost == 4 || $rang == 2 || $rang == 1))
	{
		// [8.2.2.1.2.A] On vérifie si il n'y a pas de contre indication
		if($editlock != -1 && $editlock != -2 && $data3['groupe'] != -1 && $data3['groupe'] != -1 && $data3['groupe'] != -3)
		{
			echo '
<div class="contour_cadre post_quickrep">
	<div class="zone_reponse_rapide">
	<div class="texte_base_titrespec titre_reponse_rapide">'.$langue_post['post55'].'</div>
		<form action="'.(($url_rewriting)
			? 'index.php?page=postadd&amp;ids='.intval($_GET['ids'])
			: 'postadd-'.intval($_GET['ids']).'-'.casse($titresujet).'.html').'"  method="post" enctype="multipart/form-data" name="news">
			'.afficher_panneau_bbcode('texte').'
			<input maxlength="64" type="text" class="reponse_rapide_titre" name="titre" />
			<textarea name="texte" id="texte" class="reponse_rapide_textarea"></textarea><br />
			<input type="submit" name="previsu" value="'.$langue_post['post56'].'" />
			<input type="submit" name="sendage" value="'.$langue_post['post57'].'" />
		</form>
	</div>
</div>
';
		}
	}
}

$req->closeCursor();
?> 
