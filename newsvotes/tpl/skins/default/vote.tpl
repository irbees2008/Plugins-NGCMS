<div class="alibi">
<strong><font color="ff0000">{vote}</font></strong><br/>
<form action="{self}" method="post" id="newsvoteForm">
<input type=hidden name="mode" value="vote" />
<input type=hidden name="voteid" value="{voteid}" />
{answers}
[is-rate]<input class="button" type=submit value="Голосовать" />[/is-rate] Всего проголосовало: <strong>{total}</strong>
</form>
</div>