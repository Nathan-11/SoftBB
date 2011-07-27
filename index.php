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
		<link href="<?php echo $design; ?>styles/general.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $design; ?>styles/index.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $design; ?>styles/bbcode.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $design; ?>styles/main.css" rel="stylesheet" type="text/css" />
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
		<!-- Tableau d'espacement d'entête-->
	<div align="center"><center>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tbody>
				<tr>
					<td class="milieu_tp" width="100%">...</td>
				</tr>
			</tbody>
		</table>
	</center></div>
	<!-- Fin du tableau d'espacement d'entête-->
				
	<!-- Tableau principale contenant l'entête de la page-->
	<div align="center"><center>
		<table class="tete" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tbody>
				<tr>
					<td width="10%">&nbsp;</td>
					<td class="centre" width="80%"><a href="<?php echo ((!$url_rewriting) ? 'index.php?page=indexforum' : 'indexforum.html' )?>" tabindex="5">
						<img src="<?php echo $design; ?>/img/titre.gif" width="346" height="30" border="0" alt="Logo"/></a><br /><span class="white">Tous pour softBB et softBB pour tous !</span></td>
					<td width="10%">&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</center></div>
	<!-- Fin du tableau principale contenant l'entête de la page-->
				
	<!-- Tableau pour la barre de menu-->
	<div align="center"><center>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tbody>
				<tr>
					<td width="10%">&nbsp;</td>
					<td class="men" width="50%"><a class="m" href="#">Profil</a><img src="<?php echo $design; ?>/img/px.gif" align="absmiddle" width="12" height="12" /><a class="m" href="#">Membres</a><img src="<?php echo $design; ?>/img/px.gif" align="absmiddle" width="12" height="12" /><a class="m" href="#">Groupes</a><img src="<?php echo $design; ?>img/px.gif" align="absmiddle" width="12" height="12" /><a class="m" href="#">Recherches</a><img src="<?php echo $design; ?>/img/px.gif" align="absmiddle" width="12" height="12" /><a class="m" href="#">Faq</a><img src="<?php echo $design; ?>/img/px.gif" align="absmiddle" width="12" height="12" /></td>
					<td class="menn" width="30%"><a class="m" href="#">Connexion</a><img src="<?php echo $design; ?>/img/px.gif" align="absmiddle" width="12" height="12" /><a class="m" href="#">Inscription</a><img src="<?php echo $design; ?>/img/px.gif" align="absmiddle" width="12" height="12" /></td>
					<td width="10%">&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</center></div>
					<?php /*
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
							echo $langue_index['index5'];*/
						?>
					</div>
				</div>
				<div align="center"><center>
					<table class="bloc_pref" cellspacing="0" cellpadding="0" border="0" width="80%">
						<tbody>
							<tr>
								<td width="100%">
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
				</td>
				</tr>
				<tr>
				
				
				<td class="fixia" width="100%">
						<div align="center"><center>
							<table cellspacing="1" cellpadding="0" border="0" width="100%">
								<tbody>
									<tr>
										<td width="100%">
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
					</table>
				</td>
									</tr>
								</tbody>
							</table>
						</center></div>
					<!-- Fin du tableau des offres -->
					</td>
				</tr>
			</tbody>
		</table>
	</center></div>
	<!-- Fin du tableau de présentation -->
						
	<!-- Tableau d'espacement d'entête-->
	<div align="center"><center>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tbody>
				<tr>
					<td class="milieu_tp" width="100%"><a href="http://www.softbb.net">
		[ Copyright SoftBB v1.0 bêta3 ]</a> , 
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
		?></td>
				</tr>
			</tbody>
		</table>
	</center></div>
	<!-- Fin du tableau d'espacement d'entête-->
	
	</body>
</html>
