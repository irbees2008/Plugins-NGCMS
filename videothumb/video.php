<?php
require 'videothumb.class.php';

$url = isset($_REQUEST['url']) ? trim($_REQUEST['url']) : '';
$title = isset($_REQUEST['title']) ? trim($_REQUEST['title']) : '';
$vt_img = isset($_REQUEST['vt_img']) ? trim($_REQUEST['vt_img']) : '';

$class = new videoThumb(array(
	'imagesPath' => $_SERVER['DOCUMENT_ROOT'] .'/uploads/videothumb/'
	,'imagesUrl' => '/uploads/videothumb/'
	,'emptyImage' => '/uploads/videothumb/_empty.png'
));
//var_dump($_SERVER['HTTP_HOST']);
$video = $class->process($url, $title, $vt_img);

print_r( ajax($video) );


// AJAX output
function ajax($data) {
    if (is_array($data) || is_object($data)) {
        $data = ajaxencode($data);
    }
   return $data;
}

function ajaxencode($data) {
    if (is_object($data)) $data = get_object_vars($data);
    $out = array();
    $keys = array();
    if (is_array($data)) $keys = array_keys($data);
    $numeric = true;
    if (!empty($keys)) $numeric = (array_values($keys) === array_keys(array_values($keys)));

    foreach ($data as $key => $val) {
        if (is_array($val) || is_object($val)) {
            $val = ajaxencode($val);
        } else {
            if (is_numeric($val)) {
                $val = $val;
            } elseif (is_bool($val)) {
                $val = ($val) ? 'true' : 'false';
            } elseif (is_null($val)) {
                $val = 'null';
            } else {
                $val = '"' . ajaxescape($val) . '"';
            }
        }
        if (!$numeric) {
            $val = '"' . $key . '"' . ':' . $val;
        }
        $out[] = $val;
    }
    if (!$numeric) {
        $rt = '{' . join(', ', $out) . '}';
    } else {
        $rt = '[' . join(', ', $out) . ']';
    }
    return $rt;
}
function ajaxescape($string) {
    // Escape these characters with a backslash:
    // " \ / \n \r \t \b \f
    $search  = array('\\', "\n", "\t", "\r", "\b", "\f", '"');
    $replace = array('\\\\', '\\n', '\\t', '\\r', '\\b', '\\f', '\"');
    $string  = str_replace($search, $replace, $string);

    // Escape certain ASCII characters:
    // 0x08 => \b
    // 0x0c => \f
    $string = str_replace(array(chr(0x08), chr(0x0C)), array('\b', '\f'), $string);

    return $string;
}