<?php if(!defined('NGCMS')) die('No direct script access allowed');


function redaktor(){
    global $mod, $skin_header;
    if($mod!='news') return;

    $template = '';

    $is_jquery = false;
    $is_jquery = !!(strpos($skin_header, 'jquery'));
    if(!$is_jquery) $template .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>';

    $template .= file_get_contents(dirname(__FILE__).'/tpl/tags.tpl');
    $template .= '</head>';

    $skin_header = preg_replace('!</head>!i', $template, $skin_header);
}

add_act('admin_header', 'redaktor');