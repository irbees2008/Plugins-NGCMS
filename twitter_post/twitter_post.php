<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');



function add_js_to_header(){
    global $mod, $skin_header;
    if($mod!='news') return;

    $template = '';

    $is_jquery = false;
    $is_jquery = !!(strpos($skin_header, 'jquery'));
    if(!$is_jquery) $template .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.2.3/jquery.min.js"></script>';
    $template .= file_get_contents(dirname(__FILE__).'/tpl/tags.tpl');
    $template .= '</head>';
    $skin_header = preg_replace('!</head>!i', $template, $skin_header);
}

add_act('admin_header', 'add_js_to_header');

class MessegingTwitterFilter extends NewsFilter {

    function addNewsForm(&$tvars) {

        $message_template = pluginGetVariable('twitter_post', 'message_template');
 
        $tvars['plugin']['twitter_post_textarea']  = '<tr><td><img src="/engine/skins/default/images/nav.png" hspace="8" alt="" /></td><td>Текст для Twitter:</td><td><textarea id ="twitter_post_textarea" name="twitter_post_textarea">'.$message_template.'</textarea></td></tr>';
        $tvars['plugin']['twitter_post_checkbox']  = '<label><input type="checkbox" name="twitter_post_checkbox" value="1" class="check"> Отправить в twitter?</label>';
        return 1;
    }

    function addNews(&$tvars, &$SQL) {
        global $mysql, $parse;

        $consumer_key = pluginGetVariable('twitter_post', 'consumer_key');
        $consumer_secret = pluginGetVariable('twitter_post', 'consumer_secret');
        $access_token = pluginGetVariable('twitter_post', 'access_token');
        $access_token_secret = pluginGetVariable('twitter_post', 'access_token_secret');
        
        require_once('TwitterAPIExchange.php');

        /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
        $settings = array(
            'oauth_access_token' => $access_token,
            'oauth_access_token_secret' => $access_token_secret,
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret
        );

        //var_dump($SQL);
        
        $matches = array();
        //preg_match_all('/(https?:\/\/\S+\.(?:jpg|png|gif))\s+/', $_REQUEST['ng_news_content'], $matches);
        preg_match_all('/(http|https):\/\/[^ ]+(\.gif|\.jpg|\.jpeg|\.png)/', $_REQUEST['ng_news_content'], $matches);
        
        $url_path_arr = array();
        foreach($matches[0] as $id=>$img_url) {
            $url_path = parse_url($img_url)['path'];
            $parts = explode('/', $url_path);
            $last = end($parts);
            $url_path_arr[$last] = $img_url;
        }
        
        //var_dump($url_path_arr);
        
        $media_ids_arr = array();
        $cnt_media = 0;
        if(!empty($url_path_arr)) {
            foreach($url_path_arr as $id=>$img_url) {
                if($cnt_media < 3) {
                    
                    $url = 'https://upload.twitter.com/1.1/media/upload.json';
                    $requestMethod = 'POST';
                    $postfields = array(
                        'media' => base64_encode(file_get_contents($img_url)),
                    );
                    
                    $twitter = new TwitterAPIExchange($settings);
                    $out = $twitter->buildOauth($url, $requestMethod)
                         ->setPostfields($postfields)
                         ->performRequest();
                    $media_json = json_decode($out, true);
                    var_dump($media_json);
                    $media_id = $media_json['media_id_string'];
                    $media_ids_arr[] = $media_id;
                    
                    /*
                    $name = basename($url);
                    $uploaded = file_put_contents(dirname(__FILE__)."/uploads/$name", file_get_contents($url)); 
                    if($uploaded) {
                        var_dump($uploaded);
                        
                    }
                    * */
                    
                    
                }
                else {
                    break;
                }
                
                $cnt_media += 1;
                
            }
            
            $media_ids = implode(",", $media_ids_arr);
            
        }
        else {
            $media_ids = "";
        }
        
        //var_dump($media_ids);
        
        
        /** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
        $url = 'https://api.twitter.com/1.1/statuses/update.json';
        $requestMethod = 'POST';
        
        if ($_REQUEST['twitter_post_checkbox'] == 1) {

            $link_to_news = newsGenerateLink($SQL, false, 0, true);
            $twitterContent = str_replace(array('{news_content}', '{link_to_news}', '{news_title}'), array($_REQUEST['ng_news_content'], $link_to_news, $_REQUEST['title']), $_REQUEST['twitter_post_textarea']);
            
            
            /** POST fields required by the URL above. See relevant docs as above **/
            $postfields['status'] = iconv("windows-1251", "UTF-8", $twitterContent);
            if($media_ids != "") {
                $postfields['media_ids'] = $media_ids;
            }

            /** Perform a POST request and echo the response **/
            
            $twitter = new TwitterAPIExchange($settings);
            $out = $twitter->buildOauth($url, $requestMethod)
                         ->setPostfields($postfields)
                         ->performRequest();
            
            //var_dump($out);
        }
        
        return 1;
    }

}

register_filter('news','twitter_post', new MessegingTwitterFilter);
