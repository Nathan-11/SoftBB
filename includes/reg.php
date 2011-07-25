<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de génération d'un nouveau mot de passe
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
	echo '<center>'.$lg_reg['r33'].'</center>';
} else {
	// Si toutes les données sont réunies
	if(isset($_POST['pseudo']))
	{
		$pseudoreg = trim($_POST['pseudo']);
		$mdpt = trim($_POST['mdp']);
		$mdp = md5($mdpt);
		$mdpc = md5(trim($_POST['mdpc']));
		$mail = trim($_POST['mail']);
	
		$sql = 'SELECT pseudo,mail FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($pseudoreg).'" OR mail = "'.add_gpc($mail).'"';
		$req = $bdd->query($sql); $requse++;
	
		while($data = $req->fetch()){
			if(strip_gpc(strtolower($pseudoreg)) == strtolower($data['pseudo'])) $p = 1;
			if($mail == $data['mail']) $m = 1;
		}
	
		$req->closeCursor();
	
		if(empty($pseudoreg)) $p = 2;
		if(empty($mdpt)) $mp = 2;
	
		if(isMailSyntaxCorrect($mail)) $m = 4;
		elseif($mdpc != $mdp) $mp = 1;
	
		if(!isset($p)) $p = 0;
		if(!isset($mp)) $mp = 0;
		if(!isset($m)) $m = 0;
		if(strlen($mdpt) < 6)  $mp = 9;
		if(preg_match('!('.strip_gpc($pseudoreg).')+!',strip_gpc($mdpt)))  $mp = 10;
	
	}

	if(!isset($p)) $p = 3;
	if(!isset($mp)) $mp = 3;
	if(!isset($m)) $m = 3;

	if(isset($_POST['condok']) && $_POST['condok'] == "true") $cond = true;
	else $cond = false;

	// ICI, commence la fin, si tout est bon
	if($p == 0 && $mp == 0 && $m == 0 && $cond == true)
	{
	
		$pass = "";
		$chaine = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		srand((double)microtime()*1000000);
	
		for($ct=0; $ct<8; $ct++) $pass .= $chaine{rand()%strlen($chaine)};
	
		if(!empty($smtp))ini_set("SMTP","$smtp");
	
		$headers = "To: $pseudoreg <$mail>\r\n";
		$headers .= "From: $nomduforum <$mailadmin>\r\n";
	
		if($mailconf)
		{
			$sql = 'INSERT INTO '.$prefixtable.'membres (`pseudo`, `mdp`, `mail`, `nbpost`, `valid`, `temps`, `tempspost`, `rang`, `avatar`, `localisation`, `www`, `mp`, `co`, `gmt`, `he`, `sign`, `signaff`, `rangspec`, `afflist`, `date_register`, `date_login`) VALUES("'.add_gpc($pseudoreg).'","'.$mdpc.'","'.add_gpc($mail).'","0","0","'.time().'",0,0,"","","",0,0,0,0,0,0,0,0, ' . time() . ', ' . time() . ')';
		
			$req = $bdd->query($sql) or exit('Erreur SQL !'.$bdd->print_r($bdd->errorInfo()));  $requse++;
			$iddumembrepourbdd = $bdd->lastInsertId();

			$sql = 'INSERT INTO '.$prefixtable.'membresvalid VALUES("'.$iddumembrepourbdd.'","'.$pass.'")';
			$req = $bdd->query($sql) or exit('Erreur SQL !'.$bdd->print_r($bdd->errorInfo()));  $requse++;
			$bdd = null;
		
			$mess = $lg_reg['r0'].$adresse.'
	'.$lg_reg['r1'].strip_gpc($pseudoreg).'
	'.$lg_reg['r2'].strip_gpc($mdpt).' 
	'.$lg_reg['r3'].'
	'.$adresse.'confirm.php?pass='.strip_gpc($pass).'&pseudo='.$iddumembrepourbdd.'
	'.$lg_reg['r4'];
		
			mail($mail, $lg_reg['r5'].' - '.$nomduforum, $mess, $headers);
		
			display_error($lg_reg['r6'], $lg_reg['r7'].'<br />
			'.$lg_reg['r8'].'<br />
			'.$lg_reg['r9']);
		}
		else
		{
			$sql = 'INSERT INTO '.$prefixtable.'membres (`pseudo`, `mdp`, `mail`, `nbpost`, `valid`, `temps`, `tempspost`, `rang`, `avatar`, `localisation`, `www`, `mp`, `co`, `gmt`, `he`, `sign`, `signaff`, `rangspec`, `afflist`, `date_register`, `date_login`) VALUES("'.add_gpc($pseudoreg).'","'.$mdpc.'","'.add_gpc($mail).'","0","1","'.time().'",0,0,"","","",0,0,0,0,0,0,0,0, ' . time() . ', ' . time() . ')';
			$req = $bdd->query($sql) or exit('Erreur SQL !'.$bdd->print_r($bdd->errorInfo()));  $requse++;
			$bdd = null;
			// enregistrement effectué
			display_error($lg_reg['r10'], $lg_reg['r11']);
		}
	}

	// ICI commence le formulaire
	else{
		echo'
	<form name="form1" method="post" action="' . ((!$url_rewriting) ? 'index.php?page=reg' : 'index.php?page=reg' ) . '">
	<table class="texte_base_normal" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="cadre1_bas td_errors" colspan="2">'; 
				if($p == 1) echo $lg_reg['r12'].'<br />';
				if($m == 1) echo $lg_reg['r13'].'<br />';
				if($mp == 1) echo $lg_reg['r14'].'<br />';
				if($p == 2) echo $lg_reg['r15'].'<br />';
				if($mp == 2) echo $lg_reg['r16'].'<br />';	
				if($cond == false) echo $lg_reg['r17'].'<br />';	
				if($m == 4) echo $lg_reg['r18'].'<br />';
				if($mp == 9) echo $lg_reg['r19'].'<br />';
				if($mp == 10) echo $lg_reg['r20'].'<br />';
				echo $lg_reg['r21'].'
			</td>
		</tr>
		<tr class="titreforum">
			<td class="titreforumstart texte_base_titrespec" colspan="2">'.$lg_reg['r21b'].'</td>
		</tr>
		<tr>
			<td width="30%" class="cadre_clair reg_tdleft">
				<label for="pseudo">'.$lg_reg['r22'].'
			</td>
			<td class="cadre_clair" style="padding:4px">
				<input name="pseudo" type="text" id="pseudo"'; 
					if(isset($_POST['pseudo'])  && $p == 0)
						echo'value="'.strip_gpc(htmlentities($_POST['pseudo'])).'"';
					elseif(isset($_POST['pseudo'])) 
						echo'value="'.strip_gpc(htmlentities($_POST['pseudo'])).'" class="boutonb"';
					else echo'class="bouton"';
					echo 'size="32" maxlength="20" />
			</td>
		</tr>
		<tr>
			<td width="30%" class="cadre_clair reg_tdleft">
				<label for="mdp">'.$lg_reg['r23'].'</label>
			</td>
			<td class="cadre_clair reg_tdright">
				<input name="mdp" type="password" id="mdp" size="32" maxlength="64" /> '.$lg_reg['r24'].'
			</td>
		</tr>
		<tr>
			<td width="30%" class="cadre_clair reg_tdleft">
				<label for="mdpc">'.$lg_reg['r25'].'</label>
			</td>
			<td class="cadre_clair reg_tdright">
				<input name="mdpc" type="password" id="mdpc" size="32" maxlength="64" /> '.$lg_reg['r26'].'
			</td>
		</tr>
		<tr>
			<td width="30%" class="cadre_clair reg_tdleft">
				<label for="mail">'.$lg_reg['r27'].'</label>
			</td>
			<td class="cadre_clair reg_tdright">
				<input name="mail" type="text"'; 
				if(isset($_POST['mail']) && $m == 0)
					echo'value="'.$_POST['mail'].'"';
				elseif(isset($_POST['mail']))
					echo'value="'.strip_gpc(htmlentities($_POST['mail'])).'" class="boutonb"';
				else
					echo'class="bouton"';
				echo'id="mail" size="32" maxlength="64" />
			</td>
		</tr>
		<tr>
			<td class="cadre_clair reg_tdrules" colspan="2">
				<p>'.$lg_reg['r28'].'</p>
					<textarea class="tcond" readonly="readonly">'.$lg_reg['r30'].'
					</textarea>
				<p>
					<input name="condok" id="condok" type="checkbox" value="true" '.( ($cond) ? ' checked="checked" ' : '' ).'> 
						<label for="condok" class="label_cond_ok">'.$lg_reg['r31'].'</label>
				</p>
			</td>
		</tr>
	</table>

	<p align="center">
		<input type="submit" name="Submit" value="'.$lg_reg['r32'].'" />
	</p>
	</form>
		';
	}
}
?>
