function SendPOST()
{
var data = document.form.content.value;
var title = document.form.title.value;
ajaxSendPOST('/plugin/autokeys','q='+encodeURI(title)+' '+encodeURI(data), SendCallback);
}
function SendCallback(answer) { var ans = eval( '(' + answer + ')' );  
if (ans.res=='error') { alert('Пусто?');return; } 
if (ans.res!='ok') { alert('Незнаю что сказать');return; } 
keys_out = ans.x1;
keys_out = keys_out.replace(/, /g,'</span> <span>');
document.getElementById('autokey_out').innerHTML = keys_out;
key_span = document.getElementById('autokey_out');
key_click = key_span.getElementsByTagName('span');
for(i in key_click)
{
key_click[i].onclick = function(){
if(document.form.keywords.value == '')
{
document.form.keywords.value = this.innerHTML;
this.parentNode.removeChild(this);
document.getElementById('autokeys_true').checked = false;
}
else
{
document.form.keywords.value += ', '+this.innerHTML;
this.parentNode.removeChild(this)
}
}
}
}
function ajaxSendPOST(xmlpage,data,callback)
{
var xmlh = null;
if(window.XMLHttpRequest)
xmlh = new XMLHttpRequest();
else
try
{ xmlh = new ActiveXObject('Msxml2.XMLHTTP'); }
catch(ex) { xmlh = new ActiveXObject('Microsoft.XMLHTTP'); }
if(xmlh)
{
xmlh.open('post', xmlpage, true);
xmlh.onreadystatechange = function(x) { if(xmlh.readyState==4) callback(xmlh.responseText); }
xmlh.setRequestHeader("Accept-Charset", "windows-1251");
xmlh.setRequestHeader("Accept-Language","ru, en");
xmlh.setRequestHeader("Connection", "close");
xmlh.setRequestHeader("Content-length", data.length); // Длинна отправляемых данных
xmlh.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlh.send(data); // Именно здесь отправляются данные
}
}
//aadata = document.form.content.value;
//aadata.substr(0, 200);
