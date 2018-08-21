<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

register_plugin_page('testmanager','','plugin_testmanager_screen', 0);
register_plugin_page('testmanager','result','plugin_testmanager_result', 0);

loadPluginLang('testmanager', 'main', '', '', ':');

// Load library
//include_once(root."/plugins/feedback/lib/common.php");

//
// Show test
function plugin_testmanager_screen(){
  plugin_testmanager_showScreen();
}

//
// Show test screen
// Mode:
// * 0 - initial show
// * 1 - show filled earlier values (error filling some fields)
function plugin_testmanager_showScreen($mode = 0, $errorText = '') {
  global $template, $lang, $mysql, $userROW, $PFILTERS, $twig, $SYSTEM_FLAGS;
  
  $hiddenFields = array();  

  $SYSTEM_FLAGS['info']['title']['group']    = $lang['testmanager:header.title'];
  $test_id = intval($_REQUEST['id']);

  // Determine template path
  $tpath = LocatePluginTemplates(array('testmanager', 'test.form'), 'testmanager', pluginGetVariable('testmanager', 'localsource'));
  $xt = $twig->loadTemplate($tpath['test.form'] . 'test.form.tpl');
  
  // Get form data
  if (!is_array($trow = $mysql->record("select * from " . prefix . "_testmanager where active = 1 and id = " . $test_id))) {
    $tVars = array(
      'title'   => $lang['testmanager:test.no.title'],
      'description' => $lang['testmanager:test.no.description'],
    );
    $template['vars']['mainblock'] = $xt->render($tVars);
    return 1;
  }

  $SYSTEM_FLAGS['info']['title']['item'] = $trow['title'];

  // Unpack form data
  $tData = unserialize($trow['struct']);
  if (!is_array($tData)) $tData = array();
  
  $tVars = array(    
    'title'       => $trow['title'],
    'name'        => $trow['name'],
    'description' => $trow['description'],
    'id'          => $trow['id'],
    'form_url'    => generateLink('core', 'plugin', array('plugin' => 'testmanager', 'handler' => 'result'), array()),
    'errorText'   => $errorText,
    'flags'       => array(
        'error'     => ($errorText) ? 1 : 0,
    ),
  );

  $tEntries = array();

  $FBF_DATA = array();

  foreach ($tData as $tName => $tInfo) {
    $tEntry = array(
      'name'    => 'fld_' . $tInfo['name'],
      'title'   => $tInfo['title'],
      'type'    => $tInfo['type'],
    );

    $FBF_DATA[$tName] = array($tInfo['type'], iconv('Windows-1251', 'UTF-8', $tInfo['title']));

    // Fill value
    $setValue = '';

    if ($mode) {
      // FILLED EARLIER
      $setValue = secure_html($_REQUEST['fld_' . $tInfo['name']]);
    }    

    $opts = '';
    foreach ($tInfo['options'] as $v) {
      $opts .= '<option value="' . secure_html($v) . '"' . ($v == $setValue ? ' selected="selected"' : '' ) . '>' . secure_html($v) . '</option>';
    }
    $tEntry['options']['select'] = $opts;

    $tEntry['flags'] = array(
      'is_select'    => 1,
    );

    $tEntries[] = $tEntry;
  }
    
  // Fill entries
  $tVars['entries'] = $tEntries;
  $tVars['FBF_DATA'] = json_encode($FBF_DATA);

  // Check if we need captcha  
  if (substr($trow['flags'],0,1)) {
    $tVars['flags']['captcha'] = 1;
    $tVars['captcha_url'] = admin_url."/captcha.php?id=testmanager";
    $tVars['captcha_rand'] = rand(00000, 99999);

    $_SESSION['captcha.testmanager'] = rand(00000, 99999);
  }  

  // Prepare hidden fields
  $hF = '';
  foreach ($hiddenFields as $k => $v) {
    $hF .= '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v, ENT_COMPAT | ENT_HTML401, 'cp1251').'"/>'."\n";
  }
  $tVars['hidden_fields'] = $hF;
 
  $template['vars']['mainblock'] =  $xt->render($tVars);

}


// Show test result
function plugin_testmanager_result() {
  global $template, $lang, $mysql, $userROW, $SYSTEM_FLAGS, $PFILTERS, $twig;

  // Determine paths for all template files
  $tpath = LocatePluginTemplates(array('testmanager', 'test.result'), 'testmanager', pluginGetVariable('testmanager', 'localsource'));
  $xt = $twig->loadTemplate($tpath['test.result'] . 'test.result.tpl');
  
  $test_id = intval($_REQUEST['id']);
  $SYSTEM_FLAGS['info']['title']['group']    = $lang['testmanager:header.title'];  

  // Get form data
  if (!is_array($trow = $mysql->record("select * from " . prefix . "_testmanager where active = 1 and id = " . $test_id))) {
    $tVars = array(
      'title'   => $lang['testmanager:test.no.title'],
      'description' => $lang['testmanager:test.no.description'],
    );
    $template['vars']['mainblock'] = $xt->render($tVars);
    return 1;
  }

  $SYSTEM_FLAGS['info']['title']['item']  = str_replace('{title}', $trow['title'], $lang['testmanager:header.send']);

  // Check if captcha check if needed
  if (substr($trow['flags'], 0, 1)) {
    $vcode = $_REQUEST['vcode'];
    if ((!$vcode) || ($vcode != $_SESSION['captcha.testmanager'])) {
      // Wrong CAPTCHA code (!!!)
      plugin_testmanager_showScreen(1, $lang['testmanager:captcha.badcode']);
      return;
    }
  }

  // Unpack form data
  $tData = unserialize($trow['struct']);
  if (!is_array($tData)) $tData = array();

  $tEntries = array();
  $fieldValues = array();
  // number of correct answers
  $right = 0;
  $total = 0;
  foreach ($tData as $tName => $tInfo) {
    $total += 1;
    $fieldValue = $_REQUEST['fld_' . $tName];
    $fieldValues[$tName] = str_replace("\n", "<br/>\n", secure_html($fieldValue));
    $correct = '&ndash;';
    if ($tInfo['answer'] == $fieldValues[$tName]) {
      $correct = '+';
      $right += 1;
    }
    $tEntry = array(
      'id'      => $tName,
      'title'   => secure_html($tInfo['title']),
      'value'   => $fieldValues[$tName],
      'answer'  => secure_html($tInfo['answer']),
      'correct' => $correct,
    );
    $tEntries[] = $tEntry;
  }
  
  $tVars = array(
    'url'         => generateLink('core', 'plugin', array('plugin' => 'testmanager'), array('id' => $test_id), true, true),
    'id'          => $trow['id'],
    'title'       => $trow['title'],
    'description' => $trow['description'],
    'entries'     => $tEntries,
    'answers'     => $fieldValues,
    'total'       => $total,
    'right'       => $right,
  );

  $template['vars']['mainblock'] = $xt->render($tVars);

  // Lock used captcha code if captcha is enabled
  if (substr($trow['flags'], 0, 1)) {
    $_SESSION['captcha.testmanager'] = rand(00000, 99999);
  }

}