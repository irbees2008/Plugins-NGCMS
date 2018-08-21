[TWIG]
<article class="full-post">
	<div class="titlef">{{ news.title }}</div>
	<span class="metaf">{{ news.date }} | {% if pluginIsActive('uprofile') %}<a href="{{ news.author.url }}">{% endif %}{{ news.author.name }}{% if pluginIsActive('uprofile') %}</a>{% endif %}</span>
	<p>{{ news.short }}{{ news.full }}</p><br/>
	<div class="post-full-footer">
		<div class="post-full-meta">Просмотров: {{ news.views }}</div>
	</div>
</article>
[/TWIG]