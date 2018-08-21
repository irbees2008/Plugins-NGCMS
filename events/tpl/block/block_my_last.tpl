{% if (entries) %}
<script>
/* when document is ready */
$(function() {
/* initiate plugin */
$("#pagination_my_last").jPages({
  containerID: "itemContainer_my_last",
  perPage: 3,
  first       : false,
  previous    : "",
  next        : "",
  last        : false
});
});
</script>

<section class="my_events">
	<h2 class="title">
		<span>Мои события</span>
	</h2>
	
	<div class="events_list">
		<ul id="itemContainer_my_last">
		{% for entry in entries %}
		<li>
			<div class="my_event_item">
				<a href="{{entry.fulllink}}" class="event_name">{{entry.announce_name}}</a>
				<a href="{{entry.editlink}}">Редактировать</a>|<a href="{{entry.unpublishlink}}">Снять с публикации</a>
			</div>
		</li>
		{% endfor %}
		</ul>
	</div>
	{% if entries|length > 3 %}
	<div id="pagination_my_last" class="pagination_my_last">
	</div>
	{% endif %}
	<div class="clear"></div>

</section>
{% endif %}