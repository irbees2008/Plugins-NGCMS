<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

register_plugin_page('guestbook','','plugin_guestbook',0);
register_plugin_page('guestbook','edit','plugin_guestbook_edit',0);


function plugin_guestbook(){
	global $template, $tpl, $userROW, $ip, $config, $mysql, $SYSTEM_FLAGS;
      
	  $SYSTEM_FLAGS['info']['title']['group'] = 'Гостевая книга';
	
	  if(isset($_POST['content']))
                                  {
	  if (!is_array($userROW)) 
	                         {
	  $_POST['author'] = secure_html(convert(trim($_POST['author'])));
	  if(!strlen($_POST['author'])) $errors[] .= "Вы не ввели свое имя.";
	  // Check captcha
	  if (extra_get_param('dr_guestbook','ecaptcha')) 
	  {
		$captcha = $_REQUEST['vcode'];
        if (!$captcha || ($_SESSION['captcha'] != $captcha)) 
		{
			$errors[] .= "Проверочный код введен неправильно.";
		}
	  }
              $_SESSION['captcha'] = rand(00000, 99999);
	                        }
      
	  if(!strlen(trim($_POST['content']))) $errors[] = "Не введен текст сообщения.";
      
      //handle message
	  $message = secure_html(convert(trim($_POST['content'])));
      $minl = extra_get_param('dr_guestbook','minlength');
      $maxl = extra_get_param('dr_guestbook','maxlength');
		   if (strlen($message) < $minl || strlen($message) > $maxl) 
		   {
		       $errors[] .= "Текст сообщения должен быть в пределах от $minl до $maxl символов.";
	       }
      $message = str_replace("\r\n", "<br />", $message);
	  
      if(!is_array($errors))
	                        {
	   	$time = time() + ($config['date_adjust'] * 60);
	  	$mysql->query("INSERT INTO ".prefix."_guestbook (postdate, message, author, ip) values (".db_squote($time).", ".db_squote($message).", ".db_squote($_POST['author']).", ".db_squote($_POST['ip']).")");
	                        }
                                 }
      
	  if($_REQUEST['mode']== 'del')
      {
      	 if(is_array($userROW) && ($userROW['status'] == "1"))
      	 {
      	 	if (!is_array($mysql->record("SELECT id FROM ".prefix."_guestbook WHERE id=".db_squote(intval($_REQUEST['id'])))))
		 {
		$template['vars']['mainblock'] = "Такой записи не существует";
		return;
	     }
      	 	$mysql->query("DELETE FROM ".prefix."_guestbook WHERE id = ".intval($_REQUEST['id']));
      	 }
  	  }
      
       //display form
	  $tfvars['vars']['author'] = ($userROW)?'Ваш комментарий будет опубликован от имени <strong>'.$userROW['name'].'</strong><input type="hidden" name="author" value="'.$userROW['name'].'"/><br/><br/>':'Имя: <br /><input type="text" name="author" /><br/><br/>';
	  $tfvars['vars']['ip'] = $ip;
	  
	  $tfvars['vars']['smilies'] = (extra_get_param('dr_guestbook','usmilies')) ? InsertSmilies('', 10) :"";
      $tfvars['vars']['bbcodes'] = (extra_get_param('dr_guestbook','ubbcodes')) ? BBCodes() :"";
	 
	 
    if (extra_get_param('dr_guestbook','ecaptcha')) {
    	if (!is_array($userROW)) {
		$tfvars['vars']['admin_url'] = admin_url;
		@session_register('captcha');
		$_SESSION['captcha'] = mt_rand(00000, 99999);
		$tfvars['vars']['captcha'] = '';
		$tfvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '\\1';
	}
	else {
		$tfvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '';
	}
	}
	                        
	
	  if (!is_array($userROW) && !extra_get_param('dr_guestbook','guests'))
	  {
         $tfvars['regx']["'\[textarea\](.*?)\[/textarea\]'si"] = 'Гостям нельзя оставлять отзывы. Зарегистрируйтесь.';
      }
      else
      {
      	 $tfvars['regx']["'\[textarea\](.*?)\[/textarea\]'si"] = '\\1';
      }

      $tpl -> template('form', extras_dir.'/guestbook/tpl');
	  $tpl -> vars('form', $tfvars);
	  $tvars['vars']['forma'] = $tpl -> show('form');
      
	  //comments
      $perpage = extra_get_param('dr_guestbook', 'perpage');
      $count = $mysql->record("SELECT COUNT(*) AS num FROM ".prefix."_guestbook");
      $numcount = $count['num'];
	  if ($config['mod_rewrite'] == 1) {
	 $href = "/plugin/guestbook/";
	 $raz = "?";
		}
		else {$href = "/index.php?action=plugin&amp;plugin=guestbook";
		$raz = "&";
		}
     $limit = false;
     $order = extra_get_param('dr_guestbook', 'order');
	 $tvars['vars']['pages']='';
     if($numcount > $perpage)
     {
     	list ($pag, $limit) = guestbook_pagination($perpage, $numcount, $href . $raz);
     	$tvars['vars']['pages'] = $pag;
     }
	 
	  if($numcount != 0)
	      $tvars['vars']['comments'] = ($limit)?guestbook_records($order, $limit):guestbook_records($order);
	  else
	      $tvars['vars']['comments'] = "Записей нет!";
	  
        if(is_array($errors))
        {
        	$tvars['regx']["'\[error\](.*?)\[/error\]'si"] = '\\1';
        	$tvars['vars']['errors'] = implode("<br/>", $errors);
        }
        else
        {
        	$tvars['regx']["'\[error\](.*?)\[/error\]'si"] = '';
        }
		
        $tpl -> template('guestbook', extras_dir.'/guestbook/tpl');
		$tpl -> vars('guestbook', $tvars);
	    $template['vars']['mainblock'] = $tpl -> show('guestbook');
	    
}


