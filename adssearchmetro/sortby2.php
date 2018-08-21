<?php
$s2 = $_GET['s2'];

//print $sort2;


if($s2 == "1") { ?>

	<label for="cat">Выбор категории:<br /></label>
	<select name="cat">
<!--	<option value="12">Все предложения</option> -->
	<option value="57">Комнаты</option>
	<option value="58">1-комн. кв-ры</option>
	<option value="59">2-комн. кв-ры</option>
	<option value="60">3-комн. кв-ры</option>
	<option value="61">4- и более комнат</option>
	<option value="62">Новостройки</option>
	</select>


<?php
}else if($s2 == "2") { ?>

	<label for="cat">Выбор категории:<br /></label>
	<select name="cat">
<!--	<option value="10">Все предложения</option> -->
	<option value="37">Комнаты</option>
	<option value="38">1-комн. кв-ры</option>
	<option value="39">2-комн. кв-ры</option>
	<option value="40">3-комн. кв-ры</option>
	<option value="41">4- и более комнат</option>
	<option value="42">Новостройки</option>
	</select>

<?php
}else if($s2 == "11") { ?>

	<label for="cat">Выбор категории:<br /></label>
	<select name="cat">
<!--	<option value="13">Все предложения</option> -->
	<option value="67">Офисы</option>
	<option value="68">Торговые</option>
	<option value="69">Склады</option>
	<option value="70">Общепит</option>
	<option value="71">Быт. услуги</option>
	<option value="72">Гаражи</option>
	<option value="73">Своб. назначения</option>
	<option value="74">Здания (ОСЗ)</option>
	<option value="75">Производственные</option>
	</select>


<?php
}else if($s2 == "12") { ?>

	<label for="cat">Выбор категории:<br /></label>
	<select name="cat">
<!--	<option value="11">Все предложения</option> -->
	<option value="47">Офисы</option>
	<option value="48">Торговые</option>
	<option value="49">Склады</option>
	<option value="50">Общепит</option>
	<option value="51">Быт. услуги</option>
	<option value="52">Гаражи</option>
	<option value="53">Своб. назначения</option>
	<option value="54">Здания (ОСЗ)</option>
	<option value="55">Производственные</option>
	<option value="56">Продажа бизнеса</option>
	</select>

<?php
}else{
print '';
}
?>
