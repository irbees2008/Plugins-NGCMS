<!---->
<form name=ads_search action="/plugin/adssearchmetro/" class="ads_search" method="GET">
<div style="margin-left:-215px;width:190px;background:#d0d0d0;">


<div id="feld0">

	<label for="s1">Жилая/Коммерческая:<br /></label>
	<select id="s1">
	<option value="1">Жилая</option>
	<option value="2">Коммерческая</option>
	</select>

</div>

<script type="text/javascript" language="JavaScript">
$(document).ready(
function (){
$("#feld1").load("http://metroskop.ru/engine/plugins/adssearchmetro/sortby1.php?s1=1");
});

    $("#s1").change(function () {
          $("#feld1").load("http://metroskop.ru/engine/plugins/adssearchmetro/sortby1.php?s1="+ $(this).val());
        })
        .change();

</script>
	
	<div id="feld1"> </div>

	<div class="clearing">&nbsp;</div>

<table width="100%">
  <tr>
	<td valign="top" width="20%">
	</td> 

	<td valign="top" width="20%">
	</td>

	<td valign="top" width="20%">
	<input style="margin:10px auto;" type="submit" value="Найти" />
	</td>

  </tr>
</table>
</div>

<input type="hidden" name="page" value="1" />
</form>

<!---->
<div style="margin-top:-135px;">
{adstypes}

<table class="table_ads" width="100%">
	<tr>
	<td class="cat_table_2">ID</td>
	<td class="cat_table_1">Фото</td>
	<td class="cat_table_3">Метро/Адрес</td>
	<td class="cat_table_5">Цена</td>
	<td class="cat_table_4">Метраж</td>
	<td class="cat_table_6">Этаж/тип дома</td>
	<td class="cat_table_7">Доп. сведения</td>
	</tr>
	
{entries}

</table>

{pagination}
</div>