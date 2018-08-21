{error}
[latent]
<script>
function NumChar() {
	p_msg = document.form.content.value.toString();
	if (p_msg.length > {max_char}) {
		alert ("Текст сообщения не должен быть не больше {max_char} символов");
		document.form.content.focus();
	return false;
	}
}
</script>
[/latent]
<table border="0" cellspacing="1" cellpadding="1" class="content">
<tr>
<td colspan="2" width=100% class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"><a href="?mod=extra-config&plugin=quotes" title="Общие настройки плагина">Общие настройки плагина</a> <b>=></b> <a href="?mod=extra-config&plugin=quotes&action=showlist">Спискок всех цитат</a></td>
</tr>
</table>
<form name="form" method="post" action="" onSubmit="return NumChar();">
<table width="100%" border="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">Редактирование формы ID {id}</td>
</tr>
<tr align="left"><td class="contentRow" width="170"><b>Текст цитаты:</b></td><td>{smilies}<br />{bbcodes}<textarea style="margin-left: 0px;" cols="80" rows="6" id="content" name="content">{content}</textarea></td></tr>
<tr align="left"><td class="contentRow" width="170"><b>Активна:</b></td><td><select name="active" style="width:auto">{active}</select></td></tr>
<tr align="left"><td class="contentRow" width="170"><b>Выставить рейтинг:</b></td><td><input type="text" size="3" name="rating" value="{rating}" /></td></tr>
<tr><td colspan="2"><input type="submit" name="actions" value="Сохранить" /></td></tr>
</table>
<hr/>
</form>