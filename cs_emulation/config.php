<?php

// Protect against hack attempts
if (!defined('NGCMS')) die('HAL');

switch ($_REQUEST['action'])
{
    // Добавление соревнования
    case 'tournament_add':
        if (tournament_add(trim($_REQUEST['name']), $_REQUEST['type'], $_REQUEST['team_count']))
        {
            tournament_list();
        }
    break;

    // Редактирование соревнования
    case 'tournament_edit':
        if (tournament_edit($_REQUEST['id'], trim($_REQUEST['name'])))
        {
            tournament_list();
        }
    break;

    // Удаление соревнования
    case 'tournament_delete':
        tournament_delete(array_keys($_REQUEST['delete']));
        tournament_list();
    break;

    // Список игр соревнования
    case 'game_list':
        tournament_menu($_REQUEST['tid']);
        game_list($_REQUEST['tid']);
    break;

    // Добавление игры
    case 'game_add':
        tournament_menu($_REQUEST['tid']);
        if (game_add($_REQUEST['tid'], trim($_REQUEST['map']), $_REQUEST['team'], $_REQUEST['score']))
        {
            game_list($_REQUEST['tid']);
        }
    break;

    // Редактирование игры
    case 'game_edit':
        tournament_menu($_REQUEST['tid']);
        if (game_edit($_REQUEST['tid'], $_REQUEST['id'], trim($_REQUEST['map']), $_REQUEST['team'], $_REQUEST['score']))
        {
            game_list($_REQUEST['tid']);
        }
    break;

    // Удаление игры
    case 'game_delete':
        tournament_menu($_REQUEST['tid']);
        game_delete($_REQUEST['tid'], $_REQUEST['id']);
        game_list($_REQUEST['tid']);
    break;

    // Список команд
    case 'team_list':
        tournament_menu($_REQUEST['tid']);
        team_list($_REQUEST['tid']);
    break;

    // Добавление команды
    case 'team_add':
        tournament_menu($_REQUEST['tid']);
        if (team_add($_REQUEST['tid'], trim($_REQUEST['name']), $_REQUEST['group'], $_REQUEST['flag']))
        {
            team_list($_REQUEST['tid']);
        }
    break;

    // Редактирование команды
    case 'team_edit':
        tournament_menu($_REQUEST['tid']);
        if (team_edit($_REQUEST['tid'], $_REQUEST['id'], trim($_REQUEST['name']), $_REQUEST['group'], $_REQUEST['flag']))
        {
            team_list($_REQUEST['tid']);
        }
    break;

    // Удаление команды
    case 'team_delete':
        tournament_menu($_REQUEST['tid']);
        team_delete($_REQUEST['tid'], array_keys($_REQUEST['delete']));
        team_list($_REQUEST['tid']);
    break;

    // Список соревнований
    default:
        tournament_list();
    break;
}

// ===================================================================
// BEGIN: ** TOURNAMENT FUNCTIONS **
// ===================================================================

function tournament_menu($id)
{
    global $mysql, $plugin, $tpl;

    $id = intval($id);
    list($row) = $mysql->select("SELECT * FROM ".prefix."_cs_tournament WHERE `id` = {$id}");

    $tpath = extras_dir . "/{$plugin}/tpl/admin/tournament/";
    $tpl->template('menu', $tpath);
    $tpl->vars('menu', array(
        'vars' => array(
            'plugin' => $plugin,
            'id' => $row['id'],
            'tournament' => $row['name']
        )
    ));
    echo $tpl->show('menu');
}

