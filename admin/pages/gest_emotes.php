<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de gestion des émoticones (traitement & affichage)
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
include('../info_emote.php');

function addslashes2 ($chaine) {
	return str_replace("'","\'",str_replace("\\","\\\\",$chaine));
}

if(!isset($_POST['reg']))
	echo '<h1>'.$lg_gemote['ge0'].'</h1>
<p>'.$lg_gemote['ge1'].'</p>

<form name="form1" method="post" action="index.php?page=gest_emotes">
	<div class="emote_view">';

if(!isset($_POST['ajout']) && !isset($_POST['reg']) || isset($_POST['re'])) {
	for($i=0;$i<$emoticonnb;$i++){
		echo '
		<p>
			<input name="'.$i.'" type="checkbox" value="true" checked />
			<input class="bouton" name="emote'.$i.'" type="text" value="'.htmlentities($emoticonc[$i]).'" />
			<input class="bouton" name="emotei'.$i.'" type="text" value="'.htmlentities($emoticonv[$i]).'" />
			<img src="../'.htmlentities($emoticonv[$i]).'" alt="emote'.$i.'">
		</p>';
	}
	echo '<input name="nb" type="hidden" value="'.$i.'" />';
}

elseif(isset($_POST['ajout'])) 
{
	$nb = 0;
	for($i=0;$i<=$_POST['nb'];$i++) {
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['emote'.$i]) )) {
			echo '
			<p>
				<input name="'.$nb.'" type="checkbox" value="true" checked />
				<input class="bouton" name="emote'.$nb.'" type="text" value="'.htmlentities(strip_gpc($_POST['emote'.$i])).'" />
				<input class="bouton" name="emotei'.$nb.'" type="text" value="'.htmlentities(strip_gpc($_POST['emotei'.$i])).'" />
				<img src="../'.htmlentities(strip_gpc($_POST['emotei'.$i])).'" alt="emote'.$nb.'">
			</p>\n';
			$nb++;
		}
	}
	echo '<input name="nb" type="hidden" value="'.$nb.'" />';
}


// ENREGISTREMENT
else {
	$nb = 0;
	$arr = '$emoticonc = array(';

	for($i=0;$i<=$_POST['nb'];$i++) {
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['emote'.$i]) )) {
			$arr .= '\''.addslashes2(strip_gpc($_POST['emote'.$i])).'\',';
			$nb++;
		}
	}
	
	$arr = substr($arr,0,strlen($arr)-1).');';
	$nb = 0;
	$arri = '$emoticonv = array(';

	for($i=0;$i<=$_POST['nb'];$i++) {
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['emotei'.$i]) )) {
			$arri .= '\''.addslashes2(strip_gpc($_POST['emotei'.$i])).'\',';
			$nb++;
		}
	}
	
	$arri = substr($arri,0,strlen($arri)-1).');';
	$fp = fopen('../info_emote.php','w+');
	fseek($fp,0);
	fputs($fp,'<?php
'.$arri.'
'.$arr.'
	
$emoticonnb = count($emoticonv); 

?>');
	fclose($fp);
	echo '
	<h1>'.$lg_gemote['ge2'].'</h1>
	'.$lg_gemote['ge3'].'
	<p>
		<a href="index.php" target="page">'.$lg_gemote['ge4'].'</a>
	</p>';
	die();
}

echo '
</div>
<h1>'.$lg_gemote['ge5'].'</h1>
	<p>'.$lg_gemote['ge6'].'
		<input class="bouton" name="emote'.($i).'" type="text" value="" /><br />
		'.$lg_gemote['ge7'].'<input class="bouton" name="emotei'.($i).'" type="text" value="" /> '.$lg_gemote['ge8'].'
	</p>
	<input class="bouton" type="submit" name="ajout" value="'.$lg_gemote['ge9'].'" />
	<input class="bouton" type="submit" name="reg" value="'.$lg_gemote['ge10'].'" />
	<input class="bouton" type="submit" name="re" value="'.$lg_gemote['ge11'].'" />
</form>';

?>
    
