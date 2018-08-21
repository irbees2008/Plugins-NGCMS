{error}
[latent]
<script type="text/javascript">
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
<form name="form" method="post" action="" onSubmit="return NumChar();">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_40.gif" width="7" height="36"></td>
				<td background="{tpl_url}/images/2z_41.gif" width="100%">&nbsp;<font color="#FFFFFF"><b>Добавить цитату</b></font></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_44.gif" width="7" height="36"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td background="{tpl_url}/images/2z_54.gif" width="7">&nbsp;</td>
				<td bgcolor="#FFFFFF">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
</td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" style="padding-left: 10px;">
<tr>
<td width="100%" style="padding: 3px;">{bbcodes}<br />{smilies}<br /><textarea name="content" id="content" rows="10" cols="75"></textarea></td>
</tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="submit" name="addpost" value="Добавить новость" class="button" />
</td>
</tr>
</table>
				</td>
				<td background="{tpl_url}/images/2z_59.gif" width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_68.gif" width="7" height="4"></td>
				<td background="{tpl_url}/images/2z_69.gif" width="100%"></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_70.gif" width="7" height="4"></td>
			</tr>
		</table>
		</td>
	</tr>
</table></form>