function tournament_list()
{
    global $mysql, $plugin, $tpl;

    $tpath = extras_dir . "/{$plugin}/tpl/admin/tournament/";

    $type = array('Групповая стадия', 'Single elimination', 'Double elimination');
    $team_count = array('--', 4, 8, 16, 32);

    $entries = '';
    foreach ($mysql->select("SELECT * FROM ".prefix."_cs_tournament ORDER BY `timestamp` DESC") as $row)
    {
        $tpl->template('table_entry', $tpath);
        $tpl->vars('table_entry', array(
            'vars' => array(
                'plugin' => $plugin,
                'id' => $row['id'],
                'date' => date('d.m.Y // H:i', $row['timestamp']),
                'name' => $row['name'],
                '[link]' => "<a href=\"?mod=extra-config&amp;plugin={$plugin}&amp;action=game_list&amp;tid={$row['id']}\">",
                '[/link]' => '</a>',
                'type' => $type[$row['type']],
                'team_count' => $team_count[$row['team_count']],
                'code' => "{l_cs_{$row['id']}}",
            )
        ));
        $entries .= $tpl->show('table_entry');
    }

    $tpl->template('table', $tpath);
    $tpl->vars('table', array(
        'vars' => array(
            'plugin' => $plugin,
            'entries' => $entries
        )
    ));
    echo $tpl->show('table');
}

function tournament_add($name, $type, $team_count)
{
    global $plugin, $mysql;

if (!empty($name) && isset($type))
    {
        $name = db_squote($name);
        $time = time();
        if ($type == 0)
        {
            $team_count = 0;
        }
        $mysql->query("INSERT INTO ".prefix."_cs_tournament (`name`, `type`, `team_count`, `timestamp`) values ({$name}, {$type}, {$team_count}, {$time})");

        if ($type != 0)
        {
            $id = mysql_insert_id($mysql->connect);

            $type_array = array('', 'single', 'double');
            $type = $type_array[$type];

            $team_count_array = array(0, 4, 8, 16, 32);
            $team_count = $team_count_array[$team_count];

            cacheStoreFile("tournament_{$id}.txt", file_get_contents(extras_dir . "/{$plugin}/tpl/admin/game/" . $type . "_" . $team_count . ".html"), $plugin);
        }

        @unlink(get_plugcache_dir($plugin) . "tournaments.txt");
        return true;
    }
    else
    {
        global $tpl;

        $type = '';
        $i = 0;
        foreach (array('Групповая стадия', 'Single elimination', 'Double elimination') as $value)
        {
            $type .= "<option value=\"{$i}\">{$value}</option>";
            $i++;
        }

        $team_count = '';
        $i = 1;
        foreach (array(4, 8, 16,32) as $value)
        {
            $team_count .= "<option value=\"{$i}\">{$value}</option>";
            $i++;
        }

        $tpath = extras_dir . "/{$plugin}/tpl/admin/tournament/";
        $tpl->template('form', $tpath);
        $tpl->vars('form', array(
            'vars' => array(
                'plugin' => $plugin,
                'action' => 'tournament_add',
                'id' => '',
                'name' => '',
                'type' => $type,
                'team_count' => $team_count,
                'disabled' => '',
                'style' => 'style="display: none;"'
            )
        ));
        echo $tpl->show('form');
        return false;
    }
}

function tournament_edit($id, $name)
{
    global $plugin, $mysql;

    $id = intval($id);
    if (!empty($name))
    {
        $name = db_squote($name);
        $time = time();
        $mysql->query("UPDATE ".prefix."_cs_tournament SET `name` = {$name}, `timestamp` = {$time} WHERE `id` = {$id}");
        @unlink(get_plugcache_dir($plugin) . "tournaments.txt");
        return true;
    }
    else
    {
        global $tpl;

	$row = array_shift($mysql->select("SELECT * FROM ".prefix."_cs_tournament WHERE `id` = {$id}"));

        $type = '';
        $i = 0;
        foreach (array('Групповая стадия', 'Single elimination', 'Double elimination') as $value)
        {
            if ($row['type'] == $i)
            {
                $type .= "<option value=\"{$i}\" selected>{$value}</option>";
            }
            else
            {
                $type .= "<option value=\"{$i}\">{$value}</option>";
            }
            $i++;
        }

        $team_count = '';
        $i = 1;
        foreach (array(4, 8, 16,32) as $value)
        {
            if ($row['team_count'] == $i)
            {
                $team_count .= "<option value=\"{$i}\" selected>{$value}</option>";
            }
            else
            {
                $team_count .= "<option value=\"{$i}\">{$value}</option>";
            }
            $i++;
        }

        $tpath = extras_dir . "/{$plugin}/tpl/admin/tournament/";
        $tpl->template('form', $tpath);
        $tpl->vars('form', array(
            'vars' => array(
                'plugin' => $plugin,
                'action' => 'tournament_edit',
                'id' => $row['id'],
                'name' => $row['name'],
                'type' => $type,
                'team_count' => $team_count,
                'disabled' => 'disabled',
                'style' => ($row['type'] == 0) ? 'style="display: none;"' : ''
            )
        ));
        echo $tpl->show('form');
        return false;
    }
}

