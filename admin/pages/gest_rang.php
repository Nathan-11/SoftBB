<?php 

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de gestion des rangs (affichage & traitement)
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

echo '
<h1>'.$lg_rang['rg0'].'</h1>
<p>'.$lg_rang['rg1'].'</p>
<p>'.$lg_rang['rg2'].'</p>
<br />
<br />';

function addslashes2 ($chaine) {
	return str_replace("'","\'",str_replace("\\","\\\\",$chaine));
}
include('../info_options_rangs.php');

if(!isset($_POST['ajout']) && !isset($_POST['reg']) || isset($_POST['re'])) 
{
	echo '<h1>'.$lg_rang['rg3'].'</h1>
	<form name="form1" method="post" action="index.php?page=gest_rang">';
	$count_rang_sp = count($rangnom);
	for($i=0;$i<$count_rang_sp;$i++) 
		echo '
		<p>
			<input name="'.$i.'" type="checkbox" value="true" checked /> 
			<input class="bouton" name="rang_sp'.$i.'" type="text" value="'.htmlentities($rangnom[$i]).'" /> 
			<input class="bouton" name="i_rang_sp'.$i.'" type="text" value="'.htmlentities($rangimage[$i]).'" /> 
			<input class="bouton2" name="c_rang_sp'.$i.'" type="text" maxlength="20" value="'.htmlentities($rangcouleur[$i]).'" /> 
			<span style="color:'.htmlentities($rangcouleur[$i]).'; font-weight:bold">'.htmlentities($rangnom[$i]).'</span>  
			<img src="../'.htmlentities($rangimage[$i]).'" alt="">
		</p>';
	echo '<input name="nb" type="hidden" value="'.$i.'" />
	<h1>'.$lg_rang['rg4'].'</h1>
	<p>
	'.$lg_rang['rg5'].' : <input class="bouton" name="rang_sp'.($i).'" type="text" value="" /><br />
	'.$lg_rang['rg6'].' : <input class="bouton" name="i_rang_sp'.($i).'" type="text" value="" /><br />
	'.$lg_rang['rg7'].' : <input class="bouton2" name="c_rang_sp'.($i).'" type="text" value="" />
	</p>';
	echo '<br />
	<h1>'.$lg_rang['rg8'].'</h1>';
	$count_rang_mb = count($rangmembre);
	for($imb=0 ; $imb<$count_rang_mb ; $imb++){
		echo '
			<p>
				<input name="a_'.$imb.'" type="checkbox" value="true" checked /> 
				<input class="bouton" name="rang_mb'.$imb.'" type="text" value="'.htmlentities($rangmembre[$imb]).'" /> 
				<input class="bouton" name="i_rang_mb'.$imb.'" type="text" value="'.htmlentities($rangimagem[$imb]).'" /> 
				<input class="bouton2" name="v_rang_mb'.$imb.'" type="text" maxlength="20" value="'.htmlentities($rangpostmin[$imb]).'" /> 
				<span style="font-weight:bold">'.htmlentities($rangmembre[$imb]).'</span>  
				<img src="../'.htmlentities($rangimagem[$imb]).'" alt="">
			</p>';
	}
	echo '<input name="nb_2" type="hidden" value="'.$imb.'" />
	<h1>'.$lg_rang['rg9'].'</h1>
	<p>
	'.$lg_rang['rg5'].' : <input class="bouton" name="rang_mb'.($imb).'" type="text" value="" /><br />
	'.$lg_rang['rg6'].' : <input class="bouton" name="i_rang_mb'.($imb).'" type="text" value="" /><br />
	'.$lg_rang['rg15'].' : <input class="bouton2" name="v_rang_mb'.($imb).'" type="text" value="" />
	</p><br />
	<input class="bouton" type="submit" name="ajout" value="'.$lg_rang['rg10'].'" /> 
	<input class="bouton" type="submit" name="reg" value="'.$lg_rang['rg11'].'" /> 
	<input class="bouton" type="submit" name="re" value="'.$lg_rang['rg12'].'" /></form>';
}
elseif(isset($_POST['ajout'])) 
{
	$nb = 0;
	echo '<h1>'.$lg_rang['rg13'].'</h1>
		<form name="form1" method="post" action="index.php?page=gest_rang">';
	for($i=0;$i<=$_POST['nb'];$i++) {
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['rang_sp'.$i]) )) {
		echo '
			<p>
			<input name="'.$nb.'" type="checkbox" value="true" checked /> 
			<input class="bouton" name="rang_sp'.$nb.'" type="text" value="'.htmlentities(strip_gpc($_POST['rang_sp'.$i])).'" /> 
			<input class="bouton" name="i_rang_sp'.$nb.'" type="text" value="'.htmlentities(strip_gpc($_POST['i_rang_sp'.$i])).'" /> 
			<input class="bouton2" name="c_rang_sp'.$nb.'" type="text" value="'.htmlentities(strip_gpc($_POST['c_rang_sp'.$i])).'" /> 
			<span style="color:'.htmlentities(strip_gpc($_POST['c_rang_sp'.$i])).'; font-weight:bold">'.htmlentities(strip_gpc($_POST['rang_sp'.$i])).'</span> 
			<img src="../'.htmlentities(strip_gpc($_POST['i_rang_sp'.$i])).'" alt="">
			</p>';
			$nb++;
		}
	}
	echo '
	<input name="nb" type="hidden" value="'.$nb.'" />
	<h1>'.$lg_rang['rg14'].'</h1>
	<p>
	'.$lg_rang['rg5'].' : <input class="bouton" name="rang_sp'.($nb).'" type="text" value="" /><br />
	'.$lg_rang['rg6'].' : <input class="bouton" name="i_rang_sp'.($nb).'" type="text" value="" /><br />
	'.$lg_rang['rg7'].' : <input class="bouton2" name="c_rang_sp'.($nb).'" type="text" value="" />
	</p>';
	$nb2 = 0;
	echo '<br /><h1>'.$lg_rang['rg8'].'</h1>';
	for($imb=0;$imb<=$_POST['nb_2'];$imb++) {
		if( (isset($_POST['a_'.$imb]) && $_POST['a_'.$imb] == 'true') || ($_POST['nb_2'] == ($imb) && !empty($_POST['rang_mb'.$imb]) )) {
			echo '
			<p>
			<input name="a_'.$nb2.'" type="checkbox" value="true" checked /> 
			<input class="bouton" name="rang_mb'.$nb2.'" type="text" value="'.htmlentities(strip_gpc($_POST['rang_mb'.$imb])).'" /> 
			<input class="bouton" name="i_rang_mb'.$nb2.'" type="text" value="'.htmlentities(strip_gpc($_POST['i_rang_mb'.$imb])).'" /> 
			<input class="bouton2" name="v_rang_mb'.$nb2.'" type="text" value="'.htmlentities(strip_gpc($_POST['v_rang_mb'.$imb])).'" /> 
			<span style="font-weight:bold">'.htmlentities(strip_gpc($_POST['rang_mb'.$imb])).'</span> 
			<img src="../'.htmlentities(strip_gpc($_POST['i_rang_mb'.$imb])).'" alt="">
			</p>'."\n";
			$nb2++;
		}
	}
	echo '
	<input name="nb_2" type="hidden" value="'.$nb2.'" />
	<h1>'.$lg_rang['rg9'].'</h1>
	<p>
	'.$lg_rang['rg5'].' : <input class="bouton" name="rang_mb'.($nb2).'" type="text" value="" /><br />
	'.$lg_rang['rg6'].' : <input class="bouton" name="i_rang_mb'.($nb2).'" type="text" value="" /><br />
	'.$lg_rang['rg15'].' : <input class="bouton2" name="v_rang_mb'.($nb2).'" type="text" value="" />
	</p><br />
	<input class="bouton" type="submit" name="ajout" value="'.$lg_rang['rg10'].'" /> 
	<input class="bouton" type="submit" name="reg" value="'.$lg_rang['rg11'].'" /> 
	<input class="bouton" type="submit" name="re" value="'.$lg_rang['rg012'].'" /></form>';
}
else
{
	$nb = 0;
	$arr_rg_sp = '$rangnom = array(';
	for($i=0;$i<=$_POST['nb'];$i++) {
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['rang_sp'.$i]) )) {
			$arr_rg_sp .= '\''.addslashes2(strip_gpc($_POST['rang_sp'.$i])).'\',';
			$nb++;
		}
	}
	$arr_rg_sp = substr($arr_rg_sp,0,strlen($arr_rg_sp)-1).');';
	$nb = 0;
	$arr_rg_sp_im = '$rangimage = array(';
	for($i=0;$i<=$_POST['nb'];$i++) {
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['rang_sp'.$i]) )) {
			$arr_rg_sp_im .= '\''.addslashes2(strip_gpc($_POST['i_rang_sp'.$i])).'\',';
			$nb++;
		}
	}
	$arr_rg_sp_im = substr($arr_rg_sp_im,0,strlen($arr_rg_sp_im)-1).');';
	$nb = 0;
	$arr_rg_sp_coul = '$rangcouleur = array(';
	for($i=0;$i<=$_POST['nb'];$i++) {
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['rang_sp'.$i]) )) {
			$arr_rg_sp_coul .= '\''.addslashes2(strip_gpc($_POST['c_rang_sp'.$i])).'\',';
			$nb++;
		}
	}
	$arr_rg_sp_coul = substr($arr_rg_sp_coul,0,strlen($arr_rg_sp_coul)-1).');';
	$nb = 0;
	$arr_rg_mb = '$rangmembre = array(';
	for($i=0;$i<=$_POST['nb_2'];$i++) {
		if( (isset($_POST['a_'.$i]) && $_POST['a_'.$i] == 'true') || ($_POST['nb_2'] == ($i) && !empty($_POST['rang_mb'.$i]) )) {
			$arr_rg_mb .= '\''.addslashes2(strip_gpc($_POST['rang_mb'.$i])).'\',';
			$nb++;
		}
	}
	$arr_rg_mb = substr($arr_rg_mb,0,strlen($arr_rg_mb)-1).');';
	$nb = 0;
	$arr_rg_mb_im = '$rangimagem = array(';
	for($i=0;$i<=$_POST['nb_2'];$i++) {
		if( (isset($_POST['a_'.$i]) && $_POST['a_'.$i] == 'true') || ($_POST['nb_2'] == ($i) && !empty($_POST['rang_mb'.$i]) )) {
			$arr_rg_mb_im .= '\''.addslashes2(strip_gpc($_POST['i_rang_mb'.$i])).'\',';
			$nb++;
		}
	}
	$arr_rg_mb_im = substr($arr_rg_mb_im,0,strlen($arr_rg_mb_im)-1).');';
	$nb = 0;
	$arr_rg_mb_val = '$rangpostmin = array(';
	for($i=0;$i<=$_POST['nb_2'];$i++) {
		if( (isset($_POST['a_'.$i]) && $_POST['a_'.$i] == 'true') || ($_POST['nb_2'] == ($i) && !empty($_POST['rang_mb'.$i]) )) {
			$arr_rg_mb_val .= '\''.addslashes2(strip_gpc($_POST['v_rang_mb'.$i])).'\',';
			$nb++;
		}
	}
	$arr_rg_mb_val = substr($arr_rg_mb_val,0,strlen($arr_rg_mb_val)-1).');';
	$fp = fopen('../info_options_rangs.php','w+');
	fseek($fp,0);
	fputs($fp,'<?php
	'.$arr_rg_sp.'
	'.$arr_rg_sp_coul.'
	'.$arr_rg_sp_im.'
	'.$arr_rg_mb.'
	'.$arr_rg_mb_im.'
	'.$arr_rg_mb_val.'
	?>');
	fclose($fp);
	echo '<h1>'.$lg_rang['rg16'].'</h1>
	'.$lg_rang['rg17'].'
	<p><a href="index.php">'.$lg_rang['rg18'].'</a></p>';
}
?>
</p>
