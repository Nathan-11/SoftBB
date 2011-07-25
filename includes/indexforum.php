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
 
 // vérification dans le forum et info.php chargé
if(!defined('IN_SOFTBB')) 
	exit('Not in SoftBB');
if(!isset($pseudo)) 
	header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' )); 

?>

<!-- informations avant la liste -->
<div class="top_infos">
	<div class="if_top_g texte_base_fin">
		<?php echo $langue_indexforum['indexforum17'].' '.datefct(time(),$gmt).', <br />
			<a href="'.((!$url_rewriting)?'index.php':'index.html').'">
				'.$langue_index['index21'].' '.htmlentities($nomduforum).'
			</a>'; 
		?>
	</div>
	<div class="if_top_d">
		<a href="cooktmp.php"><?php echo $langue_indexforum['indexforum1']; ?></a>
	</div>
	<div style="clear:both;"></div>
</div>

<?php
	echo '<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
	';
	
	if($cache_forum) 
		$sql = 'SELECT f.*,m.pseudo AS pseudoposteur FROM '.$prefixtable.'forum AS f LEFT JOIN '.$prefixtable.'membres AS m ON f.adernier=m.id '.$where.' ORDER BY position';
	else 
		$sql = 'SELECT f.*,m.pseudo AS pseudoposteur FROM '.$prefixtable.'forum AS f LEFT JOIN '.$prefixtable.'membres AS m ON f.adernier=m.id ORDER BY position';
	$req = $bdd->query($sql);
	$requse++;
	$req52->closeCursor();
	$firstTable = true;		// pour l'affichage
	$displayHead = '';		// retient affichage de la ligne descriptive du forum pour l'afficher s'il y a des forums
	
	while($data = $req->fetch())
	{
		if(empty($data['fatt']))
		{
			$displayHead = '';
			if(!$firstTable)	// espace entre forums
				$displayHead .= '
			<tr class="space_forums"><td colspan="5"></td></tr>';
			$displayHead .= '
			<tr class="titreforum">
				<td colspan="2" class="titreforumstart texte_base_titrespec">
					'.bbcode(htmlentities($data['nom'])).'
				</td>
				<td width="52" class="titreforum texte_base_titre">
					<div align="center">'.$langue_indexforum['indexforum2'].'</div>
				</td>
				<td width="75" class="titreforum texte_base_titre">
					<div align="center">'.$langue_indexforum['indexforum3'].'</div>
				</td>
				<td width="155" class="titreforumend texte_base_titre">
					<div align="center">'.$langue_indexforum['indexforum4'].'</div>
				</td>
			</tr>
			';
			$firstTable = false;
		}
		else
		{
			// affichage top : il y a donc des forums dans la section ;)
			if(!empty($displayHead)){
				echo $displayHead;
				$displayHead = '';
			}
			echo'
			<tr>
				<td class="cell1_if_logo">
			';
			if($data['groupe'] == -1 ||$data['groupe'] == -3) 
				echo '<img src="'.$design.'statut/forum_verrouille.png" alt="'.$langue_indexforum['indexforum5'].'" />';
			elseif(isset($_SESSION['forumtime'.$data['id']]) && !empty($pseudo))
			{
				if($_SESSION['lastvisit'] < $_SESSION['forumtime'.$data['id']])
				{
					if($data['temps'] <= $_SESSION['forumtime'.$data['id']] && $_SESSION['kk'.$data['id']] == 0) 
						echo '<img src="'.$design.'statut/forum.png" alt="'.$langue_indexforum['indexforum5'].'" />';
					else 
						echo '<img src="'.$design.'statut/n_forum.png" alt="'.$langue_indexforum['indexforum6'].'" />';
				}
				else
				{
					if($data['temps'] > $_SESSION['lastvisit']) 
						echo '<img src="'.$design.'statut/n_forum.png" alt="'.$langue_indexforum['indexforum7'].'" />';
					else 
						echo '<img src="'.$design.'statut/forum.png" alt="'.$langue_indexforum['indexforum6'].'" />';
				}
			}
			elseif(isset($_SESSION['lastvisit'])  && !empty($pseudo))
			{
				if($data['temps'] > $_SESSION['lastvisit']) 
					echo '<img src="'.$design.'statut/n_forum.png" alt="'.$langue_indexforum['indexforum7'].'" />';
				else 
					echo '<img src="'.$design.'statut/forum.png" alt="'.$langue_indexforum['indexforum6'].'" />';
			}
			else 
				echo '<img src="'.$design.'statut/forum.png" alt="'.$langue_indexforum['indexforum6'].'" />';
			
				echo '
				</td>
				<td class="cell2_if_titre">
					<a href="'.( (!$url_rewriting) 
									? 'index.php?page=forum&amp;idf='.$data['id']
									: 'forum-'.$data['id'].'-'.casse($data['nom']).'.html' ).'">
						'.bbcode(htmlentities($data['nom'])).'
					</a><br />
					<span class="texte_base_fin">
						'.bbcode(htmlentities($data['description'])).'
					</span>
				</td>
				<td class="cell3_if_nbsujet">'.$data['nbsujet'].'</td>
				<td class="cell4_if_nbmsg">'.$data['nbmessage'].'</td>
				<td class="cell5_if_pseudo">';
				if($data['adernier'] != "-")
				{
					echo datefct($data['temps'],$gmt).'<br />
					'.$langue_indexforum['indexforum8'].' 
					<a href="'.((!$url_rewriting)
							?'index.php?page=affprofil&amp;id='.$data['adernier']
							:'affprofil-'.$data['adernier'].'-'.casse($data['pseudoposteur']).'.html').'">
						'.htmlentities($data['pseudoposteur']).'
					</a>
					<a href="redir_last_post.php?forum='.$data['id'].'">
						<img src="'.$design.'statut/icon_latest_reply.gif" alt="'.$langue_indexforum['indexforum9'].'" />
					</a>';
				}
				else 
					echo '-';
				echo'
				</td>
			</tr>
			';
		}
	}

if($req->rowCount() == 0)
	echo '
		<tr>
			<td class="cadre_standard_bas" style="padding:30px">
				<h2>'.$langue_indexforum['indexforum10'].'</h2>
				<p class="texte_base_fin">'.$langue_indexforum['indexforum11'].'</p>
				<p class="texte_base_fin">'.$langue_indexforum['indexforum12'].' 
					<a href="admin/">'.$langue_indexforum['indexforum13'].'</a>
				</p>
				<h2>'.$langue_indexforum['indexforum14'].'</h2>
				<p class="texte_base_fin">'.$langue_indexforum['indexforum15'].' 
					<a href="http://www.softbb.net">Softbb.net</a>
				</p>
			</td>
		</tr>
	</table>';
else 
	echo '
	</table>
	
	<!-- affichage de la légende -->
	<table class="table_legende_icones">
		<tr>
			<td><img src="'.$design.'statut/n_forum.png" alt="'.$langue_indexforum['indexforum7'].'" /></td>
			<td class="texte_base_normal">'.$langue_indexforum['indexforum7'].'</td>
			<td><img src="'.$design.'statut/forum.png" alt="'.$langue_indexforum['indexforum6'].'" />
			<td class="texte_base_normal">'.$langue_indexforum['indexforum6'].'</td>
			<td><img src="'.$design.'statut/forum_verrouille.png" alt="'.$langue_indexforum['indexforum5'].'" /></td>
			<td class="texte_base_normal">'.$langue_indexforum['indexforum5'].'</td>
		</tr>
	</table>
';
$req->closeCursor();
?>
