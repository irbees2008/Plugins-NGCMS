{% if success %}
  <ul>
    {% for entry in entries %}
      <li>������: {{ entry.title }} - {{ entry.description }} �� {{ entry.quantity }} �����.</li>
    {% endfor  %}
  </ul>
{% else %}
  <div>������ �������� ����������</div>
{% endif %}
