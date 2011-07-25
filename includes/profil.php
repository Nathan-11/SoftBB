<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Pae d'édition de profil
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
if($rang == -1 || !isset($rang))
	include('./includes/notifs.php');
else
{
	if(isset($_GET['id']))
	{ 
		if($rang == 2 && !empty($_GET['id']))
		{
			$id = $_GET['id'];
			$pasmod = 0;
			$sql = 'SELECT www,avatar,sign,signaff,rangspec,afflist,localisation,rang,valid,pseudo,mail,gmt,he,langue FROM '.$prefixtable.'membres WHERE id = "'.intval($id).'"';
		}
		else
		{
			$pasmod = 1; 
			$sql = 'SELECT www,avatar,sign,signaff,afflist,localisation,pseudo,mail,gmt,he,langue FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
		}
	}
	else
	{
		$pasmod = 1;  
		$sql = 'SELECT id,pseudo,sign,rangspec,signaff,afflist,www,avatar,localisation,rang,valid,mail,gmt,he,langue FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
	}

	$req = $bdd->query($sql);
	$bdd = null;
	$requse++;
	$data = $req->fetch();
	$req->closeCursor();
	echo'
<form action="profils.php'.((isset($_GET['id']) && $pasmod == 0) ? '?id='.$id : '').'" method="post" enctype="multipart/form-data" name="form1">
<div class="top_infos">
	<a href="' . ((!$url_rewriting) ? 'index.php' : 'index.html') .'">
		'.$lg_profil['p0'].htmlentities($nomduforum).'
	</a>
</div>

<fieldset class="fieldset_compte">
	<legend>'.$lg_profil['p1'].'</legend>
	<div class="profil_opt">
		<label for="pseudo" class="profil_label">'.$lg_profil['p2'].'</label>
		<input id="pseudo" name="pseudoren" '. (($rang != 2 && !$autmodpseudo) ? 'disabled="true"' : '') . ' type="text" size="25" maxlength="64" value="'.htmlentities($data['pseudo']).'" />
	</div>
	<div class="profil_opt">
		<label for="email" class="profil_label">'.$lg_profil['p3'].'</label>
		<input id="email" name="mail" ' . (($rang != 2) ? 'disabled="true"' : '') . ' type="text" size="25" maxlength="64" value="'.htmlentities($data['mail']).'" />
	</div>
	<div class="profil_opt">
		<label for="mdpa" class="profil_label">'.$lg_profil['p4'].'</label>
		<input id="mdpa" name="mdp" type="password" size="25" maxlength="64" />
			' . ((isset($_GET['countmdp'])) 
				? ' <span class="red">'.$lg_profil['p5'].'</span>' 
				: '') . '<br />
		<span class="texte_base_fin">'.$lg_profil['p6'].'</span>
	</div>
	<div class="profil_opt">
		<label for="nmdp" class="profil_label">'.$lg_profil['p7'].'</label>
		<input id="nmdp" name="mdp1" type="password" size="16" maxlength="64" />
	</div>
	<div class="profil_opt">
		<label for="nmdpc" class="profil_label">'.$lg_profil['p8'].'</label>
		<input id="nmdpc" name="mdp2" type="password" size="16" maxlength="64" />
	</div>
</fieldset>


