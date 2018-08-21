<form method="post" id="commit_delete" action="admin.php?mod=extra-config&amp;plugin=social&amp;action=del">
<input type="hidden" name="id" value="{id}" />
<input type="hidden" id="commit" name="commit" value="no" />
<div align="center">{commit}</div>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="submit" value="{l_noa}" class="button" />&#160;
<input type="submit" onclick="document.forms['commit_delete'].elements['commit'].value='yes'; return true;" value="{l_yesa}" class="button" />
</td>
</tr>
</table>
</form>