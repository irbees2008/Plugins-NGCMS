<?
if (!defined('NGCMS')) { die("Don't you figure you're so cool?"); }

include_once(root."/plugins/autokeys/inc/class.php");

register_plugin_page('autokeys','','autokeys_ajax',0);

function autokeys_ajax()
{
global $userROW;
if($userROW['status'] <=3)
{
if($_POST['q'])
{
header('Content-type: text/html; charset=windows-1251');
include_once("inc/class.php");
if($_POST['q']=='') die('{"res":"error", "msg":"Нет данных!"}');
		$params['content'] = iconv('UTF-8', 'windows-1251', $_POST['q']);
		$params['min_word_length'] = (intval(extra_get_param('autokeys','length'))) ? intval(extra_get_param('autokeys','length')) : '5';// yes
		$params['max_word_length'] = (intval(extra_get_param('autokeys','sub'))) ? intval(extra_get_param('autokeys','sub')) : '100';// yes
		$params['min_word_occur'] = (intval(extra_get_param('autokeys','occur'))) ? intval(extra_get_param('autokeys','occur')) : '2';// yes
		$params['word_sum'] = (intval(extra_get_param('autokeys','sum'))) ? intval(extra_get_param('autokeys','sum')) : '245';// yes
		$params['block_word'] = extra_get_param('autokeys','block_y') ? extra_get_param('autokeys','block_y') : false;// yes
		$params['block_array'] = extra_get_param('autokeys','block');// yes
		$params['good_word'] = extra_get_param('autokeys','good_y') ? extra_get_param('autokeys','good_y') : false;// yes
		$params['good_array'] = extra_get_param('autokeys','good');// yes
		$params['add_title'] = (intval(extra_get_param('autokeys','add_title'))) ? intval(extra_get_param('autokeys','add_title')) : '0';// yes
		$params['word_count'] = (intval(extra_get_param('autokeys','count'))) ? intval(extra_get_param('autokeys','count')) : '245';// yes		
		$params['good_b'] = extra_get_param('autokeys','good_b') ? extra_get_param('autokeys','good_b') : false;// yes	
		$keyword = new autokeyword($params, "windows-1251");
		$SQL['keywords'] = substr($keyword->parse_words(),0,$params['word_sum']);
		$SQL['keywords'] = substr($SQL['keywords'],0,strrpos($SQL['keywords'], ', '));		
echo '{"res":"ok", "x1":"<span>'.$SQL['keywords'].'</span>"}';
exit;
}
}
}

class autoKeysNewsFilter extends NewsFilter {

	function addNews(&$tvars, &$SQL) 
	{ 
		if ($_POST['autokeys_true'] == 1)
		{
		$params['content'] = $SQL['content'];// yes
		$params['title'] = $SQL['title'];
		$params['min_word_length'] = (intval(extra_get_param('autokeys','length'))) ? intval(extra_get_param('autokeys','length')) : '5';// yes
		$params['max_word_length'] = (intval(extra_get_param('autokeys','sub'))) ? intval(extra_get_param('autokeys','sub')) : '100';// yes
		$params['min_word_occur'] = (intval(extra_get_param('autokeys','occur'))) ? intval(extra_get_param('autokeys','occur')) : '2';// yes
		$params['word_sum'] = (intval(extra_get_param('autokeys','sum'))) ? intval(extra_get_param('autokeys','sum')) : '245';// yes
		$params['block_word'] = extra_get_param('autokeys','block_y') ? extra_get_param('autokeys','block_y') : false;// yes
		$params['block_array'] = extra_get_param('autokeys','block');// yes
		$params['good_word'] = extra_get_param('autokeys','good_y') ? extra_get_param('autokeys','good_y') : false;// yes
		$params['good_array'] = extra_get_param('autokeys','good');// yes
		$params['add_title'] = (intval(extra_get_param('autokeys','add_title'))) ? intval(extra_get_param('autokeys','add_title')) : '0';// yes
		$params['word_count'] = (intval(extra_get_param('autokeys','count'))) ? intval(extra_get_param('autokeys','count')) : '245';// yes		
		$params['good_b'] = extra_get_param('autokeys','good_b') ? extra_get_param('autokeys','good_b') : false;// yes	
		$keyword = new autokeyword($params, "windows-1251");
		$SQL['keywords'] = substr($keyword->parse_words(),0,$params['word_sum']);
		$SQL['keywords'] = substr($SQL['keywords'],0,strrpos($SQL['keywords'], ', '));
		return 1;
		}
		else
		{
		return 1;
		}		
	}
	
	function editNews($newsID, $SQLold, &$SQLnew, &$tvars) 

	{ 
		if ($_POST['autokeys_true'] == 1)
		{
		$params['content'] = $SQLold['content'];// yes
		$params['title'] = $SQLold['title'];
		$params['min_word_length'] = (intval(extra_get_param('autokeys','length'))) ? intval(extra_get_param('autokeys','length')) : '5';// yes
		$params['max_word_length'] = (intval(extra_get_param('autokeys','sub'))) ? intval(extra_get_param('autokeys','sub')) : '100';// yes
		$params['min_word_occur'] = (intval(extra_get_param('autokeys','occur'))) ? intval(extra_get_param('autokeys','occur')) : '2';// yes
		$params['word_sum'] = (intval(extra_get_param('autokeys','sum'))) ? intval(extra_get_param('autokeys','sum')) : '245';// yes
		$params['block_word'] = extra_get_param('autokeys','block_y') ? extra_get_param('autokeys','block_y') : false;// yes
		$params['block_array'] = extra_get_param('autokeys','block');// yes
		$params['good_word'] = extra_get_param('autokeys','good_y') ? extra_get_param('autokeys','good_y') : false;// yes
		$params['good_array'] = extra_get_param('autokeys','good');// yes
		$params['add_title'] = (intval(extra_get_param('autokeys','add_title'))) ? intval(extra_get_param('autokeys','add_title')) : '0';// yes
		$params['word_count'] = (intval(extra_get_param('autokeys','count'))) ? intval(extra_get_param('autokeys','count')) : '245';// yes		
		$params['good_b'] = extra_get_param('autokeys','good_b') ? extra_get_param('autokeys','good_b') : false;// yes	
		$keyword = new autokeyword($params, "windows-1251");
		$SQLnew['keywords'] = substr($keyword->parse_words(),0,$params['word_sum']);
		$SQLnew['keywords'] = substr($SQLnew['keywords'],0,strrpos($SQLnew['keywords'], ', '));		
		return 1;
		}
		else
		{
		return 1;
		}
	}
	
		function editNewsForm($newsID, $SQLold, &$tvars) 
		{
		$tvars['vars']['autokeys'] .= ' <input type="checkbox" name="autokeys_true" value="1" checked class="check" id="autokeys_true" /> Генерировать keywords?';
		return 1;
		}
	
		function addNewsForm(&$tvars) {
		$tvars['vars']['autokeys'] .= ' <input type="checkbox" name="autokeys_true" value="1" checked class="check" id="autokeys_true" /> Генерировать keywords?';		return 1;
	}
	
}

register_filter('news','autokeys', new autoKeysNewsFilter);
?>