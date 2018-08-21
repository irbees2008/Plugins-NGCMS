[error]
<div class="feed-me">
{l_login.error}
</div>
[/error]
[banned]
<div class="feed-me">
{l_login.banned}
</div>
[/banned]
[need.activate]
<div class="feed-me">
{l_login.need.activate}
</div>
[/need.activate]
<div class="block-title">{l_login.title}</div>
<form name="login" method="post" class="comment-form" action="{form_action}">
<input type="hidden" name="redirect" value="{redirect}"/>
	<div class="label pull-left">
		<label for="logn">Логин:</label>
		<input type="text" type="text" name="username" class="input">
	</div><br/><br/>
	<div class="label pull-right">
		<label for="pass">Пароль:</label>
		<input type="password" type="password" name="password" class="input">
	</div>
	<div class="clearfix"></div>
	<div class="label"><br/>
		<input type="submit" value="Войти" class="button">
	</div>
</form>