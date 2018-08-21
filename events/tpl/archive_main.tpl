<script>
$(document).ready(function(){

archive_go(0);

function archive_go(announce_page_filter) {
 
	$.post('/engine/rpc.php', { json : 1, methodName : 'events_archive_main', rndval: new Date().getTime(), params : json_encode({'announce_page_filter' : announce_page_filter }) }, function(data) {

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

<div id="event_archive_status"></div>

				<!--
				<div class="event">
					<a href="{{home}}" class="event_name">����� �������</a>
					<div class="event_info">
						<span class="time">21 ��� 2014�., 9,30</span>
						<span class="place">���� ������</span>
					</div>
					<div class="user_info">
						<a href="{{home}}" class="avatar">
			    			<img alt="" src="{{tpl_url}}/images/temp/E1Jsckbqq00.jpg" class="avatar_round">
			    		</a>
			    		<div class="info">
			    			<a href="{{home}}" class="name">������� ��������</a>
			    			<div class="about">
			    				<span>23 ���</span>, <span>������</span>
			    			</div>
			    		</div>
			    		<div class="clear"></div>
					</div>
					<div class="description">
						<div class="head">��������</div>
						<span class="text">
							���� 20-25 ��/� ����� ����, �����, ���. ���� �� ��������, ����������� ����������
						</span>
					</div>
					<a href="{{home}}" class="comments">���������� (5)</a>
				</div>
				

				<div class="clear"></div>
				<div class="pagination_wrap">
					<div class="pagination">
						<a href="{{home}}" class="prev">����.</a>
						<a href="{{home}}">1</a>
						<a href="{{home}}" class="current">2</a>
						<a href="{{home}}">3</a>
						<a href="{{home}}">4</a>
						<a href="{{home}}">5</a>
						<a href="{{home}}">6</a>
						<a href="{{home}}">7</a>
						<a href="{{home}}">..</a>
						<a href="{{home}}" class="next">����.</a>
					</div>
				</div>
			</div>-->