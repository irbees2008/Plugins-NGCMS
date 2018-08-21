{% if (flags.error) %}
<div class="ng-message">
  {{ errorText }}
</div>
{% endif %}
<h2>{{ title }}</h2>
<p>{{ description }}</p>
{% if (entries) %}
<div class="post">
  <form method="post" action="{{ form_url }}" id="test_form" name="test_form">
    {{ hidden_fields }}
    <input type="hidden" name="id" value="{{ id }}" />
    <table class="table">
      {% for entry in entries %}
        <tr>
          <td width="50%">{{ entry.title }}</td>
          <td width="50%"><select name="{{ entry.name }}">{{ entry.options.select }}</select></td>
        </tr>
      {% endfor %}
      {% if (flags.captcha) %}
      <tr>
        <td width="50%">{{ lang['testmanager:captcha'] }}<img id="img_captcha" onclick="this.src='{{ captcha_url }}&rand='+Math.random();" src="{{ captcha_url }}&rand={{ captcha_rand }}" alt="captcha" /></td>
        <td width="50%"><input type="text" name="vcode" style="width:80px" class="input" /></td>
      </tr>
      {% endif %}
      <table class="table">
        <tr align="center">
          <td width="100%" valign="top">
            <input type="submit" value="{{ lang['testmanager:test.result'] }}" class="btn" />
          </td>
        </tr>
    </table>
  </form>  
</div>
{% endif %}