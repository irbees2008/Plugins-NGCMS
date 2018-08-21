<script type="text/javascript">
var ajax = new sack();
function rating(rating, id){
	ajax.onShow("");
	ajax.setVar("rating", rating);
	ajax.setVar("id", id);
	ajax.requestFile = '{ajax_url}';
	ajax.method = 'GET';
	ajax.element = 'ratingdiv_'+id;
	ajax.runAJAX();
}
</script>

<div id="ratingdiv_{id}">
<a href="#" title="+" onclick="rating('1', '{id}'); return false;"><img src="{admin_url}/plugins/quotes/tpl/plus.gif" /></a>
<span class="rating_{color}">{rating}</span>
<a href="#" title="-" onclick="rating('-1', '{id}'); return false;"><img src="{admin_url}/plugins/quotes/tpl/minus.gif" /></a>
</div>