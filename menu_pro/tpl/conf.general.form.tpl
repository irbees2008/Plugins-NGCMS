<form method="post" action="admin.php?mod=extra-config&amp;plugin=menu_pro&amp;action=general_submit">
<fieldset>
<legend><b>{l_menu_pro:legend_general}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_localize}<br /><small>{l_menu_pro:desc_localize}</small></td>
<td width="50%" class="contentEntry2">{localize_list}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_locate_tpl}<br /><small>{l_menu_pro:desc_locate_tpl}</small></td>
<td width="50%" class="contentEntry2">{locate_tpl_list}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_menu_pro:label_if_auto_cash}<br /><small>{l_menu_pro:desc_if_auto_cash}</small></td>
<td width="50%" class="contentEntry2">{if_auto_cash_list}</td>
</tr>
</table></fieldset><br />

<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="button" onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=menu_pro&action=clear_cash'" value="{l_menu_pro:button_clear_cash}" class="button" />&nbsp
<input type="submit" value="{l_menu_pro:button_save}" class="button" />
</td>
</tr>
</table>
</form>