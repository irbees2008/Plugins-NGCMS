<script src="{{tpl_home}}/plugins/zboard/upload/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="{{tpl_home}}/plugins/zboard/upload/uploadify/uploadify.css" type="text/css" />
<link rel="stylesheet" href="{{tpl_home}}/plugins/zboard/tpl/config/capty/jquery.capty.css" type="text/css" />
<script type="text/javascript" src="{{tpl_home}}/plugins/zboard/upload/uploadify/jquery.uploadify.js"></script>
<script type="text/javascript" src="{{tpl_home}}/plugins/zboard/tpl/config/capty/jquery.capty.min.js"></script>

{% if (error) %}
<div class="feed-me">
{{error}}
</div>
{% endif %}

<script language="javascript" type="text/javascript">
var currentInputAreaID = 'content_description';
</script>
<div class="comment">
<h3><span>�������������� ����������</span></h3>
<form method="post" action="" class="comment-form" name="form" enctype="multipart/form-data">
<ul class="comment-author">
<li class="item clearfix">
<input type="text" name="announce_name" value="{{announce_name}}" tabindex="1">
<label>��������� ���������� <i>(*)</i></label>
</li>
<li class="item clearfix">
<input type="text" name="author" value="{{author}}" tabindex="1">
<label>����� <i>(*)</i></label>
</li>
<li class="item clearfix">
<select name="announce_period">
{{list_period}}
</select>
<label>������ ���������� <i>(*)</i></label>
</li>
<li class="item clearfix">
<select name="cat_id">
{{options}}
</select>
<label>��������� <i>(*)</i></label>
</li>
</ul>
<span class="textarea">
<label>�������� ���������� <i>(*)</i></label><br/><br/>
{{bb_tags}}
<textarea type="text" id="content_description" name="announce_description" tabindex="4">{{announce_description}}</textarea>

</span>
<span class="textarea">
�������� <i>(*)</i>
<textarea type="text" name="announce_contacts" tabindex="4">{{announce_contacts}}</textarea>
</span>

<ul class="comment-author">
<li class="item clearfix">
<script type="text/javascript">
$(document).ready(function() {
    $("#file_upload").fileUpload({
        'uploader': '/engine/plugins/zboard/upload/uploadify/uploader.swf',
        'cancelImg': '/engine/plugins/zboard/upload/uploadify/cancel.png',
        'script': '/engine/plugins/zboard/upload/libs/subirarchivo.php?id={{id}}',
        'folder': '',
        'buttonText': 'Select files ...',
        'checkScript': '/engine/plugins/zboard/upload/uploadify/check.php',
        'fileDesc': 'images',
        'auto':false,
		'fileExt': '*.jpg;*.jpeg;*.gif;*.png',
        'multi': true,
		'scriptData': $("#txtdes").val(),
        'displayData': 'percentage',
        onComplete: function (){
			verlistadoimagenes();
			$("#txtdes").val();
			location.reload();
        }
       });
	   
	   $('#file_upload').fileUploadSettings('scriptData','&des='+$("#txtdes").val());

$('.fix').capty({
   cWrapper:  'capty-tile',
   height:   36,
   opacity:  .6
 });
	   
	   
});



function startUpload(id)
{	$('#'+id).fileUploadStart();
}
</script>
<label>���������� �����������</label><br/><br/>
<input type="hidden" id="txtdes" name="txtdes" value="{{id}}" />
<input id="file_upload" type="file" name="file_upload" />
</li>

<li class="item clearfix">
<label>������������� �����������</label><br/><br/>
<table>
<tr>
{% for entry in entriesImg %}
<td style="padding-left:5px;">
<a href='{{entry.home}}/uploads/zboard/{{entry.filepath}}' target='_blank'><img class="fix" name="#content-target-{{entry.pid}}" src='{{entry.home}}/uploads/zboard/thumb/{{entry.filepath}}' width='150' height='120'></a>
<div id="content-target-{{entry.pid}}">
<a href="{{entry.del}}">[x]</a>&nbsp;&nbsp;&nbsp;
</div>
</td>
{% endfor %}
</tr>
</table>
</li>

</ul>
<span class="submit"><button name="submit" type="submit"  tabindex="5" onclick="javascript:startUpload('file_upload')" >���������</button></span>
<span class="submit"><button tabindex="5" type="reset" >�����</button></span>
</form>
</div>