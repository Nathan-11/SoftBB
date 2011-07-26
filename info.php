<?php
define('IN_SOFTBB',true);

session_start();
if(isset($_SESSION['idlog']) && 
	( isset($_SESSION['ip_anti_vol']) && $_SESSION['ip_anti_vol'] != $_SERVER['REMOTE_ADDR'] ) )
	$_SESSION = array();

// addslashes et stripslashes pour les magic_quotes
include_once('includes/gpc.php');


// Données utiles
include_once('info_bdd.php');
include_once('info_emote.php');
include_once('info_options.php');
include_once('info_options_rangs.php');
include_once('langue/'.$languedef.'/_global.php');
include_once('fonctions.php');

// variable du dossier de design si non définie
if(!isset($design) || !file_exists($design))
	$design = 'design/smoothGreen/';

// Fonction BBcode, merci à Skybattle et à Loufoque
$bbcode = array (	
	'£\\[b\\](.+)\\[/b\\]£isU' ,
	'£\\[i\\](.+)\\[/i\\]£isU' ,
	'£\\[u\\](.+)\\[/u\\]£isU' ,
	'£\\[s\\](.+)\\[/s\\]£isU' ,
	'£\\[color=(red|darkred|blue|darkblue|green|darkgreen|yellow|gold|black|white|grey|darkgrey|orange|darkorange|brown|olive|cyan|indigo|purple|violet|#[\\w\\d]{6};)\\](.+)\\[/color\\]£isU' ,
	'£\\[color=#([a-zA-Z0-9]{6}|[a-zA-Z0-9]{3})\\](.+)\\[/color\\]£isU' ,
	'£\\[size=(xx-small|x-small|small|medium|large|x-large|xx-large)\\](.+)\\[/size\\]£isU' ,
	'/\\[size=(1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|)\\](.*?)\\[\\/size\\]/si',
	'/\\[img\\](.+?)\\[\\/img\\]/si',
	'£\\[url=(?:http://)?([\\w\\d_/?&%#=~\\.;-]+)\\](.+)\\[/url\\]£iU' ,
	'£\\[url=(?:http://)?([\\w\\d_/?&%#=~\\.;-]+:[0-9]+)\\](.+)\\[/url\\]£iU' ,
	'£\\[url\\](?:http://)?([\\w\\d_/?&%#=~\\.;-]+)\\[/url\\]£iU' ,
	'£\\[url\\](?:http://)?([\\w\\d_/?&%#=~\\.;-]+:[0-9]+)\\[/url\\]£iU' ,
	'£(?<![\\w\\d_/?&%#=~\\.;->"])(?:(http://)|(w{3}\\d?\\.))([\\w\\d_/?&%#=~\\.;-]+)£i' ,
	'£\\[url=(ftp://[\\w\\d_/?&%#=~\\.;-]+)\\](.+)\\[/url\\]£iU' ,
	'£\\[url\\](ftp://[\\w\\d_/?&%#=~\\.;-]+)\\[/url\\]£iU' ,
	'£(?<![\\w\\d_/?&%#=~\\.;->"])(ftp://[\\w\\d_/?&%#=~\\.;-]+)£i' ,
	'£\\[email\\]([\\w\\d_\\.-]+@[\\w\\d_\\.-]+\\.[\\w\\d]{2,5})\\[/email\\]£iU' ,
	'£\\[email=([\\w\\d_\\.-]+@[\\w\\d_\\.-]+\\.[\\w\\d]{2,5})\\](.+)\\[/email\\]£iU',
	'£\\[spoil\\](.+)\\[/spoil\\]£isU' ,
	'£\\[float=left\\](.+)\\[/float\\]£isU' ,
	'£\\[float=right\\](.+)\\[/float\\]£isU', 
	'£\\[textalign=(left|right|justify|center)\\](.+)\\[/textalign\\]£isU',
	'£\\[list\\](.+)\\[/list\\]£isU',
	'£\\[puce\\](.+)\\[/puce\\]£isU',
	'`\\[quote\\](.+)\\[/quote\\]`isU',
	'`\\[quote=(.+)\\](.+)\\[/quote\\]`isU',
	'`\\[sub\\](.+)\\[/sub\\]`isU',
	'`\\[sup\\](.+)\\[/sup\\]`isU',
	'£\\[shell\\](.+)\\[/shell\\]£isU'
);
			
