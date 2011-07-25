<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de recherche de membres
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
 
$sql = 'SELECT id,pseudo,rang,nbpost,localisation,www,valid FROM '.$prefixtable.'membres';
$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
$requse++;
$nbentree = $req->rowCount();
$req->closeCursor();
if(isset($_GET['pg'])){		
	$de = intval($_GET['pg']) * $membreparpage;    $p2=intval($_GET['pg']); 
}
else{ 						
	$de = 0; $p2=0;
}

?>
<!-- Cadre supérieur : recherche et pagination -->
<div class="top_infos texte_base_normal">
	<!-- Formulaire -->
	<div class="topinfo_form texte_base_normal">	
		<form name="form1" method="post" action="<?php echo (($url_rewriting) 
				? 'index.php?page=affprofil' : 'affrofil.html') ?>">
			<label for="search"><?php echo $langue_membre['membre1']; ?></label> 
			<input id="search" name="pseudo" type="text" size="20" maxlength="128" />
			<input type="submit" name="Submit" value="<?php echo $langue_membre['membre8']; ?>" />
		</form>
	</div>
	<!-- Pagination -->
	<div class="topinfo_page texte_base_normal">	
		<?php 
		echo $langue_membre['membre2'];
		
		if($p2 > 1)
			echo'... ';
			
		$nbpage = ceil($nbentree/$membreparpage); 
		if($p2 > 0){			$p=$p2-1; $pc=0; } 
		elseif($nbpage == 2){	$p=0; $pc=0; }
		else{					$p=0; $pc=1; }
		
		if($p2 < $nbpage-1){	$pmax=$p2+1+$pc; }
		else{					$pmax = $nbpage-1; }
		
		for($p;$p<=$pmax;$p++)
		{ 
			echo '<a href="index.php?page=membre&amp;pg='.$p.'">';
			if($p2 == $p)
				echo'<span class="admin">';
			echo $p+1; 
			if($p2 == $p)
				echo'</span>';	
			echo '</a>';
			if($p != $nbpage-1)
				echo',';
		}
		if($p2 < $nbpage-2-$pc)
			echo'... ';
		?>
	</div>
	<div style="clear:both;"></div>
</div>

<?php
$sql = 'SELECT id,pseudo,rang,nbpost,localisation,www,valid FROM '.$prefixtable.'membres ORDER BY pseudo LIMIT '.intval($de).','.intval($membreparpage);
$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
$requse++;
$bdd = null;
echo '
<!-- Tableau des membres -->
<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumstart texte_base_titrespec">'.$langue_membre['membre3'].'</td>
		<td width="20%" class="titreforum texte_base_titre">'.$langue_membre['membre4'].'</td>
		<td width="25%" class="titreforum texte_base_titre">'.$langue_membre['membre5'].'</td>
		<td width="10%" class="titreforumend texte_base_titre">'.$langue_membre['membre6'].'</td>
	</tr>
';
while ($data = $req->fetch()) 
{
	echo '
	<tr>
		<td class="cadre_clair" style="padding:5px">
			<a href="index.php?page=affprofil&amp;id='.$data['id'].'">';
			if($data['valid'] == 0)
				echo '<span class="red">'.htmlentities($data['pseudo']).'</span>';
	  		elseif($data['rang'] == 2)
				echo '<span class="admin">'.htmlentities($data['pseudo']).'</span>';
			elseif($data['rang'] == 1 || $data['rang'] == 3)
				echo '<span class="modo">'.htmlentities($data['pseudo']).'</span>';
			else
				echo htmlentities($data['pseudo']);
			echo'
			</a>
		</td>
		<td class="cadre_fonce" align="center" style="padding:5px">';
		if(!empty($data['www']) && $data['www'] != 'http://')
			echo'<a href="'.htmlentities($data['www']).'">[ '.$langue_membre['membre7'].' ]</a>';
		else
			echo '-';
			
		echo '</td>
		<td class="cadre_clair" align="center" style="padding:5px">
			'.htmlentities($data['localisation']).'
		</td>
		<td class="cadre_fonce_end" align="center" style="padding:5px">
			'.$data['nbpost'].'
		</td>
	</tr>';
} 
echo '
</table>
';
$req->closeCursor();
?>
