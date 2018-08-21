<?php

/*
 * LastTweets for NGCMS
 * Copyright (C) 2010 Alexey N. Zhukov (http://digitalplace.ru)
 * http://digitalplace.ru
 * 
 * use twitteroauth library https://github.com/abraham/twitteroauth
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
 
	// Protect against hack attempts
	if (!defined('NGCMS')) die ('Galaxy in danger');

	define('CONSUMER_KEY', 'VmsYgJt2EqZ0u4QbClesg');
	define('CONSUMER_SECRET', 'UQVYYsy98n1mO6Xop3EmhJfpiZokGliQig743M');
	
	add_act('index', 'lasttweets');

function lasttweets(){
    global $template, $tpl, $config;	
	
	// Generate cache file name
	$cacheFileName = md5('lasttweets'.$config['theme'].$config['default_lang']).'.txt';
	
	if (pluginGetVariable('lasttweets', 'cache')) {
		$cacheData = cacheRetrieveFile($cacheFileName, pluginGetVariable('lasttweets','cacheExpire'), 'lasttweets');
		if ($cacheData != false) {
			// We got data from cache. Return it and stop
			$template['vars']['plugin_lasttweets'] = $cacheData;
			return;
		}
	}
	
	$count = intval(pluginGetVariable('lasttweets', 'count'));
	$GMT = intval(pluginGetVariable('lasttweets', 'GMT'));
	$username = pluginGetVariable('lasttweets', 'twi_username');
	if (($count < 1) || ($count > 200))  $count = 5;
	
	switch(intval(pluginGetVariable('lasttweets', 'timeline'))){
		case 0: {
			$rss_url = 'http://twitter.com/statuses/user_timeline/'.$username.'.rss?count='.$count;
			$rss_data = @file_get_contents($rss_url);
			break;
		}
		
		case 1: {
			require_once('inc/twitteroauth.php');
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, pluginGetVariable('lasttweets', 'oauth_token'), pluginGetVariable('lasttweets', 'oauth_token_secret'));
			$connection->format = 'rss';
			$rss_data = $connection->get('statuses/home_timeline',array('id' => pluginGetVariable('lasttweets', 'user_id'), 'count' => $count));
			break;
		}
		
		case 2: {
			$rss_data = @file_get_contents('http://search.twitter.com/search.rss?lang=ru&q='.urlencode(pluginGetVariable('lasttweets', 'search')).'&rpp='.$count);
			break;
		}
	}
			
			
	// Determine paths for all template files
	$tpath = locatePluginTemplates(array('lasttweets', 'entries'), 'lasttweets', intval(pluginGetVariable('lasttweets', 'localsource')));
	
    if (!$rss_data) {
		$template['vars']['plugin_lasttweets'] = "<p>Ooooops</p><p>Looks like Twitter's feed isn't working at the moment.</p>";;
		return;
    } else {

    $rss_xml = SimpleXML_Load_String($rss_data);
	
	$channel_title = $rss_xml->channel->title;
    $channel_link = $rss_xml->channel->link;
	
    foreach ($rss_xml->channel->item as $item) {
        $item_title = $item->title;
        $item_link = $item->link;
		$item_pubDate = $item->pubDate;
		$GMT2 = gmdate("D, d M Y H:i:s", strtotime($item_pubDate)+$GMT);
		
		if(pluginGetVariable('lasttweets', 'timeline') == 0) 
			$item_title = substr($item_title, strpos($item_title, ': ') + 2);
		
        $tweetmessage = preg_replace("/(http:\/\/[^\s]+)/", "<noindex><a href=\"$1\" rel=\"nofollow\">$1</a></noindex>", $item_title);
		$tweetmessage = preg_replace("/(@[^\s]+)/", "<span class=\"tweet-url\">$1</span>", $tweetmessage);

		$tvars['vars'] = array(
			'tweet'		=> iconv("utf-8", "windows-1251", $tweetmessage)."\n",
			'time' 		=> $GMT2,
			'link'		=> $item_link
		);
	
	    $tpl -> template('entries', $tpath['entries']);
        $tpl -> vars('entries', $tvars);
        $tweets .= $tpl -> show('entries');
        }
	}
	
	$tpl -> template('lasttweets', $tpath['lasttweets']);
	$tpl -> vars('lasttweets', array ('vars' => array (
			 'entries' => $tweets,
			 'title' => $channel_title, 
			 'link' =>  $channel_link
			)));
	
	$output .= $tpl -> show('lasttweets');
	
	$template['vars']['plugin_lasttweets'] = $output;
	
	if (pluginGetVariable('lasttweets','cache')) {
		cacheStoreFile($cacheFileName, $output, 'lasttweets');
	}
}