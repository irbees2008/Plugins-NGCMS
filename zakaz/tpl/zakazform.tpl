<form method="post" action="{form_url}" id="zakazform" name="zakaz" enctype="multipart/form-data">
<input type="hidden" name="catid" value="{catid}"/>
[error]<pstyle="background: red; color: white;">{errorText}</p>[/error]
[isform]	<p>
		Ф.И.О:<br />
		<input id="zname" name="zname" style="width: 190px;" type="text" value="{zname}" /></p>
	<p>
		e-mail:<br />
		<input id="zemail" name="zemail" style="width: 190px;" type="text" value="{zemail}" /></p>
	<p>
		Телефон для связи:<br />
		<input id="phone" name="phone" style="width: 190px;" type="text" value="{phone}" /></p>
	<p>
		<a name="zakaz"></a> Модели:<br />
		<textarea id="zakazmes" name="zakazmes" rows="2" style="width: 190px; font-size:10px;">{zakazmes}</textarea></p>
	<p>
		Прикрепить документ с заявкой:<br />
		<input id="attach" name="attach" size="1" style="width: 190px;" type="file" value="{attach}" /></p>
		<p>
		Введите код подтверждения::<br />
		<input type="text" name="vcode" size="8"/> <img id="img_captcha" onclick="this.src='{captcha_url}&rand='+Math.random();" src="{captcha_url}&rand={rand}" alt="captcha" /></p>
	<p>
		<input name="smb" type="submit" value="Отправить" /></p>[/isform]
		[issend]<h3>Сообщение успешно отправлено! Ожидайте ответа<table width="100%" cellspacing="1" cellpadding="1">
<tr><td width="35%" style="font-weight: bold;">Имя</td><td style="font-weight: bold;">{zname}</td></tr>
<tr><td width="35%" style="font-weight: bold;">e-mail:</td><td style="font-weight: bold;">{zemail}</td></tr>
<tr><td width="35%" style="font-weight: bold;">Телефон для связи</td><td style="font-weight: bold;">{phone}</td></tr>
<tr><td width="35%" style="font-weight: bold;">Заказ</td><td style="font-weight: bold;">{zakazmes}</td></tr>
</table></h3>[/issend]
</form>

