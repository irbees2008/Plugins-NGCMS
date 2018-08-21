<?php

if (!defined('NGCMS')) { die("Don't you figure you're so cool?"); }
add_act('index', 'randtext');

function randtext(){
     $dir = root . 'plugins/randtext/';
$filename=$dir.'texts.txt';
	 global $template;
     $ret = '';

	function randval($mass){
 srand ((double) microtime() * 10000000);
 return $mass[ rand(0, sizeof($mass)-1 ) ];
}

$ret.=randval(file($filename));

$template['vars']['randtext'] = $ret;
}

?>