function tournament_delete($id)
{
    global $plugin, $mysql;

    if (is_array($id))
    {
        foreach ($id as $v)
        {
            $v = intval($v);
            $mysql->query("DELETE FROM ".prefix."_cs_tournament WHERE `id` = {$v}");
        }
    }
    else
    {
        $id = intval($id);
        $mysql->query("DELETE FROM ".prefix."_cs_tournament WHERE `id` = {$id}");
    }
    @unlink(get_plugcache_dir($plugin) . "tournaments.txt");
}

// ===================================================================
// END: ** TOURNAMENT FUNCTIONS **
// ===================================================================

// ===================================================================
// BEGIN: ** GAME FUNCTIONS **
// ===================================================================

function game_list($tid)
{
    global $mysql, $plugin, $tpl;

    $row = array_shift($mysql->select("SELECT * FROM ".prefix."_cs_tournament WHERE `id` = {$tid}"));

    $tpath = extras_dir . "/{$plugin}/tpl/admin/game/";

    if ($row['type'] == 0)
    {
        $games = array();
        $score_length = array(0);
        $tid = intval($tid);
        foreach ($mysql->select("SELECT *, g.id AS gid, t1.name AS team1_name, t2.name AS team2_name, t1.flag AS team1_flag, t2.flag AS team2_flag FROM ".prefix."_cs_game g LEFT JOIN ".prefix."_cs_team t1 ON g.team1_id = t1.id LEFT JOIN ".prefix."_cs_team t2 ON g.team2_id = t2.id WHERE g.tid = {$tid} AND t1.group = t2.group") as $row)
        {
            if (empty($games[$row['group']]))
            {
                $games[$row['group']] = array();
            }
            $score_length[] = max(strlen($row['team1_score']), strlen($row['team2_score']));
            $games[$row['group']][] = array(
                'id' => $row['gid'],
                'map' => $row['map'],
                'team1_name' => $row['team1_name'],
                'team2_name' => $row['team2_name'],
                'team1_flag' => $row['team1_flag'],
                'team2_flag' => $row['team2_flag'],
                'team1_score' => $row['team1_score'],
                'team2_score' => $row['team2_score']
            );
        }
        $score_length = max($score_length);
        $groups = array_unique(array_keys($games));
        sort($groups);

        $entries = '';
        foreach ($groups as $group)
        {
            $group_entries = '';
            foreach ($games[$group] as $game)
            {
                $tpl->template('table_group_entry', $tpath);
                $tpl->vars('table_group_entry', array(
                    'vars' => array(
                        'plugin' => $plugin,
                        'tid' => $tid,
                        'id' => $game['id'],
                        'map' => $game['map'],
                        'team1_name' => $game['team1_name'],
                        'team2_name' => $game['team2_name'],
                        'team1_flag' => $game['team1_flag'],
                        'team2_flag' => $game['team2_flag'],
                        'team1_score' => sprintf("%0{$score_length}d", $game['team1_score']),
                        'team2_score' => sprintf("%0{$score_length}d", $game['team2_score'])
                    )
                ));
                $group_entries .= $tpl->show('table_group_entry');
            }
            $tpl->template('table_group', $tpath);
            $tpl->vars('table_group', array(
                'vars' => array(
                    'plugin' => $plugin,
                    'group' => $group,
                    'entries' => $group_entries
                )
            ));
            $entries .= $tpl->show('table_group');
        }
        $submit = 'Добавить';
    }
    else
    {
        $tpl->template('wysiwyg', $tpath);
        $tpl->vars('wysiwyg', array(
            'vars' => array(
                'plugin' => $plugin,
                'text' => cacheRetrieveFile("tournament_{$tid}.txt", time(), $plugin)
            )
        ));
        $entries .= $tpl->show('wysiwyg');
        $submit = 'Отправить';
    }

    $tpl->template('table', $tpath);
    $tpl->vars('table', array(
        'vars' => array(
            'plugin' => $plugin,
            'tid' => $tid,
            'entries' => $entries,
            'submit' => $submit
        )
    ));
    echo $tpl->show('table');
}