$xhtml = array (	
	'<strong>$1</strong>' ,
	'<em>$1</em>' ,
	'<ins>$1</ins>' ,
	'<s>$1</s>' ,
	'<span style="color: $1">$2</span>' ,
	'<span style="color: #$1">$2</span>' ,
	'<span style="font-size: $1">$2</span>' ,
	'<span style="font-size: $1px">$2</span>',
	'<img class="bbcode_img" src="$1" alt="Image" />' ,
	'<a href="http://$1">$2</a>' ,
	'<a href="http://$1">$2</a>' ,
	'<a href="http://$1">http://$1</a>' ,
	'<a href="http://$1">http://$1</a>' ,
	'<a href="http://$2$3">$1$2$3</a>' ,
	'<a href="$1">$2</a>' ,
	'<a href="$1">$1</a>' ,
	'<a href="$1">$1</a>' ,
	'<a href="mailto:$1">$1</a>' ,
	'<a href="mailto:$1">$2</a>',
	'<span class="spoilertexte">Texte caché : cliquez sur le cadre pour l\'afficher</span><div class="spoiler" class="bbcode_spoil" onclick="switch_spoiler(this)"><div style="visibility: hidden;" class="spoiler3">$1</div></div>',
	'<div style="float:left; padding:3px;">$1</div>',
	'<div style="float:right; padding:3px;">$1</div>',
	'<p style="text-align:$1;">$2</p>',
	'<ul class="bbcode_list">$1</ul>',
	'<li class="bbcode_puce">$1</li>',
	'<div class="bbcode_quote_global"><span class="bbcode_quote_titre">Citation</span><div class="bbcode_quote">$1</div></div>',
	'<div class="bbcode_quote_global"><span class="bbcode_quote_titre">Citation de $1</span><div class="bbcode_quote">$2</div></div>',
	'<sub>$1</sub>' ,
	'<sup>$1</sup>' ,
	'<div class="bbcode_shell_titre">Code console : </div><div class="bbcode_shell"><pre>$1</pre></div>'
);

function strbbcode1 ($matches) {
	global $bbcode , $xhtml , $emoticonc , $emoticonv ,$emoticonnb;
	for($em=0;$em<$emoticonnb;$em++)	$matches[1] = str_replace($emoticonc[$em],'<img src="'.$emoticonv[$em].'" border="0" alt="" />',$matches[1]);
	return preg_replace ($bbcode , $xhtml , $matches[1]).'<h6 class="bbcode_code_titre">Code :</h6><div class="bbcode_code"><pre>';
}
function strbbcode2 ($matches) {
	global $bbcode , $xhtml , $emoticonc , $emoticonv ,$emoticonnb;
	for($em=0;$em<$emoticonnb;$em++)	$matches[1] = str_replace($emoticonc[$em],'<img src="'.$emoticonv[$em].'" border="0" alt="" />',$matches[1]);
	return '</pre></div>'.preg_replace ($bbcode , $xhtml , $matches[1]).'<h6 class="bbcode_code_titre">Code :</h6><div class="bbcode_code"><pre>';

}
function strbbcode3 ($matches) {
	global $bbcode , $xhtml , $emoticonc , $emoticonv ,$emoticonnb;
	for($em=0;$em<$emoticonnb;$em++)	$matches[1] = str_replace($emoticonc[$em],'<img src="'.$emoticonv[$em].'" border="0" alt="" />',$matches[1]);
	return '</pre></div>'.preg_replace ($bbcode , $xhtml , $matches[1]);
}

function decodequote ($matches) {
	$matches[1] = str_replace('[/quote','[@/quote',$matches[1]);
	return '[code]'.str_replace('[quote','[@quote',$matches[1]).'[/code]';
}
function recodequote ($matches) {
	$matches[1] = str_replace('[@/quote','[/quote',$matches[1]);
	return '[code]'.str_replace('[@quote','[quote',$matches[1]).'[/code]';
}

