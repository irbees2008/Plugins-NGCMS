<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

add_act('index', 'elka');

function elka($params)
    {
	//global $confArray, $template, $CurrentHandler;
	global $confArray, $template, $CurrentHandler, $catz, $SYSTEM_FLAGS;
	//�����
	if ($CurrentHandler['pluginName'] == 'news' && $CurrentHandler['handlerName'] == 'main' )
		$template['regx']['/\[elka_morda\](.*?)\[\/elka_morda\]/si'] = '\\1';
	else
		$template['regx']['/\[elka_morda\](.*?)\[\/elka_morda\]/si'] = '';
    
    //����� ����� ����� 
     if ($CurrentHandler['pluginName'] == 'news' && $CurrentHandler['handlerName'] == 'main' ) 
          $template['regx']['/\[elka_not-morda\](.*?)\[\/elka_not-morda\]/si'] = ''; 
     else 
          $template['regx']['/\[elka_not-morda\](.*?)\[\/elka_not-morda\]/si'] = '\\1';

    //���������
    if ($CurrentHandler['pluginName'] == 'news' && $CurrentHandler['handlerName'] == 'by.category' )
        $template['regx']['/\[elka_category\](.*?)\[\/elka_category\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_category\](.*?)\[\/elka_category\]/si'] = '';

//��������� �������
     foreach($catz as $k)
     {
        $template['regx']['#\[elka_category-'.$k["alt"].'\](.+?)\[/elka_category-'.$k["alt"].'\]#is'] = (isset($SYSTEM_FLAGS['news']['currentCategory.id']) && ($k['id'] == $SYSTEM_FLAGS['news']['currentCategory.id']))?'\\1':'';
      if (!substr($k['flags'],1,0)) continue;
     }

    //����� ����� ������� ��������� �������
     foreach($catz as $v)
     {
      $template['regx']['#\[elka_category_not-'.$v["alt"].'\](.+?)\[/elka_category_not-'.$v["alt"].'\]#is'] = (isset($SYSTEM_FLAGS['news']['currentCategory.id']) && ($v['id'] == $SYSTEM_FLAGS['news']['currentCategory.id']))?'':'\\1';
      if (!substr($v['flags'],0,1)) continue;
     }


	 
    //������ �������
    if ($CurrentHandler['pluginName'] == 'news' && $CurrentHandler['handlerName'] == 'news' )
        $template['regx']['/\[elka_full-news\](.*?)\[\/elka_full-news\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_full-news\](.*?)\[\/elka_full-news\]/si'] = '';

    //�������
    if ($CurrentHandler['pluginName'] == 'static' && $CurrentHandler['handlerName'] == '' )
        $template['regx']['/\[elka_static\](.*?)\[\/elka_static\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_static\](.*?)\[\/elka_static\]/si'] = '';
 
    //������ 404
    if ($CurrentHandler['pluginName'] == $lang['404.title'] && $CurrentHandler['handlerName'] == '' )
        $template['regx']['/\[elka_error-404\](.*?)\[\/elka_error-404\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_error-404\](.*?)\[\/elka_error-404\]/si'] = '';

    //�����
    if ($CurrentHandler['pluginName'] == 'search' && $CurrentHandler['handlerName'] == '' )
        $template['regx']['/\[elka_search\](.*?)\[\/elka_search\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_search\](.*?)\[\/elka_search\]/si'] = '';
        
    //�� �����
    if ($CurrentHandler['pluginName'] != 'search')
        $template['regx']['/\[elka_not_search\](.*?)\[\/elka_not_search\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_not_search\](.*?)\[\/elka_not_search\]/si'] = '';

    //�������
    if ($CurrentHandler['pluginName'] == 'uprofile' && $CurrentHandler['handlerName'] == ('show' || 'edit') )
        $template['regx']['/\[elka_uprofile\](.*?)\[\/elka_uprofile\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_uprofile\](.*?)\[\/elka_uprofile\]/si'] = '';
    //������� - ��������
    if ($CurrentHandler['pluginName'] == 'uprofile' && $CurrentHandler['handlerName'] == 'show' )
        $template['regx']['/\[elka_uprofile-show\](.*?)\[\/elka_uprofile-show\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_uprofile-show\](.*?)\[\/elka_uprofile-show\]/si'] = '';
    //������� - �������������
    if ($CurrentHandler['pluginName'] == 'uprofile' && $CurrentHandler['handlerName'] == 'edit' )
        $template['regx']['/\[elka_uprofile-edit\](.*?)\[\/elka_uprofile-edit\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_uprofile-edit\](.*?)\[\/elka_uprofile-edit\]/si'] = '';

    //�������� �����
    if ($CurrentHandler['pluginName'] == 'feedback' && $CurrentHandler['handlerName'] == '' )
        $template['regx']['/\[elka_feedback\](.*?)\[\/elka_feedback\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_feedback\](.*?)\[\/elka_feedback\]/si'] = '';

    //���+���
    if ($CurrentHandler['pluginName'] == 'core' && $CurrentHandler['handlerName'] == ('login' || 'registration' || 'activation' || 'lostpassword') )
        $template['regx']['/\[elka_log-reg\](.*?)\[\/elka_log-reg\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_log-reg\](.*?)\[\/elka_log-reg\]/si'] = '';
    //�����
    if ($CurrentHandler['pluginName'] == 'core' && $CurrentHandler['handlerName'] == 'login' )
        $template['regx']['/\[elka_login\](.*?)\[\/elka_login\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_login\](.*?)\[\/elka_login\]/si'] = '';
    //�����������
    if ($CurrentHandler['pluginName'] == 'core' && $CurrentHandler['handlerName'] == 'registration' )
        $template['regx']['/\[elka_registration\](.*?)\[\/elka_registration\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_registration\](.*?)\[\/elka_registration\]/si'] = '';
    //���������
    if ($CurrentHandler['pluginName'] == 'core' && $CurrentHandler['handlerName'] == 'activation' )
        $template['regx']['/\[elka_activation\](.*?)\[\/elka_activation\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_activation\](.*?)\[\/elka_activation\]/si'] = '';
    //�������������� ������
    if ($CurrentHandler['pluginName'] == 'core' && $CurrentHandler['handlerName'] == 'lostpassword' )
        $template['regx']['/\[elka_lostpassword\](.*?)\[\/elka_lostpassword\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_lostpassword\](.*?)\[\/elka_lostpassword\]/si'] = '';

    //��������� ��������
    if ($CurrentHandler['pluginName'] == 'lastcomments' && $CurrentHandler['handlerName'] == '' )
        $template['regx']['/\[elka_lastcomments\](.*?)\[\/elka_lastcomments\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_lastcomments\](.*?)\[\/elka_lastcomments\]/si'] = '';

    //����
    if ($CurrentHandler['pluginName'] == 'tags' || $CurrentHandler['handlerName'] == 'tag' && $CurrentHandler['handlerName'] == '' )
        $template['regx']['/\[elka_tags\](.*?)\[\/elka_tags\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_tags\](.*?)\[\/elka_tags\]/si'] = '';
    //���� - �������� ����
    if ($CurrentHandler['pluginName'] == 'tags' && $CurrentHandler['handlerName'] == 'tag' )
        $template['regx']['/\[elka_tags-tag\](.*?)\[\/elka_tags-tag\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_tags-tag\](.*?)\[\/elka_tags-tag\]/si'] = '';
    //���� - ������ �����
    if ($CurrentHandler['pluginName'] == 'tags' && $CurrentHandler['handlerName'] == '' )
        $template['regx']['/\[elka_tags-tags\](.*?)\[\/elka_tags-tags\]/si'] = '\\1';
    else
        $template['regx']['/\[elka_tags-tags\](.*?)\[\/elka_tags-tags\]/si'] = '';

    //��� �������
    if ($CurrentHandler['pluginName'] == 'news' && $CurrentHandler['handlerName'] == 'main' || $CurrentHandler['handlerName'] == 'by.category' || $CurrentHandler['pluginName'] == 'news' && $CurrentHandler['handlerName'] == 'news' )
        $template['regx']['/\[elka_plugin\](.*?)\[\/elka_plugin\]/si'] = '';
    else
        $template['regx']['/\[elka_plugin\](.*?)\[\/elka_plugin\]/si'] = '\\1';

    }

?>
