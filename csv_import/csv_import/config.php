<form action="" method="post">
<?php
global $mysql, $config, $parse, $lang;
     # Open the File.
     if (($handle = fopen("pin_hamer_1.csv", "r")) !== FALSE) {
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
	//var_dump($csvarray);



echo "<input type='submit' name='sented' class='button' />"."<br/>"."<br/>";

if($_POST['sented']) 
  {

		 foreach ( $csvarray as $value ) {

			$mainpage = 1;
			$approve = 1;
			$flags = 2;
			$title = $value[0];
			$content = $value[3];
			
			$out_arr['manufacturer']=$value[1];
			$out_arr['product']=$value[2];
			$out_arr['applicationarea']=$value[4];
			$out_arr['featuresapply']=$value[5];
			$out_arr['dilution']=$value[6];
			$out_arr['spraying']=$value[7];
			$out_arr['composition']=$value[8];
			$out_arr['unuqecomponents']=$value[9];
			$out_arr['activesubstance']=$value[10];
			$out_arr['thinner']=$value[11];
			$out_arr['solvent']=$value[12];
			$out_arr['tools']=$value[13];
			$out_arr['solids']=$value[14];
			$out_arr['density']=$value[15];
			$out_arr['waterperm']=$value[16];
			$out_arr['steamperm']=$value[17];
			$out_arr['steampermclass']=$value[18];
			$out_arr['heatresist']=$value[19];
			$out_arr['thicknesswet']=$value[20];
			$out_arr['thicknessdry']=$value[21];
			$out_arr['timefinishcoat']=$value[22];
			$out_arr['timecomplete']=$value[23];
			$out_arr['consumptionlay']=$value[24];
			$out_arr['bases']=$value[25];
			$out_arr['systemtinting']=$value[26];
			$out_arr['color']=$value[27];
			$out_arr['storagelife']=$value[28]
			$out_arr['caresurface']=$value[29];
			$out_arr['gardner']=$value[30];
			$out_arr['glossdegreet1']=$value[31];
			$out_arr['glossdegreet2']=$value[32];
			$out_arr['glossdegreet3']=$value[33];
			$out_arr['glossdegreet4']=$value[34];
			$out_arr['glossdegreet5']=$value[35];
			$out_arr['glossdegreet6']=$value[36];
			$out_arr['glosswords']=$value[37];
			$out_arr['systemtinting']=$value[38];
			$out_arr['classwetabrasion1']=$value[39];
			$out_arr['classwetabrasion2']=$value[40];
			$out_arr['grainsize']=$value[41];
			$out_arr['timegrinding']=$value[42];
			$out_arr['tflash']=$value[43];
			$out_arr['restrictions']=$value[44];
			$out_arr['frostresistance']=$value[45];

		$xf = xf_configLoad();
		if (!is_array($xf))
			return 1;
		$rcall = $out_arr;
		if (!is_array($rcall)) $rcall = array();

		$xdata = array();
		foreach ($xf['news'] as $id => $data) {
			if ($data['disabled'])
				continue;

			if ($data['type'] == 'images') { continue; }
			// Fill xfields. Check that all required fields are filled
			if ($rcall[$id] != '') {
				$xdata[$id] = $rcall[$id];
			} else if ($data['required']) {
				msg(array("type" => "error", "text" => str_replace('{field}', $id, $lang['xfields_msge_emptyrequired'])));
				return 0;
			}
			// Check if we should save data into separate SQL field
			if ($data['storage'] && ($rcall[$id] != ''))
				$SQL['xfields_'.$id] = $rcall[$id];
		}

	    $SQL['xfields']   = xf_encode($xdata);
  
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
			$SQL['flags'] = $flags;

			
			if(empty($error_text))
			{
				$vnames = array(); $vparams = array();
				foreach ($SQL as $k => $v) { $vnames[]  = $k; $vparams[] = db_squote($v); }
				$mysql->query("insert into ".prefix."_news (".implode(",",$vnames).") values (".implode(",",$vparams).")");

			}
				
		 }
	
	echo "<META HTTP-EQUIV='Refresh' Content='0'>";
	
	}



}
?>
</form>

<?php
plugins_load_config();
$cfg = array();
array_push($cfg, array('descr' => 'Плагин позволяет добавлять контент из csv'));


if ($_REQUEST['action'] == 'commit') {

	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete('csv_import');
} else {
	generate_config_page('csv_import', $cfg);	
	}