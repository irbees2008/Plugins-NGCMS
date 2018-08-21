<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

class NewsvoteNewsfilter extends NewsFilter {
	function addNewsForm(&$tvars) {
	        global $tpl;
		$tpath = locatePluginTemplates(array('nv_addnews'), 'newsvotes', 0, 'default');

		$tpl -> template('nv_addnews', $tpath['nv_addnews']);
		$tpl -> vars('nv_addnews', array ( 'vars' => array ()));
		$tvars['vars']['newsvote'] = $tpl -> show('nv_addnews');

		return 1;
	}
    

    ///////////
    function addNews(&$tvars, &$SQL) {
        if($_REQUEST['nvenable'])
           $SQL['nvote'] = 1;
        
		return 1;
	}

	function addNewsNotify(&$tvars, $SQL, $newsid) {
		global $mysql;

		$answers = array();
        if(!$_REQUEST['nvenable'])
           return 1;
           
        $vtitle = trim($_REQUEST['nvtitle']);
        if (!strlen($vtitle)) return 1;
        $vtitle = db_squote($vtitle);
        
        $mysql->query("insert into ".prefix."_newsvote (newsid, title) values (".$newsid.", ".$vtitle.")");
        $vid = $mysql->lastid("newsvote");
        
        addAnswers($_REQUEST['nvanswers'], $vid);
        
		return 1;
	}

	function editNewsForm($newsID, $SQLold, &$tvars) {
	        global $tpl, $mysql;
        
        if($SQLold['nvote'] > 0)
        {
            $result = '';
            $k = 1;
            foreach ($mysql->select("select v.id AS voteid, v.title, a.id, a.name from ".prefix."_newsvote v inner join ".prefix."_newsanswer a on a.voteid=v.id where v.newsid=".$newsID) as $ad) {
                    $dt['vars']['num'] = $k++;
                    $dt['vars']['id'] = $ad['id'];
                    $dt['vars']['name'] = $ad['name'];
                   
                   $tpath = locatePluginTemplates(array('edit_answer'), 'newsvotes', 0, 'default');
                   $tpl -> template('edit_answer', $tpath['edit_answer']);
                   $tpl -> vars('edit_answer', $dt);
                   $result .= $tpl -> show('edit_answer');
                }
    
            $tpath = locatePluginTemplates(array('nv_editnews'), 'newsvotes', 0, 'default');
    		$tpl -> template('nv_editnews', $tpath['nv_editnews']);
    		$tpl -> vars('nv_editnews', array ( 'vars' => array ( 'answers' => $result, 'title' => secure_html($ad['title']), 'voteid' => intval($ad['voteid']))));
    		$tvars['vars']['newsvote_edit'] = $tpl -> show('nv_editnews');
        }
        else
        {
            $tpath = locatePluginTemplates(array('nv_addnews'), 'newsvotes', 0, 'default');

    		$tpl -> template('nv_addnews', $tpath['nv_addnews']);
    		$tpl -> vars('nv_addnews', array ( 'vars' => array ()));
    		$tvars['vars']['newsvote_edit'] = $tpl -> show('nv_addnews');
        }
        

		return 1;
	}

	function editNews($newsID, $SQLold, &$SQLnew, &$tvars) {
        if($_REQUEST['nvenable']) $SQLnew['nvote'] = 1;
        return 1;
	}
    
	// Make changes in DB after EditNews was successfully executed
	function editNewsNotify($newsID, $SQLnews, &$SQLnew, &$tvars) {
		global $mysql;
        
        if($_REQUEST['mode'] == "addv")
        {
            $answers = array();

            if(!$_REQUEST['nvenable'])
               return 1;
               
            $vtitle = trim($_REQUEST['nvtitle']);
            if (!strlen($vtitle)) return 1;
            $vtitle = db_squote($vtitle);
            
            $mysql->query("insert into ".prefix."_newsvote (newsid, title) values (".$newsID.", ".$vtitle.")");
            $vid = $mysql->lastid("newsvote");
            
            addAnswers($_REQUEST['nvanswers'], $vid);
            
        }
        else
        {
            $vid = intval($_REQUEST['voteid']);
            if($_REQUEST['nvdelete'])
            {
                deleteVote($vid, $newsID);
                return 1;
            }
            
            $mysql->query("update ".prefix."_newsvote set title = ".db_squote(trim($_REQUEST['nvtitle']))." where newsid = ".intval($newsID));
            
            if(isSet($_REQUEST['nvupdate']))
            {
                foreach($_REQUEST['ans'] as $aid => $aval)
                {
                    $mysql->query("update ".prefix."_newsanswer set name = ".db_squote(trim(htmlspecialchars($aval, ENT_QUOTES)))." where id = ".intval($aid));
                }
                
                addAnswers($_REQUEST['nvanswers'], $_REQUEST['voteid']);
            }
            
            if($_REQUEST['nvrefresh'])
            {
                $mysql->query("update ".prefix."_newsanswer set number = 0 where voteid = ".$vid);
                $mysql->query("update ".prefix."_newsvote set count = 0 where id = ".$vid);
                $mysql->query("delete from ".prefix."_newsvoted where voteid = ".$vid);    
            }
               
        }
        
        return 1;
	}

