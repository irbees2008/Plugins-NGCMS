<?php
if (!defined('NGCMS')) { die("Don't you figure you're so cool?"); }

plugins_load_config();
	$cfg = array();
		array_push($cfg, array('name' => 'length', 'title' => 'Минимальная длина слова', 'descr' => '(хороший вариант 5)', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','length')));
		array_push($cfg, array('name' => 'sub', 'title' => 'Максимальная длина слова', 'descr' => 'По умолчанию не ограничено', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','sub')));
		array_push($cfg, array('name' => 'occur', 'title' => 'Минимальное число повторений слова', 'descr' => '(хороший вариант 2)','type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','occur')));
		array_push($cfg, array('name' => 'block_y', 'title' => '<b>Нежелательные слова</b>', 'descr' => 'включение/выключение опции','type' => 'checkbox', value => extra_get_param('autokeys','block_y')));
		array_push($cfg, array('name' => 'block', 'title' => 'Список нежелательных слов<br><br><i>На каждой строке вводится по одноу слову. Слова из этого списка не будут попадать в keywords.</i>','type' => 'text', 'html_flags' => 'rows=8 cols=60', 'value' => extra_get_param('autokeys','block')));
		array_push($cfg, array('name' => 'good_y', 'title' => '<b>Желаемые слова</b>', 'descr' => 'включение/выключение опции','type' => 'checkbox', value => extra_get_param('autokeys','good_y')));
		array_push($cfg, array('name' => 'good', 'title' => 'Список желаемых слов<br><br><i>На каждой строке вводится по одноу слову. Слова из этого всегда будут попадать в keywords.</i>','type' => 'text', 'html_flags' => 'rows=8 cols=60', 'value' => extra_get_param('autokeys','good')));
		array_push($cfg, array('name' => 'add_title', 'title' => 'Учитывать заголовок', 'descr' => 'Добавления заголовка новости к тексту новости для генерации ключевых слов<br />значение от 0 до бесконечности: <br />0 - не добавлять, 1 - добавлять, 2 - добавить два раза', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','add_title')));
		array_push($cfg, array('name' => 'sum', 'title' => 'Длина ключевых слов', 'descr' => 'Длина всех ключевых слов генерируемых плагином (По умолчанию <=245 симолов)', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','sum')));
		array_push($cfg, array('name' => 'count', 'title' => 'Количество ключевых слов', 'descr' => 'Количество ключевых слов генерируемых плагином (По умолчанию неограниченное количество)', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','count')));
		array_push($cfg, array('name' => 'good_b', 'title' => '<b>Усиление слов</b>', 'descr' => 'Усиление слов в теге [b]','type' => 'checkbox', value => extra_get_param('autokeys','good_b')));
		
		
	if ($_REQUEST['action'] == 'commit') {
		commit_plugin_config_changes('autokeys', $cfg);
		print "Настройки забацаны: <a href='admin.php?mod=extra-config&plugin=autokeys'>Маладец жми назад</a>\n";
	} else {
		generate_config_page('autokeys', $cfg);
	}
?>