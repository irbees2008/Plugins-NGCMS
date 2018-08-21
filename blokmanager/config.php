<?php
if(!defined('NGCMS')) exit('HAL');

pluginsLoadConfig();
LoadPluginLang('blokmanager', 'config', '', '', ':');

switch ($_REQUEST['action']) {
	case 'list': showlist(); break;
	case 'menulist': menushowlist(); break;
	case 'add': add(); break;
	case 'edit': add(); break;
	case 'menuadd': menuadd(); break;
	case 'menuedit': menuadd(); break;
	case 'add_submit': add_submit(); break;
	case 'edit_submit': add_submit(); break;
	case 'menuadd_submit': menuadd_submit(); break;
	case 'menuedit_submit': menuadd_submit(); break;
	case 'move_up': move('up'); break;
	case 'move_down': move('down'); break;
	case 'menudell': menudelete(); break;
	case 'dell': delete(); break;
	case 'on_off': blokonoff(); break;
	case 'copy':blokcopy();break;
	case 'menucopy':menucopy();break;
	case 'save_bloklocation':save_bloklocation();break;
	case 'save_deftemplates':save_deftemplates();break;
	case 'clear_cash': clear_cash();
	default: main();
}

function main()
{
	global $tpl, $lang,$userROW;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.general'), 'blokmanager', 1);
	if ($userROW['status']!=1)
	{
		$tvars['regx']["'\[locationlist\](.*?)\[/locationlist\]'si"] = '';		
	}
	else
	{
		$tvars['regx']["'\[locationlist\](.*?)\[/locationlist\]'si"] = '$1';
		$pos = pluginGetVariable('blokmanager', 'locationlist');
		
		$defs = pluginGetVariable('blokmanager', 'deftemplates');
		$llist=array();
		if($pos)
		{
			foreach($pos as $k=>$v)
			{
				array_push($llist,$k.' => '.$v);
			}
	}
	else
	{$defs=array();
			$defs['defmenurow']='<li>[caticon][mark]<a href="[caturl]">[catname]</a></li>';
			$defs['defblokouter']='<h2>[blokname]</h2><p>[blokcode]</p>';
			pluginSetVariable('blokmanager', 'deftemplates', $defs);
			pluginSetVariable('blokmanager', 'ckonoff',0);
			pluginsSaveConfig();}
    $ttvars['vars']['locationlist']=implode("\n", $llist);
	}
	$ckonoff = pluginGetVariable('blokmanager', 'ckonoff');
	$ttvars['vars']['сkonoffos']= MakeDropDown(array(0 => $lang['blokmanager:lno'], 1 => $lang['blokmanager:lyes']),'ckonoff',$ckonoff);
	$ttvars['vars']['defblokouter']=($defs['defblokouter'])?$defs['defblokouter']:'<h2>[blokname]</h2><p>[blokcode]</p>';
	$ttvars['vars']['defmenurow']=($defs['defmenurow'])?$defs['defmenurow']:'<li>[caticon][mark]<a href="[caturl]">[catname]</a></li>';
	$ttvars['vars']['action'] = $lang['blokmanager:button_general'];
	
	$tpl->template('conf.general', $tpath['conf.general']);
	$tpl->vars('conf.general', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.general');
	$tvars['vars']['action'] = $lang['blokmanager:button_general'];
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function showlist()
{
	global $tpl, $lang,$userROW;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.list', 'conf.list.row'), 'blokmanager', 1);
	
	$var = pluginGetVariable('blokmanager', 'data');

	$output = '';
	$t_time = time();
	$t_type = array(0 => $lang['blokmanager:html'], 1 => $lang['blokmanager:php'], 2 => $lang['blokmanager:text'],3 => $lang['blokmanager:menu'],4 => $lang['blokmanager:datarotate']);
	foreach ($var as $k => $v)
	{
		foreach ($v as $kk => $vv)
		{
			$pvars['vars']['name'] = $k ? $k : $lang['blokmanager:error_name'];
			$pvars['vars']['id'] = $kk;
			$pvars['vars']['description'] = $vv['description'];
				switch ($vv['state'])
				{
					case 0: {
						$pvars['vars']['online'] = '<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/off.png" title="'.$lang['blokmanager:online_off'].'"  onmousedown="javascript:window.location.href=\'{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=on_off&id='.$kk.'\'" />';
						break;
					}
					case 1: {
						$pvars['vars']['online'] ='<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/on.png" title="'.$lang['blokmanager:online_on'].'"  onmousedown="javascript:window.location.href=\'{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=on_off&id='.$kk.'\'" />'; 
						break;
					}
					case 2: {
						if (($vv['start_view'] && $vv['start_view'] > $t_time)||($vv['end_view'] && $vv['end_view'] <= $t_time))
						{
							$pvars['vars']['online'] ='<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/t-off.png" title="'.$lang['blokmanager:online_toff'].'" onmousedown="javascript:window.location.href=\'{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=on_off&id='.$kk.'\'"/>'; 
						}
						else
						{
							$pvars['vars']['online'] ='<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/t-on.png" title="'.$lang['blokmanager:online_ton'].'" onmousedown="javascript:window.location.href=\'{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=on_off&id='.$kk.'\'"/>'; 
						}
						break;
					}
				}
			
			$pvars['vars']['type'] = ($vv['type']==3)?$t_type[$vv['type']].' ('.$vv['menuid'].')':$t_type[$vv['type']];
			$pvars['vars']['editbutton']=($userROW['status']==1||$vv['perms']!=3)?'<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/edit.png" title="{l_blokmanager:button_edit}"  onmousedown="javascript:window.location.href=\'{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=edit&id='.$kk.'\'" return false;/>':'<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/noedit.png"/>';
			$pvars['vars']['dellbutton']=($userROW['status']==1||$vv['perms']<1)?'<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/dell.png" title="{l_blokmanager:button_dell}"  onmousedown="if(window.confirm(\'Вы уверены что хотите удалить этот блок?\')){ window.location.href=\'{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=dell&id='.$kk.'\'; } return false;" />':'<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/nodel.png"/>';
			$tpl->template('conf.list.row', $tpath['conf.list.row']);
			$tpl -> vars('conf.list.row', $pvars);
			$output .= $tpl->show('conf.list.row');
		}
	}
	$ttvars['vars']['entries'] = $output;
	
	$tpl->template('conf.list', $tpath['conf.list']);
	$tpl->vars('conf.list', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.list');
	
	$tvars['vars']['action'] = $lang['blokmanager:button_list'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function menushowlist()
{
	global $tpl, $lang;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.menulist', 'conf.menulist.row'), 'blokmanager', 1);
	
	$menuarray = pluginGetVariable('blokmanager', 'menudata');
	$output = '';
	$t_type = array('static' => $lang['blokmanager:menustatic'], 'dynamic' => $lang['blokmanager:menudynamic']);
	foreach ($menuarray as $menu => $param)
	{
			$pvars['vars']['menuname'] = $param['menuname'] ? $param['menuname'] : $lang['blokmanager:error_name'];
			$pvars['vars']['id'] = $menu;
			$pvars['vars']['menutype'] = $t_type[$param['menutype']];
			$tpl->template('conf.menulist.row', $tpath['conf.menulist.row']);
			$tpl -> vars('conf.menulist.row', $pvars);
			$output .= $tpl->show('conf.menulist.row');

	}
	$ttvars['vars']['entries'] = $output;
	
	$tpl->template('conf.menulist', $tpath['conf.menulist']);
	$tpl->vars('conf.menulist', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.menulist');
	
	$tvars['vars']['action'] = $lang['blokmanager:button_menulist'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function menuadd()
{
	global $mysql,$catz,$tpl, $lang,$userROW;
	$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
	$var = '';
	$catselected=array();
	if ($id)
	{
		$menuarray = pluginGetVariable('blokmanager', 'menudata');
		foreach ($menuarray as $k => $v)
		{
				if ($id == $k)
				{
					$var = $v;
					break;
				}
		}
		if($var['menucatids'])
		$catselected=explode('|',$var['menucatids']);
	}

	$tpath = locatePluginTemplates(array('conf.main', 'conf.menuadd_edit.form'), 'blokmanager', 1);
	$catlist = '';
	foreach($catz as $k => $v){
		if (!substr($v['flags'],0,1)) continue;
		if (!empty($catselected))
		{ 
			$catlist.='<option value="'.$v['id'].'" '.(in_array($v['id'],$catselected)?"selected":"").' >'.str_repeat('&#8212;', $v['poslevel']).$v['name'].'</option>';
		}
		else
		{
			$catlist.='<option value="'.$v['id'].'">'.str_repeat('&#8212;', $v['poslevel']).$v['name'].'</option>';
		}
	}
	$ttvars['vars']['catlist'] = $catlist;
	$defs = pluginGetVariable('blokmanager', 'deftemplates');
	if ($id)
	{
		$ttvars['vars']['id'] = $id;
		$ttvars['vars']['menuname'] = $var['menuname'];
		$ttvars['vars']['menulevel'] = $var['menulevel'];
		$ttvars['vars']['levelmark'] = $var['levelmark'];
		$ttvars['vars']['menutemplate'] = ($var['menutemplate'])?$var['menutemplate']:$defs['defmenurow'];
	}
    else
    {
		$ttvars['vars']['menutemplate'] = $defs['defmenurow'];
	}
	$ttvars['regx']['/\[add\](.*?)\[\/add\]/si'] = $id?'':'$1';
	$ttvars['regx']['/\[edit\](.*?)\[\/edit\]/si'] = $id?'$1':'';
	
	$tpl->template('conf.menuadd_edit.form', $tpath['conf.menuadd_edit.form']);
	$tpl->vars('conf.menuadd_edit.form', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.menuadd_edit.form');
	
	$tvars['vars']['action'] = $id?$lang['blokmanager:button_edit']:$lang['blokmanager:button_add'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}
function add()
{
	global $mysql, $tpl, $lang,$userROW;
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
	$var = '';
	$name = '';
	
	if ($id)
	{
		$t_var = pluginGetVariable('blokmanager', 'data');
		$if_brek = false;
		foreach ($t_var as $k => $v)
		{
			foreach ($v as $kk => $vv)
			{
				if ($id == $kk)
				{
					$var = $vv;
					$name = $k?$k:'';
					$if_brek = true;
					break;
				}
			}
			if ($if_brek)
				break;
		}
	}
if($userROW['status']!=1&&$id&&$var['perms']==3)
{
	msg(array("type" => "error", "text" => $lang['blokmanager:nopermedit']));
		$error = 1;
		showlist();
}
else
{
	$pos = pluginGetVariable('blokmanager', 'locationlist');
	$defs = pluginGetVariable('blokmanager', 'deftemplates');
	if ($pos)
	{
		$locationlist='';
		foreach($pos as $k=>$v)
		{
			$locationlist.=($k==$name)?'<option value="'.$k.'" selected>'.$v.'</option>':'<option value="'.$k.'">'.$v.'</option>';
		}
	}else
	{
		$locationlist='Сначала задайте позиции блоков во вкладке "Основные настройки"';
	}
	$tpath = locatePluginTemplates(array('conf.main', 'conf.add_edit.form'), 'blokmanager', 1);
	
	$ttvars['vars']['category_list'] = '';
	$ttvars['vars']['category_list'] .= 'subsubel = document.createElement("option");';
	$ttvars['vars']['category_list'] .= 'subsubel.setAttribute("value", "0");';
	$ttvars['vars']['category_list'] .= 'subsubel.appendChild(document.createTextNode("'.$lang['blokmanager:all'].'"));';
	$ttvars['vars']['category_list'] .= 'subel.appendChild(subsubel);';
	$t_category_list = array(0 => $lang['blokmanager:all']);
	foreach ($mysql->select('select `id`, `name` from '.prefix.'_category') as $row)
	{
		$t_category_list[$row['id']] = $row['name'];
		$ttvars['vars']['category_list'] .= 'subsubel = document.createElement("option");';
		$ttvars['vars']['category_list'] .= 'subsubel.setAttribute("value", "'.$row['id'].'");';
		$ttvars['vars']['category_list'] .= 'subsubel.appendChild(document.createTextNode("'.$row['name'].'"));';
		$ttvars['vars']['category_list'] .= 'subel.appendChild(subsubel);';
	}

	$ttvars['vars']['static_list'] = '';
	$ttvars['vars']['static_list'] .= 'subsubel = document.createElement("option");';
	$ttvars['vars']['static_list'] .= 'subsubel.setAttribute("value", "0");';
	$ttvars['vars']['static_list'] .= 'subsubel.appendChild(document.createTextNode("'.$lang['blokmanager:all'].'"));';
	$ttvars['vars']['static_list'] .= 'subel.appendChild(subsubel);';
	$t_static_list = array(0 => $lang['blokmanager:all']);
	foreach ($mysql->select('select `id`, `title` from '.prefix.'_static') as $row)
	{
		$t_static_list[$row['id']] = $row['title'];
		$ttvars['vars']['static_list'] .= 'subsubel = document.createElement("option");';
		$ttvars['vars']['static_list'] .= 'subsubel.setAttribute("value", "'.$row['id'].'");';
		$ttvars['vars']['static_list'] .= 'subsubel.appendChild(document.createTextNode("'.$row['title'].'"));';
		$ttvars['vars']['static_list'] .= 'subel.appendChild(subsubel);';
	}	
	$ttvars['vars']['locationlist']=$locationlist;
	$ttvars['vars']['recursiv'] .= 'catsubsubel = document.createElement("option");';
	$ttvars['vars']['recursiv'] .= 'catsubsubel.setAttribute("value", "0");';
	$ttvars['vars']['recursiv'] .= 'catsubsubel.appendChild(document.createTextNode("только"));';
	$ttvars['vars']['recursiv'] .= 'catsubel.appendChild(catsubsubel);';
	$ttvars['vars']['recursiv'] .= 'catsubsubel = document.createElement("option");';
	$ttvars['vars']['recursiv'] .= 'catsubsubel.setAttribute("value", "1");';
	$ttvars['vars']['recursiv'] .= 'catsubsubel.appendChild(document.createTextNode("и все подкатегории"));';
	$ttvars['vars']['recursiv'] .= 'catsubel.appendChild(catsubsubel);';
	if ($id)
	{
		$ttvars['vars']['id'] = $id;
		$ttvars['vars']['name'] = $name;
		$ttvars['vars']['description'] = $var['description'];
		$ttvars['vars']['start_view'] = $var['start_view']?date('Y.m.d H:i', $var['start_view']):'';
		$ttvars['vars']['end_view'] = $var['end_view']?date('Y.m.d H:i', $var['end_view']):'';
		$ttvars['vars']['location_list'] = '';
		foreach($var['location'] as $k => $v)
		{
			$ttvars['vars']['location_list'] .= '<tr><td>'.($k).': </td><td align="left">';
			$ttvars['vars']['location_list'] .= MakeDropDown(array(0 => $lang['blokmanager:around'], 1 => $lang['blokmanager:main'], 2 => $lang['blokmanager:not_main'], 3 => $lang['blokmanager:category'], 4 => $lang['blokmanager:static']), 'location['.($k).'][mode]" onchange="AddSubBlok(this, '.($k).');', $v['mode']);
			if ($v['mode'] == 3) $ttvars['vars']['location_list'] .= MakeDropDown(array(0 => 'только', 1 => 'и все подкатегории'),'location['.($k).'][recursiv]',$v['recursiv']);
			if ($v['mode'] == 3) $ttvars['vars']['location_list'] .= MakeDropDown($t_category_list, 'location['.($k).'][id]', $v['id']);
			if ($v['mode'] == 4) $ttvars['vars']['location_list'] .= MakeDropDown($t_static_list, 'location['.($k).'][id]', $v['id']);
			$ttvars['vars']['location_list'] .= MakeDropDown(array(0 => $lang['blokmanager:view'], 1 => $lang['blokmanager:not_view']), 'location['.($k).'][view]', $v['view']);
			$ttvars['vars']['location_list'] .= '</td></tr>';
		}
		$ttvars['vars']['blokcode'] = '';
		$ttvars['vars']['outerblok'] = '';
		foreach ($mysql->select('select `blokcode`,`outerblok` from '.prefix.'_blokmanager where `id`='.db_squote($id).' limit 1') as $row)
		{
			 $ttvars['vars']['outerblok'] = $row['outerblok'];
			 $ttvars['vars']['blokcode'] = htmlspecialchars($row['blokcode']);
			 if ($var['type']==4){$ttvars['vars']['blokchangecode'] = $row['blokcode'];}
		 }
	}
	$ttvars['vars']['outerblok'] = ($ttvars['vars']['outerblok'])?htmlspecialchars($ttvars['vars']['outerblok']):htmlspecialchars($defs['defblokouter']);
	$ttvars['vars']['type_list'] = '<option value="0">'.$lang['blokmanager:html'].'</option><option value="1">'.$lang['blokmanager:php'].'</option><option value="2">'.$lang['blokmanager:text'].'</option><option value="3">'.$lang['blokmanager:menu'].'</option><option value="4">'.$lang['blokmanager:datarotate'].'</option>';
	 $ttvars['vars']['type']=($id)?$var['type']:0;
	 $ttvars['vars']['menu_list'] ='';
	$menuarray = pluginGetVariable('blokmanager', 'menudata'); 
	if (empty($menuarray))	 
	{
		$ttvars['vars']['menu_list'] ='<option>'.$lang['blokmanager:nomenu'].'</option>';
	} else
	{
		foreach($menuarray as $menu=>$param)
		{
			$ttvars['vars']['menu_list'].='<option value='.$menu.' '.(($ttvars['vars']['blokcode']==$menu)?'selected':'').'>'.$param['menuname'].'</option>';
		}
	}
	$ttvars['vars']['permlist'] = MakeDropDown(array(0 => $lang['blokmanager:perm_full'], 1 => $lang['blokmanager:perm_nodelete'], 2 => $lang['blokmanager:perm_noeditcont'], 3 => $lang['blokmanager:perm_noanychange']),'perms' , $var['perms']);
	$ttvars['vars']['menuid']=(($id)&&($var['type']==3))?$var['menuid']:0;
	$ttvars['vars']['state_list'] = MakeDropDown(array(0 => $lang['blokmanager:label_off'], 1 => $lang['blokmanager:label_on'], 2 => $lang['blokmanager:label_sched']), 'state', $id?$var['state']:0);
	$ttvars['vars']['period']=$var['period'];
	$ttvars['vars']['start_viewperiod'] = $var['start_viewperiod']?date('Y.m.d H:i', $var['start_viewperiod']):'';
	$ttvars['regx']['/\[editcontent\](.*?)\[\/editcontent\]/si'] = ($userROW['status']==1||!$id||$var['perms']<2)?'$1':'';
		$ckonoff = pluginGetVariable('blokmanager', 'ckonoff');
	$ttvars['regx']['/\[isck\](.*?)\[\/isck\]/si'] = $ckonoff?'':'$1';

	$ttvars['regx']['/\[add\](.*?)\[\/add\]/si'] = $id?'':'$1';
	$ttvars['regx']['/\[edit\](.*?)\[\/edit\]/si'] = $id?'$1':'';
	$ttvars['regx']['/\[outerblok\](.*?)\[\/outerblok\]/si'] = ($userROW['status']==1)?'$1':'';
	$tpl->template('conf.add_edit.form', $tpath['conf.add_edit.form']);
	$tpl->vars('conf.add_edit.form', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.add_edit.form');
	
	$tvars['vars']['action'] = $id?$lang['blokmanager:button_edit']:$lang['blokmanager:button_add'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}
}
function menuadd_submit()
{
	global $mysql, $parse, $lang;
	$id = $_REQUEST['id'];
	$menuarray = pluginGetVariable('blokmanager', 'menudata');
	$t_id=0;
	foreach ($menuarray as $k => $v)
	{
		if ($id == $k)
		{
			$t_id = $k;
			break;
		}
	}
	if ((!$id)||(strlen($id)<2)||(($id==$t_id)&&($REQUEST['action']=='menuadd_submit')))
	{
		msg(array("type" => "error", "text" => $lang['blokmanager:msge_errid']));
			$error = 1;
	}
	$menuarray[$id]['menuname'] = secure_html($_REQUEST['menuname']);
	$menuarray[$id]['menulevel'] = intval($_REQUEST['menulevel']);
	$menuarray[$id]['levelmark'] = $_REQUEST['levelmark'];
	$defs = pluginGetVariable('blokmanager', 'deftemplates');
	$menuarray[$id]['menutemplate'] = ($_REQUEST['menutemplate'])?$_REQUEST['menutemplate']:$defs['defmenurow'];
	$menuarray[$id]['menucatids']=implode('|',$_REQUEST['menucatids']);
	pluginSetVariable('blokmanager', 'menudata', $menuarray);
	pluginsSaveConfig();
clear_cash();
	menushowlist();
}
function add_submit()
{
	global $mysql, $parse, $lang,$userROW;
	
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
	$name = secure_html($_REQUEST['name']);
	if (!$name)
	{
		msg(array("type" => "error", "text" => $lang['blokmanager:msge_errlocation']));
			$error = 1;
	}
	$description = trim(secure_html(convert($_REQUEST['description'])));
	$type = intval($_REQUEST['type']);
	$location = $_REQUEST['location'];
	array_walk_recursive($location, intval);
	$state = intval($_REQUEST['state']);
	$start_view = GetTimeStamp(trim(secure_html(convert($_REQUEST['start_view']))));
	$start_viewperiod = ($_REQUEST['start_viewperiod'])?GetTimeStamp(trim(secure_html(convert($_REQUEST['start_viewperiod'])))):time();
	$end_view = GetTimeStamp(trim(secure_html(convert($_REQUEST['end_view']))));
	$defs = pluginGetVariable('blokmanager', 'deftemplates');
	$blokcode = ($type==3)?$_REQUEST['menulist']:(($type==4)?$_REQUEST['blokchangecode']:$_REQUEST['blokcode']);
	$outerblok = ($_REQUEST['outerblok'])?$_REQUEST['outerblok']:$defs['defblokouter'];
	$var = pluginGetVariable('blokmanager', 'data');
	if (!id)
	{
		$mysql->query("insert into ".prefix."_blokmanager (`blokcode`,`outerblok`) values (
					".db_squote($blokcode).",
					".db_squote($outerblok)."
					)");
		$id = intval($mysql->lastid('blokmanager'));
	}
	else
	{

		$t_name = 0;

		$if_brek = false;
		foreach ($var as $k => $v)
		{
			foreach ($v as $kk => $vv)
			{
				if ($id == $kk)
				{
					$t_name = $k;
					$if_brek = true;
					break;
				}
			}
			if ($if_brek)
				break;
		}
		if ($t_name !== $name)
		{
			unset($var[$t_name][$id]);
			if (!count($var[$t_name])) unset($var[$t_name]);
		}
	}
$t_update = ($userROW['status']==1||!$id||$var[$name][$id]['perms']<2)?$mysql->query('update '.prefix.'_blokmanager set '.
			'`blokcode`='.db_squote($blokcode).',`outerblok`='.db_squote($outerblok).' '.
			'where `id`='.db_squote($id).' limit 1'):$mysql->query('update '.prefix.'_blokmanager set '.
			'`outerblok`='.db_squote($outerblok).' '.
			'where `id`='.db_squote($id).' limit 1');
	$var[$name][$id]['description'] = $description;
	if($userROW['status']==1||!$id||$var[$name][$id]['perms']<2){$var[$name][$id]['type'] = $type;}
	$var[$name][$id]['state'] = $state;
	$var[$name][$id]['start_viewperiod'] = $start_viewperiod;
	$var[$name][$id]['period'] = intval($_REQUEST['period']);
	$var[$name][$id]['start_view'] = $start_view;
	$var[$name][$id]['end_view'] = $end_view;
	$var[$name][$id]['location'] = $location;
	$var[$name][$id]['menuid']=($type==3)?$_REQUEST['menulist']:0;
	if($userROW['status']!=1&&!$id){$var[$name][$id]['perms']=0;}
	if($userROW['status']==1){$var[$name][$id]['perms']=$_REQUEST['perms'];}
	pluginSetVariable('blokmanager', 'data', $var);
	
	pluginsSaveConfig();
	clear_cash();
	showlist();
}

function move($action)
{
	$id = intval($_REQUEST['id']);
	$var = pluginGetVariable('blokmanager', 'data');
	
	$keys = array_keys($var);
	$values = array_values($var);
	$count = count($keys);
	$if_break = false;
	
	for ($i = 0; $i < $count; $i++)
	{
		$sub_keys = array_keys($var[$keys[$i]]);
		$sub_values = array_values($var[$keys[$i]]);
		$sub_count = count($sub_keys);
		for ($j = 0; $j < $sub_count; $j++)
		{
			if ($id == $sub_keys[$j])
			{
				$if_break = true;
				if ($action == 'up')
				{
					if ($j == 0 && $i != 0)
					{
						array_splice($keys, $i - 1, 2, array($keys[$i], $keys[$i - 1]));
						array_splice($values, $i - 1, 2, array($values[$i], $values[$i - 1]));
						$var = array_combine($keys, $values);
						break;
					}
					else if ($j != 0)
					{
						array_splice($sub_keys, $j - 1, 2, array($sub_keys[$j], $sub_keys[$j - 1]));
						array_splice($sub_values, $j - 1, 2, array($sub_values[$j], $sub_values[$j - 1]));
						$var[$keys[$i]] = array_combine($sub_keys, $sub_values);
						break;
					}
				}
				else if ($action == 'down')
				{
					if ($j == $sub_count - 1 && $i != $count - 1)
					{
						array_splice($keys, $i, 2, array($keys[$i + 1], $keys[$i]));
						array_splice($values, $i, 2, array($values[$i + 1], $values[$i]));
						$var = array_combine($keys, $values);
						break;
					}
					else if ($j != $sub_count - 1)
					{
						array_splice($sub_keys, $j, 2, array($sub_keys[$j + 1], $sub_keys[$j]));
						array_splice($sub_values, $j, 2, array($sub_values[$j + 1], $sub_values[$j]));
						$var[$keys[$i]] = array_combine($sub_keys, $sub_values);
						break;
					}
				}
			}
		}
		if ($if_break)
			break;
	}
	pluginSetVariable('blokmanager', 'data', $var);
	pluginsSaveConfig();
	showlist();
}

function GetTimeStamp($date)
{
	$stamp = explode(' ', $date);
	$tdate = null;
	$ttime = null;
	switch (count($stamp))
	{
		case 1:
			$tdate = explode('.', $stamp[0]);
			break;
		case 2:
			$tdate = explode('.', $stamp[0]);
			$ttime = explode(':', $stamp[1]);
			break;
		default:
			return null;
			break;
	}
	if (!is_array($tdate) && count($tdate) != 3)
		$tdate = null;
	if (!is_array($ttime) && count($ttime) != 2)
		$ttime = null;
	if ($tdate === null && $ttime === null)
		return null;
	if ($tdate === null) $tdate = array(0,0,0);
	if ($ttime === null) $ttime = array(0,0);
	$tstamp = mktime($ttime[0], $ttime[1], 0, $tdate[1], $tdate[2], $tdate[0]);
	if ($tstamp < 0) return null;
	return $tstamp;
}

function delete()
{
	global $mysql, $lang,$userROW;

	$id = intval($_REQUEST['id']);
	$var = pluginGetVariable('blokmanager', 'data');
	$if_brek = false;
	$name = '';
	$title = '';
	foreach ($var as $k => $v)
	{
		foreach ($v as $kk => $vv)
		{
			if ($id == $kk)
			{
				$title = $vv['description'];
				$name = $k;
				$if_brek = true;
				break;
			}
		}
		if ($if_brek)
			break;
	}
	if ($var[$name][$id]['perms']<1 || $userROW['status']==1)
	{
		$mysql->query('delete from '.prefix.'_blokmanager where `id`='.db_squote($id));
		unset($var[$name][$id]);
		if (!count($var[$name])) unset($var[$name]);
		pluginSetVariable('blokmanager', 'data', $var);
		pluginsSaveConfig();
		msg(array('type' => 'info', 'info' => sprintf($lang['blokmanager:info_delete'] ,$title)));
		clear_cash();
	}
	else
	{
		msg(array("type" => "error", "text" => $lang['blokmanager:nopermdelete']));
			$error = 1;
	}
	showlist();
}
function menudelete()
{
	global $mysql, $lang;

	$id = $_REQUEST['id'];
	$menuarray = pluginGetVariable('blokmanager', 'menudata');
	$title ='';
	foreach ($menuarray as $menu => $param)
	{
			if ($id == $menu)
			{
				$title = $param['menuname'];
					break;
			}
	}
	unset($menuarray[$id]);
	pluginSetVariable('blokmanager', 'menudata', $menuarray);
	pluginsSaveConfig();
	msg(array('type' => 'info', 'info' => sprintf($lang['blokmanager:menu_delete'] ,$title)));
	clear_cash();
	menushowlist();
}


function save_bloklocation()
{
	global $userROW,$parse;
	if ($userROW['status']==1)
	{
		if ($_REQUEST['blocation'])
		{
			$opts = $_REQUEST['blocation'];
			$locationlist = array();
			foreach (explode("\n", $opts) as $line) {
				$line = trim($line);
				if (preg_match('/^(.+?) *\=\> *(.+?)$/', $line, $match)) {
					$match[1]=$parse->translit(trim(secure_html(convert($match[1]))));
					$match[2]=trim(secure_html($match[2]));
					$locationlist[$match[1]] = $match[2];
				} 
			}
			if(empty($locationlist))
			{
				msg(array("type" => "error", "text" => $lang['blokmanager:msge_errlocation']));
				$error = 1;
			}
			pluginSetVariable('blokmanager', 'locationlist', $locationlist);
			pluginSetVariable('blokmanager', 'ckonoff', $_REQUEST['ckonoff']);
			pluginsSaveConfig();
		}
		else
		{
			msg(array("type" => "error", "text" => $lang['blokmanager:msge_errlocation']));
			$error = 1;
		}
	}
	main();
}
function save_deftemplates()
{
	global $userROW,$parse;
	if ($userROW['status']==1)
	{
		if (($_REQUEST['defmenurow'])||($_REQUEST['defblokouter']))
		{
			$defs=array();
			$defs['defmenurow']=($_REQUEST['defmenurow'])?$_REQUEST['defmenurow']:'<li>[caticon][mark]<a href="[caturl]">[catname]</a></li>';
			$defs['defblokouter']=($_REQUEST['defblokouter'])?$_REQUEST['defblokouter']:'<h2>[blokname]</h2><p>[blokcode]</p>';
			pluginSetVariable('blokmanager', 'deftemplates', $defs);
			pluginsSaveConfig();
		}

	}
	main();
}
function blokonoff()
{
	$id = intval($_REQUEST['id']);
	$var = pluginGetVariable('blokmanager', 'data');
	foreach ($var as $k => $v)
	{
		foreach ($v as $kk => $vv)
		{
			if ($id == $kk)
			{
				$name = $k;
				$if_brek = true;
				break;
			}
		}
		if ($if_brek)
			break;
	}
	switch ($var[$name][$id]['state'])
				{
					case 0:$var[$name][$id]['state']=1;break;
					case 1:$var[$name][$id]['state']=0;break;
					case 2:{
						if (($var[$name][$id]['start_view'] && $var[$name][$id]['start_view'] > $t_time)||($var[$name][$id]['end_view'] && $var[$name][$id]['end_view'] <= $t_time))
						{
							$var[$name][$id]['start_view']='';
							$var[$name][$id]['end_view']='';
							$var[$name][$id]['state']=1;							
						}
						else
						{
							$var[$name][$id]['start_view']='';
							$var[$name][$id]['end_view']='';
							$var[$name][$id]['state']=0;		
						}
						break;
					}				
				}
	pluginSetVariable('blokmanager', 'data', $var);
	pluginsSaveConfig();
	showlist();
}

function menucopy()
{
	global $lang;

	$id = $_REQUEST['id'];
 	$menuarray = pluginGetVariable('blokmanager', 'menudata');
	$title ='';
		$newname=sprintf("%04u",rand(1,9999));
	$exname=array();
foreach ($menuarray as $menu => $param)
	{
			if ($id == $menu)
			{
				$title = $param['menuname'];
			}
			$exname[]=$menu;
	}
	if ($title=='')
	{
		msg(array("type" => "error", "text" => $lang['blokmanager:mcopy_errnoid']));
		return 1;
	}
	else
	{
		if (in_array($newname,$exname)) 
		{
			$try = 0;
			do {
				$newname = sprintf("%04u",rand(1,9999));
				$try++;
			} while (($try < 100) && (in_array($newname,$exname)));
					if ($try == 100) {
				// Can't create RAND name - all values are occupied
				msg(array("type" => "error", "text" => $lang['blokmanager:mcopy_errnewid']));
				return 1;
			}

		}
	$menuarray[$newname]=$menuarray[$id];
	$menuarray[$newname]['menuname']=$menuarray[$newname]['menuname'].$lang['blokmanager:copyl'];
 	pluginSetVariable('blokmanager', 'menudata', $menuarray);
 	pluginsSaveConfig();
	menushowlist();
	}
		clear_cash(); 
}
function blokcopy()
{
	global $lang, $mysql;
	$id = intval($_REQUEST['id']);
	$var = pluginGetVariable('blokmanager', 'data');
	$name='';
	foreach ($var as $k => $v)
	{
		foreach ($v as $kk => $vv)
		{
			if ($id == $kk)
			{
				$name = $k;
				$if_brek = true;
				break;
			}
		}
		if ($if_brek)
			break;
	}
	if ($name=='')
	{
		msg(array("type" => "error", "text" => $lang['blokmanager:bcopy_errnoid']));
				return;
	}
	else
	{
		$row=$mysql->record("select `blokcode`,`outerblok` from ".prefix."_blokmanager where `id`=".db_squote($id)."");
		$mysql->query("insert into ".prefix."_blokmanager (`blokcode`,`outerblok`) values (".db_squote($row['blokcode']).",".db_squote($row['outerblok']).")");
		$newid=intval($mysql->lastid('blokmanager'));
		$var[$name][$newid]=$var[$name][$id];
		$var[$name][$newid]['description']=$var[$name][$newid]['description'].$lang['blokmanager:copyl'];
		$var[$name][$newid]['state']=0;
			pluginSetVariable('blokmanager', 'data', $var);
	pluginsSaveConfig();
	clear_cash();
	showlist();

	}
}
function clear_cash()
{
	if (($dir = get_plugcache_dir('blokmanager'))) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) { 
				if ($file == "." || $file == "..")
					continue;
				unlink ($dir.$file);
			}
			closedir($handle); 
		}
	}
}
