<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de génération d'un nouveau mdp
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

/* Retourne un mot de passe généré de longueur $taille */
function genere_passwd() {
	$tpass = array();
	$id = 0;
	$taille = 8;
	// récupération des chiffres et lettre
	for($i=48; $i<58; $i++) $tpass[$id++] = chr($i);
	for($i=65; $i<91; $i++) $tpass[$id++] = chr($i);
	for($i=97; $i<123; $i++) $tpass[$id++] = chr($i);
	
	$passwd="";
	for($i=0; $i<$taille; $i++)
		$passwd .= $tpass[rand(0,$id-1)];
	return $passwd;
}

?>
<table class="texte_base_normal" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px"><?php echo '<a href="'.((!$url_rewriting) ? 'index.php' : 'index.html').'">Index : '.htmlentities($nomduforum).'</a>'; ?></td>
	</tr>
</table>
<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumstart texte_base_titrespec"><?php echo $langue_forgot['forgot1']; ?></td>
	</tr>
	<tr align="center">
		<td class="cadre_clair" style="padding:10px">
				<?php
				$displayForm = true;
				
				// si les données formulaire sont envoyées et syntaxiquement correctes
				if(isset($_POST['pseudo']) && isset($_POST['mail'])
					&& !empty($_POST['pseudo']) && !isMailSyntaxCorrect($_POST['mail']))
				{
					// on vérifie qu'elles correspondent à une entrée et qu'un email n'a pas été généré trop récement (antiflood)
					$sql = 'SELECT pseudo,mail FROM '.$prefixtable.'membres WHERE mail = "'.$_POST['mail'].'" AND pseudo="'.addslashes($_POST['pseudo']).'"';
					$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
					$data = $req->fetch();
					$pseudo2 = stripslashes($data['pseudo']);
					$mail = $data['mail'];
				
					// si une entrée correspond on envoie l'email
					if($req->rowCount() == 1)
					{
						// données et enregistrement bdd
						$displayForm = false;	// on n'affiche pas le formulaire dans ce cas seulement
						$mdpt = genere_passwd();
						$mdpc = md5($mdpt);
						// on désactive toutes les autres demandes
						//$sql = 'DELETE FROM '.$prefixtable.'oubli WHERE pseudo = "'.add_gpc($pseudo2).'"';
						//$bdd->exec($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
						
						$sql = 'INSERT INTO '.$prefixtable.'oubli (pseudo, mail, mdp, date) 
							VALUES("'.add_gpc($pseudo2).'","'.add_gpc($mail).'", "'.$mdpc.'", "'.time().'")';
						$bdd->exec($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
						
						$bdd = null; 
						
						
						// envoi email
						$mess = $langue_forgot['forgot7'].' : '.$adresse.'
'.$langue_forgot['forgot5'].' : '.strip_gpc($pseudo2).'
'.$langue_forgot['forgot8'].' : '.strip_gpc($mdpt).'
'.$langue_forgot['forgot9'].' 
'.$adresse.'confirm_mdp.php?pseudo='.$pseudo2.'&psw='.$mdpc.'
'.$langue_forgot['forgot10'];

						
						if(!empty($smtp))
							ini_set("SMTP","$smtp");
						$headers = "To: $pseudo2 <$mail>\r\n";
						$headers .= "From: $nomduforum <$mailadmin>\r\n";
						
						mail($mail, $langue_forgot['forgot11'].' - '.$nomduforum, $mess, $headers);
						echo $langue_forgot['forgot12'];
					}
				}
					
				// Affichage du formulaire
				if($displayForm)
				{
					?>
					<form name="form1" method="post" action="index.php?page=forgot">
						<table cellspacing="0" cellpadding="0">	
							<tr>
								<td colspan="2" style="padding:10px">
									<div align="center">
										<?php 
										if(!isset($_POST['pseudo']) && !isset($_POST['mail']))	
											echo $langue_forgot['forgot2'].'<br />'.$langue_forgot['forgot3'].'<br />'.$langue_forgot['forgot4'];
										else if(empty($pseudo) || !isMailSyntaxCorrect($_POST['mail']))
											echo '<font class="red">'.$langue_forgot['forgot14'].'</font>';
										else
											echo '<font class="red">'.$langue_forgot['forgot15'].'</font>';
										?>
									</div>
								</td>
							</tr>
							<tr>
								<td align="right" style="padding:5px">
									<label for="pseudo"><?php echo $langue_forgot['forgot5']; ?> :</label>
								</td>
								<td style="padding:5px">
									<input id="pseudo" name="pseudo" type="text" id="pseudo" value="<?php echo ((isset($_POST['pseudo'])) ? $_POST['pseudo'] : ''); ?>" />
								</td>
							</tr>
							<tr>
								<td align="right" style="padding:5px">
									<label for="mail"><?php echo $langue_forgot['forgot6']; ?> :</label>
								</td>
								<td style="padding:5px">
									<input id="mail" name="mail" type="text" id="mail" value="<?php echo ((isset($_POST['mail'])) ? $_POST['mail'] : ''); ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-top:10px">
									<div align="center"><input type="submit" name="Submit" value="<?php echo $langue_forgot['forgot13']; ?>" /></div>
								</td>
							</tr>
						</table>
					</form>
					<?php
				}
				?>
			
		</td>
	</tr>
</table>
