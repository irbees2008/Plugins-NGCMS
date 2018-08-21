{% if (entries) %}
<script>
/* when document is ready */
$(function() {
/* initiate plugin */
$("#pagination_my_archive").jPages({
  containerID: "itemContainer_my_archive",
  perPage: 3,
  first       : false,
  previous    : "",
  next        : "",
  last        : false
});
});
</script>


<section class="my_events my_archive">
	<h2 class="title">
		<span>Мои события (Архив)</span>
	</h2>
	
	<div class="events_list">
		<ul id="itemContainer_my_archive">
		{% for entry in entries %}
		<li>
			<div class="my_event_item">
				<a href="{{entry.fulllink}}" class="event_name">{{entry.announce_name}}</a>
			</div>
		</li>
		{% endfor %}
		</ul>
	</div>
	{% if entries|length > 3 %}
	<div id="pagination_my_archive" class="pagination_my_last">
	</div>
	{% endif %}
	<div class="clear"></div>

</section>
{% endif %}

