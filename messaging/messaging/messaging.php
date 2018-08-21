<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

function messaging($method, $group, $subject, $content) {
	global $lang, $mysql;

	LoadPluginLang('messaging', 'messaging', '', 'mes');

	if (!$subject || trim($subject) == "") {
		msg(array("type" => "error", "text" => $lang['mes_msge_subject']));
	}
	elseif (!$content || trim($content) == "") {
		msg(array("type" => "error", "text" => $lang['mes_msge_content']));
	}
	else {
		if ($method == "0") {
			$mails = array();

			if ($group == 0) {
				foreach ($mysql->select("SELECT mail FROM `".uprefix."_users`") as $row) {
					$mails[] = $row['mail'];
				}
			}
			else {
				foreach ($mysql->select("SELECT mail FROM `".uprefix."_users` WHERE status='".$group."'") as $row) {
					$mails[] = $row['mail'];
				}
			}
			if (empty($mails)) {
				msg(array("type" => "error", "text" => $lang['mes_msge_status'], "info" => $lang['mes_msgi_status']));
			}
			else {
				$mails		=	join(', ', $mails);
				$content	=	nl2br($content);
				zzMail($mails, $subject, $content);
				msg(array("text" => $lang['mes_msgo_sent']));
			}
		}
		else {
			if ($group == 0) {
				foreach ($mysql->select("SELECT id FROM `".uprefix."_users`") as $row) {
					$ids[] = $row['id'];
				}
			}
			else {
				foreach ($mysql->select("SELECT id FROM `".uprefix."_users` WHERE status = '".$group."'") as $row) {
					$ids[] = $row['id'];
				}
			}
			if (empty($ids)) {
				msg(array("type" => "error", "text" => $lang['mes_msge_status'], "info" => $lang['mes_msgi_status']));
			}
			else {
				foreach ($ids as $to_id) {
					$sql = $mysql->query("INSERT INTO ".uprefix."_users_pm (from_id, to_id, pmdate, title, content) values ('0', '$to_id', '".time()."', '$subject', '$content')");
				}
				msg(array("text" => $lang['mes_msgo_sent']));
			}
		}
	}
}
?>