<fieldset class="fieldset_profil">
	<legend>'.$lg_profil['p10'] . ((isset($_GET['id']) && $pasmod == 0) ? ' - '.htmlentities($data['pseudo']) : '' ) .'</legend>
	<div class="profil_opt">
		<label for="loca" class="profil_label">'.$lg_profil['p11'].'</label>
		<input id="loca" name="localisation" type="text" size="25" maxlength="64" value="'.htmlentities($data['localisation']).'" />
	</div>
	<div class="profil_opt">
		<label for="site_web" class="profil_label">'.$lg_profil['p12'].'</label>
		<input id="site_web" name="urlwww" type="text" value="'.htmlentities($data['www']).'" size="25" maxlength="128" />
	</div>
		';
		
		if($rang == 2)
		{
			echo'
			<div class="profil_opt">
				<label for="rang" class="profil_label">'.$lg_profil['p13'].'</label>
				<select name="rang" id="rang" class="sbouton" id="rang">
					<option value="2" '.(($data['rang'] == 2) ? 'selected' : '').'>'.$lg_profil['p14'].'</option>
					<option value="1" '.(($data['rang'] == 1) ? 'selected' : '').'>'.$lg_profil['p15'].'</option>
					<option value="3" '.(($data['rang'] == 3) ? 'selected' : '').'>'.$lg_profil['p16'].'</option>
					<option value="0" '.(($data['rang'] == 0) ? 'selected' : '').'>'.$lg_profil['p17'].'</option>
				</select>
				<input name="rangi" type="hidden" id="rangi" value="'.$data['rang'].'" /><br />
				<span class="texte_base_fin profil_precision">
					'.$lg_profil['p18'].'
				</span>
			</div>
			<div class="profil_opt">
				<label for="valid" class="profil_label">'.$lg_profil['p19'].'</label>
				<select name="valid" class="sbouton" id="valid">
					<option value="1" '.(($data['valid'] == 1) ? 'selected' : '').'>'.$lg_profil['p20'].'</option>
					<option value="0" '.(($data['valid'] == 0) ? 'selected' : '').'>'.$lg_profil['p21'].'</option>
				</select> 
				<input name="validi" type="hidden" id="validi" value="'.$data['valid'].'" /><br />
				'.$lg_profil['p22'].'
			</div>
			';
			if(isset($_GET['id']) && $pasmod == 0)
			{		     
				echo'
			<div class="profil_opt">
				'.$lg_profil['p23'].'
				<input type="button" VALUE="'.$lg_profil['p24'].'" NAME="button1" onclick="decision(\''.$lg_profil['p25'].''.addslashes(htmlentities($data['pseudo'])).'\',\'delutil.php?id='.$_GET['id'].'\')" />
			</div>
			';
			}
			echo'
			<div class="profil_opt">
				<label for="rangspec" class="profil_label">'.$lg_profil['p26'].'</label>
				<select name="rangspec" id="rangspec" class="sbouton">
					<option value="0" '.(($data['rangspec'] == 0) ? ' selected="selected" ' : '').'>'.$lg_profil['p27'].'</option>';
			for($si=0;$si<count($rangnom);$si++)
			{
				echo '<option value="'.($si+1).'"';
				if($data['rangspec'] == ($si+1)) 
					echo ' selected="selected" ';
				echo'>'.$rangnom[$si].'</option>';
			}

			echo'
				</select>
			</div>';

		}
		// fin partie réservée admin
		
		echo'
	<div class="profil_opt">
		<label for="langue" class="profil_label">'.$lg_profil['p28'].'</label>
		<p>';
			$t = 0;
			if ($handle = opendir('langue/')) {
				while (false !== ($lang = readdir($handle))) {
					if (is_dir('langue/'.$lang) && $lang != '.' && $lang != '..')
						echo '
						<input type="radio" name="langue" '.(($langue == $lang)? 'checked ':'').' id="cl'.$t.'" value="'.$lang.'" />
						<label for="cl'.$t++.'"><img src="langue/'.$lang.'/logo_'.$lang.'.gif" title="'.$lang.'" alt="'.$lang.'" /> '.$lang.' </label>';
				}
				closedir($handle);
			}
		echo '
	</div>';
		
		echo'
	<div class="profil_opt">
		<label for="sign" class="profil_label">'.$lg_profil['p29'].'</label>
		<span class="texte_base_fin">
			'.$lg_profil['p30'].'
		</span>
		<textarea id="sign" name="signtxt" class="textarea_sign">'.(htmlentities($data['sign'])).'</textarea>
	</div>
	<div class="profil_opt">
		<label class="profil_label">'.$lg_profil['p31'] . ((!$autorisationsign) ? ' '.$lg_profil['p32'] : '').'</label>
		<input type="radio" name="sign" value="1"'; if($data['signaff'] == 1) echo '  checked '; echo' /> '.$lg_profil['p33'].'
		<input type="radio" name="sign" value="0"'; if($data['signaff'] == 0) echo '  checked '; echo' /> '.$lg_profil['p34'].'
	</div>
	<div class="profil_opt">
		<label class="profil_label">'.$lg_profil['p35'].((!$afflistdelauto) ? ' '.$lg_profil['p32'] : '').'</label>
		<input type="radio" name="ligne" value="1"'; if($data['afflist'] == 1) echo '  checked '; echo' /> '.$lg_profil['p33'].'
		<input type="radio" name="ligne" value="0"'; if($data['afflist'] == 0) echo '  checked '; echo' /> '.$lg_profil['p34'].'
	</div>
	<div class="profil_opt">
		<label class="profil_label">'.$lg_profil['p36'].'</label>
		<select name="gmt" class="sbouton">
			<option value="-12" '; if($data['gmt'] == -12) echo 'selected="selected"'; echo'>GMT - 12 '.$lg_profil['p37'].'</option>
			<option value="-11" '; if($data['gmt'] == -11) echo 'selected="selected"'; echo'>GMT - 11 '.$lg_profil['p37'].'</option>
			<option value="-10" '; if($data['gmt'] == -10) echo 'selected="selected"'; echo'>GMT - 10 '.$lg_profil['p37'].'</option>
			<option value="-9" '; if($data['gmt'] == -9) echo 'selected="selected"'; echo'>GMT - 9 '.$lg_profil['p37'].'</option>
			<option value="-8" '; if($data['gmt'] == -8) echo 'selected="selected"'; echo'>GMT - 8 '.$lg_profil['p37'].'</option>
			<option value="-7" '; if($data['gmt'] == -7) echo 'selected="selected"'; echo'>GMT - 7 '.$lg_profil['p37'].'</option>
			<option value="-6" '; if($data['gmt'] == -6) echo 'selected="selected"'; echo'>GMT - 6 '.$lg_profil['p37'].'</option>
			<option value="-5" '; if($data['gmt'] == -5) echo 'selected="selected"'; echo'>GMT - 5 '.$lg_profil['p37'].'</option>
			<option value="-4" '; if($data['gmt'] == -4) echo 'selected="selected"'; echo'>GMT - 4 '.$lg_profil['p37'].'</option>
			<option value="-3.5"  '; if($data['gmt'] == -3.5) echo 'selected="selected"'; echo'>GMT - 3:30 '.$lg_profil['p37'].'</option>
			<option value="-3" '; if($data['gmt'] == -3) echo 'selected="selected"'; echo'>GMT - 3 '.$lg_profil['p37'].'</option>
			<option value="-2" '; if($data['gmt'] == -2) echo 'selected="selected"'; echo'>GMT - 2 '.$lg_profil['p37'].'</option>
			<option value="-1" '; if($data['gmt'] == -1) echo 'selected="selected"'; echo'>GMT - 1 '.$lg_profil['p37s'].'</option>
			<option value="0" '; if($data['gmt'] == 0) echo 'selected="selected"'; echo'>GMT</option>
			<option value="1" '; if($data['gmt'] == 1) echo 'selected="selected"'; echo'>GMT + 1 '.$lg_profil['p37s'].'</option>
			<option value="2" '; if($data['gmt'] == 2) echo 'selected="selected"'; echo'>GMT + 2 '.$lg_profil['p37'].'</option>
			<option value="3" '; if($data['gmt'] == 3) echo 'selected="selected"'; echo'>GMT + 3 '.$lg_profil['p37'].'</option>
			<option value="3.5" '; if($data['gmt'] == 3.5) echo 'selected="selected"'; echo'>GMT + 3:30 '.$lg_profil['p37'].'</option>
			<option value="4" '; if($data['gmt'] == 4) echo 'selected="selected"'; echo'>GMT + 4 '.$lg_profil['p37'].'</option>
			<option value="4.5" '; if($data['gmt'] == 4.5) echo 'selected="selected"'; echo'>GMT + 4:30 '.$lg_profil['p37'].'</option>
			<option value="5" '; if($data['gmt'] == 5) echo 'selected="selected"'; echo'>GMT + 5 '.$lg_profil['p37'].'</option>
			<option value="5.5" '; if($data['gmt'] == 5.5) echo 'selected="selected"'; echo'>GMT + 5:30 '.$lg_profil['p37'].'</option>
			<option value="6" '; if($data['gmt'] == 6) echo 'selected="selected"'; echo'>GMT + 6 '.$lg_profil['p37'].'</option>
			<option value="6.5" '; if($data['gmt'] == 6.5) echo 'selected="selected"'; echo'>GMT + 6:30 '.$lg_profil['p37'].'</option>
			<option value="7" '; if($data['gmt'] == 7) echo 'selected="selected"'; echo'>GMT + 7 '.$lg_profil['p37'].'</option>
			<option value="8" '; if($data['gmt'] == 8) echo 'selected="selected"'; echo'>GMT + 8 '.$lg_profil['p37'].'</option>
			<option value="9" '; if($data['gmt'] == 9) echo 'selected="selected"'; echo'>GMT + 9 '.$lg_profil['p37'].'</option>
			<option value="9.5" '; if($data['gmt'] == 9.5) echo 'selected="selected"'; echo'>GMT + 9:30 '.$lg_profil['p37'].'</option>
			<option value="10" '; if($data['gmt'] == 10) echo 'selected="selected"'; echo'>GMT + 10 '.$lg_profil['p37'].'</option>
			<option value="11" '; if($data['gmt'] == 11) echo 'selected="selected"'; echo'>GMT + 11 '.$lg_profil['p37'].'</option>
			<option value="12" '; if($data['gmt'] == 12) echo 'selected="selected"'; echo'>GMT + 12 '.$lg_profil['p37'].'</option>
			<option value="13" '; if($data['gmt'] == 13) echo 'selected="selected"'; echo'>GMT + 13 '.$lg_profil['p37'].'</option>
		</select><br />
		<input type="checkbox" name="he" id="he" value="1" ' . (($data['he'] == 1) ? 'checked="checked"' : '').' />
		<label for="he">'.$lg_profil['p38'].'</label>
	</div>
