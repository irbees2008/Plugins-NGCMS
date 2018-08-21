<form method="post" action="{admin_url}/admin.php?mod=extra-config&amp;plugin=addnews&amp;action=setCats" name="cats">
<table width="100%" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr align="center" width="100%" class="contHead">
		<td>{l_addnews:cats.title}</td>
		<td width="5%"><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(cats)" /></td>
	</tr>
	{entries}
	<tr>
		<td width="100%" colspan="5" class="contentEdit" align="center">
			<input type="submit" value="{l_addnews:buttonSave}" class="button" />
		</td>
	</tr>
</table>
</form>