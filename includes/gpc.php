<?php

$quotes_gpc = get_magic_quotes_gpc();

function add_gpc ($chaine) {
	global $quotes_gpc;
	if($quotes_gpc) return $chaine;
	else return addslashes($chaine);
}

function strip_gpc ($chaine) {
	global $quotes_gpc;
	if($quotes_gpc) return stripslashes($chaine);
	else return $chaine;
}

?>
