{% if success %}
  <ul>
    {% for entry in entries %}
      <li>Валюта: {{ entry.title }} - {{ entry.description }} за {{ entry.quantity }} тенге.</li>
    {% endfor  %}
  </ul>
{% else %}
  <div>Сервис временно недоступен</div>
{% endif %}
