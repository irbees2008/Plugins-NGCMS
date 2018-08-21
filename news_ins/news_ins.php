<?

if (!defined('NGCMS')) die ('HAL');
 

class PluginStaticFilter extends StaticFilter {

function showStatic($staticID, $SQLnstatic, &$tvars){

global $template, $config, $mysql, $tpl, $lang, $CurrentHandler, $SYSTEM_FLAGS;


$num = intval(extra_get_param('news_ins','number'));
		if (($num < 1) || ($num > 50)) {$num = 10;}	
 
	$tpath = locatePluginTemplates(array('news_ins', 'entries'), 'news_ins');
 
 switch ($SYSTEM_FLAGS['static']['db.id']) {
    case 1:
        $like = 'аэрофлот';
        break;
		
    case 5:
        $like ="вим-авиа";
        break;
		
    case 7:
        $like ="владивосток авиа";
		break;
		
	case 11:
        $like ="кубань";	
        break;
		
	case 2:
        $like ="нордавиа";	
        break;
		
	case 4:
        $like ="оренбургские авиалинии";	
        break;
		
	case 9:
        $like ="Ред Вингс";	
        break;
		
	case 3:
        $like ="S7";	
        break;
		
	case 6:
        $like ="трансаэро";	
        break;

	case 10:
        $like ="уральские авиалинии";	
        break;
		
	case 12:
        $like ="ютэйр";	
        break;
		
	case 8:
        $like ="rossiya airlines";	
        break;

	default: $like = '0';
}

if($like == '0')
$v = 'Ничего нет';
else
foreach ($mysql->select("select * from ".prefix."_news WHERE `title` LIKE '%".$like."%' limit ".$num) as $row) {
          $tvarss['vars'] = array(
               'link'          =>     newsGenerateLink($row),
               'title'          =>     $row['title']
          );
          $tpl -> template('entries', $tpath['entries']);
          $tpl -> vars('entries', $tvarss);
          $v .= $tpl -> show('entries');
}
  
 
 $tvart['vars'] = array ( 'entries' => $v);
 $tpl -> template('news_ins', $tpath['news_ins']);
 $tpl -> vars('news_ins', $tvart);
 $tvars['vars']['news_ins'] = $tpl -> show('news_ins');
}

}

register_filter('static','news_ins', new PluginStaticFilter);