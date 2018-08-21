{% if (state == 'append-account-success') %}
	Поздравляем!
	<br />
	Вы привязали аккаунт {{account}} к вашему профилю.
	<br />
	Теперь Вам не нужно постоянно вводить пароль для входа.
{% elseif (state == 'append-account-error') %}
	Кто-то уже зарегистрировался под аккаунтом {{account}}
{% elseif (state == 'register') %}
	<h2 class="title top">
		<span> Осталось уточнить некоторые данные</span>
	</h2><br/><br/>
	<form name="register" action="/plugin/auth_loginza/register/" method="post">
	<input type="hidden" name="type" value="doregister" />
	<input type="hidden" name="avatar" name="type" value="{{advanced.photo}}" />

	<section>
		<div class="label label-table">
			<label>Аватар:</label>
			<img src="{{advanced.photo}}" style="margin: 5px; border: 0px; max-width: 80px; max-height: 80px;" alt=""/>
		</div>
		<div class="label label-table">
			<label>Имя пользователя:</label>
			<input name="login" type="text" title="Имя пользователя"  value="{{login}}"/>
		</div>
		<div class="label label-table">
			<label>Пароль:</label>
			<input name="password" type="text" title="Пароль"  value="{{password}}"/>
		</div>
		<!--
		<div class="label label-table">
			<label>E-mail адрес:</label>
			<input name="email" type="text" title="E-mail адрес"  value="{{email}}"/>
		</div>
		-->
		<div class="label label-table">
			<label>Пол:</label>
			<select name="xfields[ugender]">
			  <option value="" {% if (responce.gender == '') %}selected{% endif %}></option>
			  <option value="M" {% if (responce.gender == 'M') %}selected{% endif %}>М</option>
			  <option value="F" {% if (responce.gender == 'F') %}selected{% endif %}>Ж</option>
			</select>
		</div>
		<div class="label label-table">
			<label>Город:</label>
			<select name="xfields[ucity]">
			{% for ci in cities %}	
			  <option value="{{ci.id}}" {% if (ci.city == advanced.city) %}selected{% endif %}>{{ci.city}}</option>
			{% endfor %}	
			</select>
		</div>
		<div class="label label-table">
			<label>Дата рождения:</label>
			<input name="xfields[ubirthdate]" type="text" title="Город" id="registration_datepicker"  value="{{responce.dob}}"/>
		</div>
		<div class="clearfix"></div>
		<div class="label">
			<input type="submit" class="button" value="Зарегистрироваться!" />
		</div>
	<div class="line"></div>
	</section>
	</form>
{% elseif (state == 'register-success') %}
	Привествую тебя, {{username}}
	<br />
	Запиши свой пароль на бумажку: {{password}}
	<br />
	А потом съешь ее и входи на сайт, как и регистрировался :)
{% elseif (state == 'register-error') %}
	Ooops! {{error-msg}}
{% elseif (state == 'account-delete') %}
	Вы отвязали свой аккаунт от профиля. Теперь вы свободный человек.
{% endif %}