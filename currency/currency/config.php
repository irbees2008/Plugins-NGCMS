<?php

# protect against hack attempts
if (!defined('NGCMS')) die ('Galaxy in danger');

# preload config file
pluginsLoadConfig();

LoadPluginLang('currency', 'config', '', 'curr', ':');

# fill configuration parameters
$cfg = array();
array_push($cfg, array('descr' => $lang['curr:description']));

$cfgX = array();
array_push($cfgX,
  array(
    'name'   => 'localsource',
    'title'  => $lang['curr:localsource'],
    'type'   => 'select',
    'values' => array ( '0' => $lang['curr:localsource_0'], '1' => $lang['curr:localsource_1']),
    'value'  => intval(pluginGetVariable($plugin, 'localsource'))
  )
);

array_push($cfg,
  array(
    'mode'    => 'group',
    'title'   => $lang['curr:template'],
    'entries' => $cfgX
  )
);

$cfgX = array();
array_push($cfgX,
  array(
    'name'    => 'cache',
    'title'   => $lang['curr:usecache'],
    'type'    => 'select',
    'values'  => array('1' => 'Да', '0' => 'Нет'),
    'value'   => intval(extra_get_param($plugin,'cache'))
  )
);
array_push($cfgX,
  array(
    'name'    => 'cacheExpire',
    'title'   => $lang['curr:expire'],
    'type'    => 'input',
    'value'   => intval(extra_get_param($plugin,'cacheExpire')) ? extra_get_param($plugin,'cacheExpire') : '3600'
  )
);

array_push($cfg, array(
    'mode'    => 'group',
    'title'   => $lang['curr:cache'],
    'entries' => $cfgX
  )
);


# RUN
if ($_REQUEST['action'] == 'commit') {
  # if submit requested, do config save
  commit_plugin_config_changes($plugin, $cfg);
  print_commit_complete($plugin);
} else {
  generate_config_page($plugin, $cfg);
}