function quote($matches) {
	if( preg_match('£\\[quote(?:="?(?:[^"]+)"?)?\\](.+)\\[/quote\\]£is', $matches[2])) 
		return '<div class="bbcode_quote_global"><span class="bbcode_quote_titre">'. ((!empty($matches[1])) ? $matches[1].' a dit :' : 'Citation :').'</span><div class="bbcode_quote">'.preg_replace_callback('£\\[quote(?:="([^"]+)")?\\]((?:(?:(?!\\[/quote]).)*?(?R).*?)+|.+?)\\[/quote\\]£is', 'quote', $matches[2]).'</div></div>';
    else 
    	return '<div class="bbcode_quote_global"><span class="bbcode_quote_titre">'. ((!empty($matches[1])) ? $matches[1].' a dit :' : 'Citation :') . '</span><div class="bbcode_quote">'.$matches[2].'</div></div>';
}

function bbcode ($chaine) {
	if( (substr_count($chaine,'[code]') > 0 &&  substr_count($chaine,'[/code]')) > 0) {
		$chaine = preg_replace_callback ('£\[code\](.*)\[/code\]£isU' , 'decodequote' , $chaine);		
		//Fonction chasseuse de QUOTE imbriquées par LOUFOQUE															
		$chaine = preg_replace_callback('£\[quote(?:="([^"]+)")?\]((?:(?:(?!\[/quote]).)*?(?R).*?)+|.+?)\[/quote\]£is', 'quote', $chaine);
		$chaine = preg_replace_callback ('£\[code\](.*)\[/code\]£isU' , 'recodequote' , $chaine);										
		$chaine = preg_replace_callback ('£^(.*)\[code\]£isU' , 'strbbcode1' , $chaine);																	
		$chaine = preg_replace_callback ('£\[/code\](.*)\[code\]£isU' , 'strbbcode2' , $chaine);																	
		$chaine = preg_replace_callback ('£\[/code\](.*)$£isU' , 'strbbcode3' , $chaine);					
	}
	
	else {
		global $bbcode , $xhtml , $emoticonc , $emoticonv ,$emoticonnb;
		$chaine = preg_replace_callback('£\[quote(?:="([^"]+)")?\]((?:(?:(?!\[/quote]).)*?(?R).*?)+|.+?)\[/quote\]£is', 'quote', $chaine);
		for($em=0;$em<$emoticonnb;$em++) $chaine = str_replace($emoticonc[$em],'<img src="'.$emoticonv[$em].'" border="0" alt="" />',$chaine);
		$chaine = preg_replace ($bbcode , $xhtml , $chaine);
	}		

	return ($chaine);
} 

// 
function sit ($chaine){
	$chaine = str_replace('<','&lt;',$chaine);							
	$chaine = str_replace('>','&gt;',$chaine);			
	$chaine = str_replace("&amp;",'&',$chaine);
	return $chaine;
}

// Retourne une date du type
function datefct ($temps,$gmt){
	$temps += ($gmt*3600)-date("Z");
	$date = date("d",$temps);
	
	$tablebbcoder = array(
	'01' => 'Jan',
	'02' => 'Fév',
	'03' => 'Mars',
	'04' => 'Avril',
	'05' => 'Mai',
	'06' => 'Juin',
	'07' => 'Juil',
	'08' => 'Ao&ucirc;t',
	'09' => 'Sept',
	'10' => 'Oct',
	'11' => 'Nov',
	'12' => 'Déc');
	
	$date .=  ' '.$tablebbcoder[date("m",$temps)].' ';
	$date .= date("Y",$temps); 
	$date .= ' '.date("H:i",$temps);
	return ($date); 
} 

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// /!\ Evitez de toucher au code çi-dessous sauf si vous êtes sûr des mesures de sécurité que celà implique /!\
// Tableau des pages pouvant être inclues
$inclauto = array('delsonde.php','voteadd.php','notifs.php','indexforum.php','type.php','mp.php','profil.php','membre.php','affprofil.php','affgroupe.php','connexion.php','erreurgroup.php','faq.php','forgot.php','forum.php','groupe.php','mpread.php','mpseek.php','mpsend.php','post.php','postadd.php','profil.php','reg.php','lockforum.php','search.php','delvalid.php', 'lgout.php');

