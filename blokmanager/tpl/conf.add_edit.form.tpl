[isck]<script type="text/javascript" src="{admin_url}/ckeditor/ckeditor.js"></script>
<script type="text/javascript"  src="{admin_url}/AjexFileManager/ajex.js"></script>
[/isck]
<script language="javascript" type="text/javascript">
function clx(val) {
 document.getElementById('type_menu').style.display = (val == '3')?'block':'none';
 document.getElementById('type_nomenu').style.display = (val == '0'||val == '1'||val == '2')?'block':'none';
 document.getElementById('type_datarotate').style.display = (val == '4')?'block':'none';
}
</script>

<form method="post" action="admin.php?mod=extra-config&amp;plugin=blokmanager&amp;action=[add]add_submit[/add][edit]edit_submit[/edit]">
<input type="hidden" name="id" value="[add]0[/add][edit]{id}[/edit]" />
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:name}<br /><small>{l_blokmanager:name_d}</small></td>
<td width="50%" class="contentEntry2"><select name="name">{locationlist}</select></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:description}<br /><small>{l_blokmanager:description_d}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="description"[edit] value="{description}"[/edit] /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:type}<br /><small>{l_blokmanager:type_d}</small></td>
<td width="50%" class="contentEntry2"><select id="type" name="type" onclick="clx(this.value);" onchange="clx(this.value);">{type_list}</select></td>
</tr></table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="30%" class="contentEntry1">{l_blokmanager:location}<br /><small>{l_blokmanager:location_d}</small></td>
<td width="70%" class="contentEntry2"><input type="button" class="button" value='{l_blokmanager:location_dell}' onClick="RemoveBlok();return false;" />&nbsp;
<input type="button" class="button" value='{l_blokmanager:location_add}' onClick="AddBlok();return false;" /><br />
<table id="blokup" align="left">[edit]{location_list}[/edit]</table>
</td>
</tr></table><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:state}<br /><small>{l_blokmanager:state_d}</small></td>
<td width="50%" class="contentEntry2">{state_list}</td>
</tr>
</table><br />
<fieldset id="type_datarotate">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:period}<br /><small>{l_blokmanager:period_d}</small></td>
<td width="50%" class="contentEntry2"><input type="period" size="10" name="period"[edit] value="{period}"[/edit] /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:start_viewperiod}<br /><small> {l_blokmanager:start_viewperiod_d} </small></td>
<td width="50%" class="contentEntry2"><input type="text" size="60" name="start_viewperiod"[edit]    value="{start_viewperiod}"[/edit] /></td>
</tr>
<tr>
<td width="100%" colspan="2" class="contentEntry1">{l_blokmanager:changeinfo}<br /><small>{l_blokmanager:changeinfo_d}</small></td></tr>
<tr>
<td width="100%" colspan="2" class="contentEntry2"><TEXTAREA class="editor2" id="editor2" NAME="blokchangecode" COLS="150" ROWS="20">[edit]{blokchangecode}[/edit]</TEXTAREA>[isck]
<script type="text/javascript">
var ckeditor = CKEDITOR.replace('editor2');
AjexFileManager.init({
	returnTo: 'ckeditor',
	editor: ckeditor,
	skin: 'light'
});
</script>[/isck]</td>
</tr>
</table>
</fieldset>
<fieldset id="type_menu">
<table  border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:menu}<br /><small>{l_blokmanager:choosemenu}</small></td>
<td width="50%" class="contentEntry2"><select id="menulist" name="menulist">{menu_list}</select></td>
</tr>
</table>
</fieldset>
</fieldset>
<fieldset id="type_nomenu">
<legend><b>{l_blokmanager:sched_legend}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:start_view}<br /><small>{l_blokmanager:start_view_d}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="start_view"[edit] value="{start_view}"[/edit] /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:end_view}<br /><small>{l_blokmanager:end_view_d}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="end_view"[edit] value="{end_view}"[/edit] /></td>
</tr>
</table>
[editcontent]
<legend><b>{l_blokmanager:blokcode_legend}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0" >
<tr>
<td width="100%" class="contentEntry1" align="center"><TEXTAREA class="editor1" id="editor1" NAME="blokcode" COLS="150" ROWS="30" overflow="auto">[edit]{blokcode}[/edit]</TEXTAREA>[isck]
<script type="text/javascript">
var ckeditor = CKEDITOR.replace('editor1');

