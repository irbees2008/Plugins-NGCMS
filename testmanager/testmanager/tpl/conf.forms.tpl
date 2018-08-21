{% include localPath(0) ~ "conf.navi.tpl" %}

<table width="100%">
<tr align="left">
<td class="contentHead"><b>Код</b></td>
<td class="contentHead"><b>ID теста</b></td>
<td class="contentHead"><b>Название теста</b></td>
<td class="contentHead"><b>Активен</b></td>
<td class="contentHead">&nbsp;</td>
</tr>
{% for entry in entries %}
<tr align="left" class="contRow1">
  <td width="30">{{ entry.id }}</td>
  <td style="padding:2px;"><a href="{{ entry.linkEdit}}">{{ entry.name }}</a></td>
  <td>{{ entry.title }}</td>  
  <td>{{ entry.flags.active ? lang['yesa'] : lang['noa'] }}</td>
  <td nowrap>
    {% if (entry.flags.active) %}
      <a onclick="alert('{{ lang['testmanager:active_nodel'] }}');">
    {% else %}
      <a href="{{ entry.linkDel }}" onclick="return confirm('{{ lang['testmanager:suretest'] }}');">
    {% endif %}
    <img src="{{ skins_url }}/images/delete.gif" alt="DEL" width="12" height="12" />
    </a>
  </td>
</tr>
{% endfor %}
<tr>
<td></td>
<td colspan="5" style="text-align: left; padding: 10px 10px 0 0;">
<a href="?mod=extra-config&plugin=testmanager&action=addtest">Создать новый тест</a>
</td>
</tr>
</table>
</form>