{% include localPath(0) ~ "conf.navi.tpl" %}
<form method="post" action="">
<input type="hidden" name="mod" value="extra-config"/>
<input type="hidden" name="plugin" value="testmanager"/>
<input type="hidden" name="action" value="savetest"/>

<table width="100%" border="0">
<tr align="left" valign="top">
  <td class="contentRow" width="230"><b>��� �����/ URL ��������:</b></td>
  <td>
    <input style="width: 30px; background: white;" type="text" name="id" value="{{ id }}" disabled="disabled"/> [ <a href="{{ url }}" target="_blank">�������</a> ]
    <br/> 
    <input style="width: 420px; background: white;" type="text" value="{{ url }}" readonly="readonly" />    
  </td>
  <td rowspan="6" width="3" style="background-image: url({{ skins_url }}/images/delim.png); background-repeat: repeat-y;"></td>
  <td>
    <input type="checkbox" id="id_active" name="active" value="1" {{ flags.active ? 'checked="checked"' : '' }} />
    <label for="id_active"><b>���� �������</b></label>    
  </td>  
</tr>

<tr align="left" valign="top">
  <td class="contentRow" width="230">
    <b>ID / �������� �����:</b>
    <br><small><b>ID</b> - ���������� �������������</small></td>
  <td>
    <input style="width: 100px;" type="text" name="name" value="{{ name }}"/> 
    <input style="width: 350px;" type="text" name="title" value="{{ title }}"/>
  </td>
  <td>
    <input type="checkbox" id="id_captcha" name="captcha" value="1" {{ flags.captcha ? 'checked="checked"' : '' }} />
    <label for="id_captcha"><b>������������ <i>captcha</i></b><br/><small>��������� ���� ������������ ���� ��� ���������� �����</small></label>
  </td>
</tr>

<tr align="left" valign="top">
  <td class="contentRow" width="230">
    <b>�������� �����:</b>
    <br/><small>��������� ������������ ����� ������</small>
  </td>
  <td>
    <textarea style="margin-left: 0px;" cols="72" rows="3" name="description">{{ description }}</textarea>
  </td>
</tr>
<tr><td colspan="6"><input type="submit" value="���������"/></td></tr>
</table>
<hr/>
<table width="100%">
<tr>
  <td class="contentHead">ID �������</td>
  <td class="contentHead">����� �������</td>
  <td class="contentHead">�������</td>
</tr>
{% for entry in entries %}
<tr align="left" class="contRow1">
  <td style="padding:2px;">
    <a href="?mod=extra-config&plugin=testmanager&action=update&subaction=up&id={{ testID }}&name={{ entry.name }}"><img src="{{ skins_url }}/images/up.gif" width="16" height="16" alt="UP" /></a>
    <a href="?mod=extra-config&plugin=testmanager&action=update&subaction=down&id={{ testID }}&name={{ entry.name }}"><img src="{{ skins_url }}/images/down.gif" width="16" height="16" alt="DOWN" /></a>
    <a href="?mod=extra-config&plugin=testmanager&action=row&test_id={{ testID }}&row={{ entry.name }}">{{ entry.name }}</a></td>
  <td>
    <a href="?mod=extra-config&plugin=testmanager&action=row&test_id={{ testID }}&row={{ entry.name }}">{{ entry.title }}</a>
  </td>
  <td nowrap>
    <a href="?mod=extra-config&plugin=testmanager&action=update&subaction=del&id={{ testID }}&name={{ entry.name }}">
      <img src="{{ skins_url }}/images/delete.gif" alt="DEL" width="12" height="12" />
    </a>
  </td>
</tr>
{% endfor %}
<tr>
<td colspan="5" style="text-align: left; padding: 10px 10px 0 0;">
<a href="?mod=extra-config&plugin=testmanager&action=row&test_id={{ testID }}">�������� ����� ������</a>
</td>
</tr>
</table>
</form>