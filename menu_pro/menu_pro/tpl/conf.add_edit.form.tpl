<script type="text/javascript" src="{admin_url}/includes/js/ajax.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/admin.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/libsuggest.js"></script>

<form method="post" action="admin.php?mod=extra-config&amp;plugin=menu_pro&amp;action=add_form&amp;id={id}">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
[show_name]
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_name}<br /><small>{l_menu_pro:desc_name}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="name" value="{name}" /></td>
</tr>
[/show_name]
[show_title]
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_title}<br /><small>{l_menu_pro:desc_title}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="title" value="{title}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_description}<br /><small>{l_menu_pro:desc_description}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="description" value="{description}" /></td>
</tr>
[/show_title]
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_icon}<br /><small>{l_menu_pro:desc_icon}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" id="icon" name="icon" value="{icon}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_skin}<br /><small>{l_menu_pro:desc_skin}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" id="skin" name="skin" value="{skin}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_if_active}<br /><small>{l_menu_pro:desc_if_active}</small></td>
<td width="50%" class="contentEntry2">{if_active_list}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_parent}<br /><small>{l_menu_pro:desc_parent}</small></td>
<td width="50%" class="contentEntry2">{parent_list}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_access}<br /><small>{l_menu_pro:desc_access}</small></td>
<td width="50%" class="contentEntry2">{l_menu_pro:label_access_title}<br />{access_list}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_url}<br /><small>{l_menu_pro:desc_url}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="url" value="{url}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_plugin}<br /><small>{l_menu_pro:desc_plugin}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="rep_plugin" id="rep_plugin" value="{plugin}" autocomplete="off" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_handler}<br /><small>{l_menu_pro:desc_handler}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="handler" id="rep_handler" value="{handler}" autocomplete="off" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_params}<br /><small>{l_menu_pro:desc_params}</small></td>
<td width="50%" class="contentEntry2"><input type="button" class="button" value='{l_menu_pro:button_params_dell}' onClick="RemoveBlok();return false;" />&nbsp;
<input type="button" class="button" value='{l_menu_pro:button_params_add}' onClick="AddBlok();return false;" /><br />
<table id="blokup" align="left">{params_list}</table>
</td>
</tr>
</table><br />


<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="submit" value="[add]{l_menu_pro:button_add_submit}[/add][edit]{l_menu_pro:button_edit_submit}[/edit]" class="button" />
</td>
</tr>
</table>
</form>

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
	var el = '<input type="text" name="params[' + iteration + '][key]" />';
	cellRight.innerHTML += el;
	el = '<input type="text" name="params[' + iteration + '][value]" />';
	cellRight.innerHTML += el;
}
function RemoveBlok() {
	var tbl = document.getElementById('blokup');
	var lastRow = tbl.rows.length;
	if (lastRow > 0){
		tbl.deleteRow(lastRow - 1);
	}
}

function sendData(obj)
{
	return json_encode(	{
							'rep_plugin'	: document.getElementById('rep_plugin').value,
							'rep_handler'	: obj.searchDest
						}
					);
}

function systemInit() {
	new ngSuggest('rep_plugin', 
								{ 
									'iMinLen'	: 1,
									'stCols'	: 2,
									'stColsClass': [ 'cleft', 'cleft' ],
									'stColsHLR'	: [ true, true ],
									'reqMethodName' : 'menu_pro_get_plugin',
								}
							);
	new ngSuggest('icon', 
								{ 
									'iMinLen'	: 1,
									'stCols'	: 1,
									'stColsClass': [ 'cleft'],
									'stColsHLR'	: [ true],
									'reqMethodName' : 'menu_pro_get_icon',
								}
							);
	new ngSuggest('skin', 
								{ 
									'iMinLen'	: 1,
									'stCols'	: 1,
									'stColsClass': [ 'cleft'],
									'stColsHLR'	: [ true],
									'reqMethodName' : 'menu_pro_get_skin',
								}
							);
	new ngSuggest('rep_handler', 
								{ 
									'iMinLen'	: 1,
									'stCols'	: 2,
									'stColsClass': [ 'cleft', 'cleft' ],
									'stColsHLR'	: [ true, true ],
									'reqMethodName' : 'menu_pro_get_handler',
									'outputGenerator' : sendData
								}
							);

}

if (document.body.attachEvent) {
	document.body.onload = systemInit;
} else {
	systemInit();
}

</script>