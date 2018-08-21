{% if (error) %}
<div class="feed-me">
{{error}}
</div>
{% endif %}

<article class="full-post">
	<div class="titlef">
     �������������� �������
	</div><br/>
	<form method="post" action="" class="comment-form" name="form" enctype="multipart/form-data">
			<section class="profile-section">

				<div class="line" style="width: 200px;">
					<span class="label">����:</span>
					<select class="select" name="announce_city_edit" id="announce_city_edit" style="width: 100px;">
						<option value="0"></option>
						{{cities}}
		    		</select>
		    		<div class="clear"></div>
		    	</div>
				<div class="line"  style="width: 200px;">
					<span class="label">���:</span>
					<select class="select" name="announce_type_edit" id="announce_type_edit">
						<option value="0"></option>
						{{options}}
		    		</select>
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label">���� ����:</span>
					<input type="text" class="text_input" name="announce_name_edit" id="announce_name_edit" value="{{announce_name}}" />
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label">����� �����:</span>
					<input type="text" class="text_input" name="announce_place_edit" id="announce_place_edit" value="{{announce_place}}" />
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label">����� �����:</span>
					<input type="text" class="text_input time ui-timepicker-input" name="announce_timepicker_edit" id="announce_timepicker_edit" autocomplete="off" value="{{time}}" />
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label">����:</span>
					<input type="text" class="text_input datepicker" name="announce_datepicker_edit" id="announce_datepicker_edit" value="{{date}}">
		    		<div class="clear"></div>
			    </div>
				<div class="line">
					<span class="label last">���-�� ����������:</span>
					<textarea name="announce_description_edit" id="announce_description_edit">{{announce_description}}</textarea>
			    </div>	
				
				<span class="submit"><button name="submit" type="submit" tabindex="5">���������</button></span>
				<span class="submit"><button tabindex="5" type="reset" >�����</button></span>			
			
			</section>
			</form>
</article>