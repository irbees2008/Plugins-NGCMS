<?php
if (!defined('NGCMS')) die ('HAL');
add_act('index', 'popular_services');

function popular_services(){
global $config, $tpl, $template, $echo_name, $userROW;

/*function get_title($html_page)
{
  // split the page into 3 sections, with the <title> and </title> tags as delimiters.
  $split_page = preg_split("%</?title[^>]*>%",$html_page);

  if (sizeof($split_page) == 3)
    return $split_page[1];
  else
    return "No title";
}*/

//$url=$_SERVER['REQUEST_URI'];
$url="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$fname= substr($_SERVER['REQUEST_URI'],Strrpos($_SERVER['REQUEST_URI'],'/')+1); 
//$temp=substr($url,0,Strlen($url)-Strlen($fname));
$path=home."/";

$title = strlen($template['vars']['titles'])-(strlen($template['vars']['titles'])-strpos($template['vars']['titles'], ":"));
$title=substr($template['vars']['titles'], $title+2);
$title = iconv( "Windows-1251", "UTF-8", $title);
$title = iconv( "UTF-8", "Windows-1251", $title);
$title = iconv( "Windows-1251", "UTF-8", $title);
//preg_match_all( "|<meta name=\"description\" content=\"(.*)\" />|sUSi", $template['vars']['mainblock'], $description);

$vkontakte="<NOINDEX><a href='http://vkontakte.ru/share.php?url=".$url."&title=".$title."&description=".$description."' target='_blank' rel='nofollow'><img src='".$path."engine/plugins/popular_services/tpl/img/vkontakte.gif'title='Поделиться ВКонтакте'></a></NOINDEX>";
$buzz="<NOINDEX><a href='http://www.google.com/buzz/post?url=".$url."&title=".$title."&srcURL=http://lugmia.gov.ru/' rel='nofollow' target='_blank'><img src='".$path."engine/plugins/popular_services/tpl/img/buzz.gif' title='Добавить в Google Buzz'></a></NOINDEX>";
$yandex="<NOINDEX><a target='_blank' rel='nofollow' href='http://my.ya.ru/posts_add_link.xml?title=".$title."&URL=".$url."'><img src='".$path."engine/plugins/popular_services/tpl/img/yandex.gif'  title='Поделиться ссылкой на Я.ру' /></a></NOINDEX>";
$mailru="<NOINDEX><a target='_blank' rel='nofollow' href='http://connect.mail.ru/share?share_url=".$url."'><img src='".$path."engine/plugins/popular_services/tpl/img/mailru.gif'  title='Добавить в Мой Мир'></a></NOINDEX>";
$facebook="<NOINDEX><a rel='nofollow' target='blank' href='http://www.facebook.com/sharer.php?u=".$url."'><img src='".$path."engine/plugins/popular_services/tpl/img/facebook.gif'  title='Поделиться ссылкой в FaceBook'></a></NOINDEX>";
$gg="<NOINDEX><a target='_blank' rel='nofollow' href='http://www.livejournal.com/update.bml?event=".$url."&subject=".$title."' ><img src='".$path."engine/plugins/popular_services/tpl/img/gg.gif' title='Добавить в свой блог на livejournal.com'></a></NOINDEX>";
$friendlent="<NOINDEX><a href='http://www.liveinternet.ru/journal_post.php?action=l_add&amp;cnurl=".$url."' target='_blank'><img src='".$path."engine/plugins/popular_services/tpl/img/friendlent.gif' title='Добавить в свой блог на ЛиРу (Liveinternet)'></a></NOINDEX>";
$blogger="<NOINDEX><a target='_blank' rel='nofollow' href='http://www.blogger.com/blog_this.pyra?t&amp;u=".$url."&amp;n=".$title."&amp;a=ADD_SERVICE_FLAG&amp;passive=true&amp;alinsu=0&amp;aplinsu=0&amp;alwf=true&amp;hl=ru&amp;skipvpage=true&amp;rm=false&amp;showra=1&amp;fpui=2&amp;naui=8'><img src='".$path."engine/plugins/popular_services/tpl/img/blogger.gif' title='Добавить в свой блог на Blogger.com'></a></NOINDEX>";
$twitter="<NOINDEX><a target='_blank' rel='nofollow' href='http://twitter.com/home/?status=".$title."+".$url."'><img src='".$path."engine/plugins/popular_services/tpl/img/twitter.gif' title='Добавить в Twitter'></a></NOINDEX>";

unset($tvars);
	$tvars['vars'] = array (
		'vkontakte' => $vkontakte,
		'buzz' => $buzz,
		'yandex' => $yandex,
		'mailru' => $mailru,
		'facebook' => $facebook,
		'gg' => $gg,
		'friendlent' => $friendlent,
		'blogger' => $blogger,
		'twitter' => $twitter,
	);
	
	$tpl->template('popular_services',extras_dir.'/popular_services/tpl');
	$tpl -> vars('popular_services',$tvars);
	
	$output = $tpl -> show('popular_services');
	$template['vars']['popular_services'] = $output;
}
?>