function game_add($tid, $map, $team, $score)
{
    global $plugin, $mysql, $tpl;

    $tid = intval($tid);
    $row = array_shift($mysql->select("SELECT * FROM ".prefix."_cs_tournament WHERE `id` = {$tid}"));

    $tpath = extras_dir . "/{$plugin}/tpl/admin/game/";

    if ($row['type'] == 0)
    {
        if (!empty($tid) && !empty($map) && is_array($team) && !empty($team[0]) && !empty($team[1]) && is_array($score))
        {
            $map = db_squote($map);
            $team1 = intval($team[0]);
            $team2 = intval($team[1]);
            if ($team1 == $team2)
            {
    	        echo "<div class=\"msgi\"><img src=\"" . skins_url . "/images/info.gif\" hspace=\"10\" />Информация: Команда не может играть против самой себя</div>";
    	        return false;
            }
            $score1 = intval($score[0]);
            $score2 = intval($score[1]);
            $row = $mysql->select("SELECT * FROM ".prefix."_cs_team t1 LEFT JOIN ".prefix."_cs_team t2 ON t1.group = t2.group WHERE t1.id = {$team1} AND t2.id = {$team2}");
            if (empty($row))
            {
    	        echo "<div class=\"msgi\"><img src=\"" . skins_url . "/images/info.gif\" hspace=\"10\" />Информация: Команды не принадлежат одной группе</div>";
    	        return false;
            }
            $mysql->query("INSERT INTO ".prefix."_cs_game (`tid`, `map`, `team1_id`, `team2_id`, `team1_score`, `team2_score`) values ({$tid}, {$map}, {$team1}, {$team2}, {$score1}, {$score2})");
            @unlink(get_plugcache_dir($plugin) . "tournament_{$tid}.txt");
            return true;
        
        }
        else
        {
            $tpl->template('form', $tpath);

            $teams = team_get($tid);
            $groups = array_unique(array_keys($teams));
            sort($groups);

            $team1 = '';
            foreach ($groups as $group)
            {
                $team1 .= "<option value=\"\" disabled>Группа {$group}</option>";
                foreach ($teams[$group] as $team)
                {
                    $team1 .= "<option value=\"{$team['id']}\">&nbsp;&nbsp;{$team['name']}</option>";
                }
            }

            $tpl->vars('form', array(
                'vars' => array(
                    'plugin' => $plugin,
                    'action' => 'game_add',
                    'tid' => $tid,
                    'id' => '',
                    'map' => '',
                    'team1' => $team1,
                    'team2' => $team1,
                    'score1' => '',
                    'score2' => ''
                )
            ));
            echo $tpl->show('form');
            return false;
        }
    }
    else
    {
        if (!empty($team))
        {
            cacheStoreFile("tournament_{$tid}.txt", $team, $plugin);
            return true;
        }
    }
}

