<article class="full-post">
	<div class="titlef">������� ������������:  {{ user.name }}</div>
	<span class="metaf"></span>

	<div class="label label-table">
	<div class="avatar">
		<img src="{{ user.avatar }}" alt=""/>
		{% if not (global.user.status == 0) %}
			{% if pluginIsActive('pm') %}<a href="/plugin/pm/?action=write&name={{ user.name }}">�������� ��</a>{% endif %}
		{% endif %}
	</div>
	</div>
	
	<div class="label label-table">
		<label>������������:</label>
		{{ user.name }}
	</div>

	<div class="label label-table">
		<label>{{ lang.uprofile['regdate'] }}:</label>
		{{ user.reg }}
	</div>
	
	<div class="label label-table">
		<label>{{ lang.uprofile['last'] }}:</label>
		{{ user.last }}
	</div>
	
	{% if (userRec.xfields_ugender) %}
	<div class="label label-table">
		<label>���:</label>
		{% if (userRec.xfields_ugender) == 'M' %}
		�
		{% elseif (userRec.xfields_ugender == 'F') %}
		�
		{% else %}
		{% endif %}
	</div>
	{% endif %}
	
	{% if (userRec.xfields_ucity) %}
	<div class="label label-table">
		<label>�����:</label>
		{{userRec.xfields_ucity}}
	</div>
	{% endif %}

	{% if (userRec.xfields_ubirthdate) %}
	<div class="label label-table">
		<label>���� ��������:</label>
		{{userRec.xfields_ubirthdate}}
	</div>
	{% endif %}	
	
	{% if (userRec.loginza_id) %}
		{% if 'http://vk.com/' in (userRec.loginza_id) %}
		<div class="label label-table">
			<label>������� � ��:</label>
			<a href="{{userRec.loginza_id}}" target="_blank" id="vk_profile">{{userRec.loginza_id}}</a>
		</div>
		{% endif %}	
	{% endif %}	

</article>