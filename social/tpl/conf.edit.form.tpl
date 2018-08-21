<br />
<form method="post" action="admin.php?mod=extra-config&amp;plugin=social&amp;action=editSubmit">
<input type="hidden" name="id" value="{id}" />
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_social:service.name}<br /><small>{l_social:service.name#desc}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="25" name="name" value="{name}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_social:service.title}<br /><small>{l_social:service.title#desc}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="title" value="{title}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_social:service.link}<br /><small>{l_social:service.link#desc}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="link" value="{link}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{service.img}<br /><small>{service.img.desc}</small></td>
<td width="50%" class="contentEntry2">{img}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_social:service.active}<br /><small>{l_social:service.active#desc}</small></td>
<td width="50%" class="contentEntry2">{active}</td>
</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="submit" value="{l_social:buttonEdit}" class="button" />
</td>
</tr>
</table>
</form>