function guestbook_records($order, $limit='') {
	global $mysql, $tpl, $userROW, $config, $parse;
	if(is_array($userROW) && ($userROW['status'] == "1"))
	{
       $tvars['regx']["'\[moderate\](.*?)\[/moderate\]'si"] = '\\1';
    }
    else
    {
   	   $tvars['regx']["'\[moderate\](.*?)\[/moderate\]'si"] = '';
    }
    foreach ($mysql->select("SELECT * FROM ".prefix."_guestbook ORDER BY id $order $limit") as $row) {
	    if (extra_get_param('dr_guestbook','usmilies')) { $row['message'] = $parse -> bbcodes($row['message']); }
		if (extra_get_param('dr_guestbook','ubbcodes'))	{ $row['message'] = $parse -> smilies($row['message']); }
		
		$tvars['vars'] = array (
		'date' => LangDate(ctimestamp, $row['postdate']),
		'message' => $row['message'],
		'author' => $row['author'],
		'ip' => $row['ip']
		);
				
	$tvars['vars']['edit'] = ($config['mod_rewrite'])?'<a href="/plugin/guestbook/?plugin_cmd=edit&amp;id='.$row['id'].'" target="_blank"> Редактировать </a>':'<a href="/index.php?action=plugin&amp;plugin=guestbook&amp;plugin_cmd=edit&amp;id='.$row['id'].'">Редактировать</a>';
		    $tvars['vars']['del'] = ($config['mod_rewrite'])?'<a href="/plugin/guestbook/?mode=del&amp;id='.$row['id'].'">Удалить</a>':'<a href="/index.php?action=plugin&amp;plugin=guestbook&amp;mode=del&amp;id=='.$row['id'].'">Удалить</a>';
			
			$tpl -> template('comments', extras_dir.'/guestbook/tpl');
			$tpl -> vars('comments', $tvars);
			$comments .= $tpl -> show('comments');
		}
		
	   return $comments;
}

