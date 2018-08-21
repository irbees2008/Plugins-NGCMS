<?php

//
// Configuration file for plugin
//

// Preload config file
plugins_load_config();

$dir = root . 'plugins/randtext/';
$filename=$dir.'texts.txt';
$handle = file_get_contents($filename);



// Fill configuration parameters
$cfg = array();
array_push($cfg, array('descr' => 'Плагин позволяет выводить случайную фразу из текстового файла. Доступно по тегу {randtext}'));
array_push($cfg, array('name' => 'texts', 'title' => "Текст из файла texts.txt<br>", 'type' => 'text', 'html_flags' => 'rows=8 cols=120', 'value' => $handle));


// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	$f = fopen($filename,"w");
fwrite ($f, $_REQUEST['texts']);
fclose($f);

	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>