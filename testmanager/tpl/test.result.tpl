<h2>{{ title }}</h2>
<p>{{ description }}</p>
{% if (entries) %}
<table border="0" class="table">
  <tr>
      <th>{{ lang['testmanager:question.text'] }}</th>
      <th>{{ lang['testmanager:user.answer'] }}</th>
      <th>{{ lang['testmanager:correct.answer'] }}</th>
      <th>{{ lang['testmanager:isright'] }}</th>
  </tr>
  {% for entry in entries %}
    <tr>
      <td>{{ entry.title }}</td>
      <td>{{ entry.value }}</td>
      <td>{{ entry.answer }}</td>
      <td>
      {% if entry.correct == "+" %}
        <b style="color:green">
      {% else %}
        <b style="color:red">
      {% endif %}
        {{ entry.correct }}
        </b>
      </td>
    </tr>
  {% endfor %}
  <tr colspan="3">
    <td>{{ lang['testmanager:total'] }}: <b>{{ total }}</b></td>
    <td>{{ lang['testmanager:right'] }}: <b>{{ right }}</b></td>
    <td><a href="{{ url }}">{{ lang['testmanager:repeat'] }}</td>
  </tr>
</table>
{% endif %}