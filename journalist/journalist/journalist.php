<?php
if (!defined('NGCMS')) die ('HAL');

register_plugin_page('journalist','','show_journalist');


function show_journalist() {
	 global $mysql, $template, $config;
                          
												$class1 = "border-bottom:1px solid #D1DCEB; border-right:1px solid #D1DCEB ; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF; background-color: #D1DCEB; color: #7A9ABC;";
												$class2 = "border-right:1px solid #D1DCEB; border-bottom:1px solid #D1DCEB; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF; background-color:#EDF1F6;";
												$class3 = "border-bottom:1px solid #D1DCEB; border-right:1px solid #D1DCEB ; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF; background-color: #438DAF; color: #FFFFFF;";
												$res = $mysql->query("SELECT COUNT(*) FROM ".prefix."_users");
												$res1 = mysql_fetch_array($res);
												$count = $res1[0];
												$perpage = extra_get_param('journalist','perpage');
												if ($perpage == '') {$perpage = "25";}
												switch ($sort = extra_get_param('journalist','sort')) {
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
													case "num_views":
													$sort = "views";
													break;
													case "num_koef":
													$sort = "koef";
													break;
													case "regdate":
													$sort = "reg";
													break;
													case "catid":
													$sort = "cat";
													break;
													case "num_posts":
													$sort = "posts";
													break;
													case "num_views1":
													$sort = "views1";
													break;
													default:
													$sort = "name";
												}
												$order = extra_get_param('journalist','order');
												if ($order == 'desc') {
													$order = "DESC";
												}
												elseif ($order == 'asc') {
													$order = "ASC";
												}
												else {
													$order = "ASC";
												}
												// Date range
						
												$f			= '';
												$href = $_SERVER['REQUEST_QUERY'];
												$raz = "?";
											    list ($pag, $limit) = dr_pagination($perpage, $count, $href . $raz);
											      $output = " <table width='100%' border='0' cellpadding='5' cellspacing='0'>"; 
										        	$output .= "<form name='data1' method='post' action=''>
										        	Початкова дата:
										        				<input type='date' name='date1' value='' placeholder='От' size='4' maxlength='4'>
										        				Кінцева дата:
    															<input type='date' name='date2' value='' placeholder='До' size='4' maxlength='4'>
  															  	<button type='submit'>Найти</button>
																</form> ";
													$date1 = ($_REQUEST['date1']) ? $_REQUEST['date1'] : '';
													$date2 = ($_REQUEST['date2']) ? $_REQUEST['date2'] : '';
										        $row = $mysql->query("select distinct u.id, name, count(distinct n.id) as s, sum(n.com) as s_c,
																		sum(distinct n.views) as c_v, (sum(distinct n.views)/count(distinct n.id)) as k
																		from 2z_users as u,  2z_news as n
																		WHERE u.id=n.author_id and (from_unixtime(postdate) > '$date1') 
																		and (from_unixtime(postdate) < '$date2') and n.approve = '1'
																		group by u.id, name order by s desc");
												$row1 = $mysql->query("select c.id as i,c.name as na,count(distinct n.id) as su,sum(views) as n_v, sum(views)/count(distinct n.id) as koefi from 2z_category as c, 2z_news as n
																		Where c.id=n.catid  and (from_unixtime(postdate) > '$date1') 
																		and (from_unixtime(postdate) < '$date2')
																		group by c.id
																		order by n_v desc
																		limit 20");
												$row2 = $mysql->query("select distinct id,title,alt_name,views as v_n FROM 2z_news
													where from_unixtime(postdate) > '$date1' and FROM_UNIXTIME(postdate) < '$date2' 
													order by views desc 
													limit 20");
										        
												 
													$output = 
													$output .= "<tr>
													<th colspan='6' style='$class3'>Пользователи</th>
													</tr>
													         <tr>
														    <th width='30%' style='$class1'>Имя</th>
														    <th width='20%' style='$class1'>Новостей</th>
														    <th width='20%' style='$class1'>Комментариев</th>
														    <th width='20%' style='$class1'>Просмотров</th>
															<th width='20%' style='$class1'>Коеф.полезности</th>
														</tr>";

													while($bos = mysql_fetch_array($row)) {
														$username = $bos['name'];
														$usernews = $bos['s'];
														$usercom = $bos['s_c'];
														$userviews = $bos['c_v'];
														$userkoef = $bos['k'];
														$userreg = date((extra_get_param('journalist','fdate')=='')?"d-m-Y":extra_get_param('journalist','fdate'), $bos['reg']);
														
														$output .= "<tr>";
														$alink = checkLinkAvailable('uprofile', 'show')?
						generateLink('uprofile', 'show', array('name' => $bos['name'], 'id' => $bos['id'])):
						generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $bos['name'], 'id' => $bos['id']));
						$output .= "<td style='$class2' align='center'><a href='http://{$_SERVER['SERVER_NAME']}".$alink."'>$username</a></td>";
														
														$output .= <<<HTML
														    <td style='$class2'><div align="center">$usernews</div></td>
														    <td style='$class2'><div align="center">$usercom</div</td>
														    <td style='$class2'><div align="center">$userviews</div</td>
															<td style='$class2'><div align="center">$userkoef</div</td>
														</tr>
HTML;
														}

													$output .= "</br>
													<tr>
													<th colspan='4' style='$class3'>Категории</th>
													</tr>
													         <tr>
														    <th width='30%' style='$class1'>Имя</th>
														    <th width='20%' style='$class1'>Новостей</th>
														    <th width='20%' style='$class1'>Просмотров</th>
														    <th width='20%' style='$class1'>Коеф.полезности</th>"
														    ;

														    		while($bos = mysql_fetch_array($row1)) {
														$usercat = $bos['na'];
														$usersum = $bos['su'];
														$userviews1 = $bos['n_v'];
														$userkoefi = $bos['koefi'];
														$output .= "<tr>";
														    $output .= <<<HTML
														    <td style='$class2'><div align="center">$usercat</div></td>
														    <td style='$class2'><div align="center">$usersum</div</td>
														    <td style='$class2'><div align="center">$userviews1</div</td>
														    <td style='$class2'><div align="center">$userkoefi</div</td>

														</tr>
HTML;
}

													$output .= "</br>
													<tr>
													<th colspan='4' style='$class3'>Топ20 новостей</th>
													</tr>
													         <tr>
														    <th width='20%' style='$class1'>Название</th>
														    <th width='20%' style='$class1'>Просмотров</th>"
														    ;
														    		while($bos = mysql_fetch_array($row2)) {
														$cid = $bos['id'];
														$catname = $bos['alt'];
														$alt_name = $bos['alt_name'];
														$newsname = $bos['title'];
														$newsviews = $bos['v_n'];
														$output .= "<tr>";
														    $output .= <<<HTML
														    <td style='$class2'><div align="center"><a href="/cat/$alt_name.html">$newsname</a></div</td>
														    <td style='$class2'><div align="center">$newsviews</div</td>
														</tr>
HTML;
}
														$output .= "</table><br>";
														$output .= $pag;
													$template['vars']['mainblock'] = $output;
}
													
function dr_pagination($rpp, $count, $href, $opts = array()) {

}
?>