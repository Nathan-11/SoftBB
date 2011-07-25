<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de d�placement de sujet
 *   Version : 1.x
 *   
 *   Copyright            : (C) 2005-201x - �quipe SoftBB.net
 *   Site-web             : http://www.softbb.net/
 *   Em@il                : Voir sur le site
 *   D�veloppement        : Equipe SoftBB - ouverte - (voir sur le site)
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique G�n�rale GNU.
 *   Plus d'infos sur /index.php
 *
 ***************************************************************************/

include('info.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>D�placement de sujet</title>
	<link href="<?php echo $design; ?>styles/general.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $design; ?>styles/moveto.css" rel="stylesheet" type="text/css">
</head>
<body>
<table  class="texte_base_normal" height="130" width="100%" border="0" style="border: 1px solid #FFFFFF" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumstart texte_base_titrespec">D&eacute;placer le sujet </td>
	</tr>
	<tr>
		<td align="center" class="cadre_clair" style="padding:13px">
		<?php
		include('info_bdd.php');
		$db = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
		
		$sql = 'SELECT id,rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
		$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
		if($req->rowCount() == 0) exit();
		$data = $req->fetch();
		$rang = $data['rang'];
		$idmembre = $data['id'];
		
		if(empty($pseudo))
			header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
		
		// V�rifie si �a vaut la peine d'aller plus loin
		elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['ids'])) 
			header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
		// Si �a en vaut la peine
		else
		{
			// Si c'est un chef de groupe qui veut modifier
			if($rang == 3)
			{
				// On cherche le forum de ce sujet
				$sql = 'SELECT idsfa FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']);
				$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
				if($req->rowCount() != 0)
				{
					$data = $req->fetch();
					// On cherche le groupe de ce forum
					$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.intval($data['idsfa']);
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
					if($req->rowCount() != 0)
					{
						$data = $req->fetch();
						// Si c'est pas un groupe particulier, on arrete l�
						if($data['groupe'] == 0 || $data['groupe'] == -1 || $data['groupe'] == -2 || $data['groupe'] == -3) $modifier = false;
						else
						{ 
							// Si c'est un groupe particulier, on v�rifie s'il en est chef
							$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.intval($idmembre).'" AND idg = "'.intval($data['groupe']).'" AND stat = "1"';
							$req = $db->query($sql);
							if($req->rowCount() == 0) 
								$modifier = false;
							else 
								$modifier = true;
						}
					}
					else
						$modifier = false;
				}
				else
					$modifier = false;
			}
			// Les modos et admins sont d'office accept�s
			else $modifier = true;
			
			// On va faire ce qu'il faut
			if($modifier)
			{
				if(!isset($_POST['select']) || $_POST['select'] == "non")
				{
					echo'
				<p>Selectionnez le forum dans lequel vous voulez d&eacute;placer ce sujet.</p>
					<form name="form1" method="post" action="moveto.php?ids='.$_GET['ids'].'&amp;f='.$_GET['f'].'">
						<select name="select" size="12">';
					$sql = 'SELECT nom,id,fatt FROM '.$prefixtable.'forum ORDER BY position';
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
					$db = null;
					while($data = $req->fetch())
					{
						if($data['fatt'] != 0) echo '<option  value="'.$data['id'].'"';
						if($data['fatt'] == 0) echo '<option value="non" ';
						if($data['fatt'] == 0) echo '  disabled="disabled" ';
						if($data['id'] == $_GET['f']) echo ' selected ';
						if($data['fatt'] == 0) echo ' class="admin"';
						echo'>';
						if($data['fatt'] != 0) echo '...';
						echo htmlentities($data['nom']);
						echo'</option>'; 
					}
					echo '
						</select>
						<p>
							<input type="checkbox" name="checkbox" id="check" value="checkbox" />
								<label for="check">Laisser un lien &agrave; l\'ancien emplacement</label>
						</p>
						<input type="submit" name="Submit" value="Deplacer" class="bouton" />
					</form>
					';
				}
				else
				{
					$sql = 'SELECT id2,titre,idfa,idsfa,pseudode,nbr,idde FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']);
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
					$data = $req->fetch();

					$sql = 'SELECT fatt FROM '.$prefixtable.'forum WHERE id = '.intval($_POST['select']);
					$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
					$data2 = $req->fetch();
					if($_POST['select'] != $data['idsfa'])
					{
						$sql = 'UPDATE '.$prefixtable.'post SET idfa = '.$data2['fatt'].', idsfa = '.intval($_POST['select']).' WHERE id2 = '.intval($_GET['ids']).' OR idsa = '.intval($_GET['ids']);
						$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
					
						$sql = 'SELECT tmpdernierpost,pseudodernier,tmppost FROM '.$prefixtable.'post WHERE idsfa = '.intval($data['idsfa']).' AND idsa = 0 AND `lock` < 1 ORDER BY tmppost DESC';
						$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
						$data18 = $req->fetch();

						$sql = 'UPDATE '.$prefixtable.'forum SET temps = "'.$data18['tmppost'].'" , adernier = "'.addslashes($data18['pseudodernier']).'" , dernier = "'.addslashes($data18['tmpdernierpost']).'" , nbsujet = nbsujet-1, nbmessage = nbmessage-'.$data['nbr'].'  WHERE id = '.$data['idsfa'];
						if($req->rowCount() == 0)
						$sql = 'UPDATE '.$prefixtable.'forum SET temps = "0" , adernier = "-" , dernier = "-" , nbsujet = nbsujet-1, nbmessage = nbmessage-'.$data['nbr'].'  WHERE id = '.$data['idsfa'];
						$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

						$sql = 'SELECT tmpdernierpost,pseudodernier,tmppost FROM '.$prefixtable.'post WHERE idsfa = '.$_POST['select'].' AND idsa = 0 AND `lock` < 1 ORDER BY tmppost DESC';
						$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));
						$data18 = $req->fetch();

						$sql = 'UPDATE '.$prefixtable.'forum SET temps = "'.$data18['tmppost'].'" , adernier = "'.addslashes($data18['pseudodernier']).'" , dernier = "'.addslashes($data18['tmpdernierpost']).'" , nbsujet = nbsujet+1, nbmessage = nbmessage+'.$data['nbr'].'  WHERE id = '.intval($_POST['select']);
						$req = $db->query($sql) or die('Erreur SQL !<br />'.$db->print_r($db->errorInfo()));

						$sql = 'DELETE FROM '.$prefixtable.'post WHERE idsfa = '.intval($_POST['select']).' AND `lock` = '.intval($_GET['ids']);
						$req = $db->query($sql) or die('Erreur SQL !'.$db->print_r($db->errorInfo()));
						
						if(isset($_POST['checkbox']))
						{
							$sql = 'INSERT INTO '.$prefixtable.'post (`titre`, `texte`, `idfa`, `idsfa`, `idsa`, `pseudode`, `idde`, `sondage`, `nbr`, `tmppost`, `pseudodernier`, `ip`, `edit`, `tmpdernierpost`, `lock`, `tmpsave`)  VALUES ("'.addslashes($data['titre']).'","","'.$data['idfa'].'","'.$data['idsfa'].'","0","'.addslashes($data['pseudode']).'","'.$data['idde'].'","0","0","'.time().'","'.addslashes($_SESSION['pseudo']).'","","","0","'.intval($_GET['ids']).'","0")';
							$req = $db->query($sql) or die('Erreur SQL !'.$db->print_r($db->errorInfo())); 
						}
						echo'
						<p>Sujet transfer&eacute; avec succ&egrave;s.</p>
						<p><a href="javascript:close();">Fermer la fen&ecirc;tre</a></p>';
					}
					else
						echo'<p>Vous avez deplace ce sujet<br /> dans son forum actuel !</p><p><a href="javascript:close();">Fermer la fen&ecirc;tre</a></p>';
					$db = null;
				}
			}
			else
				echo 'Retournez sur le forum! <p><a href="javascript:close();">Fermer la fen&ecirc;tre</a></p>';
		}
		?>
		</td>
	</tr>
</table>
</body>
</html>          
