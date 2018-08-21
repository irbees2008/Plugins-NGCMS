{% if (info) %}
<div class="feed-me">
{{info}}
</div>
{% endif %}

<div class="archive items">
	{% for entry in entries %}			
					<div class="event">
						<a href="{{entry.fulllink}}" class="event_name">{{entry.announce_name}}</a>
						<div class="event_info">
							<span class="time">{{entry.date|date('Y-m-d h:i:s')}}</span>
							<span class="place">{{entry.announce_place}}</span>
						</div>
						<div class="user_info">
							<a href="{{entry.author_link}}" class="avatar">
								<img alt="" src="{{entry.avatar}}" class="avatar_round">
							</a>
							<div class="info">
								<a href="{{entry.author_link}}" class="name">{{entry.uname}}</a>
								<div class="about">
									<span>{{entry.uage}} {{entry.wordage}}</span>, <span>{{entry.ucity}}</span>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="description">
							<div class="head">Описание</div>
							<span class="text">
								{{entry.announce_description}}
							</span>
						</div>
						<a href="{{entry.fulllink}}" class="comments">Подробнее...</a>
					</div>
	{% endfor %}
	<div class="clear"></div>
	{{pagesss}}
</div>

{% if (pages.true) %}
<div class="pagenavi clearfix">

{% if (prevlink.true) %}
{{ prevlink.link }}
{% endif %}

{{ pages.print }}

{% if (nextlink.true) %}
{{ nextlink.link }}
{% endif %}

</div>
{% endif %}