// Pour que l'ip prenne moins de place dans la bdd
function professordekodor($ip) {
	$ipe = explode('$',$ip);$j = count($ipe);$ip = "";for($i=0;$i<$j;$i++){ $ip .= hexdec($ipe[$i]);if($i != $j-1) $ip .='.';}return ' '.$ip;
}

// Renvoie  vrai si l'email en argument est syntaxiquement correct
function isMailSyntaxCorrect($mail){
	return !preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $mail);
}

// Configuration non effectuée -> installation
if(!isset($host)) 
{
	header('Location: install/');
	exit();
}
if($gzip)
	ob_start("ob_gzhandler");
	
// variable contenant le nombre de requêtes, +1 à chaques requètes
$requse = 0;

// variables essentielles du membre
$pseudo = "";
$rang = -1;
$idmembre = -1;		// -1 = visiteur
$gmt = 0;
$he = 0;

// première page visitée
if(!isset($_SESSION['lastvisit'])) 
	$_SESSION['lastvisit'] = time();
$tmp = time();
$tmpmoins = $tmp-300;
$listepersonne = array();
$listerang = array();
$listeid= array();
$i = 0;
$affnon = 0;
$fait = false;	

try{	
	$bdd = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
} catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
}

// Récupération des données du membres
if(isset($_COOKIE) && !empty($_COOKIE['idlog']) && !isset($_SESSION['idlog']) && empty($_SESSION['idlog']))
{
	$sql = 'SELECT id,mdp,temps,pseudo, valid FROM '.$prefixtable.'membres WHERE id = "'.intval($_COOKIE['idlog']).'" AND mdp="'.add_gpc($_COOKIE['mdp']).'" AND valid = "1" LIMIT 0,1';
	$req = $bdd->query($sql); 
	$requse++;
	
	$sql = 'UPDATE ' . $prefixtable . 'membres SET date_login=\''.time().'\' WHERE id=\''.intval($_COOKIE['idlog']).'\'';
	$req = $bdd->query($sql);
	$requse++;

	if($req->rowCount() == 1) {
		$data = $req->fetch();
		// Si on est banni
		if($data['valid'] == 0) {
			setcookie("idlog","",time()-(365*24*3600));
			setcookie("mdp","",time()-(365*24*3600));
		} else {
			$_SESSION['pseudo'] = $data['pseudo'];
			$_SESSION['idlog'] = $data['id'];
			$_SESSION['lastvisit'] = $data['temps'];
			$_SESSION['ip_anti_vol'] = $_SERVER['REMOTE_ADDR'];
		}
	}
	else{
		setcookie("idlog","",time()-(365*24*3600));
		setcookie("mdp","",time()-(365*24*3600));
	}

}
// [8] Choix de la requete, longue ou courte si connecté ou pas
if(isset($_SESSION['idlog']) && !empty($_SESSION['idlog'])) 
	$sql = 'SELECT  a.afflist,a.pseudo,a.avatar,a.rang,b.mp,a.id,b.valid,b.tempspost,b.gmt,b.he,b.langue 
		FROM '.$prefixtable.'membres AS a 
		LEFT JOIN '.$prefixtable.'membres AS b ON b.id = "'.intval($_SESSION['idlog']).'"  
		WHERE a.temps > "'.$tmpmoins.'" AND a.co = "1" OR a.id = "'.intval($_SESSION['idlog']).'" ORDER BY pseudo';
else 
	$sql = 'SELECT afflist,pseudo,rang,id,langue FROM '.$prefixtable.'membres WHERE temps > "'.$tmpmoins.'" AND co = "1" ORDER BY pseudo ';

$req52 = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
$requse++;

// [9] Scanage des entrées,
while ($data52 = $req52->fetch()) 
{
	// [9.1] Recherche des entrées pour le membre
	if (isset($_SESSION['idlog']) && $data52['id'] == $_SESSION['idlog'])
	{
		$rang = $data52['rang'];
		$mp = $data52['mp'];
		$idmembre = $data52['id'];
		$avatar = $data52['avatar'];
		$tempspostlast = $data52['tempspost'];
		$gmt = $data52['gmt'];
		$he = $data52['he'];
		$pseudo = $data52['pseudo']; 
		$langue1 = $data52['langue']; 
	} 
	// [9.2] Mise en cache des membres logués
	if($data52['afflist'] == 1 && $afflistdelauto) $affnon++;
	else
	{
		$listepersonne [$i] = $data52['pseudo'];
		$listerang[$i] = $data52['rang'];
		$listeid[$i] = $data52['id'];
		$i++;
	}
}


	

