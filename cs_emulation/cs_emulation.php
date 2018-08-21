<?php

$plugin = $key;

function cs_result_sort($team1, $team2)
{
    if ($team1['o'] > $team2['o'])
    {
        return -1;
    }
    if ($team1['o'] < $team2['o'])
    {
        return 1;
    }

    if ($team1['r'] > $team2['r'])
    {
        return -1;
    }
    if ($team1['r'] < $team2['r'])
    {
        return 1;
    }

    if ($team1['name'] > $team2['name'])
    {
        return 1;
    }
    if ($team1['name'] < $team2['name'])
    {
        return -1;
    }

    return 0;
}

function cs_result()
{
    global $mysql, $tpl;

    $plugin = 'cs_emulation';

    $tournaments = cacheRetrieveFile("tournaments.txt", time(), $plugin);
    if ($tournaments == false || ($tournaments = unserialize($tournaments)) == false)
    {
        $tournaments = array();
        foreach ($mysql->select("SELECT id, type FROM ".prefix."_cs_tournament") as $row)
        {
            $tournaments[$row['id']] = $row['type'];
        }
        cacheStoreFile("tournaments.txt", serialize($tournaments), $plugin);
    }

    $tpath = extras_dir . "/{$plugin}/tpl/site/";

    $result = array();
    foreach ($tournaments as $id => $type)
    {
        if ($type == 0)
        {
            $result[$id] = cacheRetrieveFile("tournament_{$id}.txt", time(), $plugin);
            if ($result[$id] != false && ($result[$id] = unserialize($result[$id])) != false)
            {
                continue;
            }

            $result[$id] = '';

            $groups = array();
            $games = array();
            $teams = array();

            $row = $mysql->select("SELECT * FROM ".prefix."_cs_team WHERE tid = {$id}");
            foreach ($row as $team)
            {
                if (!in_array($team['group'], $groups))
                {
                    $groups[] = $team['group'];
                    $games[$team['group']] = array();
                    $teams[$team['group']] = array();
                }
                $teams[$team['group']][$team['id']] = array(
                    'name' => $team['name'],
                    'flag' => $team['flag'],
                    'v' => 0,
                    'p' => 0,
                    'n' => 0,
                    'r' => 0,
                    'o' => 0
                );
            }

            $score_length = array(0);
            foreach ($mysql->select("SELECT * FROM ".prefix."_cs_game g LEFT JOIN ".prefix."_cs_team t1 ON g.team1_id = t1.id LEFT JOIN ".prefix."_cs_team t2 ON g.team2_id = t2.id WHERE g.tid = {$id} AND t1.group = t2.group") as $row)
            {
                if ($row['team1_score'] > $row['team2_score'])
                {
                    $teams[$row['group']][$row['team1_id']]['v']++;
                    $teams[$row['group']][$row['team2_id']]['p']++;

                    $teams[$row['group']][$row['team1_id']]['r'] += $row['team1_score'] - $row['team2_score'];
                    $teams[$row['group']][$row['team2_id']]['r'] += $row['team2_score'] - $row['team1_score'];

                    $teams[$row['group']][$row['team1_id']]['o'] += 3;
                }
                else if ($row['team1_score'] < $row['team2_score'])
                {
                    $teams[$row['group']][$row['team1_id']]['p']++;
                    $teams[$row['group']][$row['team2_id']]['v']++;

                    $teams[$row['group']][$row['team1_id']]['r'] += $row['team1_score'] - $row['team2_score'];
                    $teams[$row['group']][$row['team2_id']]['r'] += $row['team2_score'] - $row['team1_score'];

                    $teams[$row['group']][$row['team2_id']]['o'] += 3;
                }
                else if ($row['team1_score'] != 0 && $row['team2_score'] != 0)
                {
                    $teams[$row['group']][$row['team1_id']]['n']++;
                    $teams[$row['group']][$row['team2_id']]['n']++;

                    $teams[$row['group']][$row['team1_id']]['o']++;
                    $teams[$row['group']][$row['team2_id']]['o']++;
                }
                $score_length[] = max(strlen($row['team1_score']), strlen($row['team2_score']));
                $games[$row['group']][] = array(
                    'name1' => $teams[$row['group']][$row['team1_id']]['name'],
                    'name2' => $teams[$row['group']][$row['team2_id']]['name'],
                    'flag1' => $teams[$row['group']][$row['team1_id']]['flag'],
                    'flag2' => $teams[$row['group']][$row['team2_id']]['flag'],
                    'score1' => $row['team1_score'],
                    'score2' => $row['team2_score'],
                    'map' => $row['map']
                );
            }
            $score_length = max($score_length);
            $groups = array_unique(array_keys($games));
            sort($groups);

            foreach ($groups as $group)
            {
                $log = '';
                foreach ($games[$group] as $game)
                {
                    $tpl->template('log_entry', $tpath);
                    $tpl->vars('log_entry', array(
                        'vars' => array(
                            'map' => $game['map'],
                            'name1' => $game['name1'],
                            'name2' => $game['name2'],
                            'flag1' => $game['flag1'],
                            'flag2' => $game['flag2'],
                            'score1' => ($game['score1'] == 0 && $game['score2'] == 0) ? "x" : sprintf("%0{$score_length}d", $game['score1']),
                            'score2' => ($game['score1'] == 0 && $game['score2'] == 0) ? "x" : sprintf("%0{$score_length}d", $game['score2']),
                            'color1' => ($game['score1'] > $game['score2']) ? 'green' : (($game['score1'] < $game['score2']) ? 'red' : 'black'),
                            'color2' => ($game['score1'] > $game['score2']) ? 'red' : (($game['score1'] < $game['score2']) ? 'green' : 'black')
                        )
                    ));
                    $log .= $tpl->show('log_entry');
                }

                $entries = '';
                usort($teams[$group], "cs_result_sort");
                foreach ($teams[$group] as $team)
                {
                    $tpl->template('table_entry', $tpath);
                    $tpl->vars('table_entry', array(
                        'vars' => array(
                            'name' => $team['name'],
                            'flag' => $team['flag'],
                            'v' => $team['v'],
                            'p' => $team['p'],
                            'n' => $team['n'],
                            'r' => ($team['r'] > 0) ? '+' . $team['r'] : $team['r'],
                            'o' => $team['o']
                        )
                    ));
                    $entries .= $tpl->show('table_entry');
                }

                $tpl->template('table', $tpath);
                $tpl->vars('table', array(
                    'vars' => array(
                        'group' => $group,
                        'entries' => $entries,
                        'log' => $log
                    )
                ));
                $result[$id] .= $tpl->show('table');
            }
            cacheStoreFile("tournament_{$id}.txt", serialize($result[$id]), $plugin);
        }
        else
        {
            $result[$id] = cacheRetrieveFile("tournament_{$id}.txt", time(), $plugin);
        }
    }
    return $result;
}

class CSNewsFilter extends NewsFilter
{
    function showNewsPre($newsID, &$SQLnews, $mode = array())
    {
        $result = cs_result();
        foreach ($result as $id => $table)
        {
            $SQLnews['content'] = str_replace("{l_cs_{$id}}", $table, $SQLnews['content']);
        }
    }
}

register_filter('news', $plugin, new CSNewsFilter);

class CSStaticFilter extends StaticFilter
{
    function showStatic($staticID, $SQLstatic, &$tvars, $mode)
    {
        $result = cs_result();
        foreach ($result as $id => $table)
        {
            $tvars['vars']['content'] = str_replace("{l_cs_{$id}}", $table, $tvars['vars']['content']);
        }
    }
}

register_filter('static', $plugin, new CSStaticFilter);

function cs_table()
{
    global $lang;

    $result = cs_result();
    foreach ($result as $id => $table)
    {
        $lang["cs_{$id}"] = $table;
    }
}

add_act('index', 'cs_table');

?>