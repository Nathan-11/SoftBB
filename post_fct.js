function decision(message, url){
	if(confirm(message)) location.href = url;
}

/** 
 * Insère un émoticones dans la zone de texte désirée.
 * Provient en partie de editeurjavascript.com
 * Arguments :
 *    - (String)  Text à insérer (émoticone)
 *    - (Strind)  Id de l'aire de texte
 */
function emoticon(text, id)
{
	var txtarea = document.getElementById(id);
	text = ' ' + text + ' ';	// ajouts espaces
	
	if (txtarea.createTextRange && txtarea.caretPos){
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
		txtarea.focus();
	}
	else{
		txtarea.value  += text;
		txtarea.focus();
	}
}


/**
 * Place le bbccode dans la zone sélectionnée.
 * Arguments : 
 *   - (String) : Chaîne de début (ex : [x])
 *   - (String) : Chaîne de fin   (ex : [\x])
 *   - (String) : Identifiant de l'aire de texte à modifier
 */
function put_bbcode(bbdebut, bbfin, idChamp)
{
	var input = document.getElementById(idChamp);
	input.focus();
	/* pour IE */
	if(typeof document.selection != 'undefined')
	{
		var range = document.selection.createRange();
		var insText = range.text;
		range.text = bbdebut + insText + bbfin;
		range = document.selection.createRange();
		if (insText.length == 0)
			range.move('character', -bbfin.length);
		else
			range.moveStart('character', bbdebut.length + insText.length + bbfin.length);
		range.select();
	}
	/* pour les navigateurs plus récents que IE comme Firefox... */
	else if(typeof input.selectionStart != 'undefined')
	{
		var start = input.selectionStart;
		var end = input.selectionEnd;
		var insText = input.value.substring(start, end);
		input.value = input.value.substr(0, start) + bbdebut + insText + bbfin + input.value.substr(end);
		var pos;
		if (insText.length == 0)
			pos = start + bbdebut.length;
		else
			pos = start + bbdebut.length + insText.length + bbfin.length;
		input.selectionStart = pos;
		input.selectionEnd = pos;
	}
	/* pour les autres navigateurs comme Netscape... */
	else
	{
		var pos;
		var re = new RegExp('^[0-9]{0,3}$');
		while(!re.test(pos))
			pos = prompt("insertion (0.." + input.value.length + "):", "0");
		if(pos > input.value.length)
			pos = input.value.length;
		var insText = prompt("Veuillez taper le texte");
		input.value = input.value.substr(0, pos) + bbdebut + insText + bbfin + input.value.substr(pos);
	}
}

window.onload = function() {
	if(document.getElementById('bbcode_size') && document.getElementById('couleur')) {
		var size = document.getElementById('bbcode_size');
		if(size.addEventListener) {
			size.addEventListener('change', function() {
				var sizeWrite = size.value;
				put_bbcode('[size=' + sizeWrite + ']', '[/size]', textarea_id);
			}, false);
		} else {
			size.attachEvent('onchange', function() {
				var sizeWrite = size.value;
				put_bbcode('[size=' + sizeWrite + ']', '[/size]', textarea_id);
			});
		}
		var couleur = document.getElementById('couleur');
		if(couleur.addEventListener) {
			couleur.addEventListener('change', function() {
				var colorWrite = couleur.value;
				put_bbcode('[color=' + colorWrite + ']', '[/color]', textarea_id);
			}, false);
		} else {
			couleur.attachEvent('onchange', function() {
				var colorWrite = couleur.value;
				put_bbcode('[color=' + colorWrite + ']', '[/color]', textarea_id);
			});
		}
	}
}