<?php 

/************ CONTIENT LES FONCTIONS UTILES ***************
 * display_error($titre, $msg)                     -> afficher panneau de notification
 * function casse($truc)                           -> transformer chaîne pour l'url
 * afficher_panneau_bbcode($id_textarea)           -> afficher formulaire de post
 * function afficher_emoticones($id_textarea)      -> afficher panneau d'émoticones */

/*
 * Description : Fenêtre affichant un message d'erreur standard. N'arrête 
 *               pas le chargement de la page, affiche juste une zone de
 *               notification.
 * Arguments :
 *    - titre : titre du message de notification (ou erreur)
 *    - msg   : message d'erreur 
 */
function display_error($titre, $msg){
	echo '
	<div class="notif_cadre">
		<div class="notif_titre texte_base_titrespec">'.$titre.'</div>
		<div class="notif_msg texte_base_normal">'.$msg.'</div>
	</div>
	';
}


/* 
 * Description : Retourne la chaîne d'entrée modifiée de telle façon qu'elle 
 *               ne soit distinguée en un seul ensemble dans les paramètres
 *               d'url.
 * Arguments :
 *    - truc :   Chaîne en entrée
 */
function casse($truc){
	if(empty($truc)) return $truc;
	
	$accents = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$ssaccents = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	$machin = strtr($truc,$accents,$ssaccents);
	
	$in = array('?', '!', '.', ',', ':', "'", '&', '(', ')',' ','/','[',']','faq','"','~','#','{','}','[',']','@','^','|','%');
	$out = array('', '', '', '', '', '-', 'et', '', '','-','','','','fa.q','','','','','','','','a','','','');
	$machin = str_replace($in, $out, $machin);
	$truc = strtolower($machin);
	$taille = strlen($truc);
	if($truc{$taille-1} == "-"){
		$taille = $taille -1;
		$truc = substr($truc, 0, $taille);
	}
	return($truc);
}

/*
 * Description : Affiche une zone de BBcode standard complète. Pour que 
 *               le bbcode fonctionne dans l'aire de texte, bien renseigner
 *               les arguments.
 * Arguments :
 *    - id_textarea (String)  : Id de l'aire de texte où il faut appliquer le bbcode. 
 * 
 */