function plugin_guestbook_edit()
{
	global $template, $tpl, $userROW, $ip, $config, $mysql;
	 if($_REQUEST['plugin_cmd']=='edit' && is_array($userROW) && $userROW['status'] == "1")
	 {
	 	if(isSet($_REQUEST['go']))
	 	{
	 		 $author = secure_html(convert(trim($_REQUEST['author'])));
			 $message = secure_html(convert(trim($_REQUEST['content'])));
		     $message = str_replace("\r\n", "<br />", $message);
		     
		     $mysql->query("UPDATE ".prefix."_guestbook SET author =".db_squote($author).", message=".db_squote($message)." WHERE id=".$_REQUEST['id']);
		     header("Location: ./");
		     
 		}
	 	else
	 	{
	 		if (!is_array($row = $mysql->record("SELECT * FROM ".prefix."_guestbook WHERE id=".db_squote(intval($_REQUEST['id'])))))
		 {
		$template['vars']['mainblock'] = "Такой записи не существует";
		return;
	     }
	     
		 $row['message'] = str_replace("<br />", "\r\n", $row['message']);
		 $template['vars']['mainblock'] = "<form method='post' action=''><input type='text' name='author' value='".$row['author']."'><br/><br/>";
		 $template['vars']['mainblock'] .= "<textarea name='content' style='width: 95%;' rows='8'>".$row['message']."</textarea><br/><br/>";
		 $template['vars']['mainblock'] .= "<input type='hidden' name='id' value='".$row['id']."'><input type='submit' name='go' value='Отредактировать'></form>";
	 		
		}
		 
	 }
	 else
	 {
	 	$template['vars']['mainblock'] = "Что тебе здесь надо?!";
	 }
}

function guestbook_pagination($rpp, $count, $href, $opts = array()) {
	$pages = ceil($count / $rpp);

	if (!$opts["lastpagedefault"])
		$pagedefault = 0;
	else {
		$pagedefault = floor(($count - 1) / $rpp);
		if ($pagedefault < 0)
			$pagedefault = 0;
	}

	if (isset($_GET["page"])) {
		$page = 0 + $_GET["page"];
		if ($page < 0)
			$page = $pagedefault;
	}
	else
		$page = $pagedefault;

	   $pager = "<td class=\"pager\">Страницы:</td><td class=\"pagebr\">&nbsp;</td>";

	$mp = $pages - 1;
	$as = "<b>«</b>";
	if ($page >= 1) {
		$pager .= "<td class=\"pager\">";
		$pager .= "<a href=\"{$href}page=" . ($page - 1) . "\" style=\"text-decoration: none;\">$as</a>";
		$pager .= "</td><td class=\"pagebr\">&nbsp;</td>";
	}

	$as = "<b>»</b>";
	if ($page < $mp && $mp >= 0) {
		$pager2 .= "<td class=\"pager\">";
		$pager2 .= "<a href=\"{$href}page=" . ($page + 1) . "\" style=\"text-decoration: none;\">$as</a>";
		$pager2 .= "</td>$bregs";
	}else	 $pager2 .= $bregs;

	if ($count) {
		$pagerarr = array();
		$dotted = 0;
		$dotspace = 3;
		$dotend = $pages - $dotspace;
		$curdotend = $page - $dotspace;
		$curdotstart = $page + $dotspace;
		for ($i = 0; $i < $pages; $i++) {
			if (($i >= $dotspace && $i <= $curdotend) || ($i >= $curdotstart && $i < $dotend)) {
				if (!$dotted)
				   $pagerarr[] = "<td class=\"pager\">...</td><td class=\"pagebr\">&nbsp;</td>";
				$dotted = 1;
				continue;
			}
			$dotted = 0;
			$start = $i * $rpp + 1;
			$end = $start + $rpp - 1;
			if ($end > $count)
				$end = $count;

			 $text = $i+1;
			if ($i != $page)
				$pagerarr[] = "<td class=\"pager\"><a title=\"$start&nbsp;-&nbsp;$end\" href=\"{$href}page=$i\" style=\"text-decoration: none;\"><b>$text</b></a></td><td class=\"pagebr\">&nbsp;</td>";
			else
				$pagerarr[] = "<td class=\"highlight\"><b>$text</b></td><td class=\"pagebr\">&nbsp;</td>";

				  }
		$pagerstr = join("", $pagerarr);
		$pagertop = "<table><tr>$pager $pagerstr $pager2</tr></table>\n";
		$pagerbottom = "Всего $count на $i страницах по $rpp на каждой странице.<br /><br /><table class=\"main\">$pager $pagerstr $pager2</table>\n";
	}
	else {
		$pagertop = $pager;
		$pagerbottom = $pagertop;
	}

	$start = $page * $rpp;

	return array($pagertop, "LIMIT $start,$rpp");
}
?>