<?php
require_once 'version.php';
echo '<h1>'.$lang['title'].'</h1>' . $lang['desc'] . '<br />';
echo '<b>'.$lang['version'].'</b> ' . $version . '<br />';
echo '<span><table><tr><td><img src="design/loader.gif" id="image_update"/></td><td id="update_search">'.$lang['search_update'].'</td></span></tr></table><br />';
echo '<span id="update" style="display:none;"><a href="?page=admin_update">'.$lang['go_update'].'</a></span><br />';
?>
<script type="text/javascript">
function getXMLHttpRequest() {
	var xhr = null;
	
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest(); 
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	
	return xhr;
}

window.onload = function() {
	// On lance la recherche de mise à jour
	var updateSearch = document.getElementById("update_search");
	var xhr = getXMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			var result = xhr.responseText.split(';');
			if(result[0] == '1') {
				updateSearch.innerHTML = "<?php echo $lang['update']; ?>" + '"' + result[1] + '"' + "<?php echo $lang['update_2']; ?>"  + result[2];
				document.getElementById('image_update').src = 'design/update.png'
				document.getElementById('update').style.display = 'block';  
			} else {
				updateSearch.innerHTML = '<?php echo $lang['noupdate']; ?>';
				document.getElementById('image_update').src = 'design/no_update.png'
			}
		}
	};
	xhr.open("GET", "admin/getUpdate.php?version=" + encodeURIComponent("<?php echo $version; ?>"), true);
	xhr.send(null);
}
</script>
