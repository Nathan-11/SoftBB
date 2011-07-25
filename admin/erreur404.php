<?php

$root = $_SERVER['PHP_SELF'];
$root = str_replace("erreur404.php","",$root);
$sousdossier = $root;
$sousdossier = str_replace("/", "\/", $sousdossier);

if (preg_match('/^'.$sousdossier.'(.*).html$/', $_SERVER['REDIRECT_URL'], $match)) { 
  header("Status: 200 OK", false, 200);   // modification du code retour 
  $_GET['page'] = $match[1];  // alimentation du paramètre GET 
  include('index.php'); 
}
else
	include('index.php');
