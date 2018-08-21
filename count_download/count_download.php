<?php
/*
Plugin Name: ������� ���������� ������
Author: Mark Avdeev
Description: ������ ��������� � ���� ������, ���������� ���������� ������. ��� ������ �������� ���������� ����� ����� ������, ���������� ������������ ������� ������ ����: [download_file url="������������� ���� � �����" title="��������"]��� �����[/download_file] ������ �������� ��� ������:[download_file url="test.zip" title="�������� �����"]����[/download_file] ������� ����� ������ �� ����� ����� � �������, �� �������� ������� ������� ����������.
Author URI: www.lifeexample.ru
*/
function install_download_file() { //��� ��������� ������� ������� ����������� �������
	global $wpdb;
			$wpdb->query('CREATE TABLE IF NOT EXISTS `download_file` (
				  `url` varchar(254) NOT NULL,
				  `count` int(9) NOT NULL
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
} 
register_activation_hook(__FILE__,'install_download_file'); 

function count_download_file ($atts, $content = null) //������� ������� ����������, ����� � �������
    {
		//��������� ������� ������� - ��������, ���� �� ����������� � �������� ����������� �����, �� ������� ���, ��� ��������� ������ �������
		if (!file_exists("download_count.php")){
		$text='<? 
		if($_SERVER["HTTP_REFERER"]){
			define("DB_NAME", "'.DB_NAME.'");
			define("DB_USER", "'.DB_USER.'");
			define("DB_PASSWORD", "'.DB_PASSWORD.'");
			define("DB_HOST", "'.DB_HOST.'");
			$connect = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
			mysql_select_db(DB_NAME, $connect);
			if(mysql_query("UPDATE download_file SET count=(count + 1) WHERE url=\'{$_GET["url"]}\'")){		
					header("Content-Length: " . filesize($_GET["url"])); 			
					header("Content-type: application/octed-stream");
					header("Content-Disposition: attachment; filename=".basename($_GET["url"]));
					readfile($_GET["url"]);
			   }
			else echo "� ��������� ���� ������. ��������� <a href=".$_SERVER["HTTP_REFERER"].">�����</a>.";
		}
		else echo "���������� �� ���������� ���� ���� ��������, � ���� ������ �������.";
	
		?>';
			$fp = fopen ("download_count.php", "w"); 
			fwrite($fp,$text); 
			fclose($fp);	
		} 
		
		// ��������� ������ �� ������� ������� � ���������� ����������  ������� �����
		global $wpdb;
		$result = $wpdb->get_results("SELECT * FROM  `download_file` WHERE  `url` LIKE  '".$atts["url"]."'", ARRAY_A);
		
		if(!isset($atts["title"]))$atts["title"]=$content;
		
		if($result[0]['url']) //���� � ������� ���������� ���������� � ���������� �������, �� ������� ��
				return "<p><noindex> <a rel='nofollow' title='".$atts["title"]."' href='/download_count.php?url=".$atts["url"]."'>".$content."</a></noindex> <span style='font-style: italic; font-size:7pt;'>( �������: ".$result[0]['count']." ���. )</span>&nbsp;</p>";
		else{ // ���� ������ ������ ���������, �� ������� �� � ����, � ����������� ���������� ������� = 0
			$wpdb->query("INSERT INTO download_file VALUES ('".$atts["url"]."', '0')");
			return "<p><noindex> <a rel='nofollow' title='".$atts["title"]."' href='/download_count.php?url=".$atts["url"]."'>".$content."</a></noindex> <span style='font-style: italic; font-size:7pt;'>( �������: 0 ���. )</span>&nbsp;</p>";		
			} 
	}

add_shortcode('download_file', 'count_download_file');
?>