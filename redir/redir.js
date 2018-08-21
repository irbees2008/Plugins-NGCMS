$(document).ready(function(){ 
$('a[href^="http:"]').each(function(){ 
if(this.host!= location.hostname ){
$(this).attr("target","_blank").attr("href",function() { return '/engine/plugins/redir/index.php?' + this.href }); 
} 
}) 
});