AjexFileManager.init({
	returnTo: 'ckeditor',
	editor: ckeditor,
	skin: 'light'
});
</script>[/isck]
</td>
</tr>
</table>
</fieldset>
[/editcontent]
[outerblok]
<fieldset>
<legend><b>{l_blokmanager:permission}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="40%" class="contentEntry1" align="left">{l_blokmanager:permission_d}</td>
<td width="60%" class="contentEntry1" align="left"> {permlist}
</td>
</tr>
</table>
</fieldset>
<fieldset>
<legend><b>{l_blokmanager:outer_blok_legend}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="40%" class="contentEntry1" align="left">{l_blokmanager:outerblok_top}</td>
<td width="60%" class="contentEntry1" align="left"><TEXTAREA  NAME="outerblok" COLS="100" ROWS="18" overflow="auto">{outerblok}</TEXTAREA>
</td>
</tr>
</table>
</fieldset>
[/outerblok]
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="submit" value="[add]{l_blokmanager:add_submit}[/add][edit]{l_blokmanager:edit_submit}[/edit]" class="button" />
</td>
</tr>
</table>
</form>
<script type="text/javascript">
clx('{type}');
document.getElementById('type').value = '{type}';
document.getElementById('menulist').value = '{menuid}';
</script>

<script language="javascript" type="text/javascript">
function AddBlok() {
	var tbl = document.getElementById('blokup');
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1;
	var row = tbl.insertRow(lastRow);
	var cellRight = row.insertCell(0);
	cellRight.innerHTML = iteration+': ';
	cellRight = row.insertCell(1);
	cellRight.setAttribute('align', 'left');

	var el = '<select name="location[' + iteration + '][mode]" onchange="AddSubBlok(this, ' + iteration + ');"><option value=0>{l_blokmanager:around}</option><option value=1>{l_blokmanager:main}</option><option value=2>{l_blokmanager:not_main}</option><option value=3>{l_blokmanager:category}</option><option value=4>{l_blokmanager:static}</option></select>';
	cellRight.innerHTML += el;
	el = '<select name="location[' + iteration + '][view]"><option value=0>{l_blokmanager:view}</option><option value=1>{l_blokmanager:not_view}</option></select>';
	cellRight.innerHTML += el;
}
function AddSubBlok(el, iteration){
	var subel = null;
	var catsubel=null;
	var subsubel = null;
	var catsubsubel = null;
	switch (el.value){
		case '3':
			subel = createNamedElement('select', 'location[' + iteration + '][id]');
			{category_list}
			catsubel =createNamedElement('select', 'location[' + iteration + '][recursiv]');
			{recursiv}
			break;
		case '4':
			subel = createNamedElement('select', 'location[' + iteration + '][id]');
			{static_list}
			break;
	}
	if (el.nextSibling.name == 'location[' + iteration + '][id]')
		el.parentNode.removeChild(el.nextSibling);
	if (el.nextSibling.name == 'location[' + iteration + '][recursiv]')
		el.parentNode.removeChild(el.nextSibling);
	if (subel)
		el.parentNode.insertBefore(subel, el.nextSibling);
	if (catsubel)
		el.parentNode.insertBefore(catsubel, el.nextSibling);
}
function RemoveBlok() {
	var tbl = document.getElementById('blokup');
	var lastRow = tbl.rows.length;
	if (lastRow > 0){
		tbl.deleteRow(lastRow - 1);
	}
}
function createNamedElement(type, name) {
    var element = null;
    try {
        element = document.createElement('<'+type+' name="'+name+'">');
    } catch (e) {
    }
    if (!element || element.nodeName != type.toUpperCase()) {
        element = document.createElement(type);
        element.setAttribute("name", name);
    }
    return element;
}
</script>
