<table border="0" cellspacing="1" cellpadding="1" class="content">
<tr>
<td colspan="2" width=100% class="contentHead">
  <img src="{{ skins_url }}/images/nav.gif" hspace="8">
  <a href="?mod=extras" title="{{ lang.extras }}">{{ lang.extras }}</a>
  &#8594; <a href="?mod=extra-config&plugin=testmanager">testmanager</a>
  {% if (flags.haveForm) %}
  &#8594; <a href="?mod=extra-config&plugin=testmanager&action=test&id={{ testID }}">���� "{{ testName }}"</a>
  {% if (flags.haveField) %}
  &#8594; <a href="?mod=extra-config&plugin=testmanager&action=row&test_id={{ testID }}&row={{ fieldName }}">������ "{{ fieldName }}"</a>
  {% endif %}
  {% if (flags.addField) %}
  &#8594;���������� ������ �������
  {% endif %}
  {% endif %}
</td>
</tr>
</table>
