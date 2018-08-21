{% if (state == 'append-account-success') %}
	�����������!
	<br />
	�� ��������� ������� {{account}} � ������ �������.
	<br />
	������ ��� �� ����� ��������� ������� ������ ��� �����.
{% elseif (state == 'append-account-error') %}
	���-�� ��� ����������������� ��� ��������� {{account}}
{% elseif (state == 'register') %}
	<h2 class="title top">
		<span> �������� �������� ��������� ������</span>
	</h2><br/><br/>
	<form name="register" action="/plugin/auth_loginza/register/" method="post">
	<input type="hidden" name="type" value="doregister" />
	<input type="hidden" name="avatar" name="type" value="{{advanced.photo}}" />

	<section>
		<div class="label label-table">
			<label>������:</label>
			<img src="{{advanced.photo}}" style="margin: 5px; border: 0px; max-width: 80px; max-height: 80px;" alt=""/>
		</div>
		<div class="label label-table">
			<label>��� ������������:</label>
			<input name="login" type="text" title="��� ������������"  value="{{login}}"/>
		</div>
		<div class="label label-table">
			<label>������:</label>
			<input name="password" type="text" title="������"  value="{{password}}"/>
		</div>
		<!--
		<div class="label label-table">
			<label>E-mail �����:</label>
			<input name="email" type="text" title="E-mail �����"  value="{{email}}"/>
		</div>
		-->
		<div class="label label-table">
			<label>���:</label>
			<select name="xfields[ugender]">
			  <option value="" {% if (responce.gender == '') %}selected{% endif %}></option>
			  <option value="M" {% if (responce.gender == 'M') %}selected{% endif %}>�</option>
			  <option value="F" {% if (responce.gender == 'F') %}selected{% endif %}>�</option>
			</select>
		</div>
		<div class="label label-table">
			<label>�����:</label>
			<select name="xfields[ucity]">
			{% for ci in cities %}	
			  <option value="{{ci.id}}" {% if (ci.city == advanced.city) %}selected{% endif %}>{{ci.city}}</option>
			{% endfor %}	
			</select>
		</div>
		<div class="label label-table">
			<label>���� ��������:</label>
			<input name="xfields[ubirthdate]" type="text" title="�����" id="registration_datepicker"  value="{{responce.dob}}"/>
		</div>
		<div class="clearfix"></div>
		<div class="label">
			<input type="submit" class="button" value="������������������!" />
		</div>
	<div class="line"></div>
	</section>
	</form>
{% elseif (state == 'register-success') %}
	���������� ����, {{username}}
	<br />
	������ ���� ������ �� �������: {{password}}
	<br />
	� ����� ����� �� � ����� �� ����, ��� � ��������������� :)
{% elseif (state == 'register-error') %}
	Ooops! {{error-msg}}
{% elseif (state == 'account-delete') %}
	�� �������� ���� ������� �� �������. ������ �� ��������� �������.
{% endif %}