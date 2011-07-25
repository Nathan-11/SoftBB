<?php 

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de listing des forums (affichage)
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

echo '<h1>'.$lg_conff['cf0'].'</h1>
<table width="700" cellspacing="0" cellpadding="0">';

$sql = 'SELECT * FROM '.$prefixtable.'forum ORDER BY position';
$req = $db->query($sql);
$db = null;
while($data = $req->fetch())
{
	if($data['fatt'] == 0)
	{
		echo '
	<tr>
		<td height="29" colspan="3" class="titreforumdeb" style="padding-left:8px">
			<a name="'.$data['id'].'"></a>'.htmlentities($data['nom']).'
		</td>
		<td width="90" class="titreforum"><div align="center">'.$lg_conff['cf1'].'</div></td>
		<td width="90" class="titreforum"><div align="center">'.$lg_conff['cf2'].'</div></td>
		<td width="90" class="titreforum"><div align="center">'.$lg_conff['cf3'].'</div></td>
	</tr>
		';
		$posrelat = 1;
		$posmax = $data['nbsf'];
		$ida = $data['id'];
	}
	else
	{
		echo'
	<tr>
		<td class="cadredeb" style="padding:10px">
			<a name="'.$data['id'].'"></a><b>'.htmlentities($data['nom']).'</b><br />
			'.htmlentities($data['description']).'
		</td>
		<td width="45" align="center" class="cadre">'.$data['nbsujet'].'</td>
		<td width="45" align="center" class="cadre">'.$data['nbmessage'].'</td>
		<td align="center" class="cadre">
			<a href="#" onclick="decision(\''.$lg_conff['cf4'].'\',\'./index.php?page=del&id='.$data['id'].'\')">'.$lg_conff['cf4a'].'</a>, 
			<a href="#" onclick="decision(\''.$lg_conff['cf5'].'\',\'./index.php?page=vidage&id='.$data['id'].'\')">'.$lg_conff['cf5a'].'</a>, 
			<a href="index.php?page=form_edit_forum&id='.$data['id'].'">'.$lg_conff['cf5b'].'</a>
		</td>
		<td align="center" class="cadre">
			<a href="index.php?page=possforum&id='.$data['id'].'&act=up">'.$lg_conff['cf6'].'</a> 
			<a href="index.php?page=possforum&id='.$data['id'].'&act=down">'.$lg_conff['cf7'].'</a>
		</td>
		<td align="center" class="cadre">
			<a href="index.php?page=form_edit_aut&id='.$data['id'].'">'.$lg_conff['cf8'].'</a>
		</td>
	</tr>
		';
		$posrelat++;
	}
	if($posrelat-1 == $posmax || $posmax == 0)
	{
		echo '
	<tr>
		<td height="29" colspan="6" class="cadredeb" style="padding:5px">
			<a href="#" onclick="decision(\''.$lg_conff['cf9'].'\',\'index.php?page=delforum&id='.$ida.'\')">'.$lg_conff['cf10a'].'</a>
			 - <a href="index.php?page=invforum&id='.$ida.'&act=up">'.$lg_conff['cf10'].'</a>
			 - <a href="index.php?page=invforum&id='.$ida.'&act=down">'.$lg_conff['cf11'].'</a>
			 - <a href="index.php?page=form_edit_cat&id='.$ida.'">'.$lg_conff['cf12'].'</a>
			 - <a href="index.php?page=form_add_forum&ida='.$ida.'">'.$lg_conff['cf13'].'</a>
		</td>
	</tr>
	<tr style="height:20px">
		<td>&nbsp;</td>
	</tr>
		';
	}
}
echo '
</table>
<table class="texte_base_gras" width="700" cellspacing="0" cellpadding="0">
	<tr>
		<td height="29" class="titreforumdeb" style="padding-left:8px">'.$lg_conff['cf14'].'</td>
	</tr>
	<tr>
		<td class="cadredeb" style="padding:12px">
			<form action="index.php?page=adforum" method="post" name="adcat">
				'.$lg_conff['cf15'].'
				<input name="nom" class="bouton" type="text" value="'.$lg_conff['cf16'].'" maxlength="128" size="35" onFocus="if(value == \''.$lg_conff['cf16'].'\') value=\'\';" />
				<input type="submit" class="bouton" name="Submit" value="'.$lg_conff['cf17'].'" />
			</form>
		</td>
	</tr>
</table>
';

$req->closeCursor();
?>
