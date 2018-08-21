<form action="/engine/admin.php?mod=extra-config&plugin=quotes&action=showlist" method="post" name="options_bar">
	<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
		<tr>
			<td style="padding-left : 10px;">Сортировать по<br />
				<select name="sort">
					{sort}
				</select>
			</td>
			<td>Месяц<br />
				<select name="postdate">
					<option selected value="">Всё</option>
					{selectdate}
					</select>
			</td>
			<td>Автор<br />
				<select name="author">
					{author}
				</select>
			</td> 
			<td>Активна<br />
				<select name="status">
					{status}
				</select>
			</td> 
			<td><input type="submit" value="Показать" class="button" /></td> 
		</tr> 
	</table> 
</form> 