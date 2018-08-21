$(document).ready(function() {
    $("#file_upload").fileUpload({
        'uploader': 'uploadify/uploader.swf',
         'cancelImg': 'uploadify/cancel.png',
                'script': 'libs/subirarchivo.php',

        'folder': 'uploads',
        'buttonText': 'examinar',
        'checkScript': 'uploadify/check.php',
        'fileDesc': 'archivos imagen',
        'auto':false,
      'fileExt': '*.jpg;*.jpeg;*.gif;*.png',

        'multi': false,
        'displayData': 'percentage',
        onComplete: function (){
     verlistadoimagenes();
            $("#txtdes").val('');
        }

       });
   $('#txtdes').bind('change', function(){
	$('#file_upload').fileUploadSettings('scriptData','&des='+$(this).val());


    });

})

function startUpload(id, conditional)
{	if(conditional.value.length != 0) {
		$('#'+id).fileUploadStart();
	} else
		alert("ingrese un descripciÃ³n para la imagen");
}