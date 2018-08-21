{% include localPath(0) ~ "conf.navi.tpl" %}

<form action="?mod=extra-config&plugin=testmanager&action=editrow&test_id={{ testID }}&name={{ fieldName }}" method="post" name="testForm">
<input type="hidden" name="edit" value="{% if (flags.addField) %}0{% else %}1{% endif %}">
<table border="0" cellspacing="1" cellpadding="1" class="content">
<tr class="contRow1">
  <td width="50%">{{ lang['testmanager:field.id'] }}</td>
  <td width="47%">
    <input type="text" name="name" value="{{ field.name }}" size="40" {% if (not flags.addField) %}readonly{% endif %}>{% if (not flags.addField) %} &nbsp; &nbsp; {{ lang['testmanager:field.noedit'] }}{% endif %}
  </td>
</tr>

<tr class="contRow1">
  <td width="50%">{{ lang['testmanager:field.title'] }}</td>
  <td><input type="text" name="title" value="{{ field.title }}" size="40" /></td>
</tr>

<div id="type_select">
<table border="0" cellspacing="1" cellpadding="1" class="content">
<tr class="contRow1"><td>{{ lang['testmanager:select.options'] }}</td><td><textarea cols=70 rows=8 name="select_options">{{ field.select_options }}</textarea></tr>
<tr class="contRow1"><td>{{ lang['testmanager:field.answer'] }}</td><td><input type="text" name="select_answer" value="{{ field.select_answer }}" size=40></tr>
</table>
</div>
<tr class="contRow1"><td colspan=2 align="center"><input type="submit" class="button" value="{{ lang['testmanager:button.save'] }}"></td></tr>
</table>
</form>
