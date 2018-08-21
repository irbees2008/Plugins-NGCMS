<?php
if (!defined('NGCMS')) die ('HAL');

register_plugin_page('userlist','','show_userlist');


function show_userlist() {
	 global $mysql, $template, $config;
                          
												$class1 = "border-bottom:1px solid #D1DCEB; border-right:1px solid #D1DCEB ; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF; background-color: #D1DCEB; color: #7A9ABC;";
												$class2 = "border-right:1px solid #D1DCEB; border-bottom:1px solid #D1DCEB; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF; background-color:#EDF1F6;";
												$class3 = "border-bottom:1px solid #D1DCEB; border-right:1px solid #D1DCEB ; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF; background-color: #438DAF; color: #FFFFFF;";
												$res = $mysql->query("SELECT COUNT(*) FROM ".prefix."_users");
												$res1 = mysql_fetch_array($res);
												$count = $res1[0];
												$perpage = extra_get_param('userlist','perpage');
												if ($perpage == '') {$perpage = "25";}
												switch ($sort = extra_get_param('userlist','sort')) {
													case "nickname":
													$sort = "name";
													break;
													case "status":
													$sort = "status";
													break;
													case "num_news":
													$sort = "news";
													break;
													case "num_com":
													$sort = "com";
													break;
													case "regdate":
													$sort = "reg";
													break;
													default:
													$sort = "name";
												}
												$order = extra_get_param('userlist','order');
												if ($order == 'desc') {
													$order = "DESC";
												}
												elseif ($order == 'asc') {
													$order = "ASC";
												}
												else {
													$order = "ASC";
												}
												
												$href = $_SERVER['REQUEST_QUERY'];
												$raz = "?";
												
											    list ($pag, $limit) = dr_pagination($perpage, $count, $href . $raz);
										        $row = $mysql->query("SELECT * FROM ".prefix."_users ORDER BY $sort $order $limit");
										        
												    $output = "<table width='100%' border='0' cellpadding='5' cellspacing='0'>"; 
													$output .= "<tr>
													<th colspan='5' style='$class3'>Пользователи</th>
													</tr>
													         <tr>
														    <th width='30%' style='$class1'>Имя</th>
														    <th width='30%' style='$class1'>Статус</th>
														    <th width='20%' style='$class1'>Новостей</th>
														    <th width='20%' style='$class1'>Комментариев</th>
														    <th width='20%' style='$class1'>Зарегистрирован</th>
														</tr>";
													while($bos = mysql_fetch_array($row)) {
														$username = $bos['name'];
													    switch ($userstatus = $bos['status']) {
															case 1:
															$userstatus = "Администратор";
															break;
															case 2:
															$userstatus = "Редактор";
															break;
															case 3:
															$userstatus = "Журналист";
															break;
															case 4:
															$userstatus = "Пользователь";
															}
														$usernews = $bos['news'];
														$usercom = $bos['com'];
														$userreg = date((extra_get_param('userlist','fdate')=='')?"d-m-Y":extra_get_param('userlist','fdate'), $bos['reg']);
														
														$output .= "<tr>";
														$alink = checkLinkAvailable('uprofile', 'show')?
						generateLink('uprofile', 'show', array('name' => $bos['name'], 'id' => $bos['id'])):
						generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $bos['name'], 'id' => $bos['id']));
						$output .= "<td style='$class2'><a href='http://{$_SERVER['SERVER_NAME']}".$alink."'>$username</a></td>";
														
														$output .= <<<HTML
														    <td style='$class2'>$userstatus</td>
														    <td style='$class2'><div align="center">$usernews</div></td>
														    <td style='$class2'><div align="center">$usercom</div</td>
														    <td style='$class2'>$userreg</td>
														</tr>
HTML;
														}
														$output .= "</table><br>";
														$output .= $pag;
													$template['vars']['mainblock'] = $output;
}
													
function dr_pagination($rpp, $count, $href, $opts = array()) {
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