// [11] Update du temps de dernière visite (en table)
if($pseudo != "" )
{
	$sql2 = 'UPDATE '.$prefixtable.'membres SET temps = "'.$tmp.'" WHERE id = "'.intval($_SESSION['idlog']).'"';
	$req2 = $bdd->query($sql2);
	$req2->closeCursor();
	$requse++;  
}

// [12] Ajout de 1, si on passe à l'heure d'été et si c'est activé
if($he == 1) $gmt += date("I");

// Titre des pages
$arr_page_titre = array(
	'indexforum' => 'Index',
	'affprofil' => 'Profil d\'un membre',
	'delsonde' => 'Suppression d\'un sondage',
	'voteadd' => 'Vote',
	'notifs' => 'Notification',
	'type' => 'Changement du type de message',
	'mp' => 'Messagerie privée',
	'affgroupe' => 'Groupes',
	'connexion' => 'Connexion',
	'erreurgroup' => 'Erreur',
	'faq' => 'Faq (questions/réponses)',
	'forgot' => 'Obtention d\'un nouveau mot de passe',
	'groupe' => 'Groupes',
	'mpread' => 'Lecture d\'un message privé',
	'mpseek' => 'Recherche d\'un membre',
	'mpsend' => 'Envoi d\'un message privé',
	'postadd' => 'ajout d\'un sujet/réponse',
	'profil' => 'Profil',
	'reg' => 'Enregistrement',
	'lockforum' => 'Verrouillage/Déverouillage',
	'search' => 'Recherche',
	'membre' => 'Liste des membres'
	);
	
$arr_page_titre_notif = array(
	'resynchok' => 'resynchronisation effectuée',
	'lgout' => 'Déconnexion',
	'erreur' => 'Erreur',
	'profsave' => 'Profil enregistré',
	'regok2' => 'Enregistrement validé',
	'delvalid' => 'Suppression de post',
	'delvalid2' => 'Suppression de sujet'
);

		if(!isset($_GET['page']) || !isset($arr_page_titre[$_GET['page']]) &&  $_GET['page'] != 'forum' && $_GET['page'] != 'post'){ 
			$titre =  $nomduforum;
		}
		elseif($_GET['page'] == 'forum') {
			
			try{
				$sql = 'SELECT groupe,nom,m,mg,v,nbsujet,temps FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['idf']).' AND fatt != 0';
				$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo()));
				$requse++;
				$data3 = $req->fetch();
				$titre = stripslashes(htmlentities($data3['nom'])).' - '.$nomduforum;
			} catch (Exception $e){
				 die('Erreur SQL : '.$e->getMessage());
			}
		}
		elseif($_GET['page'] == 'post') {
		
			$sql = 'SELECT p.idsa,p.tmpdernierpost,p.idsfa,p.sondage,p.titre, p.`lock`,p.tmppost,p.nbr,f.groupe,f.nom,f.m,f.v,f.mg 
					FROM '.$prefixtable.'post AS p 
					LEFT JOIN  '.$prefixtable.'forum AS f ON f.id = p.idsfa 
					WHERE id2 = '.intval($_GET['ids']);
			$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); 
			$requse++;
			
			// [3] Expulsé si pas de post pour GET['ids']
			if($req->rowCount() == 0)
				header('Location: '.((!$url_rewriting) ? 'index.php?page=notifs&aff=erreur' : 'erreur.html' ));
			
			// [4] Mise en cache de quelques données relatives à GET['ids'] 
			$data3 = $req->fetch();
			
			$titre = stripslashes(htmlentities($data3['titre'])).' - '.$nomduforum;

		
		}
		else {
			$titre = $arr_page_titre[$_GET['page']].' - '.$nomduforum;
			// pages de notifs
			if($_GET['page'] == 'notifs' && isset($_GET['aff']) && isset($arr_page_titre_notif[$_GET['aff']]))
				$titre = $arr_page_titre_notif[$_GET['aff']].' - '.$nomduforum;
			else if($_GET['page'] == 'notifs' && !isset($_GET['aff']))
				$titre = $arr_page_titre_notif['erreur'].' - '.$nomduforum;
		}
		(isset($_GET['page'])) ? $page = $_GET['page'] : $page = 'indexforum';

