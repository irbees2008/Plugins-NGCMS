<?php if(!defined('NGCMS')) die ('HAL');

function tblank(){
    

    
}
register_htmlvar('js', admin_url.'/plugins/tblank/tpl/js/engage.tblank-min.js');

register_htmlvar('css', admin_url.'/plugins/tblank/tpl/css/engage.tblank.css');


add_act('index', 'tblank');
 
function tblank() {
	global $template, $tvars;
 
		// ВАШ КОД
		$template['vars']['tblank'] = $*;
}