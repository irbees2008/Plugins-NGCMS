<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

class VideoThumbFilter extends NewsFilter {

	function addNewsForm(&$tvars) {
		//$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array('personal.publish', 'personal.unpublish', 'other.publish', 'other.unpublish'));
		
		//var_dump( $_SERVER['PHP_SELF'] );

		$tvars['plugin']['videothumb_form']  = '
				<input type="text" id="videoThumb_url" name="videoThumb_url" size="60" placeholder="—сылка на видео с youTube" />
				<input type="button" value="ќтправить" class="videothumb-button" onclick="videothumb();" />
				<a id="videoThumb_clear" href="#" class="btn" style="display:none">ќчистить</a>
				<div id="videoThumb_response">
				<div id="videoThumb_spinner" style="text-align:center;display:none;margin:20px 0;"><img src="/engine/skins/default/images/loading.gif" alt="" title="" /></div>
				</div>
				<input type="hidden" id="vt_img" name="vt_img" value="" />
				<input type="hidden" id="vt_url" name="vt_url" value="" />
		';
		
		return 1;
	}
	
	function addNews(&$tvars, &$SQL) {
	global $mysql, $parse;

		$vt_img = $_REQUEST['vt_img'];
		$vt_url = $_REQUEST['vt_url'];

		// Make a resulting line
		$SQL['videothumb_img']   = isset($vt_img)?$vt_img:'';
		$SQL['videothumb_link']   = isset($vt_url)?$vt_url:'';

		return 1;

	}
	
	
	function editNewsForm($newsID, $SQLold, &$tvars) {
	        global $tpl;
		
		if(isset($SQLold["videothumb_img"]) && $SQLold["videothumb_img"] != '') $findImage = "<img src=".$SQLold["videothumb_img"]." width='320' />";
		
		$tvars['plugin']['videothumb_form']  = '
				<input type="text" id="videoThumb_url" name="videoThumb_url" size="60" placeholder="—сылка на видео с youTube" value="'.$SQLold["videothumb_link"].'" />
				<input type="button" value="ќтправить" class="videothumb-button" onclick="videothumb();" />
				<a id="videoThumb_clear" href="#" class="btn" style="display:none">ќчистить</a>
				<div id="outImg">'.$findImage.'</div>
				<div id="videoThumb_response">
				<div id="videoThumb_spinner" style="text-align:center;display:none;margin:20px 0;"><img src="/engine/skins/default/images/loading.gif" alt="" title="" /></div>
				</div>
				<input type="hidden" id="vt_img" name="vt_img" value="'.$SQLold["videothumb_img"].'" />
				<input type="hidden" id="vt_url" name="vt_url" value="'.$SQLold["videothumb_link"].'" />
		';
		
		return 1;
	}
	
	function editNews($newsID, $SQLold, &$SQLnew, &$tvars) {

	
		$vt_img = $_REQUEST['vt_img'];
		$vt_url = $_REQUEST['vt_url'];

		// Make a resulting line
		$SQLnew['videothumb_img']   = isset($vt_img)?$vt_img:'';
		$SQLnew['videothumb_link']   = isset($vt_url)?$vt_url:'';

		return 1;
	}
	
	// Show news call :: processor (call after all processing is finished and before show)
	function showNews($newsID, $SQLnews, &$tvars, $mode = array()) {
		global $mysql, $config, $twigLoader, $twig, $PFILTERS, $twig, $twigLoader, $parse;

		$tvars['vars']['p']['videothumb']['img']['value'] = "<img src=".$SQLnews['videothumb_img']." title=".$SQLnews['title']." title=".$SQLnews['alt']." />";
		$tvars['vars']['p']['videothumb']['img_src']['value'] = $SQLnews['videothumb_img'];
		$tvars['vars']['p']['videothumb']['link']['value'] = $SQLnews['videothumb_link'];
	
	}

}

register_filter('news','videothumb', new VideoThumbFilter);