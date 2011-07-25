<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de recherche dans le forum
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
 
if(!defined('IN_SOFTBB')) 
	exit('Not in SoftBB');
echo '
<div class="top_infos">
	<a href="'.((!$url_rewriting) ? 'index.php' : 'index.html').'">
		Index : '.htmlentities($nomduforum).'
	</a>
</div>
';

if(!isset($_GET['clearsearch']) )
{
	echo'
<form action="index.php?page=search&amp;clearsearch" method="post">
<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumend texte_base_titrespec" colspan="2">Rechercher</td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px" height="50px"><label for="mots_cles">Recherche par Mots-cl�s :</label></td>
		<td class="cadre1_bas" style="padding:4px">
			<input id="mots_cles" name="key" type="text" size="40" maxlength="64" />
				<br />
			<input name="separer" id="sep1" type="radio" value="0" checked="checked" /> <label for="sep1">Separement</label>
			<input name="separer" id="sep2" type="radio" value="1" /> <label for="sep2">Tous</label>
			<input name="separer" id="sep3" type="radio" value="2" /> <label for="sep3">Le tout</label>
		</td>
	</tr>   
	<tr>
		<td width="30%" class="cadre_clair" height="35px" style="padding:4px">
			<label for="auteur">Recherche par Auteur :</label>
		</td>
		<td class="cadre1_bas" style="padding:4px">
			<input id="auteur" name="pseudoren" type="text" size="40" maxlength="64" />
		</td>
	</tr>
</table>

<table class="texte_base_normal" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumend texte_base_titrespec" colspan="2">Options de recherche</td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" height="35px" style="padding:4px">Dans quel forum :</td>
		<td class="cadre1_bas" style="padding:4px">
	';
	echo '
			<select name="forum" class="sbouton">
				<option value="all" selected>&raquo; Dans tout les forums</option>
	';
				$sql = 'SELECT nom,id,fatt FROM '.$prefixtable.'forum ORDER BY position';
				$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
				$requse++;
				$bdd = null;
				while($data = $req->fetch())
				{
					if($data['fatt'] != 0) echo '<option  value="sf'.$data['id'].'"';
					if($data['fatt'] == 0) echo '<option value="f'.$data['id'].'" ';
					echo' />';
					if($data['fatt'] != 0) echo '.. ';
					echo bbcode(htmlentities($data['nom']));
					echo'</option>'; 
				}
	echo '
			</select>
	';
	echo'
		</td>
	</tr>   
	<tr>
		<td width="30%" class="cadre_clair" height="35px" style="padding:4px">&Agrave; quel moment :</td>
		<td class="cadre1_bas" style="padding:4px">
			<select name="temps" class="sbouton">
				<option value="0" checked>Tous les message</option>
				<option value="86400">Il y a moins d\'un jour</option>
				<option value="604800">Il y a moins d\'une semaine</option>
				<option value="1209600">Il y a moins de 2 semaines</option>
				<option value="2592000">Il y a moins d\'un mois</option>
				<option value="7776000">Il y a moins de 3 mois</option>
				<option value="15552000">Il y a moins de 6 mois</option>
				<option value="31104000">Il y a moins d\'un an</option>
			</select>
		</td>
	</tr> 
	<tr>
		<td width="30%" class="cadre_clair" height="35px" style="padding:4px">Trier par date :</td>
		<td class="cadre1_bas" style="padding:4px">
			<input name="order" type="radio" id="tri1" value="ASC" checked /> <label for="tri1">Croissante</label>
			<input name="order" type="radio" id="tri2" value="DESC" /> <label for="tri2">Decroissant</label>
		</td>
	</tr> 		  
		<tr>
		<td width="30%" class="cadre_clair" height="35px" style="padding:4px">Rechercher dans le :</td>
		<td class="cadre1_bas" style="padding:4px">
			<input name="in" id="searchin1" type="radio" value="titre" checked="checked" /> <label for="searchin1">Titre</label>
			<input name="in" id="searchin2" type="radio" value="texte" /> <label for="searchin2">Corps du message (plus lent)</label>
		</td>
	</tr> 
</table>

<p align="center"><input type="submit" name="Submit" value="Lancer la recherche maintenant" /></p></form>';
}
else
{
	$where = '';
	
	if($_POST['in'] == 'texte') $in_search = 'texte';
	else  $in_search = 'titre';
	
	if(isset($_POST['key']))
	{
		if($_POST['separer'] == 0)
		{
			$where .= '(';
			$key = explode(' ',$_POST['key']);
			for($keyi=0;$keyi<count($key);$keyi++)
			{
				if(strlen($key[$keyi]) >= 3)
				{
					$where .= ' '.$in_search.' LIKE \'%'.add_gpc($key[$keyi]).'%\' ';
					if($keyi != count($key)-1) $where .= ' OR ';
				}		
			}
			
			if(strlen($key[count($key)-1]) < 3 && strlen($where) > 1) $where = substr($where,0,strlen($where)-3);
			$where .= ')';
		}
		elseif($_POST['separer'] == 1)
		{
			$where .= '(';
			$key = explode(' ',$_POST['key']);
			for($keyi=0;$keyi<count($key);$keyi++)
			{
				if(strlen($key[$keyi]) > 3)
				{
					$where .= ' '.$in_search.' LIKE \'%'.add_gpc($key[$keyi]).'%\' ';
					if($keyi != count($key)-1) $where .= ' AND ';
				}
			}
			if(strlen($key[count($key)-1]) <= 3 && strlen($where) > 1) $where = substr($where,0,strlen($where)-4);
			$where .= ')';
		}
		else $where = '( '.$in_search.' LIKE \'%'.add_gpc($_POST['key']).'%\' )';
		if($where == '()') $where='';
		if(!empty($_POST['pseudoren']))
		{
			$sql = 'SELECT id FROM '.$prefixtable.'membres WHERE pseudo LIKE \'%'.add_gpc($_POST['pseudoren']).'%\' ';
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
			$dataid = $req->fetch();
			$requse++;
			
			if(empty($where)) $where .= ' pseudode = '.intval($dataid['id']).' ';
			else  $where .= ' AND pseudode = '.intval($dataid['id']).' ';
		}

		if(!empty($where))
		{
			//////////////////////////////
			if( !empty($_POST['temps']) )  $where .= ' AND tmppost > '.(time()-intval($_POST['temps'])).' ';
			$whereforum = ' AND ( idsfa = 0 OR ';
			
			if($_POST['forum'] == 'all') $sql = 'SELECT f.id AS iddef , f.groupe , g.id AS iddeg , f.mg , f.v , f.m, g.stat FROM '.$prefixtable.'forum AS f LEFT JOIN '.$prefixtable.'groupemembre AS g ON g.idg = f.groupe AND g.idm='.$idmembre.' WHERE f.fatt > 0';
			elseif(ereg("^f",$_POST['forum'])) $sql = 'SELECT f.id AS iddef , f.groupe , g.id AS iddeg , f.mg , f.v , f.m, g.stat FROM '.$prefixtable.'forum AS f LEFT JOIN '.$prefixtable.'groupemembre AS g ON g.idg = f.groupe AND g.idm='.$idmembre.' WHERE f.fatt = '.intval(str_replace('f',' ',$_POST['forum'])).' ';
			else $sql = 'SELECT f.id AS iddef , f.groupe , g.id AS iddeg , f.mg , f.v , f.m, g.stat FROM '.$prefixtable.'forum AS f LEFT JOIN '.$prefixtable.'groupemembre AS g ON g.idg = f.groupe AND g.idm='.$idmembre.' WHERE f.id = '.intval(str_replace('sf',' ',$_POST['forum'])).' ';
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
			$requse++;

			while($dataforum = $req->fetch())
			{
				if($rang == 2 || $rang == 1 ||  $dataforum['groupe'] == 0 || (!empty($dataforum['iddeg']) && $dataforum['mg'] > 0)  || $dataforum['stat'] == 1  || ($dataforum['groupe'] == -2 && rang > -1) || $dataforum['groupe'] == -1 || ($dataforum['groupe'] == -4 && $rang == -1 && $dataforum['v'] == 1) || ($dataforum['groupe'] == -4 && $rang == 0 && $dataforum['m'] > 0) )
				{
					$whereforum .= ' idsfa = '.$dataforum['iddef'].' OR ';
				}
			}
			$whereforum = substr($whereforum,0,strlen($whereforum)-3);
			$whereforum .= ')';
			$where .= $whereforum;
			if($rang != 1 && $rang != 2) $where .= ' AND `lock` > -2 ';
			if($_POST['order'] == 'DESC') $where .= ' ORDER BY id2 DESC';
			else $where .= ' ORDER BY id2 ASC';		
			$_SESSION['wheres'] = $where;
			
			////////////////////
		}
		else $_SESSION['wheres'] = ' id2=0 ';
	}
	
	if(isset($_GET['p'])) $p = intval($_GET['p']);
	else $p = 0;
	//$sql = 'SELECT id2 FROM '.$prefixtable.'post WHERE '.$_SESSION['wheres'];
	$sql2 = 'SELECT id2,rangspec,titre,sign,signaff,edit,ip,texte,pseudode,idsa,tmpsave,pseudo,nbpost,idde,rang,id,avatar,tmppost,www FROM '.$prefixtable.'post
		LEFT JOIN '.$prefixtable.'membres ON '.$prefixtable.'membres.id = '.$prefixtable.'post.idde
		WHERE '.$_SESSION['wheres'].' LIMIT '.intval($postparpageaff*$p).','.intval($postparpageaff);
	//$req2 = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
	//$requse++;
	$req = $bdd->query($sql2) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
	$requse++;
	if($req->rowCount() == 0)
		display_error('Recherche', '<p>Aucune r�ponse n\'a �t� trouv�e pour votre requ�te</p>
			<p><a href="'.((!$url_rewriting) ? 'index.php?page=search' : 'search.html').'">Faire une autre recherche</a></p>');
			
		if($req->rowCount() > 0)
		{
			$color = "alternate2";
			echo '
<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
            <tr class="titreforum">
              <td class="titreforumstart texte_base_titrespec">Auteur</td>
              <td class="titreforumend texte_base_titre">Message</td>
              </tr>';
				 while($data = $req->fetch()) {
				
				///////////////////
					if($color == "alternate1") { $color = "alternate2"; } else { $color = "alternate1"; }
	echo '            <tr>
              <td width="150" height="50" align="center" valign="top" class="cadre_clair" style="padding:10px"><a name="'.$data['id2'].'"></a><b>'.htmlentities($data['pseudo']).'</b><br />
                  ';
				if($data['rangspec'] > 0)
				{
					$kk = $data['rangspec'] - 1;
					if(!empty($rangimage[$kk])) 
						echo '<img src="'.$design.'rang/'.$rangimage[$kk].'" /><br />';
				  	echo '<span style="color: '.$rangcouleur[$kk].'">'.$rangnom[$kk].'</span>';
				}
				else
				{
					$cont=0;
					for($kk=0;$cont<1;$kk++)
					{
						if($rangpostmin[$kk] <= $data['nbpost']) 
						{ 
							if(!empty($rangimagem[$kk])) 
								echo '<img src="'.$design.'rang/'.$rangimagem[$kk].'" /><br />';
							$cont=1;
							echo $rangmembre[$kk];
						}
					}
				}
				echo'<br />
                <br />';
				$idavatar = $data['id'];
                if(!empty($data['avatar']) && $data['avatar'] != 'http://')
					echo '<img src="'.$data['avatar'].'" alt="'.$data['pseudo'].'">';
                echo'<br />                  
					Messages: '.$data['nbpost'].'<br />';
				
				if($rang == 2 && $ipaff) 
					echo 'ip : '.professordekodor($data['ip']);

				echo'
                </td>
              <td valign="top" class="'.$color.'" style="padding:10px">
			  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-bottom:5px">
                <tr>
                  <td class="posthaut">Post&eacute; le: ';
				  if($data['idsa'] == 0) echo datefct($data['tmpsave'],$gmt); else echo datefct($data['tmppost'],$gmt);
				  echo' || Sujet du message: '.sit(htmlentities(($data['titre']))).' </td>
                  <td width="200" align="right" class="posthaut">';

				  echo'</td>
                </tr>
              </table>
			  
                '.bbcode(nl2br(htmlentities((substr($data['texte'],0,401)))));
				if(strlen($data['texte']) > 400) echo '...';
				if(empty($data['idsa'])) 
					echo '
					<p class="edit">
						<a href="index.php?page=post&amp;ids='.$data['id2'].'">
							[ Atteindre cette discussion ]
						</a>
					</p>';
				else 
					echo '
					<p class="edit">
						<a href="index.php?page=post&amp;ids='.$data['idsa'].'">
							[ Atteindre cette discussion ]
						</a>
					</p>';
				if(!empty($data['edit'])) 
					echo'
					<p class="edit">
						[Ce message a &eacute;t&eacute; &eacute;dit&eacute; par son auteur pour la derni&egrave;re fois le '.datefct($data['edit'],$gmt).']</p> ';

				if($bbcodesign && !empty($data['sign']) && $data['signaff'] == 1 && $autorisationsign) 
					echo '<br />________________<br />'.bbcode(nl2br(htmlentities($data['sign'])));
				elseif(!empty($data['sign']) && $data['signaff'] == 1 && $autorisationsign) 
					echo '<br />________________<br />'.nl2br(htmlentities($data['sign']));
				
				
				echo'</td>
              </tr>
              <tr>
				<td class="cadre_clair" style="padding:6px">
					<a href="#">Revenir en haut</a>
				</td>
				<td valign="top" class="'.$color.'" style="padding:8px">
					<a href="index.php?page=affprofil&id='.$data['id'].'">
						<img src="'.$design.'actions/fr/profil.gif" alt="Voir le Profil" />
					</a>';
			  
			  if($rang != -1 && $data['id'] != $idmembre) 
				echo' 
				<a href="index.php?page=mpsend&id='.$data['id'].'">
					<img src="'.$design.'actions/fr/mp.gif" alt="Envoyer un message priv�" />
				</a>';
			  if($data['www'] != "" && $data['www'] != "http://") 
				echo '
				<a href="'.htmlentities($data['www']).'">
					<img src="'.$design.'actions/fr/www.gif" alt="Voir le Site Web" />
				</a> ';
			  
			  echo'</td>
            </tr>
			<tr>
				<td colspan="2" class="espace">
					<img src="img/space.gif" width="1" height="1">
				</td>
			</tr>';

				 }
				 /*
				  echo '
		</table>
		
		<div class="bottom_infos">
					<a href="index.php">Navigation :</a> ';
			if($p > 0) 
				echo '<a href="index.php?page=search&amp;p='.($p-1).'&amp;clearsearch">Page pr�c�dente</a>';
			if($p > 0 && $req->rowCount() == 15) 
				echo ' || ';
			if($req->rowCount() == 15 ) 
				echo '<a href="index.php?page=search&amp;p='.($p+1).'&amp;clearsearch">Page Suivante</a>';
	echo '
					</a>
		</div>';
		*/
		echo '</table><table class="texte_base_gras" width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="cadre1_bas" style="padding:10px"><a href="index.php">Navigation : ';
	
	if($p>0) echo '<a href="index.php?page=search&amp;p='.($p-1).'&amp;clearsearch">Page pr�c�dente</a>';
	if($p>0 && $req->rowCount() == 15) echo ' || ';
	if($req->rowCount() == 15 ) echo '<a href="index.php?page=search&amp;p='.($p+1).'&amp;clearsearch">Page Suivante</a>';
	
	echo '</a>
    </td>
</tr>
</table>';
				 
		}	// fin si r�sultats
}

?>
