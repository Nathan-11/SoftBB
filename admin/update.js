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

function update_part(version, onFinish) {
	var xhr = getXMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			document.getElementById('nb').innerHTML = parseInt(document.getElementById('nb').innerHTML)+1;
			onFinish();
		}
	}
	xhr.open("GET", "admin/launch_update/update.php?v=" + version, false);
	xhr.send(null);
}

function clean() {
	var xhr = getXMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			document.getElementById('update_img').src = 'design/update_finish.png';
			document.getElementById('maj').style.display = 'none';
			document.getElementById('maj_finish').style.display = 'block';
		}
	}
	xhr.open("GET", "admin/launch_update/clean.php", false);
	xhr.send(null);
}
