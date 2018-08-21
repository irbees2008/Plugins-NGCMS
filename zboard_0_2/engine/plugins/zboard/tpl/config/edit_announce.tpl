<script src="{tpl_home}/plugins/zboard/upload/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="{tpl_home}/plugins/zboard/upload/uploadify/uploadify.css" type="text/css" />
<link rel="stylesheet" href="{tpl_home}/plugins/zboard/tpl/config/capty/jquery.capty.css" type="text/css" />
<script type="text/javascript" src="{tpl_home}/plugins/zboard/upload/uploadify/jquery.uploadify.js"></script>
<script type="text/javascript" src="{tpl_home}/plugins/zboard/tpl/config/capty/jquery.capty.min.js"></script>

{error}
<form method="post" action="" name="form" enctype="multipart/form-data">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">Заголовок объявления<br /><small></small></td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="announce_name" value="{announce_name}"  /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Автор<br /><small></small></td>
<td width="50%" class="contentEntry2"><input type="text" size="40"  name="author" value="{author}"  /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Период объявления<br /><small></small></td>
<td width="50%" class="contentEntry2">
<select name="announce_period">
{list_period}
</select></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Категория<br /><small></small></td>
<td width="50%" class="contentEntry2"><select name="cat_id">
{options}
</select>
</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Текст объявления<br /><small></small></td>
<td width="50%" class="contentEntry2"><textarea type="text" name="announce_description" cols="100" rows="10">{announce_description}</textarea></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Контакты<br /><small></small></td>
<td width="50%" class="contentEntry2"><textarea type="text" name="announce_contacts" cols="100" rows="10">{announce_contacts}</textarea></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Прикрепить изображения<br /><small></small></td>
<td width="50%" class="contentEntry2">

<script type="text/javascript">
$(document).ready(function() {
    $("#file_upload").fileUpload({
        'uploader': '/engine/plugins/zboard/upload/uploadify/uploader.swf',
        'cancelImg': '/engine/plugins/zboard/upload/uploadify/cancel.png',
        'script': '/engine/plugins/zboard/upload/libs/subirarchivo.php?id={id}',
        'folder': '',
        'buttonText': 'Select files ...',
       // 'checkScript': '/engine/plugins/zboard/upload/uploadify/check.php',
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

<input type="hidden" id="txtdes" name="txtdes" value="{id}" />
<input id="file_upload" type="file" name="file_upload" />
<!--<input type="button" value="Загрузить" onclick="javascript:startUpload('file_upload')"/> -->

</td>
</tr>

<tr>
<td width="50%" class="contentEntry1">Прикрепленные изображения<br /><small></small></td>
<td width="50%" class="contentEntry2">
<table>
<tr>
{entriesImg}
</tr>
</table>
</td>
</tr>

<tr>
<td width="50%" class="contentEntry1">Активировать объявление?<br /><small></small></td>
<td width="50%" class="contentEntry2"><input type="checkbox" name="announce_activeme" {announce_activeme} value="1" > </td>
</tr>

</table>




<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="submit" name="submit" value="Отредактировать" onclick="javascript:startUpload('file_upload')" class="button" />
<input type="submit" name="delme" value="Удалить" class="button" />
</td>
</tr>
</table>
</form>
