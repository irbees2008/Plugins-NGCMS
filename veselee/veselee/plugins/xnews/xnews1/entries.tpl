<div class="news_item">
	<div class="left_col">
		<span class="data">{{ news.date }}</span>
		<a href="{{home}}"><img alt="" src="{{p.xfields.nimages.entries.0.url}}" class="avatar_round"></a>
	</div>
	<div class="new_info">
		<a href="{{ news.url.full }}" class="article_name">{{ news.title|truncateHTML(70,'...') }}</a>
		<span class="article_desc">
			{{ news.short }}
		</span>
	</div>
</div>