function game_edit($tid, $id, $map, $team, $score)
{
    global $plugin, $mysql;

    $tid = intval($tid);
    $id = intval($id);
    if (!empty($tid) && !empty($id) && !empty($map) && is_array($team) && !empty($team[0]) && !empty($team[1]) && is_array($score))
    {
        $map = db_squote($map);
        $team1 = intval($team[0]);
        $team2 = intval($team[1]);
        if ($team1 == $team2)
        {
    	    echo "<div class=\"msgi\"><img src=\"" . skins_url . "/images/info.gif\" hspace=\"10\" />Информация: Команда не может играть против самой себя</div>";
    	    return false;
        }
        $score1 = intval($score[0]);
        $score2 = intval($score[1]);
        $row = $mysql->select("SELECT * FROM ".prefix."_cs_team t1 LEFT JOIN ".prefix."_cs_team t2 ON t1.group = t2.group WHERE t1.id = {$team1} AND t2.id = {$team2}");
        if (empty($row))
        {
    	    echo "<div class=\"msgi\"><img src=\"" . skins_url . "/images/info.gif\" hspace=\"10\" />Информация: Команды не принадлежат одной группе</div>";
    	    return false;
        }
        $mysql->query("UPDATE ".prefix."_cs_game SET `tid` = {$tid}, `map` = {$map}, `team1_id` = {$team1}, `team2_id` = {$team2}, `team1_score` = {$score1}, `team2_score` = {$score2} WHERE `id` = {$id}");
        @unlink(get_plugcache_dir($plugin) . "tournament_{$tid}.txt");
        return true;
        
    }
    else
    {
        global $tpl;

        $tpath = extras_dir . "/{$plugin}/tpl/admin/game/";
        $tpl->template('form', $tpath);

        $row = array_shift($mysql->select("SELECT * FROM ".prefix."_cs_game WHERE `id` = {$id}"));

        $teams = team_get($tid);
        $groups = array_unique(array_keys($teams));
        sort($groups);

        $team1 = '';
        $team2 = '';
        foreach ($groups as $group)
        {
            $team1 .= "<option value=\"\" disabled>Группа {$group}</option>";
            $team2 .= "<option value=\"\" disabled>Группа {$group}</option>";
            foreach ($teams[$group] as $team)
            {
                $selected = '';
                if ($team['id'] == $row['team1_id'])
                {
                    $selected = 'selected';
                }
                $team1 .= "<option {$selected} value=\"{$team['id']}\">&nbsp;&nbsp;{$team['name']}</option>";

                $selected = '';
                if ($team['id'] == $row['team2_id'])
                {
                    $selected = 'selected';
                }
                $team2 .= "<option {$selected} value=\"{$team['id']}\">&nbsp;&nbsp;{$team['name']}</option>";
            }
        }

        $tpl->vars('form', array(
            'vars' => array(
                'plugin' => $plugin,
                'action' => 'game_edit',
                'tid' => $tid,
                'id' => $id,
                'map' => $row['map'],
                'team1' => $team1,
                'team2' => $team2,
                'score1' => $row['team1_score'],
                'score2' => $row['team2_score']
            )
        ));
        echo $tpl->show('form');
        return false;
    }
}

function game_delete($tid, $id)
{
    global $plugin, $mysql;

    $tid = intval($tid);
    $id = intval($id);
    $mysql->query("DELETE FROM ".prefix."_cs_game WHERE `id` = {$id}");
    @unlink(get_plugcache_dir($plugin) . "tournament_{$tid}.txt");
}

// ===================================================================
// END: ** GAME FUNCTIONS **
// ===================================================================

// ===================================================================
// BEGIN: ** TEAM FUNCTIONS **
// ===================================================================

