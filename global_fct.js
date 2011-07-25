<!--
function MM_openBrWindow(theURL,winName,features)
{ // v2.0
	window.open(theURL,winName,features);
}
function decision(message, url)
{
	if(confirm(message)) location.href = url;
}
//-->
// Pompé sur le site du zéro
function switch_spoiler(div2)
{
	var divs = div2.getElementsByTagName('div');
	var div3 = divs[0];
	if (div3.style.visibility == 'visible')
		div3.style.visibility = 'hidden';
	else
		div3.style.visibility = 'visible';
	return true;
}