// Système de masquage des forums qui ne concernent pas le membre		
if($cache_forum && $page == 'indexforum') 
{
	// $fc = FORUM ou CATEGORIE
	// $ff = FORUM OU FATT
	// admin ou modo : pas de retsriction
	if($rang == 2 || $rang == 1) 
		$where = '';
	// membre, groupe
	elseif(!empty($_SESSION['idlog']))
	{
		$sql = 'SELECT f.position,f.nbsf,f.fatt,g.stat,g.id FROM '.$prefixtable.'forum AS f 
			LEFT JOIN '.$prefixtable.'groupemembre AS g ON f.groupe = g.idg AND g.idm = '.intval($_SESSION['idlog']).' 
			WHERE fatt = 0 OR (f.groupe = "-4" AND f.m = 0) OR (f.groupe > 0 AND f.m = 0 AND g.id IS NULL) 
				OR  (f.groupe > 0 AND f.mg = 0 AND g.stat < 1) OR groupe = "-3" 
			ORDER BY position';
		$req = $bdd->query($sql) or die('Erreur SQL !<br />'.print_r($bdd->errorInfo())); $requse++;
		
		$where = ' WHERE position != ';
		$pos_max = 0;
		
		while($data = $req->fetch()) { 
			$fc[$data['position']] = $data['nbsf'];
			$ff[$data['position']] = $data['fatt']; 
			if($data['position'] > $pos_max) 
				$pos_max = $data['position'];
		}
		
		for( $a=1 ; $a<=$pos_max ; $a++ ) 
		{
			if(isset($fc[$a]) && $ff[$a] == 0) 
			{
				$future_aff = false;
				for($b=0;$b<$fc[$a];$b++) 
				{
					if(isset($fc[$a+$b+1])) 
						$where .= ($a+$b+1).' AND position != ';
					else 
						$future_aff = true; 
				} 
				if(!$future_aff) 
					$where .= ($a).' AND position != ';
				$a += $fc[$a];
			}
		}
		$where .= ' 0 ';
	}
	// visiteur
	else {
		$sql = 'SELECT position,nbsf,fatt FROM '.$prefixtable.'forum 
			WHERE fatt = 0 OR (groupe = "-4" AND v = 0) OR (groupe > 0 AND v = 0) OR groupe = "-3" OR groupe = "-2" 
			ORDER BY position';
		$req = $bdd->query($sql); $requse++;
		
		$where = ' WHERE position != ';
		$pos_max = 0;
		
		// $fc : $fc[position_catégorie] = nombre de sous forums 
		// $ff : $ff[position_catégorie] = fatt (attribut 0 pour les catégories)
		while($data = $req->fetch()) { 
			$fc[$data['position']] = $data['nbsf'];
			$ff[$data['position']] = $data['fatt']; 
			if($data['position'] > $pos_max) 
				$pos_max = $data['position'];
		}
		
		for($a=1 ; $a<=$pos_max ; $a++) 
		{
			if(isset($fc[$a]) && $ff[$a] == 0) 
			{
				$future_aff = false;
				for($b=0 ; $b < $fc[$a] ; $b++) {
					if(isset($fc[ $a + $b + 1 ]))
						$where .= ($a+$b+1).' AND position != ';
					else 
						$future_aff = true; 
				} 
				if(!$future_aff) $where .= ($a).' AND position != ';
				$a += $fc[$a];
			}
		}
		$where .= ' 0 ';
	}
}


$time = date("l, j F Y [h:i a]");
if(!empty($pseudo))
	$ip = $pseudo.':'.$_SERVER['REMOTE_ADDR'];
else
	$ip = $_SERVER['REMOTE_ADDR'];

// Page 404 (url-rewriting)
if(!empty($_GET['page']) && $_SERVER['PHP_SELF']=='/erreur404.php')
	$page_regarder=$_GET['page'].'.html';
elseif(!empty($_GET['page']))
	$page_regarder='index.php?page='.$_GET['page'];
else
	$page_regarder='index.php';

?>
