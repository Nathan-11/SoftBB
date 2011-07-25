<?php
session_start();
require_once '../../info_bdd.php';
try{	
	$bdd = new PDO('mysql:host='.$host.';dbname='.$db, $user, $mdpbdd);
} catch (Exception $e){
	die('Erreur : ' . $e->getMessage());
}
$version = $_GET['v'];
$update_xml = new DomDocument();
$update_xml->load('../update/' . $version . '.xml');
$elms = $update_xml->getElementsByTagName('update');
$elm = $elms->item(0);
$child = $elm->childNodes;
foreach($child as $el) {
		if($el->nodeName == 'file') {
			$file = $el->nodeValue;
			file_put_contents('../../' . $file, file_get_contents(file_get_contents('../update/depot.txt') . 'update/' . $version . '/' . $file));
			echo 'File:' . $file;
		} elseif($el->nodeName == 'sql'){
			$query = str_replace('{prefix}', $prefixtable, $el->nodeValue);
			$bdd->query($query) or die (print_r($bdd->errorInfo()));
			echo 'SQL:' . $query;
		} elseif($el->nodeName == 'mkdir') {
			mkdir('../../'.$el->nodeValue, 0777, true);
		} elseif($el->nodeName == 'rmdir') {
			require_once 'clearDir.php';
			clearDir('../../'.$el->nodeValue);
		} elseif($el->nodeName == 'rmfile') {
			unlink('../../'.$el->nodeValue);
		}
} 

?>
