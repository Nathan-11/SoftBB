<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page principale de l'affichage
 *   Version : 1.x
 *   
 *   Copyright            : (C) 2005-201x - Équipe SoftBB.net
 *   Site-web             : http://www.softbb.net/
 *   Em@il                : Voir sur le site
 *   Développement        : Equipe SoftBB - ouverte - (voir sur le site)
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique Générale GNU, 
 *   telle que publiée par la Free Software Foundation ; soit la version 2 de 
 *   la Licence, ou (à votre discrétion) une version ultérieure quelconque. 
 *   Ce programme est distribué dans l'espoir qu'il sera utile, mais 
 *   SANS AUCUNE GARANTIE ; sans même une garantie implicite de COMMERCIABILITE 
 *   ou DE CONFORMITE A UNE UTILISATION PARTICULIERE. Voir la Licence Publique 
 *   Générale GNU pour plus de détails. Vous devriez avoir reçu un exemplaire 
 *   de la Licence Publique Générale GNU avec ce programme ; si ce n'est pas le 
 *   cas, écrivez à la Free Software Foundation Inc., 
 *   51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 ***************************************************************************/

ob_start();
$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
// [3] Ajout de la page info.php, elle contient les différentes options
include_once('info.php');

// Option pour la sélection du langage
// Langue par défaut, vérifie si le répertoire exite si non, langue par défaut choisie
$langue = ( (empty($langue1) || !is_dir('./langue/'.$langue1.'/')) ? 'fr' : $langue1 );
require_once('langue/'. ( (file_exists('./langue/'.$langue.'/langue_index.php')) ? $langue : $languedef) .'/langue_index.php');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $titre; ?></title>
		<link href="<?php echo $design?>styles/general.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $design?>styles/index.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $design?>styles/bbcode.css" rel="stylesheet" type="text/css" />
		<?php
		
		// pas de variable page = indexforum
		if(!isset($_GET['page']))
			$_GET['page'] = 'indexforum';
		
		// feuille de style pour une page en particulier
		if(isset($_GET['page']) && file_exists($design.'styles/'.$_GET['page'].'.css'))
			echo '<link href="'.$design.'styles/'.$_GET['page'].'.css" rel="stylesheet" type="text/css" />
		';
			
		// [13] Affichage d'un script javascript pour les formulaires de post
		if(isset($_GET['page']) && ($_GET['page'] == 'postadd' || $_GET['page'] == 'post' || $_GET['page'] == 'mpsend'))
			echo '<script type="text/javascript" src="post_fct.js"></script>
		';
		?>
		<script type="text/javascript" src="global_fct.js"></script>
	</head>
