<div class="pagination_wrap">
	<div class="pagination" id="archive">
		[prev-link]Пред.[/prev-link]
		{pages}
		[next-link]След.[/next-link]
	</div>
</div>

<script>

$(document).ready(function(){
	$("#archive a").on('click', function(e) {
	var page = $(this).data('page');
	
	if(page == 'След.') {
		$("#archive a").each(function() {
			   if($(this).hasClass("current")) {
				page = $(this).data('page')+1;
			   }
		});
	}
	else if (page == 'Пред.') {
		$("#archive a").each(function() {
			   if($(this).hasClass("current")) {
				page = $(this).data('page')-1;
			   }
		});
	
	}

		archive_go(page);
		e.preventDefault();
	});
	
  
function archive_go(announce_page_filter) {
 
	$.post('/engine/rpc.php', { json : 1, methodName : 'events_archive_main', rndval: new Date().getTime(), params : json_encode({ 'announce_page_filter' : announce_page_filter }) }, function(data) {

		// Try to decode incoming data
		try {
			resTX = data;
		//	alert(resTX['data']['feedback_text']);
		} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }
		if (!resTX['status']) {
			alert('Error ['+resTX['errorCode']+']: '+resTX['errorText']);
		} else {
			if ((resTX['data']['event_archive']>0)&&(resTX['data']['event_archive'] < 100)) {
				$("div#event_archive_status").html("<span style='color:#b54d4b; font-size:10px;'>"+resTX['data']['event_archive_text']+"</span>");
			} else {
				$("div#event_archive_status").html("<span style='color:#94c37a; font-size:10px;'>"+resTX['data']['event_archive_text']+"</span>");
			}
		}
	}).error(function() { 
		alert('HTTP error during request', 'ERROR'); 
	});
 
 }
});
</script>