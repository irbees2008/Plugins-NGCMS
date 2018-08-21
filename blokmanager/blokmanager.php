<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

add_act('index', 'plugin_blokmanager');
$plugin_blokmanager_data = array('stat' => false, 'stat_id' => null, 'cat' => false, 'cat_id' => null, 'main' => false);
class BlokManagerStaticFilter extends StaticFilter {
	function showStatic($staticID, $SQLstatic, &$tvars, $mode) { 
		global $plugin_blokmanager_data;
		$plugin_blokmanager_data['stat'] = true;
		$plugin_blokmanager_data['stat_id'] = $staticID;
		return 1; 
	}
}
register_filter('static','blokmanager', new BlokManagerStaticFilter);

function plugin_blokmanager($params)
{	
	global $template, $config, $CurrentHandler, $catmap,$catz, $mysql, $plugin_blokmanager_data;
	$var = pluginGetVariable('blokmanager', 'data');
	$pos = pluginGetVariable('blokmanager', 'locationlist');
	$menuarray=pluginGetVariable('blokmanager', 'menudata');
	if (!is_array($var)) return;

	if ($CurrentHandler['params'][0] == '/') $plugin_blokmanager_data['main'] = true;
	if (isset($CurrentHandler['params']['catid'])) 
	{
		$plugin_blokmanager_data['cat'] = true;
		$plugin_blokmanager_data['cat_id'] = $CurrentHandler['params']['catid'];
	}
	else if (isset($CurrentHandler['params']['category'])) 
	{
		$plugin_blokmanager_data['cat'] = true;
		$plugin_blokmanager_data['cat_id'] = array_search($CurrentHandler['params']['category'], $catmap);
	}
	$t_time = time();
	$childmap=generate_ChildMap();
	$defs = pluginGetVariable('blokmanager', 'deftemplates');
	foreach ($var as $k => $v)
	{
		if (!$k) continue;
		if (!isset($template['vars'][$k])) $template['vars'][$k] = '';
		foreach ($v as $kk => $vv)
		{
			if (!$vv['state']) continue;
			$if_view = true;
			if ($vv['state'] == 2)
			{
				if ($vv['start_view'] && $vv['start_view'] > $t_time)
					$if_view = false;
				if ($vv['end_view'] && $vv['end_view'] <= $t_time)
					$if_view = false;
			}
			if (!$if_view) continue;
			if (is_array($vv['location']))
			{
				$if_view = false;
				$if_break = false;
				foreach ($vv['location'] as $kkk => $vvv)
				{
					switch ($vvv['mode'])
					{
						case 0:
							if ($vvv['view']) {$if_view = false; $if_break = true;} else $if_view = true;
							break;
						case 1:
							if ($plugin_blokmanager_data['main']) 
							{
								if ($vvv['view']) {$if_view = false; $if_break = true;} else $if_view = true;
							}
							break;
						case 2:
							if (!$plugin_blokmanager_data['main']) 
							{
								if ($vvv['view']) {$if_view = false; $if_break = true;} else $if_view = true;
							}
							break;
						case 3:
							if ($plugin_blokmanager_data['cat'])
							{
								if ($vvv['recursiv'])
								{
									$topid=$plugin_blokmanager_data['cat_id'];
									$chmap=array();
									$chmap=getfromChMap($plugin_blokmanager_data['cat_id'],$childmap);
									$parents=array();
									while ($chmap['parent']!=0)
									{
										$topid=$chmap['parent'];
										$parents[]=$topid;
										$chmap=getfromChMap($topid,$childmap);
									}
								}
								if (!$vvv['id'])
								{
									if ($vvv['view']) {$if_view = false; $if_break = true;} else $if_view = true;
								}
								else if (($plugin_blokmanager_data['cat_id'] == $vvv['id'])||(($vvv['recursiv'])&&(($vvv['id'] ==$topid)||(in_array($vvv['id'],$parents)))))
								{
									if ($vvv['view']) {$if_view = false; $if_break = true;} else $if_view = true;
								}
							}
							break;
						case 4:
							if ($plugin_blokmanager_data['stat'])
							{
								if (!$vvv['id'])
								{
									if ($vvv['view']) {$if_view = false; $if_break = true;} else $if_view = true;
								}
								else if ($plugin_blokmanager_data['stat_id'] == $vvv['id'])
								{
									if ($vvv['view']) {$if_view = false; $if_break = true;} else $if_view = true;
								}
							}
							break;
					}
					if ($if_break) break;
				}
				if (!$if_view) continue;
			}
			if ($vv['type']==3)
			{
				$cacheFileName =md5('blokmanager'.$kk.$vv['type']).$vv['menuid'].'.txt';
				$cacheData = cacheRetrieveFile($cacheFileName, 30000, 'blokmanager');
				if ($cacheData != false) {
					$template['vars'][$k] .= $cacheData;
					continue;
										}
				foreach ($menuarray as $menu=>$param)
				{
					if ($menu==$vv['menuid'])
					{
						$blokcode='';
						$coll=array();
						$perrow=array();
						$delim=array();
						$levelcode=array();
						$perrowdo=0;
						$catselected=explode('|',$param['menucatids']);
						$coderow=($param['menutemplate'])?$param['menutemplate']:$defs['defmenurow'];
						$levelcode=explode('###NextLevel###',$coderow);
						$perrow=explode('###INROW###',$coderow);
							if (sizeof($perrow)>1){$delim=explode('###DELIM###',$perrow[1]);$perrowdo=1;}
						$markrepeat=0;		
						$prevlev=0;		
						$rowcounter=1;
						foreach ($catz as $cat=>$val)
						{
							if (!substr($val['flags'],0,1)) continue;
							$code='';
							$chmap=array();
							$chmap=getfromChMap($val['id'],$childmap);
							$rlevel=(in_array($val['id'],$catselected))?0:(($markrepeat==0)?$val['poslevel']-$prevlev:$markrepeat);
							$rlevel2=(in_array($val['parent'],$catselected))?0:(($markrepeat==0)?$val['poslevel']-$prevlev:$markrepeat);
							if ((($param['menulevel']<1000)&&(in_array($val['id'],$catselected)||(is_array($chmap['childs'])&& in_array($val['id'],$chmap['childs']) && in_array($chmap['parent'],$coll) &&($rlevel<=$param['menulevel']))))||(($param['menulevel']>=1000)&&in_array($val['parent'],$catselected)||(is_array($chmap['childs'])&& in_array($val['id'],$chmap['childs']) && in_array($chmap['parent'],$coll) &&($rlevel<=$param['menulevel']-1000))))
							{
								 if ($perrowdo==1)
								{
									$code=$perrow[0];
								}
								else
								{ 
									$code=(sizeof($levelcode)>1)?$levelcode[$rlevel]:$coderow;
								}
								$coll[]=$val['id'];
								$prevlev=$val['poslevel'];
								$markrepeat=($param['menulevel']<1000)?$rlevel:$rlevel2;
								$code=($val['icon'])?preg_replace("#\[caticon\]#is",'<img src="'.$val['icon'].'" border="0" align="center" alt='.$val['name'].'""><br>',$code):preg_replace("#\[caticon\]#is",'<img src="/uploads/images/111/file_broken.png" border="0" align="center" alt='.$val['name'].'""><br>',$code);
								$code=preg_replace("#\[mark\]#is",str_repeat($param['levelmark'], $markrepeat),$code);
								$code=preg_replace("#\[catid\]#is",$val['id'],$code);
								$code=preg_replace("#\[caturl\]#is",'/'.$val['alt'].'.html',$code);
								if ($val['posts']!=0 && $val['poslevel']>1)
								{
									$code=preg_replace("#\[catname\]#is",'<b>'.strtoupper($val['name']).'</b>',$code);
								}
								elseif ($val['posts']==0 && $val['poslevel']>1)
								{
									$code=preg_replace("#\[catname\]#is",strtolower($val['name']),$code);
								}
								else
								{
									$code=preg_replace("#\[catname\]#is",$val['name'],$code);
								}
								if (($perrowdo==1)&&($rowcounter==$delim[0]))
								{
									$code.=$delim[1];
									$rowcounter=1;
								}
								else
								{
									$rowcounter++;
								}
								$blokcode.=$code;
							}
								
						}
						if ($perrowdo==1 && $rowcounter!=1)
						{
							while ($rowcounter<=$delim[0])
							{
								$code=$perrow[0];
								$code=preg_replace("#\[caticon\]#is",'',$code);
								$code=preg_replace("#\[mark\]#is",'',$code);
								$code=preg_replace("#\[catid\]#is",'',$code);
								$code=preg_replace("#\[caturl\]#is",'',$code);
								$code=preg_replace("#\[catname\]#is",'',$code);
								$blokcode.=$code;
								$rowcounter++;
							}
						}
						break;
					}
				}
				$rescode = '';
				if (is_array($row = $mysql->record('select `outerblok` from '.prefix.'_blokmanager where `id`='.db_squote($kk))))
				{
					if ($row['outerblok'])
					{
						$rescode= preg_replace("#\[blokname\]#is", $vv['description'], $row['outerblok']);
						$rescode= preg_replace("#\[blokcode\]#is",$blokcode, $rescode);
					}
					else
					{
						$rescode=$blokcode;
					}
				}
				$template['vars'][$k] .= $rescode;
				cacheStoreFile($cacheFileName, $rescode, 'blokmanager');
			}
			elseif ($vv['type']==4)
			{
				
				$rescode = '';
				$codear=array();
					if ($vv['period']==0)
					{
						if (is_array($row = $mysql->record('select `blokcode`,`outerblok` from '.prefix.'_blokmanager where `id`='.db_squote($kk))))
						{
							$code=$row['blokcode'];
							$codear=explode('###NEXT###',$code);
							$codelen=count($codear);
							if ($row['outerblok'])
							{
								$rescode= preg_replace("#\[blokname\]#is", $vv['description'], $row['outerblok']);
								$rescode= preg_replace("#\[blokcode\]#is",$codear[mt_rand(0,$codelen-1)], $rescode);
							}
							else
							{
								$rescode=$codear[mt_rand(0,$codelen-1)];
							}
						}
					}
					else
					{
						$partnum=intval(($t_time-$vv['start_viewperiod'])/($vv['period']*60));
						$timediff=$vv['period']*60-(($t_time-$vv['start_viewperiod'])-($vv['period']*60*$partnum));
						$cacheFileName = md5('blokmanager'.$kk.$vv['type']).'.txt';
						$cacheData = cacheRetrieveFile($cacheFileName, $timediff, 'blokmanager');
						if ($cacheData != false) 
						{
							$template['vars'][$k] .= $cacheData;
							continue;
						}
						if (is_array($row = $mysql->record('select `blokcode`,`outerblok` from '.prefix.'_blokmanager where `id`='.db_squote($kk))))
						{
							$code=$row['blokcode'];
							$codear=explode('###NEXT###',$code);
							$codelen=count($codear);
							if($codelen>0)
							{
								if($partnum>=$codelen)
								{
									$partnum=$partnum-$codelen*intval($partnum/$codelen);
								}
								if ($row['outerblok'])
								{
									$rescode= preg_replace("#\[blokname\]#is", $vv['description'], $row['outerblok']);
									$rescode= preg_replace("#\[blokcode\]#is",$codear[$partnum], $rescode);
								}
								else
								{
									$rescode= $codear[$partnum];
								}
							}
						}
						cacheStoreFile($cacheFileName, $rescode, 'blokmanager');
					}

					$template['vars'][$k] .= $rescode;
					
			}
			elseif ($vv['type'] == 1)
			{
				$code='';
				if (is_array($row = $mysql->record('select `blokcode`,`outerblok` from '.prefix.'_blokmanager where `id`='.db_squote($kk))))
				{
					$code = $row['blokcode'];
				}
				ob_start();
				@eval($code);
				$out2 = ob_get_contents();
				ob_end_clean();
				if ($row['outerblok'])
				{
					$code= preg_replace("#\[blokname\]#is", $vv['description'], $row['outerblok']);
					$out2= preg_replace("#\[blokcode\]#is", $out2, $code);
				}
				$template['vars'][$k] .= $out2;
			}
			else
			{
				$cacheFileName = md5('blokmanager'.$kk.$vv['type']).'.txt';
				$cacheData = cacheRetrieveFile($cacheFileName, 30000, 'blokmanager');
				if ($cacheData != false) {
					$template['vars'][$k] .= $cacheData;
					continue;
				}
				$rescode = '';
				if (is_array($row = $mysql->record('select `blokcode`,`outerblok` from '.prefix.'_blokmanager where `id`='.db_squote($kk))))
				{
					$code = $vv['type']?nl2br(htmlspecialchars($row['blokcode'])):$row['blokcode'];
					if ($row['outerblok'])
					{
						$rescode= preg_replace("#\[blokname\]#is", $vv['description'], $row['outerblok']);
						$rescode= preg_replace("#\[blokcode\]#is", $code, $rescode);
					}
					else
					{
						$rescode= $code;
					}
				}
				$template['vars'][$k] .= $rescode;
				
				cacheStoreFile($cacheFileName, $rescode, 'blokmanager');
			}
			

		}
		if ($if_brek)
			break;
	}
}
function generate_ChildMap()
{
	global $catz;
	$childmap=array();
   foreach ($catz as $cat=>$val)
   {
		if ($val['parent']!=0 && $val['poslevel']<8)
		{
			$childmap[$val['parent']][$val['poslevel']][]=$val['id'];
		}
																
   }
/*	foreach ($childmap as $parent=>$data)
	{
		foreach ($data as $pos=>$childs)
		{
			echo $pos.' -'.$parent.' - '.implode(',',$childs).'<br>';
		}
	}
	* */
	return $childmap;
}
function getfromChMap($id,$childmap)
{
	$lev=array();
	$lev['rlevel']=0;
	$lev['childs']=0;
	$lev['parent']=0;
	foreach ($childmap as $parent=>$data)
	{
		foreach ($data as $pos=>$childs)
		{
			if (in_array($id,$childs))
			{
				$lev['rlevel']=$pos;
				$lev['childs']=$childs;
				$lev['parent']=$parent;
				break;
			}

		}
		if($lev['childs']!=0) 
		  break;
	}
	return $lev;
}