function team_get($tid)
{
    global $mysql;

    $teams = array();
    foreach ($mysql->select("SELECT * FROM ".prefix."_cs_team WHERE `tid` = {$tid} ORDER by `name` ASC") as $row)
    {
        if (empty($teams[$row['group']]))
        {
            $teams[$row['group']] = array();
        }
        $teams[$row['group']][] = array(
            'id' => $row['id'],
            'date' => $row['timestamp'],
            'name' => $row['name'],
            'flag' => $row['flag']
        );
    }
    return $teams;
}

function team_list($tid)
{
    global $mysql, $plugin, $tpl;

    $tid = intval($tid);

    $tpath = extras_dir . "/{$plugin}/tpl/admin/team/";

    $row = array_shift($mysql->select("SELECT * FROM ".prefix."_cs_tournament WHERE `id` = {$tid}"));
    if ($row['type'] == 1 || $row['type'] == 2)
    {
        $type = array('Групповая стадия', 'Single elimination', 'Double elimination');
        $tpl->template('disabled', $tpath);
        $tpl->vars('disabled', array(
            'vars' => array(
                'type' => $type[$row['type']]
            )
        ));
        echo $tpl->show('disabled');
        return;
    }

    $teams = team_get($tid);
    $groups = array_unique(array_keys($teams));
    sort($groups);

    $entries = '';
    foreach ($groups as $group)
    {
        $group_entries = '';
        foreach ($teams[$group] as $team)
        {
            $tpl->template('table_group_entry', $tpath);
            $tpl->vars('table_group_entry', array(
                'vars' => array(
                    'plugin' => $plugin,
                    'tid' => $tid,
                    'id' => $team['id'],
                    'date' => date('d.m.Y // H:i', $team['date']),
                    'name' => $team['name'],
                    'group' => $group,
                    'flag' => $team['flag']
                )
            ));
            $group_entries .= $tpl->show('table_group_entry');
        }
        $tpl->template('table_group', $tpath);
        $tpl->vars('table_group', array(
            'vars' => array(
                'plugin' => $plugin,
                'group' => $group,
                'entries' => $group_entries
            )
        ));
        $entries .= $tpl->show('table_group');
    }

    $tpl->template('table', $tpath);
    $tpl->vars('table', array(
        'vars' => array(
            'plugin' => $plugin,
            'tid' => $tid,
            'entries' => $entries
        )
    ));
    echo $tpl->show('table');
}

function team_add($tid, $name, $group, $flag)
{
    global $plugin, $mysql;

    $tid = intval($tid);
    if (!empty($tid) && !empty($name) && !empty($group) && strlen($group) == 1 && ord($group) >= 65 && ord($group) <= 90 && !empty($flag))
    {
        $name = db_squote($name);
        $group = db_squote($group);
        $flag = db_squote($flag);
        $row = $mysql->select("SELECT * FROM ".prefix."_cs_team WHERE `tid` = {$tid} AND `name` = {$name}");
        if (!empty($row))
        {
    	    echo "<div class=\"msgi\"><img src=\"" . skins_url . "/images/info.gif\" hspace=\"10\" />Информация: Команда с названием {$name} в этом соревновании уже существует</div>";
    	    return false;
        }
        $time = time();
        $mysql->query("INSERT INTO ".prefix."_cs_team (`tid`, `name`, `group`, `timestamp`, `flag`) values ({$tid}, {$name}, {$group}, {$time}, {$flag})");
        @unlink(get_plugcache_dir($plugin) . "tournament_{$tid}.txt");
        return true;
        
    }
    else
    {
        global $tpl;

        $tpath = extras_dir . "/{$plugin}/tpl/admin/team/";
        $tpl->template('form', $tpath);

        $groups = '';
        for ($i = 0; $i < 26; $i++)
        {
            $group = chr(65 + $i);
            $groups .= "<option value=\"{$group}\">{$group}</option>";
        }

        $flags = '';
        $dh = opendir(extras_dir . "/{$plugin}/flag/");
        while (($file = readdir($dh)) !== false)
        {
            if ($file == '.' || $file == '..' || $file == 'Thumbs.db')
            {
                continue;
            }
            $file = explode('.', $file);
            array_pop($file);
            $file = implode('.', $file);
            $selected = '';
            if ($file == 'ru')
            {
                $selected = 'selected';
            }
            $flags .= "<option {$selected} value=\"{$file}\">{$file}</option>";
        }
        closedir($dh);

        $tpl->vars('form', array(
            'vars' => array(
                'plugin' => $plugin,
                'action' => 'team_add',
                'tid' => $tid,
                'id' => '',
                'name' => '',
                'groups' => $groups,
                'flags' => $flags
            )
        ));
        echo $tpl->show('form');
        return false;
    }
}