</fieldset>

<fieldset class="fieldset_avatar">
	<legend>'.$lg_profil['p40'].'</legend>
	<div class="profil_opt">';
	if(isset($_GET['s'])) $s=$_GET['s']; else $s=0;
	if(isset($_GET['f'])) $f=$_GET['f']; else $f=0;
	if(isset($_GET['p'])) $p=$_GET['p']; else $p=0;
	if(isset($_GET['m'])) $m=$_GET['m']; else $m=0;
	if(!isset($_GET['h'])) $h=0; else $h = $_GET['h'];
	if(!isset($_GET['l'])) $l=0; else $l = $_GET['l'];
	if(!isset($_GET['ma'])) $ma=0; else $ma = $_GET['ma'];
	
	if($data['avatar'] != "http://" && $data['avatar'] != "" && $h != 1 && $l != 1 && $ma != 1 )
	{
		echo '
			<p><img src="'.$data['avatar'].'" title="'.$lg_profil['p41'].'" alt="'.$lg_profil['p42'].'" class="profil_avatarimg">
		';
	}
	
	echo '
	<span class="texte_base_fin">
		'.$lg_profil['p41b'].'
	</span>';

	if($s == 1) echo '<br /><span class="red">'.$lg_profil['p42b'].'</span>';
	if($p == 1) echo '<br /><span class="red">'.$lg_profil['p43'].'</span>';
	if($f == 1) echo '<br /><span class="red">'.$lg_profil['p44'].'</span>';
	if($m == 1) echo '<br /><span class="red">'.$lg_profil['p45'].'</span>';
	if($h == 1) echo '<span class="red">'.$lg_profil['p46'].'</span><br />';
	if($l == 1) echo '<span class="red">'.$lg_profil['p47'].'</span>';
	if($ma == 1) echo '<span class="red">'.$lg_profil['p48'].'</span>';
	  
	echo'
	</div>
	<div class="profil_opt">
		<input name="delavatar" type="checkbox" id="delavatar" value="ok" /> <label for="delavatar">'.$lg_profil['p49'].'</label> 
		<input type="hidden" name="avatarr"  value="'.$data['avatar'].'" />
	</div>
	';
	echo '
	<div class="profil_opt">
		<label for="avatar_url" class="profil_label">'.$lg_profil['p50'].'</label>
		<input id="avatar_url" name="avatar" type="text" id="avatar" size="25" maxlength="128" />
	</div>
	<div class="profil_opt">
		<label for="avatarup" class="profil_label">'.$lg_profil['p51'].'</label>
		<input id="avatar" name="avatarup" type="file" id="avatarup" size="15" />
	</div>
	';
	
	
	echo'
</fieldset>
	';
	if($rang != -1)
	{
		echo '
	<div class="post_savebutton">
		<input type="submit" name="Submit" value="'.$lg_profil['p52'].'">
	</div>
</form>
		';
	}
}
?>
