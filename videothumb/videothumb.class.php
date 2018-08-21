<?php

class videoThumb {

	var $config;

	function __construct($config = array()) {
		$this->config = array_merge(array(
			'imagesPath' => $_SERVER['DOCUMENT_ROOT'] . '/uploads/videothumb/'
			,'imagesUrl' => '/uploads/videothumb/'
			,'emptyImage' => '/uploads/videothumb/_empty.png'
		),$config);

		if (!is_dir($this->config['imagesPath'])) {
			mkdir($this->config['imagesPath']);
		}
	}

	/*
	 * Return error message from lexicon array
	 * @param string $msg Array key
	 * @return string Message
	 * */
	function lexicon($msg = '') {
		$array = array(
			'video_err_ns' => 'Вы забыли указать ссылку на видео.'
			,'video_err_nf' => 'Не могу найти видео, может - неверная ссылка?'
			,'video_err_nt' => 'Вы не ввели заголовок новости.'
		);

		return @$array[$msg];
	}


	/*
	 * Check and format video link, then fire download of preview image
	 * @param string $video Remote url on video hosting
	 * @return array $array Array with formatted video link and preview url
	 * */
	function process($video = '', $title = '', $vt_img = '') {
		if (empty($video)) {return array(error => $this->lexicon('video_err_ns'));}
		if (!preg_match('/^(http|https)\:\/\//i', $video)) {
			$video = 'http://' . $video;
		}
		
		if($title == '') {return array(error => $this->lexicon('video_err_nt'));}
		
		if(is_file($_SERVER['DOCUMENT_ROOT'] . $vt_img)) { @unlink($_SERVER['DOCUMENT_ROOT'] . $vt_img);		}
		
		$translated_title = $this->translit(trim(iconv("utf-8", "windows-1251", $title)));

		// YouTube
		if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $video, $matches) || preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $video, $matches) || preg_match('/[http|https]+:\/\/(?:www\.|)youtu\.be\/([a-zA-Z0-9_\-]+)/i', $video, $matches)) {
			$video = 'http://www.youtube.com/embed/'.$matches[1];
			$image = 'http://img.youtube.com/vi/'.$matches[1].'/0.jpg';

			$array = array(
				'video' => $video
				,'image' => $this->getRemoteImage($image, $translated_title)
				,'ftitle' => $this->getYtitle($matches[1])
			);
		}
		// Vimeo
		else if (preg_match('/[http|https]+:\/\/(?:www\.|)vimeo\.com\/([a-zA-Z0-9_\-]+)(&.+)?/i', $video, $matches) || preg_match('/[http|https]+:\/\/player\.vimeo\.com\/video\/([a-zA-Z0-9_\-]+)(&.+)?/i', $video, $matches)) {
			$video = 'http://player.vimeo.com/video/'.$matches[1];
			$image = '';
			if ($xml = simplexml_load_file('http://vimeo.com/api/v2/video/'.$matches[1].'.xml')) {
				$image = $xml->video->thumbnail_large ? (string) $xml->video->thumbnail_large: (string) $xml->video->thumbnail_medium;
				$image = $this->getRemoteImage($image);
			}
			$array = array(
				'video' => $video
				,'image' => $image
			);
		}
		// ruTube
		else if (preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/video\/embed\/([a-zA-Z0-9_\-]+)/i', $video, $matches) || preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/tracks\/([a-zA-Z0-9_\-]+)(&.+)?/i', $video, $matches)) {
			$video = 'http://rutube.ru/video/embed/'.$matches[1];
			$image = '';
			if ($xml = simplexml_load_file("http://rutube.ru/cgi-bin/xmlapi.cgi?rt_mode=movie&rt_movie_id=".$matches[1]."&utf=1")) {
				$image = (string) $xml->movie->thumbnailLink;
				$image = $this->getRemoteImage($image);
			}
			$array = array(
				'video' => $video
				,'image' => $image
			);
		}
		else if (preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/video\/([a-zA-Z0-9_\-]+)\//i', $video, $matches)) {
			$html = $this->Curl($matches[0]);
			return $this->process($html);
		}
		// No matches
		else {
			$array = array(error => $this->lexicon('video_err_nf'));
		}

		return $array;
	}

	/*
	 * Download ans save image from remote service
	 * @param string $url Remote url
	 * @return string $image Url to image or false
	 * */
	function getRemoteImage($url = '', $translated_title = '') {
		if (empty($url)) {return false;}

		$image = '';
		$response = $this->Curl($url);
		if (!empty($response)) {
			$tmp = explode('.', $url);
			$ext = '.' . end($tmp);

			$filename = $translated_title . '_' . substr(md5($url), 0, 5) . $ext;
			if (file_put_contents($this->config['imagesPath'] . $filename, $response)) {
				//$this->img_resize($this->config['imagesPath'] . $filename, $this->config['imagesPath'] . $filename, 100, 60);
				$image = $this->config['imagesUrl'] . $filename;
			}

		}
		if (empty($image)) {$image = $this->config['emptyImage'];}

		return $image;
	}

	/*
	 * Method for loading remote url
	 * @param string $url Remote url
	 * @return mixed $data Results of an request
	 * */
	function Curl($url = '') {
		if (empty($url)) {return false;}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);

		$data = curl_exec($ch);
		return $data;
	}
	
	function getYtitle($video_id = '') {
		if (empty($video_id)) {return false;}
		
		$xmlData = simplexml_load_string(file_get_contents("http://gdata.youtube.com/feeds/api/videos/{$video_id}?fields=title"));

		$ftitle = (string)$xmlData->title;

		return $ftitle;
	}
	
		function translit($content, $allowDash = 0, $allowSlash = 0) {
		// $allowDash is not used any more

		$utf2enS = array('А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Ґ' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'jo', 'Є' => 'e', 'Ж' => 'zh', 'З' => 'z', 'И' => 'i', 'І' => 'i', 'Й' => 'i', 'Ї' => 'i', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u', 'Ў' => 'u', 'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sz', 'Ъ' => '', 'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya');
		$utf2enB = array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'є' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'і' => 'i', 'й' => 'i', 'ї' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sz', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '&quot;' => '', '&amp;' => '', 'µ' => 'u', '№' => 'num');

		$content = trim(strip_tags($content));
		$content = strtr($content, $utf2enS);
		$content = strtr($content, $utf2enB);

		$content = str_replace(array(' - '), array('-'), $content);
		$content = preg_replace("/\s+/ms", "-", $content);
		$content = preg_replace("/[ ]+/", "-", $content);

		$content = preg_replace("/[^a-z0-9_\-\.".($allowSlash?'\/':'')."]+/mi", "", $content);
		$content = preg_replace("#-(-)+#", "-", $content);

		return $content;
	}
	
	function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
	{
  if (!file_exists($src)) return false;

  $size = getimagesize($src);

  if ($size === false) return false;

  // Определяем исходный формат по MIME-информации, предоставленной
  // функцией getimagesize, и выбираем соответствующую формату
  // imagecreatefrom-функцию.
  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
  $icfunc = "imagecreatefrom" . $format;
  if (!function_exists($icfunc)) return false;

  $x_ratio = $width / $size[0];
  $y_ratio = $height / $size[1];

  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);

  $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
  $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
  $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
  $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

  $isrc = $icfunc($src);
  $idest = imagecreatetruecolor($width, $height);

  imagefill($idest, 0, 0, $rgb);
  imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, 
    $new_width, $new_height, $size[0], $size[1]);

  imagejpeg($idest, $dest, $quality);

  imagedestroy($isrc);
  imagedestroy($idest);

  return true;

}
	
}