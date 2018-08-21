<section class="news">
	<h2 class="title"><span>Новости</span></h2>
	{% for entry in entries %}
		{{ entry }}
	{% endfor %}
	<div class="clear"></div>
</section>
