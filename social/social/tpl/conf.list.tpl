<table width="100%" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr align="center" width="100%" class="contHead">
<td width="18%">{l_social:service.name}</td>
<td>{l_social:service.title}</td>
<td>{l_social:service.link}</td>
<td width="10%">{l_social:service.img}</td>
<td width="8%">{l_social:service.action}</td>
</tr>
{entries}
<tr>
<td width="100%" colspan="5" class="contentEdit" align="center">
<input type="button" onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=social&action=restore'" value="{l_social:buttonRestore}" class="button" />&nbsp
<input type="button" onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=social&action=add'" value="{l_social:buttonAdd}" class="button" />
</td>
</tr>
</table>
