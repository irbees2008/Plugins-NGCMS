<?php

if (!defined('NGCMS')) exit('HAL');

rpcRegisterFunction('news_feedback_add', 'add_feedback');
rpcRegisterFunction('news_feedback_captcha', 'captcha_feedback');

/*
add_act('index', 'news_feedback_form');
function news_feedback_form($params) {
	global $mysql, $twig, $config, $template;

		@session_register('captcha');
		$_SESSION['captcha'] = mt_rand(00000, 99999);

		$template['vars']['plugin_news_feedback'] = show_feedback();	
}
*/

class ShowFeedback_NewsFilter extends NewsFilter {
	function showNews($newsID, $SQLnews, &$tvars, $mode = array()){
		global $mysql, $twig, $config;

		@session_register('captcha');
		$_SESSION['captcha'] = mt_rand(00000, 99999);

		$tvars['vars']['plugin_news_feedback'] = show_feedback($SQLnews);
		
	}
}


function show_feedback($Snews)
{
global $tpl, $SYSTEM_FLAGS, $twig, $config, $parse, $userROW;
	
	$tpath = locatePluginTemplates(array('news_feedback'), 'news_feedback', pluginGetVariable('news_feedback', 'localsource'));
	$xt = $twig->loadTemplate($tpath['news_feedback'].'news_feedback.tpl');

	$tVars = array(
			'news_url'	=> home.newsGenerateLink($Snews),
			'news_title'	=> $Snews['title'],
			'act' => home."/engine/rpc.php?methodName=news_feedback_add"
			);
	
	$output = $xt->render($tVars);

	return $output;
}

function add_feedback($params)
{
global $tpl, $template, $twig, $SYSTEM_FLAGS, $config, $userROW, $mysql;

	// Prepare basic reply array
		$results = array();

		// Check for promocode
		if ( isset($params['name']) && isset($params['phone']) && isset($params['message']) && isset($params['mcode']) && isset($params['news_url']) && isset($params['news_title']) ) {

			$name = secure_html(convert(trim($params['name'])));
			$phone = secure_html(convert(trim($params['phone'])));
			$message = secure_html(convert(trim($params['message'])));
			$mcode = secure_html(convert(trim($params['mcode'])));
			$news_url = secure_html(convert(trim($params['news_url'])));
			$news_title = secure_html(convert(trim($params['news_title'])));

			if(empty($name))
			{
				$error_text[] = 'Вы не заполнили имя!';
			}
			
			if(empty($message))
			{
				$error_text[] = 'Вы не заполнили сообщение!';
			}
			
			if(empty($phone))
			{
				$error_text[] = 'Вы не заполнили телефон!';
			}
					
			if (!$mcode || ($_SESSION['captcha'] != $mcode)) 
			{
				$error_text[] = "Проверочный код введен неправильно!".$mcode;
			}		
			//$_SESSION['captcha'] = rand(00000, 99999);
			
			if( empty($error_text) )
			{

				foreach ($mysql->select('SELECT id, mail FROM '.prefix.'_users WHERE status = \'1\'', 1) as $row)
				{
					$tpath = locatePluginTemplates(array('mail_feedback'), 'news_feedback', pluginGetVariable('news_feedback', 'localsource'));
					$xt = $twig->loadTemplate($tpath['mail_feedback'].'mail_feedback.tpl');

					$tVars = array(
						'news_url'	=> $news_url,
						'news_title'	=> $news_title,
						'name' => $name,
						'phone' => $phone,
						'message' => $message,
						'datetime' => time()
						);
				
					$mailBody = $xt->render($tVars);
					$mailSubject = "Пришло сообщение по обратной связи";
				
					sendEmailMessage($row['mail'], $mailSubject, $mailBody, $filename = false, $mail_from = false, $ctype = 'text/html');
				}

				
				$results = array(
				'feedback' => 100,
				'feedback_text' => iconv('Windows-1251', 'UTF-8','Ваше сообщение отправленно')
				);
//				goto endFeedbackCheck;
			}
			
			if (!empty($error_text))
			{
				$results = array(
				'feedback'	=> 2,
				'feedback_text' => iconv( 'Windows-1251', 'UTF-8', implode('<br />', $error_text) )
				);
//				goto endFeedbackCheck;
			}

		}
	
//	endFeedbackCheck:
	
	// Scan incoming params
	if (!is_array($params)) {
		return array('status' => 0, 'errorCode' => 999, 'errorText' => 'Wrong params type');
	}

	return array('status' => 1, 'errorCode' => 0, 'data' => $results);

}

function captcha_feedback($params)
{
global $tpl, $template, $twig, $SYSTEM_FLAGS, $userROW, $mysql;
		
	// Prepare basic reply array
		$results = array();

		// Check for promocode
		if ( isset($params['mcode']) ) {

			$mcode = secure_html(convert(trim($params['mcode'])));
					
			if (!$mcode || ($_SESSION['captcha'] != $mcode)) 
			{
				$results['feedback_captcha'] = 2;
			}
			else
			{
				$results['feedback_captcha'] = 100;
			}			

		}
	

	if (!is_array($params)) {
		return array('status' => 0, 'errorCode' => 999, 'errorText' => 'Wrong params type');
	}

	return array('status' => 1, 'errorCode' => 0, 'data' => $results);

}

register_filter('news','news_feedback', new ShowFeedback_NewsFilter);