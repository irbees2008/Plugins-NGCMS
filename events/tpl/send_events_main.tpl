<script>
$(document).ready(function(){

$("#send_event").click(function() {

	$.post('/engine/rpc.php', { json : 1, methodName : 'events_send_main', rndval: new Date().getTime(), params : json_encode({ 'announce_city' : $('#announce_city').val(), 'announce_type' : $('#announce_type').val(), 'announce_name' : $('#announce_name').val(), 'announce_place' : $('#announce_place').val(), 'announce_timepicker' : $('#announce_timepicker').val(), 'announce_datepicker' : $('#announce_datepicker').val(), 'announce_description' : $('#announce_description').val() }) }, function(data) {
		// Try to decode incoming data
		try {
			resTX = data;
		//	alert(resTX['data']['feedback_text']);
		} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }
		if (!resTX['status']) {
			alert('Error ['+resTX['errorCode']+']: '+resTX['errorText']);
		} else {
			if ((resTX['data']['event_send']>0)&&(resTX['data']['event_send'] < 100)) {
				$("div#event_send_status").html("<span style='color:#b54d4b; font-size:10px;'>"+resTX['data']['event_send_text']+"</span>");
			} else {
				$("div#event_send_status").html("<span style='color:#94c37a; font-size:10px;'>"+resTX['data']['event_send_text']+"</span>");
				$('#name').val('');
				$('#phone').val('');
				$('#message').val('');
				$('#mcode').val('');
			}
		}
	}).error(function() { 
		alert('HTTP error during request', 'ERROR'); 
	});

  });
  
});

</script>

			<section class="create">
				<h2 class="title top_title">
					Создать событие
				</h2>
				<div class="line">
					<span class="label">Куда:</span>
					<select class="select" id="announce_city">
						<option value="0"></option>
						{{cities}}
		    		</select>
		    		<div class="clear"></div>
		    	</div>
				<div class="line">
					<span class="label">Вид:</span>
					<select class="select" id="announce_type">
						<option value="0"></option>
						{{categories}}
		    		</select>
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label">Куда едем:</span>
					<input type="text" class="text_input" id="announce_name" value="{{announce_name}}" />
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label">Место сбора:</span>
					<input type="text" class="text_input" id="announce_place" value="{{announce_place}}" />
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label">Время сбора:</span>
					<input type="text" class="text_input time ui-timepicker-input" name="announce_timepicker" id="announce_timepicker" autocomplete="off" />
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label">Дата:</span>
					<input type="text" class="text_input datepicker" name="announce_datepicker" id="announce_datepicker">
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label last">Доп-ая информация:</span>
					<textarea name="announce_description" id="announce_description">{{announce_description}}</textarea>
			    </div>
			    <a href="#" class="main_btn" id="send_event">Создать событие</a>
				<div id="event_send_status"></div>
    		</section>