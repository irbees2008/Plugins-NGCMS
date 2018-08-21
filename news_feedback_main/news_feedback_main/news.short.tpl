[TWIG]
<div class="news-news">
<div class="news-news-image">
<a href="{{ news.url.full }}">
{% if (p.xfields.poster.count < 1) %}
<img src="{{ tpl_url }}/images/noimage.jpg" alt="{{ news.title }}" />
{% else %}
<img src="{{ p.xfields.poster.entries[0].url }}" alt="{{ news.title }}" />
{% endif %}
</a>
</div>
<h2><a href="{{ news.url.full }}">{{ news.title }}</a></h2>
<div class="news-news-bg"></div>
<div class="news-news-triangle"></div>
</div>
[/TWIG]