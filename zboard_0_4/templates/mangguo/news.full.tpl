[TWIG]
<div class="t-side">
<div class="bd">

					<div class="news-post clearfix">
					{% if pluginIsActive('comments') %}
						<div class="review">
							<a href="{{ news.url.full }}">{{ news.views }}</a>
						</div>
					{% endif %}
						<div class="entry">
							<div class="hd"><h2><a href="{{ news.url.full }}">{{ news.title }}</a></h2></div>
							<div class="tag">
								{% for catz in news.categories.list %}
<a class="tag-{{catz.id}}" href="{{catz.url}}">{{catz.name}}</a>
{% endfor %}
							</div>
							<div class="desc">{{ news.date }} / {% if pluginIsActive('uprofile') %}<a href="{{ news.author.url }}">{% endif %}{{ news.author.name }}{% if pluginIsActive('uprofile') %}</a>{% endif %}</div>
						</div>
					</div>
</div>						
<div class="text">
<p>{{ news.short }}{{ news.full }}</p>
</div>
</div>

{% if pluginIsActive('comments') %}
	<div class="comment">
	<h3>Комментарии ({comments-num})</h3>
	{{ plugin_comments }}
	</div>
{% endif %}
[/TWIG]