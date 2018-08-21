<?php

if (!defined('NGCMS')) die ('HAL');

class socialNewsFilter extends NewsFilter {
  
  function showNews($newsID, $SQLnews, &$tvars) {
	global $config, $tpl, $parse;

	if (pluginGetVariable('social', 'cache')) {
		$cacheFileName = md5('social'.$newsID.$config['home_url'].$config['theme'].$config['default_lang']).'.txt';
        
		$cacheData = cacheRetrieveFile($cacheFileName, pluginGetVariable('social', 'cacheExpire'), 'social');
		if ($cacheData != false) {
	   	   $tvars['vars']['plugin_social'] = $cacheData;
		  return 1;				
	   }
	}

	// Determine paths for all template files
    $tpath  = locatePluginTemplates(array('social', 'entries'), 'social', pluginGetVariable('social', 'localsource'), pluginGetVariable('social', 'skin')?pluginGetVariable('social', 'skin'):'default');

	$link	= pluginGetVariable('social','integration')?make_bitly_url(newsGenerateLink($SQLnews, false, 0, true),pluginGetVariable('social','login'),pluginGetVariable('social','api_key'),'json'):newsGenerateLink($SQLnews, false, 0, true);

	$title	= $SQLnews['title'];
	list ($short_news, $full_news) = explode('<!--more-->', $SQLnews['content'], 2);
    $content= arrayCharsetConvert(0, $parse->truncateHTML(strip_tags($short_news?$short_news:$full_news), 30));
	
	$services = pluginGetVariable('social', 'services');
	
	$entries = '';
	if (is_array($services)) {
		foreach($services as $id=>$row) {
	       if ($row['active']) {		
                $pvars['vars'] = array (
				    'tpl_url'	=> tpl_url,
                    'url'		=> str_replace(array('%title%','%content%','%link%'),array(urlencode(arrayCharsetConvert(0,$title)), urlencode($content), urlencode($link)), $row['link']),
                    'title'		=> $title,
				    'desc'		=> $row['title']?$row['title']:''
                );
        		
                $pvars['regx']['/\[img\](.*?)\[\/img\]/si']       = '$1';
        		$pvars['regx']['/\[no-img\](.*?)\[\/no-img\]/si'] = '';
                
                if ((!pluginGetVariable('social', 'localsource'))&&(is_dir(tpl_site.'/plugins/social/images'))) {
                    $pvars['vars']['img'] = tpl_url.'/plugins/social/images/'.$row['img'];
                } elseif ((pluginGetVariable('social', 'localsource'))&&(pluginGetVariable('social', 'skin'))&&(is_dir(extras_dir.'/social/tpl/skins/'.pluginGetVariable('social', 'skin').'/images'))) {
                    $pvars['vars']['img'] = admin_url.'/plugins/social/tpl/skins/'.pluginGetVariable('social', 'skin').'/images/'.$row['img'];
                } elseif (is_dir(extras_dir.'/social/tpl/skins/default/images')){
                    $pvars['vars']['img'] = admin_url.'/plugins/social/tpl/skins/default/images/'.$row['img'];
                } else {
                    $pvars['regx']['/\[img\](.*?)\[\/img\]/si']       = '';
            		$pvars['regx']['/\[no-img\](.*?)\[\/no-img\]/si'] = '$1';
                    $pvars['vars']['img'] = '';
                }
                
                $tpl -> template('entries', $tpath['entries']);
			    $tpl -> vars('entries', $pvars);
			    $entries .= $tpl -> show('entries');
    	   }
        }
	}
    
	$tpl -> template('social', $tpath['social']);
	$tpl -> vars('social', array ('vars' => array ('entries' => $entries, 'tpl_url' => tpl_url)));
	$tvars['vars']['plugin_social'] = $tpl -> show('social');	 

    if (pluginGetVariable('social', 'cache')) cacheStoreFile($cacheFileName, $tvars['vars']['plugin_social'], 'social');
   
	return 1;
  }
}

register_filter('news', 'social', new socialNewsFilter);

/*	
	Bit.ly shortener 
	Based on code from David Walsh  
	http://davidwalsh.name/bitly-php  
*/   
function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1') {   
	//create the URL   
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;   
  
    //get the url   
    //could also use cURL here   
    $response = file_get_contents($bitly);   
  
    //parse depending on desired format   
    if(strtolower($format) == 'json') {   
		$json = @json_decode($response,true);   
        return $json['results'][$url]['shortUrl'];   
	} else {//xml     
		$xml = simplexml_load_string($response);   
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;   
	}   
}