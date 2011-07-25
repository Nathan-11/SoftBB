<?php
// Recuperation de la liste des dépots
$depot = unserialize(file_get_contents('../depot.conf'));
// Recuperation de la version de softBB installer
require_once '../version.php';
$versionSoftbb = $versionWS;
// On boucle pour tester les mises à jour sur chaque dépot
$result = 0;
$name = '';
$result = file_get_contents($depot['url'] . 'updateVersion.php?v=' . $versionSoftbb);
$result = unserialize($result);
if(is_array($result) && $result[0]) {
	$name = $depot['name'];
	$v = $result[1];
}

echo $result[0] . ';' . $name . ';' . $v;
?>
