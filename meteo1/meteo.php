<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

add_act('index', 'plugin_meteo');

function plugin_meteo() {
	global $config, $template;

$city   = intval(pluginGetVariable('meteo','city'));

	// Generate cache file name [ we should take into account SWITCHER plugin ]
	$cacheFileName = md5('meteo'.$config['theme'].$config['default_lang']).'.txt';


	if (pluginGetVariable('meteo','cache')) {
		$cacheData = cacheRetrieveFile($cacheFileName, extra_get_param('meteo','cacheExpire'), 'meteo');
		if ($cacheData != false) {
			// We got data from cache. Return it and stop
			$template['vars']['plugin_meteo'] = $cacheData;
			return;
		}
		}
	
	
$url = "http://pogoda.mail.ru/informer/weather.js?city=".$city."&view=2&encoding=win"; 
$html = file_get_contents($url); 
$html = str_replace("document.writeln('",'',$html); 
$html = str_replace("');",'',$html);
// If your don't want to show name of the city uncomment line below
// $html = preg_replace('/<h2>.*?<\/h2>/i','',$html); 
$html = preg_replace('/<a.*?[>^]/i','',$html); 
$html = str_replace('</a>','',$html); 
$html = str_replace('<br>подробный прогноз','',$html); 
$html = preg_replace('/<div class=\"top\">.*<\/div>/i','',$html); 
$html = str_replace(':1px solid #ced0d9;border-top','',$html); 
$meteo = str_replace('h1 a','h1',$html);

$template['vars']['plugin_meteo'] = $meteo;

	if (extra_get_param('meteo','cache')) {
		cacheStoreFile($cacheFileName, $meteo, 'meteo');
	}


}
