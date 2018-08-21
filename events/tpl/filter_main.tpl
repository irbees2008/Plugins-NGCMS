<script>
$(document).ready(function(){

filter_go('', '', '', '', 0);

$("#filter_event").click(function() {

	filter_go($('#announce_city_filter').val(), $('#announce_type_filter').val(), $('#announce_datepicker_filter').val(), $('#announce_sex_filter').val(), $('#announce_page_filter').val());

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

	<h2 class="title top">
			<span>Актуальные события</span>
	</h2>

	<div class="filter">
				<div class="title">
					Фильтр
				</div>
				<div class="type">
		    		<select class="select" id="announce_type_filter">
		    			<option value="0">Тип</option>
						{{categories}}
		    		</select>
	    		</div>

	    		<select class="select" id="announce_city_filter">
	    			<option value="0">Город</option>
					{{cities}}
	    		</select>

				<input class="select datepicker" name="announce_datepicker_filter" id="announce_datepicker_filter" placeholder="Дата">

	    		<select class="select" id="announce_sex_filter">
	    			<option value="N">Пол</option>
					<option value="M">Мужской</option>
					<option value="F">Женский</option>
	    		</select>
	    		<a href="#" id="filter_event" class="btn">НАЙТИ</a>
	    		<div class="clear"></div>
	</div>
	<div id="event_filter_status"></div>
