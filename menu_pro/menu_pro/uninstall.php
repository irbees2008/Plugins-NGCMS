<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
loadPluginLang('menu_pro', 'config', '', '', ':');

if ($_REQUEST['action'] == 'commit') {
	plugin_mark_deinstalled('menu_pro');
} else {
	$text = $lang['menu_pro:desc_deinstall'];
	generate_install_page('menu_pro', $text, 'deinstall');
}