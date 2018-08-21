<div class="pagination_wrap">
	<div class="pagination" id="events">
		[prev-link]Пред.[/prev-link]
		{pages}
		[next-link]След.[/next-link]
	</div>
</div>

<script>

$(document).ready(function(){
	$("#events a").on('click', function(e) {
	var page = $(this).data('page');
	
	if(page == 'След.') {
		$("#events a").each(function() {
			   if($(this).hasClass("current")) {
				page = $(this).data('page')+1;
			   }
		});
	}
	else if (page == 'Пред.') {
		$("#events a").each(function() {
			   if($(this).hasClass("current")) {
				page = $(this).data('page')-1;
			   }
		});
	
	}

		filter_go($('#announce_city_filter').val(), $('#announce_type_filter').val(), $('#announce_datepicker_filter').val(), $('#announce_sex_filter').val(), page);
		e.preventDefault();
	});
	
  
function filter_go(announce_city_filter, announce_type_filter, announce_datepicker_filter, announce_sex_filter, announce_page_filter) {
 
	$.post('/engine/rpc.php', { json : 1, methodName : 'events_filter_main', rndval: new Date().getTime(), params : json_encode({ 'announce_city_filter' : announce_city_filter, 'announce_type_filter' : announce_type_filter, 'announce_datepicker_filter' : announce_datepicker_filter, 'announce_sex_filter' : announce_sex_filter, 'announce_page_filter' : announce_page_filter }) }, function(data) {

		// Try to decode incoming data
		try {
			resTX = data;
		//	alert(resTX['data']['feedback_text']);
		} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }
		if (!resTX['status']) {
			alert('Error ['+resTX['errorCode']+']: '+resTX['errorText']);
		} else {
			if ((resTX['data']['event_filter']>0)&&(resTX['data']['event_filter'] < 100)) {
				$("div#event_filter_status").html("<span style='color:#b54d4b; font-size:10px;'>"+resTX['data']['event_filter_text']+"</span>");
			} else {
				$("div#event_filter_status").html("<span style='color:#94c37a; font-size:10px;'>"+resTX['data']['event_filter_text']+"</span>");
			}
		}
	}).error(function() { 
		alert('HTTP error during request', 'ERROR'); 
	});
 
 }
});
</script>