{% if (info) %}
<div class="feed-me">
{{info}}
</div>
{% endif %}

		<ul class="section clearfix">
			{% for entry in entries %}
					<li class="post clearfix">
						<div class="review">
							<a href="#" title="">{% if (entry.pid) %}<a href='{{entry.fulllink}}'><img src='{{home}}/uploads/zboard/thumb/{{entry.filepath}}' width='60' height='60'></a>{% else %}<a href='{{entry.fulllink}}'><img src='{{tpl_url}}/img/noimage.png' width='60' height='60'></a>{% endif %}</a>
						</div>
						<div class="entry">
							<h2><a href="{{ entry.fulllink }}">{{ entry.announce_name }}</a> {% if (entry.vip_added != "" and entry.vip_added != 0) %}VIP: {{entry.vip_expired|date("d-m-Y H:i")}} {% endif %}</h2>
							<div class="tag">
								<a href="{{entry.catlink}}" class="tag-{{entry.cid}}">{{entry.cat_name}}</a>
							</div>
							<div class="desc">{{entry.date|date("d-m-Y H:i")}} / <a href="{{ entry.vip }}">[Vip]</a> / <a href="{{ entry.edit }}">[Edit]</a> / <a href="{{ entry.del }}">[Del]</a></div>
							<div class="view">
								<a title="{{entry.views}}">{{entry.views}}</a>
							</div>
						</div>
					</li>
			{% endfor %}
		</ul>				

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
