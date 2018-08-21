<article class="full-post">
	<div class="titlef">Профиль пользователя</div>
	<span class="metaf"></span>

<form id="profileForm" method="post" action="{{ form_action }}" enctype="multipart/form-data">
<input type="hidden" name="token" value="{{ token }}"/>
<section>

<!--
	<div class="label label-table">
		<label>{{ lang.uprofile['email'] }}:</label>
		<input type="text" name="editmail" value="{{ user.email }}" class="input" />
	</div>
-->
	<div class="label label-table">
		<label>Имя:</label>
		<input type="text" name="name" value="{{ user.name }}" class="input" />
	</div>
	{% if (flags.avatarAllowed) %}
	<div class="label label-table">
		<label>{{ lang.uprofile['avatar'] }}:</label>
		{% if (user.flags.hasAvatar) %}
			<div id="avatar_url"><img src="{{ user.avatar }}" style="margin: 5px; border: 0px; max-width: 80px; max-height: 80px;" alt=""/></div>
			{% if 'http://vk.com/' in (userRec.loginza_id) %}
			<a href="javascript:{}" onclick="reupload_vk_avatar(); return false;" style="margin: 5px;">Обновить из ВК</a><br /><br />
			{% endif %}
			<!--<input type="checkbox" name="delavatar" id="delavatar" class="selectbutton" />&nbsp;{{ lang.uprofile['delete'] }} -->
		{% endif %}
		<div >
			<input type="file" name="newavatar"  />
		</div>
	</div>
	{% else %}
	<div class="label label-table">
		<label>{{ lang.uprofile['avatar'] }}:</label>
		{{ lang.uprofile['avatars_denied'] }}
	</div>
	{% endif %}
	
	{% if(userRec.loginza_id) %}
		{% if 'http://vk.com/' in (userRec.loginza_id) %}
		<div class="label label-table">
			<label>Профиль в ВК:</label>
			<a href="{{userRec.loginza_id}}" target="_blank" id="vk_profile">{{userRec.loginza_id}}</a>
		</div>
		{% endif %}
	{% endif %}

	{{plugin_smsfox}}

	{% if pluginIsActive('xfields') %}{{ plugin_xfields_0 }}{% endif %}
	<div class="clearfix"></div>
	<div class="label">
	<a href="javascript:{}" onclick="document.getElementById('profileForm').submit(); return false;" class="main_btn" style="width: 200px;">{{ lang.uprofile['save'] }}</a>
	</div>
<div class="line"></div>
</section>
</form>
	
</article>

{% if 'http://vk.com/' in (userRec.loginza_id) %}
<script>
function reupload_vk_avatar() {

	$.post('/engine/rpc.php', { json : 1, methodName : 'auth_loginza_r_vk_a', rndval: new Date().getTime(), params : json_encode({'vk_id' : $('#vk_profile').text() }) }, function(data) {

		// Try to decode incoming data
		try {
			resTX = data;
		//	alert(resTX['data']['feedback_text']);
		} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }
		if (!resTX['status']) {
			alert('Error ['+resTX['errorCode']+']: '+resTX['errorText']);
		} else {
			if ((resTX['data']['loginza_vk_avatar_refrash']>0)&&(resTX['data']['loginza_vk_avatar_refrash'] < 100)) {
				//$("div#lphone_status").html("");
				//$("div#lcode").css( "display", "table");
				//$("div#lphone_status").html("<span style='color:#b54d4b; font-size:10px;'>"+resTX['data']['loginza_vk_avatar_refrash_url']+"</span>");
			} else {
			
			$("div#avatar_url").html("<img src='"+resTX['data']['loginza_vk_avatar_refrash_url']+"' style='margin: 5px; border: 0px; max-width: 80px; max-height: 80px;' alt=''/>");
				//$("div#lphone_status").html("");
				//$("div#lcode").css( "display", "table");
				//$("div#lphone_status").html("<span style='color:#94c37a; font-size:10px;'>"+resTX['data']['smsfox_text']+"</span>");
			}
		}
	}).error(function() { 
		alert('HTTP error during request', 'ERROR'); 
	});

 
}
</script>
{% endif %}