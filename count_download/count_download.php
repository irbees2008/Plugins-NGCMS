<?php
/*
Plugin Name: Счетчик скачивания файлов
Author: Mark Avdeev
Description: Плагин сохраняет в базу данных, количество скачиваний файлов. Для вывода счетчика скачиваний файла около ссылки, необходимо использовать шорткод такого вида: [download_file url="относительный путь к файлу" title="описание"]Имя файла[/download_file] Пример шорткода для ссылки:[download_file url="test.zip" title="Тестовый архив"]Тест[/download_file] вставив такую строку на любое место в записях, вы получите готовый счетчик скачиваний.
Author URI: www.lifeexample.ru
*/
function install_download_file() { //при активации плагина создаем необходимую таблицу
	global $wpdb;
			$wpdb->query('CREATE TABLE IF NOT EXISTS `download_file` (
				  `url` varchar(254) NOT NULL,
				  `count` int(9) NOT NULL
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
} 
register_activation_hook(__FILE__,'install_download_file'); 

function count_download_file ($atts, $content = null) //Выводим счетчик скачиваний, рядом с ссылкой
    {
		//проверяем наличие скрипта - счетчика, если он отсутствует в корневой дирректории сайта, то создаем его, для корретной работы плагина
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
			else echo "К сожалению файл удален. Вернуться <a href=".$_SERVER["HTTP_REFERER"].">назад</a>.";
		}
		else echo "Пожалуйста не открывайте этот фаил напрямую, у него другие функции.";
	
		?>';
			$fp = fopen ("download_count.php", "w"); 
			fwrite($fp,$text); 
			fclose($fp);	
		} 
		
		// считываем данные из таблицы плагина о количестве скачиваний  данного файла
		global $wpdb;
		$result = $wpdb->get_results("SELECT * FROM  `download_file` WHERE  `url` LIKE  '".$atts["url"]."'", ARRAY_A);
		
		if(!isset($atts["title"]))$atts["title"]=$content;
		
		if($result[0]['url']) //если в таблицы существует информация о количестве нажатий, то выводим ее
				return "<p><noindex> <a rel='nofollow' title='".$atts["title"]."' href='/download_count.php?url=".$atts["url"]."'>".$content."</a></noindex> <span style='font-style: italic; font-size:7pt;'>( Скачали: ".$result[0]['count']." чел. )</span>&nbsp;</p>";
		else{ // если ссылка только появилась, то заносим ее в базу, и присваиваем количество нажатий = 0
			$wpdb->query("INSERT INTO download_file VALUES ('".$atts["url"]."', '0')");
			return "<p><noindex> <a rel='nofollow' title='".$atts["title"]."' href='/download_count.php?url=".$atts["url"]."'>".$content."</a></noindex> <span style='font-style: italic; font-size:7pt;'>( Скачали: 0 чел. )</span>&nbsp;</p>";		
			} 
	}

add_shortcode('download_file', 'count_download_file');
?>