<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de gestion des options (affichage)
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

include_once('log.php'); 
include('../info_options.php');

echo '
<script>
function showSection(id){
	var i;
	for(i=1; i<=4; i++){
		if(i != id){
			document.getElementById("opt"+i).style.cssText=\'height:0px; visibility:hidden;\';
			document.getElementById("mclic"+i).className=\'mopt_b\';
		}
		else{
			document.getElementById("opt"+i).style.cssText=\'visibility:visible; height:auto;\';
			document.getElementById("mclic"+i).className=\'moptbo\';
		}
	}
}

window.onload = function() {
	showSection(1);
	if(document.getElementById(\'c16n\').checked)
		document.getElementById(\'c17\').disabled=true;
}
</script>

<h1>'.$lg_gesto['go0'].'</h1>
<div class="menu_opt">
	<span class="moptbo" id="mclic1" onclick="showSection(1)">'.$lg_gesto['go1'].'</span>
	<span class="mopt_b" id="mclic2" onclick="showSection(2)">'.$lg_gesto['go2'].'</span>
	<span class="mopt_b" id="mclic3" onclick="showSection(3)">'.$lg_gesto['go3'].'</span>
	<span class="mopt_b" id="mclic4" onclick="showSection(4)">'.$lg_gesto['go4'].'</span>
</div>

<form name="form1" method="post" action="index.php?page=save_opt">
	<div id="opt1">
	<fieldset id="opt1">
		<legend>'.$lg_gesto['go5'].'</legend>
		<p><input type="text" name="nomduforum" id="c1" value="'.htmlentities($nomduforum).'" class="bouton" /> <label for="c1">'.$lg_gesto['go6'].'</label></p>
		<p><input type="text" name="url" id="c2" value="'.$adresse.'" class="bouton" /> <label for="c2">'.$lg_gesto['go7'].'</em></label></p>
		<p><input type="text" name="mailadmin" id="c3" value="'.$mailadmin.'" class="bouton" /> <label for="c3">'.$lg_gesto['go8'].'</label></p>
		<p><input type="text" name="smtp" id="c4" value="'.$smtp.'" class="bouton" /> <label for="c4">'.$lg_gesto['go9'].'</label></p>
		<p><input name="gzip" type="radio" id="c5o" value="true" '.(($gzip) ? ' checked=" checked"' : '' ).'> <label for="c5o">'.$lg_gesto['go0y'].'</label> <input name="gzip" type="radio" id="c5n" value="false" '.((!$gzip) ? ' checked=" checked"' : '' ).'> <label for="c5n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go10'].'</em></p>
		<p><input name="url_rewriting" type="radio" id="c6o" value="true" '.(($url_rewriting) ? ' checked=" checked"' : '' ).'/> <label for="c6o">'.$lg_gesto['go0y'].'</label> <input name="url_rewriting" type="radio" id="c6n" value="false" '.((!$url_rewriting) ? ' checked=" checked"' : '' ).' /> <label for="c6n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go11'].'</p>
		<p>';
			$i = 0;
			if ($handle = opendir('../langue/')) {
				while (false !== ($lang = readdir($handle))) {
					if (is_dir('../langue/'.$lang) && $lang != '.' && $lang != '..')
						echo '<input type="radio" name="languedef" '.(($languedef == $lang)? 'checked ':'').'id="cl'.$i.'" value="'.$lang.'" /><label for="cl'.$i++.'"><img src="../langue/'.$lang.'/logo_'.$lang.'.gif" alt="'.$lang.'" /> '.$lang.' </label> ';
				}
				closedir($handle);
			}
		echo '
		|| '.$lg_gesto['go12'].'<br />
		<em>'.$lg_gesto['go13'].'</p>
		<p>';
			$i = 0;
			if ($handle = opendir('../design/')) {
				while (false !== ($theme = readdir($handle))) {
					if (is_dir('../design/'.$theme) && $theme != '.' && $theme != '..' && $theme != 'emoticones')
						echo '<input type="radio" name="designdef" '.((substr($design, 7, -1) == $theme)? 'checked ':'').'id="des'.$i.'" value="'.$theme.'" /><label for="des'.$i++.'">'.$theme.' </label> ';
				}
				closedir($handle);
			}
		echo '
		|| '.$lg_gesto['go12b'].'<br />
		<em>'.$lg_gesto['go13b'].'</p>
		</fieldset>
	</div>
	
	<div id="opt2">
	<fieldset id="opt2">
		<legend>'.$lg_gesto['go15'].'</legend>	
		<p><input type="text" name="membreparpage" id="c7" value="'.$membreparpage.'" class="bouton" /> <label for="c7">'.$lg_gesto['go16'].'</label></p>
		<p><input type="text" name="postparpage" id="c8" value="'.$postparpage.'" class="bouton" /> <label for="c8">'.$lg_gesto['go17'].'</label></p>
		<p><input type="text" name="postparpageaff" id="c9" value="'.$postparpageaff.'" class="bouton" /> <label for="c9">'.$lg_gesto['go18'].'</label></p>
		<p><input type="text" name="nbsondage" id="c10" value="'.$nbsondage.'" class="bouton" /> <label for="c10">'.$lg_gesto['go19'].'</label></p>
		<p><input type="radio" name="affreprapide" id="c11o" value="true" '.(($affreprapide) ? ' checked=" checked"' : '' ).' /> <label for="c11o">'.$lg_gesto['go0y'].'</label> <input type="radio" name="affreprapide" id="c11n" value="false" '.((!$affreprapide) ? ' checked=" checked"' : '' ).' /> <label for="c11n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go20'].'</p>
		<p><input type="radio" name="cache_forum" id="c12o" value="true" '.(($cache_forum) ? ' checked=" checked"' : '' ).' /> <label for="c12o">'.$lg_gesto['go0y'].'</label> <input type="radio" name="cache_forum" id="c12n" value="false" '.((!$cache_forum) ? ' checked=" checked"' : '' ).' /> <label for="c12n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go21'].'</p>
	</fieldset>
	</div>
	
	<div id="opt3">
	<fieldset id="opt3">
		<legend>'.$lg_gesto['go25'].'</legend>	
		<p><input type="radio" name="mailconf" id="c13o" value="true" '.(($mailconf) ? ' checked=" checked"' : '' ).' /> <label for="c13o">'.$lg_gesto['go0y'].'</label> <input type="radio" name="mailconf" id="c13n" value="false" '.((!$mailconf) ? ' checked=" checked"' : '' ).' /> <label for="c13n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go26'].'</p>
		<p><input type="text" name="tmpfreepost" id="c14" value="'.$tmpfreepost.'" class="bouton" /> <label for="c14">'.$lg_gesto['go27'].'</label></p>
		<p><input type="radio" name="ipaff" id="c15o" value="true" '.(($ipaff) ? ' checked=" checked"' : '' ).' /> <label for="c15o">'.$lg_gesto['go0y'].'</label> <input type="radio" name="ipaff" id="c15n" value="false" '.((!$ipaff) ? ' checked=" checked"' : '' ).' /> <label for="c15n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go28'].'</p>
		<p><input type="radio" name="lockforum" id="c16o" onclick="document.getElementById(\'c17\').disabled=false;" value="true" '.(($lockforum) ? ' checked=" checked"' : '' ).' /> <label for="c16o">'.$lg_gesto['go0y'].'</label> <input type="radio" name="lockforum" id="c16n" onclick="document.getElementById(\'c17\').disabled=true;" value="false" '.((!$lockforum) ? ' checked=" checked"' : '' ).' /> <label for="c16n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go29'].'</p>
		<p><b><label for="c17">'.$lg_gesto['go30'].'</label></b></p>
		<p><textarea name="message_de_lock" id="c17" class="tbouton">'.$message_de_lock.'</textarea></p>
	</fieldset>
	</div>
	
	<div id="opt4">
	<fieldset id="opt4">
		<legend>'.$lg_gesto['go35'].'</legend>
		<p><input type="radio" name="autmodpseudo" id="c18o" value="true" '.(($autmodpseudo) ? ' checked=" checked"' : '' ).'/> <label for="c18o">'.$lg_gesto['go0y'].'</label> <input type="radio" id="c18n" name="autmodpseudo" value="false" '.((!$autmodpseudo) ? ' checked=" checked"' : '' ).' /> <label for="c18n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go36'].'</b></p>
		<p><input type="radio" name="afflistdelauto" id="c19o" value="true" '.(($afflistdelauto) ? ' checked=" checked"' : '' ).' /> <label for="c19o">'.$lg_gesto['go0y'].'</label> <input type="radio" name="afflistdelauto" id="c19n" value="false" '.((!$afflistdelauto) ? ' checked=" checked"' : '' ).' /> <label for="c19n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go37'].'</p>
		<p><input type="radio" name="autorisationsign" id="c20o" value="true" '.(($autorisationsign) ? ' checked=" checked"' : '' ).' /> <label for="c20o">'.$lg_gesto['go0y'].'</label> <input type="radio" name="autorisationsign" id="c20n" value="false" '.((!$autorisationsign) ? ' checked=" checked"' : '' ).' /> <label for="c20n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go38'].'</p>
		<p><input type="radio" name="bbcodesign" id="c21o" value="true" '.(($bbcodesign) ? ' checked=" checked"' : '' ).' /> <label for="c21o">'.$lg_gesto['go0y'].'</label> <input type="radio" name="bbcodesign" id="c21n" value="false" '.((!$bbcodesign) ? ' checked=" checked"' : '' ).' /> <label for="c21n">'.$lg_gesto['go0n'].'</label> || '.$lg_gesto['go39'].'</p>
		<p><input type="text" name="lmax" id="c22" value="'.$lmax.'" class="bouton" /> <label for="c22">'.$lg_gesto['go40'].'</label></p>
		<p><input type="text" name="hmax" id="c23" value="'.$hmax.'" class="bouton" /> <label for="c23">'.$lg_gesto['go41'].'</label></p>
		<p><input type="text" name="pmax" id="c24" value="'.$pmax.'" class="bouton" /> <label for="c24">'.$lg_gesto['go42'].'</label></p>
	</fieldset>
	</div>
	<div style="text-align:center; padding-top:5px;">
		<input type="submit" name="Submit" value="'.$lg_gesto['go45'].'" onclick="document.getElementById(\'c17\').disabled=false;" class="bouton" />
	</div>	
</form>';

?>
