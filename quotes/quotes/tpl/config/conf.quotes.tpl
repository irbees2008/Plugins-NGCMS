<table border="0" cellspacing="1" cellpadding="1" class="content">
<tr>
<td colspan="2" width=100% class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"><a href="?mod=extra-config&plugin=quotes" title="����� ��������� �������">����� ��������� �������</a></td>
</tr>
</table>
<table width="100%">
<tr>
<td colspan="7" width=100% class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">������ ���� �����</td>
</tr>
<form action="/engine/admin.php?mod=extra-config&plugin=quotes&action=modify" method="post" name="editquotes">
<tr align="left">
<td class="contentHead"><b>ID</b></td>
<td class="contentHead"><b>����</b></td>
<td class="contentHead"><b>�������</b></td>
<td class="contentHead"><b>����� ������</b></td>
<td class="contentHead"><b>�������</b></td>
<td class="contentHead">&nbsp;</td>
<td width="5%" class="contentHead"><input class="check" type="checkbox" name="master_box" title="������� ����" onclick="javascript:check_uncheck_all(editquotes)" /></td>
</tr>
{entries}
{pagesss}
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="47%" align="right" class="contentEntry2"><div id="submit">
<select name="subaction">
<option value="">-- �������� --</option>
<option value="do_mass_delete">�������</option>
<option value="do_mass_approve">������������</option>
<option value="do_mass_forbidden">�������������</option>
</select>
<input type="submit" name="" value="OK" class="button" />
</div></td>
</tr>
</table>
</table>
</form>