<?php
// On reverifie les mises à jour
// Recuperation de la liste des dépots
$depot = unserialize(file_get_contents('depot.conf'));
// Recuperation de la version de softBB installer
require_once 'version.php';
$versionSoftbb = $versionWS;
// On boucle pour tester les mises à jour sur chaque dépot
$result = 0;
$name = '';
$haveUpdate = false;
$result = file_get_contents($depot['url'] . 'updateVersion.php?v=' . $versionSoftbb);
$result = unserialize($result);
if(is_array($result) && $result[0]) {
	$depot_url = $depot['url'];
	$depot_name = $depot['name'];
	$v = $result[1];
	$haveUpdate = true;
}
if($haveUpdate && isset($_GET['maj']) && isset($_SESSION['maj'])) {
	$update_list = unserialize(file_get_contents($depot_url . 'nbre_update.php?v=' . $versionSoftbb));
	echo '<h1>' . $lang['title_1'] . $depot_name . $lang['title_2'] . $versionSoftbb . $lang['title_3'] . $v . '</h1><br />';
	echo '<b>' . $lang['nbre_update'] . '</b>' . count($update_list);
	@mkdir('admin/update', 0777);
	foreach($update_list as $update) {
		file_put_contents('admin/update/' . $update . '.xml', file_get_contents($depot_url . 'update/' . $update . '.xml'));
	}
	file_put_contents('admin/update/list.txt', serialize($update_list));
	file_put_contents('admin/update/depot.txt', $depot_url);
	echo '<center><img src="design/loader-2.gif" id="update_img" /><br /><span id="maj">'.$lang['update_current'].' <span id="nb">1</span>/'.count($update_list).'</span>
	<span id="maj_finish" style="display:none;">'.$lang['update_finish'].'</span></center>';
	echo '<script type="text/javascript" src="admin/update.js"></script>';
	echo '<script type="text/javascript">';
	if(count($update_list) == 1) {
		echo 'update_part(\''.$update_list[0].'\', function() {  });';//clean();
	} else {
		$js = 'update_part(\''.$update_list[0].'\', function() {';
		for($i = 1, $count = count($update_list)-1; $i <= $count; $i++) {
			$js .= 'update_part(\''.$update_list[$i].'\', function() {';
		}
		//$js .= 'clean();';
		for($i = 1, $count = count($update_list)-1; $i <= $count; $i++) {
			$js .= '});';
		}
		echo $js . '});';
	}
	echo '</script>';
} elseif(!$haveUpdate) {
	echo '<h1>'.$lang['no_update'].'</h1>';
} else {
	$update_list = unserialize(file_get_contents($depot_url . 'nbre_update.php?v=' . $versionSoftbb));
	echo '<h1>' . $lang['info_update'] . '</h1>';
	echo '<b>' . $lang['update_list'] . '</b><ul>';
	for($i = 0, $count = count($update_list)-1; $i <= $count; $i++) {
		echo '<li>' . $update_list[$i] . '</li>';
	}
	echo '</ul>';
	$updateChangelog = '';
	for($i = 0, $count = count($update_list)-1; $i <= $count; $i++) {
		$updateChangelog .= file_get_contents($depot_url . 'update/' . $update_list[$i] . '/changelog.txt') . '<br /><br />';
	}
	echo '<h3>Changelog</h3>';
	$_SESSION['maj'] = true;
	echo '<div style="margin-left: 100px; width: 600px; height: 100; border: 1px solid black; overflow: scroll;">'.nl2br($updateChangelog).'</div><center><a href="?page=admin_update&&maj=1">'.$lang['launch_update'].'</a>';
}
?>
