<form method="post" action="admin.php?mod=extra-config&plugin=events">
<tr>
<td colspan=2>
<fieldset class="admGroup">
<legend class="title">���������</legend>
<table width="100%" border="0" class="content">
<tr>
<td class="contentEntry1" valign=top>��������� ������ ��������� ����������?<br /></td>
<td class="contentEntry2" valign=top><select name="send_guest" >{send_guest}</select></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>���������� ���������� �� ��������<br /></td>
<td class="contentEntry2" valign=top><input name="count" type="text" title="���������� ���������� �� ��������" size="4" value="{count}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>���������� ���������� � �������<br /></td>
<td class="contentEntry2" valign=top><input name="count_filter" type="text" title="���������� ���������� �� ��������" size="4" value="{count_filter}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>���������� ���������� � ������<br /></td>
<td class="contentEntry2" valign=top><input name="count_filter_archive" type="text" title="���������� ���������� �� ��������" size="4" value="{count_filter_archive}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>���������� ���������� �� �������� �����������<br /></td>
<td class="contentEntry2" valign=top><input name="count_list" type="text" title="���������� ���������� �� �������� �����������" size="4" value="{count_list}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>���������� ���������� �� �������� ������<br /></td>
<td class="contentEntry2" valign=top><input name="count_search" type="text" title="���������� ���������� �� �������� ������" size="4" value="{count_search}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>�������� ��� ������� ����������<br /><small></small></td>
<td class="contentEntry2" valign=top><input name="description" type="text" title="�������� ��� ����������" size="50" value="{description}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>�������� ����� ��� ������� ����������<br /><small></small></td>
<td class="contentEntry2" valign=top><input name="keywords" type="text" title="�������� ����� ��� ����������" size="50" value="{keywords}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>����������, ��������� ����� ����������<br /><small></small></td>
<td class="contentEntry2" valign=top><textarea name="info_send" title="����������, ��������� ����� ����������" rows=8 cols=100>{info_send}</textarea></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>���������� ��������� ����� ��������������<br /><small></small></td>
<td class="contentEntry2" valign=top><textarea name="info_edit" title="���������� ��������� ����� ��������������" rows=8 cols=100>{info_edit}</textarea></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>�������� ������ ���������� �� ��������� �����?<br /></td>
<td class="contentEntry2" valign=top><select name="use_expired" >{use_expired}</select></td>
</tr>
<!--
<tr>
<td class="contentEntry1" valign=top>����� ����� ����������<br /></td>
<td class="contentEntry2" valign=top><input name="list_period" type="text" title="����� ����� ����������" size="10" value="{list_period}" /></td>
</tr>
-->
<tr>
<td class="contentEntry1" valign=top>���� ��������� ����������?<br /></td>
<td class="contentEntry2" valign=top><select name="views_count" >{views_count}</select></td>
</tr>
<!--
<tr>
<td class="contentEntry1" valign=top>���������, ������� ����� ������� ������� �� ������� �������� ����������<br /></td>
<td class="contentEntry2" valign=top><select name="cat_id" >{cat_id}</select></td>
</tr>

<tr>
<td class="contentEntry1" valign=top>���������� ������ � ����� �����������?<br /></td>
<td class="contentEntry2" valign=top><select name="notice_mail" >{notice_mail}</select></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>������ ����������� �� �����<br /><small>��������� ����:<br />%announce_name% - <br />%author% - <br />%announce_description% - <br />%announce_period% - <br />%announce_contacts% - <br />%date% -</small></td>
<td class="contentEntry2" valign=top><textarea name="template_mail" title="������ ����������� �� �����" rows=8 cols=100>{template_mail}</textarea></td>
</tr>

<tr>
<td class="contentEntry1" valign=top>������� ������ ��� ����������<br /><small>���� ����� �� ������� �������� ������ <b>main.tpl</b><br />������: ������� template.tpl � ngcms/www/templates/default/ � �������� � ��� ���� ������ �������� <b>template</b> ��� ����������</small></td>
<td class="contentEntry2" valign=top><input name="main_template" type="text" title="������� ������ ��� ����������" size="20" value="{main_template}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>������ ����������� �����<br /></td>
<td class="contentEntry2" valign=top><input name="width_thumb" type="text" title="������ ����������� �����" size="20" value="{width_thumb}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>����������� ���������� ��� �����������<br /><small>������ ������ <b>*.jpg;*.jpeg;*.gif;*.png</b></small></td>
<td class="contentEntry2" valign=top><input name="ext_image" type="text" title="����������� ���������� ��� �����������" size="50" value="{ext_image}" /></td>
</tr>

<tr>
<td class="contentEntry1" valign=top>������������ ������ ������������ �����������<br /><small>������ � ����������</small></td>
<td class="contentEntry2" valign=top><input name="max_image_size" type="text" title="������������ ������ ������������ �����������" size="20" value="{max_image_size}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>������������ ������ ������������ �����������<br /><small>����������� � ��������</small></td>
<td class="contentEntry2" valign=top><input name="width" type="text" title="������������ ������ ������������ �����������" size="20" value="{width}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>������������ ������ ������������ �����������<br /><small>����������� � ��������</small></td>
<td class="contentEntry2" valign=top><input name="height" type="text" title="������������ ������ ������������ �����������" size="20" value="{height}" /></td>
</tr>
-->
</table>
</fieldset>
</td>
</tr>
<tr>
<td colspan=2>
<fieldset class="admGroup">
<legend class="title">��������� reCaptcha</legend>
<table width="100%" border="0" class="content">
<tr>
<td class="contentEntry1" valign=top>������������ reCaptcha?<br /></td>
<td class="contentEntry2" valign=top><select name="use_recaptcha" >{use_recaptcha}</select></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>Public Key<br /></td>
<td class="contentEntry2" valign=top><input name="public_key" type="text" title="Public Key" size="50" value="{public_key}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>Private Key<br /></td>
<td class="contentEntry2" valign=top><input name="private_key" type="text" title="Private Key" size="50" value="{private_key}" /></td>
</tr>
</table>
</fieldset>
</td>
</tr>
<tr>
<td colspan=2>
<fieldset class="admGroup">
<legend class="title">��������� �������</legend>
<table width="100%" border="0" class="content">
<tr>
<td class="contentEntry1" valign=top>���������� ���������� �� ��������<br /></td>
<td class="contentEntry2" valign=top><input name="admin_count" type="text" title="���������� ���������� �� ��������" size="4" value="{admin_count}" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>������ ����<br /></td>
<td class="contentEntry2" valign=top><input name="date" type="text" title="������ ����" size="10" value="{date}" /></td>
</tr>
</table>
</fieldset>
</td>
</tr>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input name="submit" type="submit"  value="���������" class="button" />
</td>
</tr>
</table>
</form>