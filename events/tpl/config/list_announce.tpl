<!-- List of news start here -->
<form action="/engine/admin.php?mod=extra-config&plugin=events&action=modify" method="post" name="events">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center" width="100%">
<tr align="left" class="contHead">
<td width="5%" nowrap>ID</td>
<td width="15%" nowrap>����</td>
<td width="15%" nowrap>���� ����������</td>
<td width="10%" nowrap>�����</td>
<td width="10%" nowrap>���������</td>
<td>���������</td>
<td width="10%">�����</td>
<td width="5%">�����?</td>
<td width="5%">�������?</td>
<td width="5%"><input class="check" type="checkbox" name="master_box" title="������� ���" onclick="javascript:check_uncheck_all(events)" /></td>
</tr>
{entries}
<tr>
<td width="100%" colspan="10">&nbsp;</td>
</tr>

<tr align="center">
<td colspan="10" class="contentEdit" align="right" valign="top">
<div style="text-align: left;">
��������: <select name="subaction" style="font: 12px Verdana, Courier, Arial; width: 230px;">
<option value="">-- �������� --</option>
<option value="mass_approve">������������</option>
<option value="mass_forbidden">��������������</option>
<option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option><option value="mass_delete">������� ����������</option>
</select>
<input type="submit" value="���������.." class="button" />
<br/>
</div>
</td>
</tr>
<tr>
<td width="100%" colspan="10">&nbsp;</td>
</tr>
<tr>
<td align="center" colspan="10" class="contentHead">{pagesss}</td>
</tr>
</table>
</form>