<?php

if (!defined('NGCMS')) die ('HAL');

add_act('comments', 'user_ranks', 2);

function user_ranks($row, &$tvars){

	$ranks_arr = array(extra_get_param('user_ranks','rank_val1') => extra_get_param('user_ranks','rank_name1'), extra_get_param('user_ranks','rank_val2') => extra_get_param('user_ranks','rank_name2'), extra_get_param('user_ranks','rank_val3') => extra_get_param('user_ranks','rank_name3'), extra_get_param('user_ranks','rank_val4') => extra_get_param('user_ranks','rank_name4'), extra_get_param('user_ranks','rank_val5') => extra_get_param('user_ranks','rank_name5'), extra_get_param('user_ranks','rank_val6') => extra_get_param('user_ranks','rank_name6'), extra_get_param('user_ranks','rank_val7') => extra_get_param('user_ranks','rank_name7'));

	foreach($ranks_arr as $k => $v) {
		if ($row['reg'] == "1") {
			if (extra_get_param('user_ranks','rank_type') == "com") {
				if ($row['com'] >= $k && $v) { $tvars['vars']['plugins_user_ranks'] = $v; }
				if ($row['com'] < $k) { break; }
			} else {
				if ($row['news'] >= $k && $v) { $tvars['vars']['plugins_user_ranks'] = $v; }
				if ($row['news'] < $k) { break; }
			}
		}
		else {
			$tvars['vars']['plugins_user_ranks'] = extra_get_param('user_ranks','rank_guest');
		}
	}
}
?>