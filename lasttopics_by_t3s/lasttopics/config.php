<?php

//
// Configuration file for plugin
//

// Preload config file
plugins_load_config();


// Fill configuration parameters

$cfg = array();

$cfgX = array();
array_push($cfgX, array('name' => 'position', 'title' => "Управление размещения блока:<small><br /><b>Нигде</b> -  не отображать нигде<br /><b>Морда</b> - только на головной странице<br /><b>!Морда </b> - везде кроме главной<br /><b>Везде</b> - на всех страницах</small>", 'type' => 'select', 'values' => array ( '' => 'Нигде', 'root' => 'Морда', 'noroot' => '!Морда', 'all' => 'Везде'), 'value' => extra_get_param('lasttopics','position')));
array_push($cfgX, array('name' => 'rssurl', 'title' => 'Адрес RSS-ленты форума:', 'descr' => 'Адрес RSS-ленты форума (напр.: http://www.nulled.cc/<b>external.php?type=RSS2</b>)', 'type' => 'input', 'value' => extra_get_param('lasttopics','rssurl')));
array_push($cfgX, array('name' => 'number', 'title' => 'Количество выводимых сообщений:', 'descr' => 'Например: 10', 'type' => 'input', 'value' => extra_get_param('lasttopics','number')));
array_push($cfgX, array('name' => 'topicname', 'title' => 'Кол-во слов в анонсе:', 'descr' => 'Например: 25', 'type' => 'input', 'value' => extra_get_param('lasttopics','topicname')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки вывода форумных сообщений</b>', 'entries' => $cfgX));

$cfgX = array();
array_push($cfgX, array('name' => 'cache', 'title' => "Использовать кеширование карты сайта<br /><small><b>Да</b> - кеширование используется<br /><b>Нет</b> - кеширование не используется</small>", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param('lasttopics','cache'))));
array_push($cfgX, array('name' => 'cacheExpire', 'title' => 'Период обновления кеша (в секундах)<br /><small>(через сколько секунд происходит обновление кеша. Значение по умолчанию: <b>10800</b>, т.е. 3 часа)', 'type' => 'input', 'value' => intval(extra_get_param('lasttopics','cacheExpire'))?extra_get_param('lasttopics','cacheExpire'):'10800'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки кеширования</b>', 'entries' => $cfgX));
// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes('lasttopics', $cfg);
	print_commit_complete('lasttopics');
} else {
	generate_config_page('lasttopics', $cfg);
}

?>