{% if (global.flags.isLogged) %}
<div class="login">
	<a href="{{ profile_link }}" class="avatar">
		<img alt="" src="{{ avatar_url }}" class="avatar_round">
	</a>
	<div class="info">
		<a href="{{ profile_link }}" class="name">{{global.user.name}}</a>
		<div class="about">
			{% if (global.user.xfields_ubirthdate) %}<span>{{ "now"|date('Y')-("now"|date('U') - global.user.xfields_ubirthdate|date('U'))|date('Y') }} лет</span>,{% endif %} {% if (global.user.xfields_ucity) %}<span>{{global.user.xfields_ucity}}</span>{% endif %}
		</div>	    			
		{% if pluginIsActive('uprofile') %}<a href="{{ profile_link }}">Редактировать</a> |{% endif %}
		<a href="{{ logout_link }}">Выход</a>
	</div>
</div>
{% else %}
<script language="javascript">
var set_login = 0;
var set_pass  = 0;
</script>
<!-- .modal -->
<div class="modal" id="auth-modal">
	<div class="modal-box">
		<div class="title">Вход через социальные сети</div>
		<div class="modal-clouse"></div>
		{% if pluginIsActive('auth_loginza') %}
		<div class="modal-footer">

			<div class="social-in-modal">
				<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
				<a href="https://loginza.ru/api/widget?token_url={home}/plugin/auth_loginza/&provider=facebook&providers_set=vkontakte,facebook,twitter" class="loginza"><img src="{{ tpl_url }}/img/social/fb.png" alt="Facebook" title="Facebook"> Facebook</a>
				<a href="https://loginza.ru/api/widget?token_url={home}/plugin/auth_loginza/&provider=vkontakte&providers_set=vkontakte,facebook,twitter" class="loginza"><img src="{{ tpl_url }}/img/social/vk.png" alt="Вконтакте" title="Вконтакте"> Вконтакте</a>
				<a href="https://loginza.ru/api/widget?token_url={home}/plugin/auth_loginza/&provider=twitter&providers_set=vkontakte,facebook,twitter" class="loginza"><img src="{{ tpl_url }}/img/social/tw.png" alt="Twitter" title="Twitter"> Twitter</a>
			</div>
		</div>
		{% endif %}
	</div>
</div>
{% endif %}