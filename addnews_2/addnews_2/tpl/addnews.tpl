<script language="javascript" type="text/javascript">

var currentInputAreaID = 'ng_news_content';

function changeActive(name) {
 if (name == 'full') {
	document.getElementById('container.content.full').className  = 'contentActive';
	document.getElementById('container.content.short').className = 'contentInactive';
	currentInputAreaID = 'ng_news_content_full';
 } else {
	document.getElementById('container.content.short').className = 'contentActive';
	document.getElementById('container.content.full').className  = 'contentInactive';
	currentInputAreaID = 'ng_news_content_short';
 }
}
</script>
{info}
{preview}
{error}
<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
<input type="hidden" name="area" value="" />
</form>
<form method="post" name="form" action="" enctype="multipart/form-data">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_40.gif" width="7" height="36" /></td>
				<td style="background-image:url('{tpl_url}/images/2z_41.gif');" width="100%">&nbsp;<b><font color="#FFFFFF">Добавить новость</font></b></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_44.gif" width="7" height="36" /></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="background-image:url('{tpl_url}/images/2z_54.gif');" width="7">&nbsp;</td>
				<td bgcolor="#FFFFFF">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="200" style="padding-left: 15px;">Заголовок</td>
						<td style="padding: 5px;"><input type="text" size="30" name="title" value="{title}" /></td>
					</tr>
					<tr>
						<td width="200" style="padding-left: 15px;">Альт. имя:</td>
						<td style="padding: 5px;"><input type="text" size="30" name="alt_name" value="{alt_name}" /></td>
					</tr>
                    <tr>
						<td width="200" style="padding-left: 15px;">Категории</td>
						<td style="padding: 5px;">{categories}</td>
					</tr>
					<tr>
						<td width="200" style="padding-left: 15px;">Дополнительные категория</td>
						<td style="padding: 5px;">{addit_category}</td>
					</tr>
					<tr>
						<td valign="top" colspan=3>{quicktags}<br /> {smilies}<br />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 15px;">
							<div id="container.content" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" name="ng_news_content" id="ng_news_content" rows="10" tabindex="2">{content}</textarea></div>
						</td>
					</tr>
					[meta]
					<tr>
						<td width="200" style="padding-left: 15px;">Описание</td>
						<td style="padding: 5px;"><input type="text" size="30" name="description" value="{description}" /></td>
					</tr>
					<tr>
						<td width="200" style="padding-left: 15px;">Ключевые слова</td>
						<td style="padding: 5px;"><input type="text" size="30" name="keywords" value="{keywords}" /></td>
					</tr>
					[/meta]
					<tr>
						[approve]<td style="padding-left: 15px;"><label><input type="checkbox" name="approve" value="1" class="check" id="approve" {approve} /> Опубликовать</label></td>[/approve]
						[mainpage]<td style="padding-left: 5px;"><label><input type="checkbox" name="mainpage" value="1" class="check" id="mainpage" {mainpage} /> Отобразить на главной</label></td>[/mainpage]
					</tr>
					[captcha]
					<tr>
						<td style="padding-left: 15px;"><img id="img_captcha" src="{captcha_url}" alt="captcha" /></td>
						<td style="padding: 5px;"><input class="important" type="text" name="vcode" maxlength="5" size="30" /></td>
					</tr>
					[/captcha]
					[protec_bot]
					<tr>
						<td style="padding-left: 15px;">{result}</td>
						<td style="padding: 5px;"><input class="important" type="text" name="result" maxlength="10" size="30" /></td>
					</tr>
					[/protec_bot]
					
					<span class="f15">Список приложенных файлов:</span>
					<table width="98%" cellspacing="1" cellpadding="2" border="0" id="attachFilelist">
					<thead>
					<tr class="contHead"><td>ID</td><td width="80">Дата</td><td width="10">&nbsp;</td><td>Имя</td><td width="90">Размер</td><td width="40">DEL</td></tr>
					</thead>
					<tbody>
					<tr><td colspan="6">Нет приложенных файлов</td></tr>
					<tr><td colspan="3">&nbsp;</td><td colspan="2"><input type="button" class="button" value="Добавить строки" style="width: 100%;" onclick="attachAddRow();" /></td></tr>
					</table>
					<tr>
						<td style="padding: 15px;" align="left" colspan="2"><input type="submit" name="submit" class="button" value="Добавить новость"/>&nbsp; <input type="submit" name="preview" class="button" value="Предосмотр" /></td>
					</tr>
				</table>
				</td>
				<td style="background-image:url('{tpl_url}/images/2z_59.gif');" width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_68.gif" width="7" height="4" /></td>
				<td style="background-image:url('{tpl_url}/images/2z_69.gif');" width="100%"></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_70.gif" width="7" height="4" /></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
<script language="javascript" type="text/javascript">
<!--
function attachAddRow() {
	var tbl = document.getElementById('attachFilelist');
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow - 1);

	// Add cells
	row.insertCell(-1).innerHTML = '*';
	row.insertCell(-1).innerHTML = 'Загрузить';
	row.insertCell(-1).innerHTML = '';

	// Add file input
	var el = document.createElement('input');
	el.setAttribute('type', 'file');
	el.setAttribute('name', 'userfile[' + (++attachAbsoluteRowID) + ']');
	el.setAttribute('size', '80');

	var xCell = row.insertCell(-1);
	xCell.colSpan = 2;
	xCell.appendChild(el);


	el = document.createElement('input');
	el.setAttribute('type', 'button');
	el.setAttribute('onclick', 'document.getElementById("attachFilelist").deleteRow(this.parentNode.parentNode.rowIndex);');
	el.setAttribute('value', '-');
	row.insertCell(-1).appendChild(el);
}
// Add first row
var attachAbsoluteRowID = 0;
attachAddRow();
-->
</script>