function afficher_panneau_bbcode($id_textarea){
	global $design, $global_lang;
	$return = '
	<img style="cursor:pointer;" onclick="put_bbcode(\'[b]\', \'[/b]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/gras.png" title="'.$global_lang['bb0'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[i]\', \'[/i]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/italique.png" title="'.$global_lang['bb1'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[u]\', \'[/u]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/souligne.png" title="'.$global_lang['bb2'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[s]\', \'[/s]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/barre.png" title="'.$global_lang['bb3'].'" />
	<img src="'.$design.'bbcode/separateur.png" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[img]\', \'[/img]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/image.png" title="'.$global_lang['bb4'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[float=left]\', \'[/float]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/flottant_gauche.png" title="'.$global_lang['bb5'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[float=right]\', \'[/float]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/flottant_droit.png" title="'.$global_lang['bb6'].'" />
	<img src="'.$design.'bbcode/separateur.png" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[url]\', \'[/url]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/lien.png" title="'.$global_lang['bb7'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[email]\', \'[/email]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/email.png" title="'.$global_lang['bb8'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[list][puce]\', \'[/puce][/list]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/liste.png" title="'.$global_lang['bb9'].'" />
	<img src="'.$design.'bbcode/separateur.png" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[quote]\', \'[/quote]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/citation.png" title="'.$global_lang['bb10'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[spoil]\', \'[/spoil]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/spoil.png" title="'.$global_lang['bb11'].'" />
	<img src="'.$design.'bbcode/separateur.png" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[code]\', \'[/code]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/code.png" title="'.$global_lang['bb12'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[shell]\', \'[/shell]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/codeconsole.png" title="'.$global_lang['bb13'].'" />
	
	<br />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[textalign=left]\', \'[/textalign]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/text_left.png" title="'.$global_lang['bb14'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[textalign=center]\', \'[/textalign]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/text_center.png" title="'.$global_lang['bb15'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[textalign=right]\', \'[/textalign]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/text_droite.png" title="'.$global_lang['bb16'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[textalign=justify]\', \'[/textalign]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/test_justify.png" title="'.$global_lang['bb17'].'" />
	<img src="'.$design.'bbcode/separateur.png" />
	
	<img style="cursor:pointer;" onclick="put_bbcode(\'[sup]\', \'[/sup]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/exposant.png" title="'.$global_lang['bb18'].'" />
	<img style="cursor:pointer;" onclick="put_bbcode(\'[sub]\', \'[/sub]\', \''.$id_textarea.'\');return(false)" src="'.$design.'bbcode/indice.png" title="'.$global_lang['bb19'].'" />
	<img src="'.$design.'bbcode/separateur.png" />
	<script type="text/javascript">var textarea_id = \''.$id_textarea.'\'</script>
	<select name="size" id="bbcode_size">
		<optgroup label="'.$global_lang['fct7'].'">
			<option value="7">'.$global_lang['fct2'].'</option>
			<option value="9">'.$global_lang['fct3'].'</option>
			<option value="12">'.$global_lang['fct4'].'</option>
			<option value="18">'.$global_lang['fct5'].'</option>
			<option  value="24">'.$global_lang['fct6'].'</option>
		</optlabel>
	</select>
	<img src="'.$design.'bbcode/separateur.png" />
	<select name="coul" class="sbouton" id="couleur">
		<optgroup label="Couleur">';
		$tab = array('#444444', 'darkred', 'red', 'orange', 'brown', 'yellow', 'green', 'olive', 'cyan', 'blue', 'darkblue', 'indigo', 'violet', 'white', 'black');
		$tabsurnon = array($global_lang['col0'], $global_lang['col1'], $global_lang['col2'], $global_lang['col3'], $global_lang['col4'], 
			$global_lang['col5'], $global_lang['col6'], $global_lang['col7'], $global_lang['col8'], $global_lang['col9'], $global_lang['col10'], 
			$global_lang['col11'], $global_lang['col12'], $global_lang['col13'], $global_lang['col14']);
		for($i=0; $i<count($tab); $i++)
			$return .= '
		// <option value="'.$tab[$i].'" class="genmed" style="color:'.$tab[$i].';">'.$tabsurnon[$i].'</option>';
		
		$return .= '</optgroup>
	</select>
	<br />
	';
	return $return;
}


/*
 * Description : Affiche une zone d'émoticones standards
 * Arguments :
 *    - nom_champ : Nom (attribut name) de l'air de texte ou input.
 *    - nom_form  : Nom du formulaire. 
 * 
 */

function afficher_emoticones($id_textarea){
	global $design, $emoticonnb, $emoticonv, $emoticonc, $global_lang;
	$vs=0;
	$max = ($emoticonnb > 24) ? 24 : $emoticonnb;
		
	echo '
	<p>
		<table cellpadding="0" cellspacing="0" align="center" style="padding:10px">
			<tr>
		';
		
		for($affem=0 ; $affem < $max ; $affem++)
		{
			$vs++;
			echo'
				<td class="smileys">
					<a href="javascript:put_bbcode(\' '.$emoticonc[$affem].' \', \'\', \'texte\')">
						<img src="./'.$emoticonv[$affem].'" alt="'.$emoticonc[$affem].'" title="'.$emoticonc[$affem].'" border="0">
					</a>
				</td>
			';
			if($vs>=4)
			{
				echo'
			</tr>
			<tr>
				';
				$vs = 0;
			}
		}
		echo'
			</tr>';
		if($emoticonnb > 20) echo '					<tr>
				<td colspan="4" align="center" style="padding:5px">
					<a href="" onclick="window.open(\'emote.php\', \'\', \'HEIGHT=450,resizable=yes,scrollbars=yes,WIDTH=250\');return false;">
						'.$global_lang['fct1'].'
					</a>
				</td>
			</tr>';
		echo '
		</table>
	</p>
	';
}
?>