	function showNews($newsID, $SQLnews, &$tvars, $mode = array()) {
		global $mysql, $tpl, $userROW, $ip;
        
        if (!$SQLnews['nvote']) {$tvars['vars']['newsvote'] = ''; return 1;}
        
        if(isSet($_POST['mode']))
        {
            process_voting($_REQUEST['voteid'], $_REQUEST['ans'], $newsID);
            unset($_POST['mode']);
        }
        
          $notrate = 0;
          if($mysql->result("select id from ".prefix."_newsvoted where ip=".db_squote($ip)." AND voteid=(select id from ".prefix."_newsvote where newsid=".intval($newsID).")"))
               $notrate = 1;
           
        
        $result = '';
        foreach ($mysql->select("select v.id AS voteid, v.title, v.count, a.id, a.name, a.number from ".prefix."_newsvote v inner join ".prefix."_newsanswer a on a.voteid=v.id where v.newsid=".$newsID) as $SQL) {
            $n = $SQL['title'];
           
            $dt['vars']['id'] = $SQL['id'];
            $dt['vars']['answer'] = $SQL['name'];
            $dt['vars']['number'] = $SQL['number'];
            $dt['vars']['width'] = @round(($SQL['number']/$SQL['count'])*100, 2);
            
            $dt['regx']['#\[is-rate\](.*?)\[\/is-rate\]#is'] = (!$notrate)?'$1':'';
            $dt['regx']['#\[not-rate\](.*?)\[\/not-rate\]#is'] = ($notrate)?'$1':'';
               
            $tpath = locatePluginTemplates(array('answer'), 'newsvotes', 0, 'default');
            $tpl -> template('answer', $tpath['answer']);
		    $tpl -> vars('answer', $dt);
		    $result .= $tpl -> show('answer');
            }

        $tpath = locatePluginTemplates(array('vote'), 'newsvotes', 0, 'default');
        $ptvars['vars']['vote'] = $SQL['title'];
        $ptvars['vars']['voteid'] = $SQL['voteid'];
        $ptvars['vars']['total'] = $SQL['count'];
        $ptvars['regx']['#\[is-rate\](.*?)\[\/is-rate\]#is'] = ($notrate)?'':'$1';
        $ptvars['regx']['#\[not-rate\](.*?)\[\/not-rate\]#is'] = ($notrate)?'$1':'';
    $ptvars['vars']['answers'] = $result;
    $ptvars['vars']['self'] = $_SERVER["REQUEST_URI"];
    
     //print_r($_POST);

    $tpl -> template('vote', $tpath['vote']);
	$tpl -> vars('vote', $ptvars);
    $tvars['vars']['newsvote'] = $tpl -> show('vote');
    
       
		return 1;
	}

	// Delete news call
	function deleteNews($newsID, $SQLnews) {
		global $mysql;

        if($SQLnews['nvote'] != 0)
        {
            $voteid = $mysql->result("select id from ".prefix."_newsvote where newsid=".intval($newsID)."");
            if($voteid) deleteVote($voteid, $newsID);
        }

		return 1;
	}

	// Mass news modify
	function massModifyNewsNotify($idList, $setValue, $currentData) {
		return 1;
	}
}

register_filter('news','newsvotes', new NewsvoteNewsfilter);

function addAnswers($area, $voteID) {
	        global $mysql;
            
            foreach (explode("\n", $area) as $answer) {
			$answer = trim(htmlspecialchars($answer, ENT_QUOTES));
			if (!strlen($answer)) continue;
			$answers[] = db_squote($answer);
		}

		if (count($answers))
			foreach ($answers as $answer)
				$mysql->query("insert into ".prefix."_newsanswer (voteid, name) values (".intval($voteID).", ".$answer.")");
    }
            
   function deleteVote($voteID, $newsID) {
	        global $mysql;
            
            $mysql->query("delete from ".prefix."_newsanswer where voteid = ".$voteID);
            $mysql->query("delete from ".prefix."_newsvoted where voteid = ".$voteID);
            $mysql->query("delete from ".prefix."_newsvote where id = ".$voteID);
            $mysql->query("update ".prefix."_news set nvote = 0 where id = ".intval($newsID));
    }


function process_voting($voteID, $answerID, $newsID) {
 global $mysql, $userROW, $ip;
 
    if($mysql->result("select id from ".prefix."_newsvoted where ip=".db_squote($ip)." AND voteid=".intval($voteID).""))
       return 1;
    
    $mysql->query("insert into ".prefix."_newsvoted (voteid, ip) values (".intval($voteID).", ".db_squote($ip).")");
    $mysql->query("update ".prefix."_newsanswer set number = number+1 where id = ".intval($answerID));
    $mysql->query("update ".prefix."_newsvote set count = count+1 where id = ".intval($voteID));
    $mysql->query("update ".prefix."_news set views = views-1 where id = ".intval($newsID));
 }