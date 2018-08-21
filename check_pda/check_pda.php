<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

add_act('core', 'check_pda');

function check_pda(){
    global $config, $SYSTEM_FLAGS;
    $SYSTEM_FLAGS['check_pda'] = check_pda_ff();
}

function check_pda_ff() {
    $phone_array = array('iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
    $agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );
    foreach ($phone_array as $value) {
        if ( strpos($agent, $value) !== false ) return true;
    }
    return false;
}
