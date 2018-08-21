<form action="" method="post">
<?php
global $mysql, $config, $parse, $lang;
     # Open the File.
     if (($handle = fopen("/home/s/stdex/air.tw1.ru/public_html/professionals/engine/plugins/csv_reimport/stdex_tez.csv", "r")) !== FALSE) {
         # Set the parent multidimensional array key to 0.
         $nn = 0;
         while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
             # Count the total keys in the row.

			 $c = count($data);
             # Populate the multidimensional array.
             for ($x=0;$x<$c;$x++)
             {
                 $csvarray[$nn][$x] = $data[$x];
             }
             $nn++;
         }
         # Close the File.
         fclose($handle);
     }
	 else
	 {
print_r("nt_file");
	 }
	 
	//var_dump($csvarray);



echo "<input type='submit' name='sented' class='button' />"."<br/>"."<br/>";
/**///var_dump($csvarray);
if($_POST['sented']) 
  {

		 foreach ( $csvarray as $key => $value ) {

//			$mainpage = 1;
//			$approve = 1;
//			$flags = 2;

$titleF = $value[1];
var_dump($titleF);
			$nrow = $mysql->record("select * from ".prefix."_news where title=".db_squote($titleF));
			if(!($nrow)) continue;
			

		// Decode previusly stored data
		$oldFields = xf_decode($nrow['xfields']);
	
			$out_arr['gosreg']=$value[2];
			$out_arr['refusalfire']=$value[3];
			$out_arr['refusal']=$value[4];
			$out_arr['conformity']=$value[5];
			$out_arr['voluntaryfire']=$value[6];
			$out_arr['roomab']=$value[7];
			
		$xf = xf_configLoad();
		if (!is_array($xf))
			return 1;
		$rcall = $out_arr;
		if (!is_array($rcall)) $rcall = array();

		$xdata = array();
		foreach ($xf['news'] as $id => $data) {
			// Skip disabled fields
			if ($oldFields[$id]) {
				$xdata[$id] = $oldFields[$id];
				continue;
			}
			if ($data['type'] == 'images') { continue; }
			// Fill xfields. Check that all required fields are filled
			if ($rcall[$id] != '') {
				$xdata[$id] = $rcall[$id];
			} else if ($data['required']) {
				msg(array("type" => "error", "text" => str_replace('{field}', $id, $lang['xfields_msge_emptyrequired'])));
				return 0;
			}
			// Check if we should save data into separate SQL field
			if ($data['storage'] && ($rcall[$id] != '')) {
				$SQL['xfields_'.$id] = $rcall[$id];
				$xdata[$id] = $rcall[$id];
				}
		}
		
		//var_dump($xdata);

	    $SQL['xfields']   = xf_encode($xdata);
  
		//$SQL['content'] = str_replace("\r\n", "\n", $content);
		//$SQL['title'] = $title;
			
/*
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
			$SQL['flags'] = $flags;

		*/		
			if(empty($error_text))
			{
				//$vnames = array(); $vparams = array();
				//foreach ($SQL as $k => $v) { $vnames[]  = $k; $vparams[] = db_squote($v); }
				foreach ($SQL as $k => $v) {
				
				$mysql->query("UPDATE ".prefix."_news SET ".$k." = ".db_squote($v)." WHERE title = ".db_squote($titleF));
				//$vnames[]  = $k; 
				//$vparams[] = db_squote($v);
				}
				

			}
		
			unset($SQL);
			unset($out_arr);
		 }

  
	
	echo "<META HTTP-EQUIV='Refresh' Content='0'>";
	
	}
/**/

?>
</form>

<?php
plugins_load_config();
$cfg = array();
array_push($cfg, array('descr' => 'Плагин позволяет добавлять контент из csv'));


if ($_REQUEST['action'] == 'commit') {

	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete('csv_reimport');
} else {
	generate_config_page('csv_reimport', $cfg);	
	}