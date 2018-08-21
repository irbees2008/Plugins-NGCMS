<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

add_act('index', 'plugin_csmonitor');

include root.'/plugins/csmonitor/inc/ServerQueries.php';
include root.'/plugins/csmonitor/inc/SourceServerQueries.php';
// include root.'/plugins/csmonitor/inc/Quake2ServerQueries.php';
// include root.'/plugins/csmonitor/inc/Quake3ServerQueries.php';


function plugin_csmonitor() {
	global $template, $config, $tpl, $tvars, $pvars;
	

	
$count = extra_get_param('csmonitor','count');
if ((intval($count) < 1)||(intval($count) > 20))
	$count = 3;
	
			for ( $i = 1; $i <= $count; $i++) {
			$v = 'plugin_csmonitor_'.$i;
			
$server    = extra_get_param('csmonitor','server'.$i);
$port    = extra_get_param('csmonitor','port'.$i);
$iadress = $server.":".$port;


	// Generate cache file name [ we should take into account SWITCHER plugin ]
	$cacheFileName = md5('csmonitor'.$config['theme'].$config['default_lang'].$i).'.txt';

	if (extra_get_param('csmonitor','cache'.$i)) {
		$cacheData = cacheRetrieveFile($cacheFileName, extra_get_param('csmonitor','cacheExpire'.$i), 'csmonitor');
		if ($cacheData != false) {
			// We got data from cache. Return it and stop
			$template['vars'][$v] = $cacheData;
			continue;
		}
		
	}


$sq = new SourceServerQueries();
$server = $iadress;

    $address = explode(':', $server);
    $sq->connect($address[0], $address[1]);
    
    $info = $sq->getInfo();
    $players = $sq->getPlayers();
	
foreach ($players as $row)
{	

$pvars['vars'] = array (
			'nickname' => $row['name'],
			'kills' => $row['score'],
			'time' => $row['time'],
			);

$tpl->template('entries', extras_dir."/csmonitor/tpl");
$tpl->vars('entries', $pvars);
$output .= $tpl->show('entries');	
}


$tvars['vars'] = array(
'ip' => $server,
'serverName' => $info['serverName'],
'mapName' => $info['mapName'],
'playerNumber' => $info['playerNumber'],
'maxPlayers' => $info['maxPlayers'],
);
 

$imagemap = $info['mapName'];

if ( is_file( extras_dir."/csmonitor/map/".$imagemap.".jpg" ) ) 
$tvars['vars']['ImageMap'] = $imagemap;
else
$tvars['vars']['ImageMap'] = 'map_no_image';

    $sq->disconnect();
    flush();

				
				$tvars['vars']['entries'] = $output;
				unset($output);
				$tpl -> template('csmonitor', extras_dir."/csmonitor/tpl");
				$tpl -> vars('csmonitor', $tvars);
				$csmonitor = $tpl -> show('csmonitor');
				$template['vars'][$v] = $csmonitor;
				
		if (extra_get_param('csmonitor','cache'.$i)) {
		cacheStoreFile($cacheFileName, $csmonitor, 'csmonitor');
		}		
		}
	

}
