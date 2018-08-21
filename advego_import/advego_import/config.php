<?php

include_once('./plugins/advego_import/simplepie.inc');

$url_atom = extra_get_param('advego_import','url_atom');

// Parse it
$feed = new SimplePie();
$feed->set_feed_url($url_atom);
$feed->init();
$feed->set_output_encoding('Windows-1251');
$feed->handle_content_type();

?>


<form action="" method="post">
<?php

global $mysql, $config, $parse;

if ($feed->data) {
$items = $feed->get_items();

echo $feed->get_item_quantity()."<br/>"."<br/>";

foreach($items as $item)
{
echo $item->get_title()."<br/>";
echo $item->get_id()."<br/>";
echo $item->get_date('j.m.Y - H:i')."<br/>";
echo $item->get_content()."<br/>";
$sent = $item->get_id();
echo "<input type='submit' value='".$sent."' name='sented' class='button' />"."<br/>"."<br/>";

if($_POST['sented'] == $sent) 
  {
  
  $title = $item->get_title();
  $content = $item->get_content();
  $mainpage = 1;
  $approve = 0;

  
			$SQL['content'] = str_replace("\r\n", "\n", $content);
			$SQL['title'] = $title;
			

				$alt_name = strtolower($parse->translit(trim($title), 1));
				
				$alt_name = preg_replace(array('/\./', '/(_{2,20})/', '/^(_+)/', '/(_+)$/'), array('_', '_'), $alt_name);
				
				if ($alt_name == '') $alt_name = '_';
				
				$i = '';
				while ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name.$i)." limit 1")) ) {
					$i++;
				}
				$alt_name = $alt_name.$i;
			
			$SQL['alt_name'] = $alt_name;
			$SQL['postdate'] = time() + ($config['date_adjust'] * 60);
			
			$SQL['author']		= !empty($userROW['name'])?$userROW['name']:'Гость';
			$SQL['author_id']	= !empty($userROW['id'])?intval($userROW['id']):0;
	
			$SQL['mainpage'] = $mainpage;
			$SQL['approve'] = $approve;

			
			if(empty($error_text))
			{
				$vnames = array(); $vparams = array();
				foreach ($SQL as $k => $v) { $vnames[]  = $k; $vparams[] = db_squote($v); }
				$mysql->query("insert into ".prefix."_news (".implode(",",$vnames).") values (".implode(",",$vparams).")");

			}
  
	
	echo "<META HTTP-EQUIV='Refresh' Content='0'>";
	
	}

}



}
?>
</form>

<?php
plugins_load_config();
$cfg = array();
array_push($cfg, array('descr' => 'Плагин позволяет добавлять контент с advego.ru'));
array_push($cfg, array('name' => 'url_atom',   'title' => 'URL (XML)', 'type' => 'input', 'value' => extra_get_param($plugin,'url_atom')));

if ($_REQUEST['action'] == 'commit') {

	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete('advego_import');
} else {
	generate_config_page('advego_import', $cfg);	
	}