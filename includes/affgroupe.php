<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'affichage d'un groupe
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

echo '
<div class="top_infos">
	<a href="' . ((!$url_rewriting) ? 'index.php' : 'index.html') . '">
		'.$langue_index['index21'].' '.htmlentities($nomduforum).'
	</a>
</div>
	
<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td colspan="3" class="titreforumstart texte_base_titrespec">';
		
		echo $langue_affgroupe['affgroupe1'] . ' - ';
		
		$groupe = ((isset($_POST['groupe'])) ? $_POST['groupe'] : $_GET['groupe'] );
		$sql = 'SELECT nom FROM '.$prefixtable.'groupe WHERE id = '.intval($groupe);
		$req = $bdd->query($sql);
		$requse++;
						
		if($req->rowCount() == 0){
			include('./includes/notifs.php'); 
			exit();
		}
		$data2 = $req->fetch();
		echo htmlentities($data2['nom']).'
		</td>
	</tr>
	<tr>';
		
		$sql = 'SELECT g.id,g.idm,g.idg,g.stat,m.pseudo FROM '.$prefixtable.'groupemembre AS g LEFT JOIN '.$prefixtable.'membres AS m ON g.idm=m.id  WHERE g.idg = "'.intval($groupe).'" ORDER BY m.pseudo';
		$req = $bdd->query($sql);
		$requse++;
		$bdd = null;
		if($req->rowCount() == 0)
		echo'
	<tr>
		<td colspan="3" class="cadre_clair" style="padding:20px" align="center">'.$langue_affgroupe['affgroupe2'].'</td>
	</tr>
		';
		while($data = $req->fetch())
		{
			echo '
			<tr>
				<td class="cadre_clair" style="padding:4px">
			';
				if($data['stat'] == 1)
					echo'<span class="admin">';
				echo htmlentities($data['pseudo']);
				if($data['stat'] == 1)
					echo'</span>';
				echo'
				</td>
				<td width="260" align="center" class="cadre_clair" style="padding:4px">
				';
				if($rang == 2 || $rang == 1)
				{
					if($data['stat'] == 0)
						echo'
						<a href="statgroupe.php?idm='.$data['idm'].'&amp;idg='.$groupe.'&amp;act=up">'.$langue_affgroupe['affgroupe3'].'';
					else
						echo'
						<a href="statgroupe.php?idm='.$data['idm'].'&amp;idg='.$groupe.'&amp;act=dw">'.$langue_affgroupe['affgroupe4'].'</a>';
				}
				else
				{
					if($data['stat'] == 0)
						echo $langue_affgroupe['affgroupe5'];
					else
						echo $langue_affgroupe['affgroupe6'];
				}
				echo'
				</td>
				<td width="200" align="center" class="cadre1_bas" style="padding:4px">
				';
				if($rang == 2 || $rang == 1)
					echo'<a href="delfgroupe.php?idm='.$data['idm'].'&amp;idg='.$groupe.'">'.$langue_affgroupe['affgroupe7'].'</a>';
				else
					echo' - ';
				echo'
				</td>
			</tr>
			';
		}
		if($rang == 1 || $rang == 2)
		{
			echo'
				<tr class="titreforum">
					<td colspan="3" class="titreforumstart texte_base_titrespec">'.$langue_affgroupe['affgroupe8'].'</td>
				</tr>
				<tr>
					<form name="form1" method="post" action="addmembre.php?groupe='.$groupe.'">
						<td colspan="3" class="cadre1_bas" style="padding:20px" align="center">
							<input name="pseudo" type="text" size="30" maxlength="64" />
							<input type="submit" name="Submit" value="'.$langue_affgroupe['affgroupe9'].'" />
						</td>
					</form>
				</tr>
			';
		}
		
		?>
	</tr>
</table>  
