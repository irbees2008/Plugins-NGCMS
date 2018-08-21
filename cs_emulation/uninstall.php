<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

plugins_load_config();

$db_update = array(
    array(
        'table'  => 'cs_tournament',
        'action' => 'drop',
    ),
    array(
        'table'  => 'cs_team',
        'action' => 'drop',
    ),
    array(
        'table'  => 'cs_game',
        'action' => 'drop',
    )
);

if ($_REQUEST['action'] == 'commit')
{
    if (fixdb_plugin_install($plugin, $db_update, 'deinstall'))
    {
        plugin_mark_deinstalled($plugin);
    }
}
else
{
    generate_install_page($plugin, "Удаление плагина {$plugin}", 'deinstall');
}

?>