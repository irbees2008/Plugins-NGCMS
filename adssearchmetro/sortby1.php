<?php
$s1 = $_GET['s1'];


if($s1 == "1") { ?>

	<label for="s2">Аренда или продажа:<br /></label>
	<select id="s2">
	<option value="1">Аренда</option>
	<option value="2">Продажа</option>
	</select>


<script type="text/javascript" language="JavaScript">
    $("#s2").change(function () {
          $("#feld2").load("http://metroskop.ru/engine/plugins/adssearchmetro/sortby2.php?s2="+ $(this).val());
        })
        .change();

</script>
	
	<div id="feld2"> </div>


<?php
}else if($s1 == "2") { ?>

	<label for="s2">Аренда или продажа<br /></label>
	<select id="s2">
	<option value="11">Аренда</option>
	<option value="12">Продажа</option>
	</select>
	

<script type="text/javascript" language="JavaScript">
    $("#s2").change(function () {
          $("#feld2").load("http://metroskop.ru/engine/plugins/adssearchmetro/sortby2.php?s2="+ $(this).val());
        })
        .change();
</script>
	
	<div id="feld2"> </div>


<?php
}else{
print '';
}

?>
