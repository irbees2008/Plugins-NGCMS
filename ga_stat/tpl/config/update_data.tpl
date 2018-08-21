<form method="post" action="">
<tr>
<td colspan=2>
<fieldset class="admGroup">
<legend class="title">Обновление</legend>
<table width="100%" border="0" class="content">
<tr>
<td class="contentEntry1" valign=top>Обновить данные из Google Analytics вручную?<br /></td>
<td class="contentEntry2" valign=top><input name="update_me" type="submit"  value="Обновить" class="button" /></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>Включить обновление по cron?<br /></td>
<td class="contentEntry2" valign=top><select name="cron_on" >{cron_on}</select></td>
</tr>
<tr>
<td class="contentEntry1" valign=top>Время вызова по cron?<br /></td>
<td class="contentEntry2" valign=top>Min: <input name="cron_time_min" value="{cron_time_min}" size="30" /> Hour: <input name="cron_time_hour" value="{cron_time_hour}" size="30" /></td>
</tr>
</table>
</fieldset>
</td>
</tr>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input name="submit" type="submit"  value="Сохранить" class="button" />
</td>
</tr>
</table>
</form>