function team_edit($tid, $id, $name, $group, $flag)
{
    global $plugin, $mysql;

    $tid = intval($tid);
    $id = intval($id);
    if (!empty($tid) && !empty($name) && !empty($group) && !empty($flag))
    {
        $name = db_squote($name);
        $group = db_squote($group);
        $flag = db_squote($flag);
        $row = $mysql->select("SELECT * FROM ".prefix."_cs_team WHERE `tid` = {$tid} AND `name` = {$name}");
        if (!empty($row))
        {
    	    echo "<div class=\"msgi\"><img src=\"" . skins_url . "/images/info.gif\" hspace=\"10\" />Информация: Команда с названием {$name} в этом соревновании уже существует</div>";
    	    return false;
        }
        $time = time();
        $mysql->query("UPDATE ".prefix."_cs_team SET `tid` = {$tid}, `name` = {$name}, `group` = {$group}, `timestamp` = {$time}, `flag` = {$flag} WHERE `id` = {$id}");
        @unlink(get_plugcache_dir($plugin) . "tournament_{$tid}.txt");
        return true;
        
    }
    else
    {
        global $tpl;

        $tpath = extras_dir . "/{$plugin}/tpl/admin/team/";
        $tpl->template('form', $tpath);

        $row = array_shift($mysql->select("SELECT * FROM ".prefix."_cs_team WHERE `id` = {$id}"));

        $groups = '';
        for ($i = 0; $i < 26; $i++)
        {
            $group = chr(65 + $i);
            $selected = '';
            if ($row['group'] == $group)
            {
                $selected = 'selected';
            }
            $groups .= "<option {$selected} value=\"{$group}\">{$group}</option>";
        }

        $flags = '';
        $dh = opendir(extras_dir . "/{$plugin}/flag/");
        while (($file = readdir($dh)) !== false)
        {
            if ($file == '.' || $file == '..' || $file == 'Thumbs.db')
            {
                continue;
            }
            $file = explode('.', $file);
            array_pop($file);
            $file = implode('.', $file);
            $selected = '';
            if ($row['flag'] == $file)
            {
                $selected = 'selected';
            }
            $flags .= "<option {$selected} value=\"{$file}\">{$file}</option>";
        }
        closedir($dh);

        $tpl->vars('form', array(
            'vars' => array(
                'plugin' => $plugin,
                'action' => 'team_edit',
                'tid' => $tid,
                'id' => $row['id'],
                'name' => $row['name'],
                'groups' => $groups,
                'flags' => $flags
            )
        ));
        echo $tpl->show('form');
        return false;
    }
}

function team_delete($tid, $id)
{
    global $plugin, $mysql;

    $tid = intval($tid);
    if (is_array($id))
    {
        foreach ($id as $v)
        {
            $v = intval($v);
            $mysql->query("DELETE FROM ".prefix."_cs_team WHERE `id` = {$v}");
        }
    }
    else
    {
        $id = intval($id);
        $mysql->query("DELETE FROM ".prefix."_cs_team WHERE `id` = {$id}");
    }
    @unlink(get_plugcache_dir($plugin) . "tournament_{$tid}.txt");
}

// ===================================================================
// END: ** TEAM FUNCTIONS **
// ===================================================================

?>