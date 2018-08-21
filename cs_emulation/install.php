<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

plugins_load_config();

$db_update = array(
    array(
        'table'  => 'cs_tournament',
        'action' => 'cmodify',
        'key'    => "PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)",
        'fields' => array(
            array('action' => 'cmodify', 'name' => '`id`', 'type' => 'int(11)', 'params' => "NOT NULL auto_increment"),
            array('action' => 'cmodify', 'name' => '`name`', 'type' => 'varchar(255)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`type`', 'type' => 'tinyint(1)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`team_count`', 'type' => 'tinyint(1)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`timestamp`', 'type' => 'int(11)', 'params' => "NOT NULL")
        )
    ),
    array(
        'table'  => 'cs_team',
        'action' => 'cmodify',
        'key'    => "PRIMARY KEY (`id`)",
        'fields' => array(
            array('action' => 'cmodify', 'name' => '`id`', 'type' => 'int(11)', 'params' => "NOT NULL auto_increment"),
            array('action' => 'cmodify', 'name' => '`tid`', 'type' => 'int(11)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`name`', 'type' => 'varchar(255)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`group`', 'type' => 'varchar(1)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`timestamp`', 'type' => 'int(11)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`flag`', 'type' => 'varchar(2)', 'params' => "NOT NULL")
        )
    ),
    array(
        'table'  => 'cs_game',
        'action' => 'cmodify',
        'key'    => "PRIMARY KEY (`id`)",
        'fields' => array(
            array('action' => 'cmodify', 'name' => '`id`', 'type' => 'int(11)', 'params' => "NOT NULL auto_increment"),
            array('action' => 'cmodify', 'name' => '`tid`', 'type' => 'int(11)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`map`', 'type' => 'varchar(255)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`team1_id`', 'type' => 'int(11)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`team2_id`', 'type' => 'int(11)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`team1_score`', 'type' => 'int(11)', 'params' => "NOT NULL"),
            array('action' => 'cmodify', 'name' => '`team2_score`', 'type' => 'int(11)', 'params' => "NOT NULL")
        )
    )
);

if ($_REQUEST['action'] == 'commit')
{
    if (fixdb_plugin_install($plugin, $db_update))
    {
        plugin_mark_installed($plugin);
    }
}
else
{
    generate_install_page($plugin, "Установка плагина {$plugin}");
}

?>