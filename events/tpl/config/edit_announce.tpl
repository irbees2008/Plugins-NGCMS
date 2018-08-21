{error}
<form method="post" action="" name="form" enctype="multipart/form-data">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">Заголовок мероприятия<br /><small></small></td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="announce_name" value="{announce_name}"  /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Место сбора мероприятия<br /><small></small></td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="announce_place" value="{announce_place}"  /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Автор<br /><small></small></td>
<td width="50%" class="contentEntry2">{author}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Дата добавления/редактирования<br /><small></small></td>
<td width="50%" class="contentEntry2">
{editdate}
</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Дата мероприятия<br /><small></small></td>
<td width="50%" class="contentEntry2">
{date}
</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Город<br /><small></small></td>
<td width="50%" class="contentEntry2"><select name="city_id">
{cities}
</select>
</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Категория<br /><small></small></td>
<td width="50%" class="contentEntry2"><select name="cat_id">
{options}
</select>
</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Текст мероприятия<br /><small></small></td>
<td width="50%" class="contentEntry2"><textarea type="text" name="announce_description" cols="100" rows="10">{announce_description}</textarea></td>
</tr>

<tr>
<td width="50%" class="contentEntry1">В архиве?<br /><small></small></td>
<td width="50%" class="contentEntry2"><input type="checkbox" name="announce_arhiveme" {announce_arhiveme} value="1" > </td>
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
<input type="submit" name="submit" value="Отредактировать" class="button" />
<input type="submit" name="delme" value="Удалить" class="button" />
</td>
</tr>
</table>
</form>
