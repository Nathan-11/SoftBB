<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'ajout d'un vote + notification
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
 
if(!defined('IN_SOFTBB')) exit('Not in SoftBB');
if(!isset($_GET['ids']) || !is_numeric($_GET['ids'])){ include('./includes/notifs.php'); exit('a'); }
if(!isset($_SESSION['idlog'])) exit('Erreur');

$sql = 'SELECT * FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']).' AND `lock` < 1';
$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
$requse++;
$numreq = $req->rowCount();
if(empty($numreq)) exit('Erreur');
$dat = $req->fetch();
$idsfa = $dat['idsfa'];
$idfa = $dat['idfa'];
$titresujet = $dat['titre'];
$editlock = $dat['lock'];

$sql = 'SELECT groupe,nom,m,mg FROM '.$prefixtable.'forum WHERE id = '.intval($idsfa);
$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
$requse++;
$data3 = $req->fetch();
$nomsujetforum = $data3['nom'];
if($data3['groupe'] == -4   && $rang != 1 && $rang != 2 && $data3['m'] != 4 && $data3['m'] != 3) exit('1');

if($data3['groupe'] == -1  && $rang != 1 && $rang != 2 || $data3['groupe'] == -3 && $rang != 1 && $rang != 2)
	exit('3');

if($data3['groupe'] != 0)
{
	$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($data3['groupe']).' AND idm = '.intval($idmembre);
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));
	$requse++;
	$autorisation = $req->rowCount();
	$d = $req->fetch();
	////////////////////////////////////////
	if($autorisation == 0 && $rang != 1 && $rang != 2) { if($data3['m'] != 3 && $data3['m'] != 4) exit('2'); } 
	if($autorisation == 1 && $d['stat'] == 0 && $rang != 1 && $rang != 2) { if($data3['mg'] != 3 && $data3['mg'] != 4) exit(3); }  else $rang = 1;

	$data2 = $req->fetch();
	$statgroupe = $data2['stat'];
	if($statgroupe == 1) $rang = 1;
}

if($editlock == -1  && $rang != 1 && $rang != 2 || $editlock == -2 && $rang != 1 && $rang != 2) exit('4');

$sql = 'SELECT idvoteur FROM '.$prefixtable.'voter WHERE idpost = '.intval($_GET['ids']).' AND idvoteur = '.$_SESSION['idlog'];
$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));  $requse++;
$datas = $req->fetch();

$sql = 'SELECT tmpvote FROM '.$prefixtable.'sondage WHERE idpost = '.intval($_GET['ids']);
$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo()));  $requse++;
$datas2 = $req->fetch();  $requse++;
if(!empty($datas['idvoteur']) || ($datas2['tmpvote'] < time() && $datas2['tmpvote'] != 0))
	include('./includes/notifs.php');

elseif(empty($_POST['id_option']))
{
	display_error($l_voteadd['va1'], '
		<p>'.$l_voteadd['va2'].'</p>
		<p>
			<a href="'.((!$url_rewriting) ? 'index.php?page=post&amp;ids='.$_GET['ids'] : 'post-'.$_GET['ids'].'.html').'">'.$l_voteadd['va3'].'</a>
		</p>');
}
else
{
	$sql = 'INSERT INTO '.$prefixtable.'voter ( `idvoteur` , `idpost` , `fofo` , `sfofo` ) VALUES ('.intval($_SESSION['idlog']).','.intval($_GET['ids']).','.$idfa.','.$idsfa.')';
    $req = $bdd->query($sql) or die('Erreur SQL !'.$bdd->print_r($bdd->errorInfo())); $requse++;
	
	$sql = 'UPDATE '.$prefixtable.'sondage SET nbvote = nbvote+1 WHERE idpost = '.intval($_GET['ids']).' AND nboption > 0 OR idpost = '.intval($_GET['ids']).' AND idsond = '.intval($_POST['id_option']);
	$req = $bdd->query($sql) or die('Erreur SQL !<br />'.$bdd->print_r($bdd->errorInfo())); $requse++;
	display_error($l_voteadd['va4'], '
		<p>'.$l_voteadd['va5'].'</p>
		<p>
			<a href="'.((!$url_rewriting) ? 'index.php?page=post&amp;ids='.$_GET['ids'] : 'post-'.$_GET['ids'].'.html').'">
				'.$l_voteadd['va6'].'
			</a>
		</p>');
}			
?>
