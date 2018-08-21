{% include localPath(0) ~ "conf.navi.tpl" %}
<form method="post" action="">
<input type="hidden" name="mod" value="extra-config"/>
<input type="hidden" name="plugin" value="testmanager"/>
<input type="hidden" name="action" value="savetest"/>

<table width="100%" border="0">
<tr align="left" valign="top">
  <td class="contentRow" width="230"><b>Код теста/ URL страницы:</b></td>
  <td>
    <input style="width: 30px; background: white;" type="text" name="id" value="{{ id }}" disabled="disabled"/> [ <a href="{{ url }}" target="_blank">открыть</a> ]
    <br/> 
    <input style="width: 420px; background: white;" type="text" value="{{ url }}" readonly="readonly" />    
  </td>
  <td rowspan="6" width="3" style="background-image: url({{ skins_url }}/images/delim.png); background-repeat: repeat-y;"></td>
  <td>
    <input type="checkbox" id="id_active" name="active" value="1" {{ flags.active ? 'checked="checked"' : '' }} />
    <label for="id_active"><b>Тест активен</b></label>    
  </td>  
</tr>

<tr align="left" valign="top">
  <td class="contentRow" width="230">
    <b>ID / Название теста:</b>
    <br><small><b>ID</b> - уникальный идентификатор</small></td>
  <td>
    <input style="width: 100px;" type="text" name="name" value="{{ name }}"/> 
    <input style="width: 350px;" type="text" name="title" value="{{ title }}"/>
  </td>
  <td>
    <input type="checkbox" id="id_captcha" name="captcha" value="1" {{ flags.captcha ? 'checked="checked"' : '' }} />
    <label for="id_captcha"><b>Использовать <i>captcha</i></b><br/><small>Требовать ввод проверочного кода для завершения теста</small></label>
  </td>
</tr>

<tr align="left" valign="top">
  <td class="contentRow" width="230">
    <b>Описание теста:</b>
    <br/><small>Выводится пользователю перед тестом</small>
  </td>
  <td>
    <textarea style="margin-left: 0px;" cols="72" rows="3" name="description">{{ description }}</textarea>
  </td>
</tr>
<tr><td colspan="6"><input type="submit" value="Сохранить"/></td></tr>
</table>
<hr/>
<table width="100%">
<tr>
  <td class="contentHead">ID вопроса</td>
  <td class="contentHead">Текст вопроса</td>
  <td class="contentHead">Удалить</td>
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
<a href="?mod=extra-config&plugin=testmanager&action=row&test_id={{ testID }}">Добавить новый вопрос</a>
</td>
</tr>
</table>
</form>