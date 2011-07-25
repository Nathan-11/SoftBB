<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de connexion (html) 
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
	
// Vérification si on n'est pas connecter
if(!empty($_SESSION['pseudo'])) {
	echo '<center>'.$langue_connexion['connexion8'].'</center>';
} else {
	// html du fomulaire
	$form = '<form name="form1" method="post" action="login.php">
				<p>
					<div align="center">
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td align="right" style="padding:5px">'.$langue_connexion['connexion2'].'</td>
							<td style="padding:5px"><input name="pseudolog" type="text" id="pseudolog" /></td>
						</tr>
						<tr>
							<td align="right" style="padding:5px">'.$langue_connexion['connexion3'].'</td>
							<td style="padding:5px"><input name="mdp" type="password" id="mdp" /></td>
						</tr>
						<tr align="center">
							<td colspan="2"  style="padding:5px">'.$langue_connexion['connexion4'].'
								<input name="souvenir" type="checkbox" id="souvenir" value="auto" />
							</td>
						</tr>
						<tr>
							<td colspan="2" style="padding-top:10px">
								<input type="submit" name="Submit" value="'.$langue_connexion['connexion5'].'" />
								<input name="Submit" type="button" onClick="javascript:document.location = \''.((!$url_rewriting) ? 'index.php?page=forgot' : 'forgot.html').'\';" value="'.$langue_connexion['connexion6'].'" />
							</td>
						</tr>
					</table>
					</div>
				</p>
			</form>';


	if(isset($_GET['erreur']))		// connexion échouée
		display_error($langue_connexion['connexion1'], $langue_connexion['connexion7'].'<br >'.$form);
	else 		// formulaire
		display_error($langue_connexion['connexion1'], $form);
		
	$bdd = null;
}
?> 
