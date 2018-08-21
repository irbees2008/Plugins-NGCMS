<?php

/*
 * Same_Category_News for NextGeneration CMS (http://ngcms.ru/)
 * Copyright (C) 2010-2011 Alexey N. Zhukov (http://digitalplace.ru)
 * http://digitalplace.ru
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
 
# protect against hack attempts
if (!defined('NGCMS')) die ('Galaxy in danger');

define('PLUGIN', 'same_category_news');

class scnNewsfilter extends NewsFilter {

	function showNews($newsID, $SQLnews, &$tvars, $mode = array()) {
		global $tpl, $catz, $mysql, $parse, $config, $PFILTERS;

		$count = pluginGetVariable(PLUGIN, 'count');
		if ((intval($count) < 1)||(intval($count) > 20)) 
			$count = 1;
		
		for ($i = 1; $i <= $count; $i++) {
					
			$view_full		= pluginGetVariable(PLUGIN, $i.'_view_full');
			$view_short		= pluginGetVariable(PLUGIN, $i.'_view_short');
					
			if(($mode['style'] == 'full' && $view_full) || ($mode['style'] == 'short' && $view_short)) {

				if (pluginGetVariable(PLUGIN, $i.'_categories') && (pluginGetVariable(PLUGIN, $i.'_categories') != $SQLnews['catid'])){
					$tvars['vars'][PLUGIN.'_'.$i] = '';
					continue;
				}
						
				unset($catfilter);
				unset($filter);
				
				$categories = explode(',', $SQLnews['catid']); 
					
				foreach ($categories as $cat) {
					$catfilter [] = "(catid regexp '[[:<:]](".trim($cat).")[[:>:]]')";
				} 
				if (count($catfilter))
					$filter [] = '('.join(' OR ', $catfilter).')';
			
				$number	= pluginGetVariable(PLUGIN, $i.'_number');
				if (!$number) 	   $number = 5;	
				
				switch (pluginGetVariable(PLUGIN , $i.'_orderby')) {
					case 'desc': $orderby = 'postdate DESC'; break;
					case 'asc': $orderby = 'postdate ASC'; break;
				default: $orderby = 'rand()';
				}
						
				# determine paths for all template files
				if (intval(pluginGetVariable(PLUGIN, 'localsource')) == 1 || (intval(pluginGetVariable(PLUGIN, 'localsource')) == 0 && !is_dir(tpl_site.'plugins/'.PLUGIN.'/'.PLUGIN.'_'.$i))) $overrideTemplatePath = root.'/plugins/'.PLUGIN.'/tpl/'.PLUGIN.'_'.$i;
				else $overrideTemplatePath = tpl_site.'plugins/'.PLUGIN.'/'.PLUGIN.'_'.$i;
		
				$tpath = array('template' => $overrideTemplatePath);
					
				# SQL query
				if(pluginGetVariable(PLUGIN, $i.'_short_news'))
					$sql = "SELECT * FROM ".prefix."_news WHERE id !=".$SQLnews['id']." AND approve=1 AND ".join(" AND ", $filter)." ORDER BY ".$orderby." LIMIT 0,".$number;
				else
					$sql = "SELECT id, postdate, author, title, views, com, alt_name, catid". (getPluginStatusActive('xfields') ? ", xfields " : "") ." FROM ".prefix."_news WHERE id !=".$SQLnews['id']." AND approve=1 AND ".join(" AND ", $filter)." ORDER BY ".$orderby." LIMIT 0,".$number;
				
				
				foreach($mysql->select($sql) as $row) {
						
					$short_news = '';
					
					if(pluginGetVariable(PLUGIN, $i.'_short_news')){
						
						$news_length 	= intval(pluginGetVariable(PLUGIN, 'news_length'));
						if (!$news_length) $news_length = 100;				
						
						list ($short_news, $full_news) = explode('<!--more-->', $row['content'], 2);
						if ($config['blocks_for_reg'])			  $short_news = $parse -> userblocks($short_news);
						if ($config['use_htmlformatter'])   	  $short_news = $parse -> htmlformatter($short_news);
						if ($config['use_bbcodes'])         	  $short_news = $parse -> bbcodes($short_news);
						if ($config['use_smilies'])         	  $short_news = $parse -> smilies($short_news);
						if (strlen($short_news) > $newslength)    $short_news = $parse -> truncateHTML($short_news, $news_length);
			
						if (pluginGetVariable(PLUGIN, $i.'_img')) $short_news = preg_replace('/<img.*?>/', '', $short_news);
					}
					
					$pvars['vars'] = array(
						"link"		=> newsGenerateLink($row),
						"title"		=> $row['title'],
						"date"		=> LangDate(timestamp, $row['postdate']),
						"author"	=> $row['author'],
						"com"		=> $row['com'],
						"views"		=> $row['views'],
						"short_news"=> $short_news
					);

					# execute filters [ if requested ]
					if (pluginGetVariable(PLUGIN, $i.'_pcall') && getPluginStatusActive('xfields')){
						require_once(root.'/plugins/xfields/xfields.php');
						if (($xf = xf_configLoad())){
							$fields = xf_decode($row['xfields']);

							if (is_array($xf['news']))
								foreach ($xf['news'] as $k => $v) {
									$kp = preg_quote($k, "'");
									$xfk = isset($fields[$k])?$fields[$k]:'';
									$pvars['regx']["'\[xfield_".$kp."\](.*?)\[/xfield_".$kp."\]'is"] = ($xfk == "")?"":"$1";
									$pvars['vars']['[xvalue_'.$k.']'] = ($v['type'] == 'textarea')?'<br/>'.(str_replace("\n","<br/>\n",$xfk).(strlen($xfk)?'<br/>':'')):$xfk;
								}
						}
					}
							
					$tpl -> template('template', $tpath['template']);
					$tpl -> vars('template', $pvars);
					$output .= $tpl -> show('template');
					
				}
				
			$tvars['vars'][PLUGIN.'_'.$i] = $output;
			$output = '';
			} else $tvars['vars'][PLUGIN.'_'.$i] = '';
		}
		return 0;
	}
}

register_filter('news', PLUGIN, new scnNewsFilter);