<body>
	<!-- Header : Logo, menus -->
	<a name="top"></a>
	
	<table cellspacing="0" cellpadding="0" class="maintable texte_base_normal">
		<!-- Décoration supérieure -->
		<tr>
			<td class="maintable_topleft"></td>
			<td class="maintable_topmiddle"></td>
			<td class="maintable_topright"></td>
		</tr>
		<!-- Structure centrale -->
		<tr>
			<td class="maintable_middleleft"></td>
			<td class="maintable_contenu">
							
				<!-- Logo, header... -->
				<div class="f_cadre_header">
					<div class="fch_logo">
						<a href="<?php echo ((!$url_rewriting) ? 'index.php?page=indexforum' : 'indexforum.html' )?>" tabindex="5">
							<img src="<?php echo $design?>menu/logo.png" alt="Logo" />
						</a>
					</div>
					<?php
						if(isset($mp) && $mp > 0){
							echo '
							<a href="'.((!$url_rewriting) ? 'index.php?page=mp' : 'mp.html').'">
								<img src="'.$design.'menu/mp.gif" alt="mp" class="mp_logo" />
							</a>';
						}
						echo '
						<div class="texte_base_fin fch_info">';
						
						// [14] Affichage du nombre de mp et tu pseudo
						if($idmembre != -1)
						{
							echo $langue_index['index1'];
							if($mp > 0)
								echo $langue_index['index'.(($mp>1) ? '2' : '3')];
							else
								echo $langue_index['index4'];
						}		
						else
							echo $langue_index['index5'];
						?>
					</div>
				</div>
				
				<!-- Menu rapide -->
				<ul id="header-top-menu">
					<!--<li><a href="../"><span><span>Retour au site</span></span></a></li>-->
				</ul>
				
				
				<!-- Menu -->
				<div class="cadre_menu">
					<?php
					if(empty($pseudo))
					{
						echo '
						<a href="' . ((!$url_rewriting) ? 'index.php?page=connexion' : 'connexion.html').'" tabindex="10">
							<img src="'.$design.'menu/'.$langue.'/connexion.gif" alt="'.$langue_index['index30'].'" /></a><a 
							href="'.((!$url_rewriting) ? 'index.php?page=reg' : 'reg.html') .'" tabindex="20"><img 
								src="'.$design.'menu/'.$langue.'/reg.gif" alt="'.$langue_index['index31'].'" /></a>';
					}
					else
					{
						echo '
						<a href="' . ((!$url_rewriting) ? 'index.php?page=lgout' : 'lgout.html').'" tabindex="10"><img 
							src="'.$design.'menu/'.$langue.'/deconnexion.gif" alt="'.$langue_index['index32'].'" /></a><a 
								href="'.((!$url_rewriting) ? 'index.php?page=mp' : 'mp.html').'" tabindex="20"><img 
							src="'.$design.'menu/'.$langue.'/messagerie.gif" alt="'.$langue_index['index32'].'" /></a>';
					}
					echo '<a href="'; 
						if(!empty($pseudo))
							echo ((!$url_rewriting) ? 'index.php?page=profil' : 'profil.html' );
						else 
							echo ((!$url_rewriting) ? 'index.php?page=connexion' : 'connexion.html' );
						echo '" tabindex="30"><img 
							src="'.$design.'menu/'.$langue.'/profil.gif" alt="'.$langue_index['index34'].'" /></a><a 
						href="'.((!$url_rewriting) ? 'index.php?page=membre' : 'membre.html').'" tabindex="40"><img 
							src="'.$design.'menu/'.$langue.'/membres.gif" alt="'.$langue_index['index35'].'" /></a><a 
						href="'.((!$url_rewriting) ? 'index.php?page=groupe' : 'groupe.html').'" tabindex="50"><img 
							src="'.$design.'menu/'.$langue.'/groupes.gif" alt="'.$langue_index['index36'].'" /></a><a 
						href="'.((!$url_rewriting) ? 'index.php?page=search' : 'search.html').'" tabindex="60"><img 
							src="'.$design.'menu/'.$langue.'/recherche.gif" alt="'.$langue_index['index37'].'" /></a><a 
						href="'.((!$url_rewriting) ? 'index.php?page=faq' : 'faq.html').'" tabindex="70"><img 
							src="'.$design.'menu/'.$langue.'/faq.gif" alt="'.$langue_index['index38'].'" /></a>
				</div>'; 
				?>
				
				<!-- Inclusion de page -->
				<div class="f_cadre_contenu">
					<?php
						$page_autorise_lock = array('lgout', 'notifs', 'connexion', '');
						
						if(isset($_GET['page']))
							$page = $_GET['page'];
						else
							$page = 'indexforum';
						$array_page = explode('_', $page);
						if($array_page[0] == 'admin' && $rang == 2) {
							unset($array_page[0]);
							$page = implode('_', $array_page);
							if($page == null OR $page == '') {
								$page = 'indexadmin.php';
							} else {
								$page .= '.php';
							}
							if(file_exists('./langue/'.$langue.'/admin/langue_'.htmlentities($page)) && $_GET['page'] != 'log')
								require_once('langue/'.$langue.'/admin/langue_'.htmlentities($page));
							elseif(!isset($_GET['page']) || $_GET['page'] != 'log')
								require_once('langue/'.$languedef.'/admin/langue_'.htmlentities($page));
							include_once('admin/'.htmlentities($page));
						} else {
							$page .= '.php';
							if(!in_array($page,$inclauto))
								$page = 'notifs.php'; 
							if($lockforum && !in_array($_GET['page'], $page_autorise_lock)){
								$_GET['aff'] = 'lock';
								$page = 'notifs.php';
							}
							if(file_exists('./langue/'.$langue.'/langue_'.htmlentities($page)) && $_GET['page'] != 'log')
								require_once('langue/'.$langue.'/langue_'.htmlentities($page));
							elseif(!isset($_GET['page']) || $_GET['page'] != 'log')
								require_once('langue/'.$languedef.'/langue_'.htmlentities($page));
						
							include_once('includes/'.htmlentities($page));
						}
					?>
				</div>
				
				
				<!-- Qui est en ligne ? + Footer -->
				<div class="f_cadre_connectes">
					<table width="100%" class="texte_base_normal" cellspacing="0" cellpadding="0">
						<tr class="titreforum">
							<td class="texte_base_titrespec onlineTable_title" colspan="2">
								<?php echo $langue_index['index17'];?>
							</td>
						</tr>
						<tr class="texte_base_gras onlineTable_contain">
							<td width="60" align="center" class="cadre1_droite">
								<img src="<?php echo $design?>footer/whois.gif" alt="<?php echo $langue_index['index17'];?>" />
							</td>
							<td class="cadre1">
								<p>
								<?php 
								
								// affichage statistiques
								echo $langue_index['index6'].'<br />'
									.$langue_index['index8'].'<br />'
								.( ($i > 1)					// code couleurs
										? $langue_index['index10']
										: $langue_index['index9']);
								
								// affichage liste des connectés
								$disp_class_rang = array('', ' class="modo"', ' class="admin"', ' class="modo"');
								for($j=0; $j<$i; $j++){
									echo '
										<a href="'. ((!$url_rewriting) 
												? 'index.php?page=affprofil&id='.$listeid[$j] 
												: 'affprofil-'.$listeid[$j].'.html') .'">
											<span'.$disp_class_rang[$listerang[$j]].'>'.htmlentities($listepersonne[$j]).'</span>
										</a>';
									if($j != $i-1)
										echo ', ';
								}
								?>
								</p>
							</td>
						</tr>
						<tr>
							<!-- Juste pour le style du tableau -->
							<td class="onlineTable_bottom" colspan="2"></td>
						</tr>
					</table>
				</div>
			</div>
			<!-- fin division cadre principal -->
		</td>
		<td class="maintable_middleright"></td>
	</tr>
	<!-- Décorations inférieures -->
	<tr>
		<td class="maintable_bottomleft"></td>
		<td class="maintable_bottommiddle"></td>
		<td class="maintable_bottomright"></td>
	</tr>
	</table>
	
	<!-- /!\ Rappel : dans les conditions d'utilisation GNU, 
		il est interdit de modifier le copyright du forum.
		Mais vous pouvez le déplacer et modifier le style /!\ -->
	<p class="footer">
		<a href="http://www.softbb.net">
		<span class="footer">[ Copyright SoftBB v1.0 bêta3 ]</span></a> , 
		<?php 
			// auteur
			echo '[ '.$langue_index['index20'].' SoftBB.net team  ] , '.
			
			// nombre de requètes
			'[ '.$requse. ' ' . (($requse > 1) ? $langue_index['index11'] : $langue_index['index12']).' ] , '.
			// actionvation ZIP
			'[ GZIP ' . (($gzip) ? $langue_index['index13'] : $langue_index['index14']) . ' ] ';
			$mtime = explode(" ",microtime());
			$endtime = $mtime[1] + $mtime[0];
			echo ' , [ '.$langue_index['index15'].' '.number_format($endtime-$starttime,4,',',''),'s ]';
			
			
			if($rang == 2) 
				echo '<a href="admin/"><span class="footer"> , [ '.$langue_index['index16'].' ]</span></a>'; 
		?>
	</p>
</body>
</html>
<?php
ob_end_flush();
?>
