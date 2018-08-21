<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

// Load langs
loadPluginLang('testmanager', 'config', '', '', ':');

// Switch action
switch ($_REQUEST['action']) {
  case 'addtest'  : addTest();
                    showList();
                    break;
  case 'savetest' : saveTest();
                    break;
  case 'test'     : showTest(0);
                    break;
  case 'row'      : showTestRow();
                    break;
  case 'editrow'  : editTestRow();
                    break;
  case 'update'   : if (doUpdate()) {
                      showTest();
                    }
                    break;
  case 'deltest'  : delTest();
                    showList();
                    break;
  default         : showList();
}

// Simply create new test
function addTest(){
  global $mysql, $lang;
  $mysql->query("insert into " . prefix . "_testmanager (name, title) values ('newtest', 'New test')");
}

// Save test params
function saveTest() {
  global $mysql, $lang;

  $id = intval($_REQUEST['id']);

  // First - try to fetch test
  if (!is_array($recF = $mysql->record("select * from " . prefix . "_testmanager where id = " . $id))) {
    msg(array('type' => 'error', 'text' => 'Указанный вами тест не существует'));
    showForm(1);
    return;
  }

  $name = trim($_REQUEST['name']);

  // Check ID
  if ($name == '') {
    msg(array('type' => 'error', 'text' => 'Необходимо заполнить ID теста'));
    showTest(1);
    return;
  }

  // Check duplicates
  if (is_array($mysql->record("select * from " . prefix . "_testmanager where id <> " . $id . " and name =" . db_squote($name)))) {
    msg(array('type' => 'error', 'text' => 'Тест с таким ID уже существует. Нельзя использовать одинаковый ID для разных тестов'));
    showTest(1);
    return;
  }

  // Save changes
  $flags = ($_REQUEST['captcha'] ? '1' : '0');

  $params = array(
    'name'        => $name,
    'title'       => $_REQUEST['title'],    
    'description' => $_REQUEST['description'],
    'active'      => $_REQUEST['active'],
    'flags'       => $flags,
  );

  $sqlParams = array();
  foreach ($params as $k => $v) {
    $sqlParams[] = $k . '=' . db_squote($v);
  }
  $mysql->select("update " . prefix . "_testmanager set " . join(", ", $sqlParams) . " where id = " . $id);
  
  showTest(1);
}

// Test list page
function showList(){
  global $mysql, $lang, $twig;

  $tVars = array();
  $tTests = array();

  foreach ($mysql->select("select * from " . prefix . "_testmanager order by name") as $trow) {
    $tTest = array(
      'id'  => $trow['id'],
      'name'  => $trow['name'],
      'title'  => $trow['title'],
      'flags' => array(
        'active'  => $trow['active'],
      ),
      'linkEdit'  => '?mod=extra-config&plugin=testmanager&action=test&id=' . $trow['id'],
      'linkDel'  => '?mod=extra-config&plugin=testmanager&action=deltest&id=' . $trow['id'],
    );
    $tTests[]= $tTest;
  }

  $tVars['entries'] = $tTests;

  $templateName = 'plugins/testmanager/tpl/conf.forms.tpl';
  $xt = $twig->loadTemplate($templateName);
  echo $xt->render($tVars);
}

// Edit test page
function showTest($edMode){
  global $mysql, $lang, $twig;

  $tVars = array();

  // Load test
  $id = intval($_REQUEST['id']);

  $tvars = array();
  if (!is_array($trow = $mysql->record("select * from " . prefix . "_testmanager where id = " . $id))) {
    $tVars['content'] = "Указанный тест [".$id."] не существует!";

    $xt = $twig->loadTemplate('plugins/testmanager/tpl/conf.notify.tpl');
    echo $xt->render($tVars);
    return false;
  }

  $tVars['testID']    = $trow['id'];
  $tVars['testName']  = $trow['name'];

  // Unpack form data
  $tData = unserialize($trow['struct']);
  if (!is_array($tData)) $tData = array();

  $tEntries = array();
  
  foreach ($tData as $tName => $tInfo) {
    $tEntry = array(
      'name'    => $tInfo['name'],
      'title'   => $tInfo['title'],
      'type'    => $tInfo['type'],
      'auto'    => intval($tInfo['auto']),
      'block'    => intval($tInfo['block']),
    );
    $tEntries[] = $tEntry;
  }  

  $tVars['id']          = $trow['id'];
  $tVars['name']        = $edMode ? $_REQUEST['name'] : $trow['name'];
  $tVars['entries']     = $tEntries;
  $tVars['title']       = $edMode ? $_REQUEST['title'] : $trow['title'];
  $tVars['description'] = $edMode ? $_REQUEST['description'] : $trow['description'];  
  $tVars['url']         = generateLink('core', 'plugin', array('plugin' => 'testmanager'), array('id' => $trow['id']), true, true);
  $tVars['flags']       = array(
      'active'      => intval($edMode ? $_REQUEST['active'] : $trow['active']),
      'captcha'     => intval($edMode ? $_REQUEST['captcha'] : intval(substr($trow['flags'], 0, 1))),
      'haveForm'    => 1,
  );
  
  $xt = $twig->loadTemplate('plugins/testmanager/tpl/conf.form.tpl');
  echo $xt->render($tVars);
}

// Edit question page
function showTestRow() {
  global $mysql, $lang, $twig;

  $tVars = array();

  // Load form
  $id    = intval($_REQUEST['test_id']);
  $tRowId  = $_REQUEST['row'];

  $recordFound = 0;
  do {
    // Check if form exists
    if (!is_array($trow = $mysql->record("select * from " . prefix . "_testmanager where id = " . $id))) {
      $tVars['content'] = "Указанный тест [" . $id . "] не существует!";
      break;
    }

    $tVars['flags']['haveForm'] = 1;
    $tVars['testID']            = $trow['id'];
    $tVars['testName']          = $trow['name'];

    // Unpack form data
    $tData = unserialize($trow['struct']);
    if (!is_array($tData)) $tData = array();

    // Check if form's row exists
    if ($tRowId && !isset($tData[$tRowId])) {
      $tVars['content'] = "Указанный вопрос [" . $id . "][" . $tRowId . "] не существует!";
      break;
    }

    $editMode = ($tRowId) ? 1 : 0;

    if ($editMode) {
      $xRow = $tData[$tRowId];

      $tVars['flags']['haveField']      = 1;
      $tVars['fieldName']               = $xRow['name'];
      $tVars['field']['name']           = $xRow['name'];
      $tVars['field']['title']          = secure_html($xRow['title']);
      $tVars['field']['answer']         = secure_html($xRow['answer']);
      $tVars['field']['type']['value']  = 'select';

    } else {
      $tVars['flags']['addField']       = 1;
      $tVars['field']['title']          = '';
      $tVars['field']['type']['value']  = 'select';
    }

    $tVars['field']['select_answer']  = secure_html($xRow['answer']);
    $tVars['field']['select_options'] = join("\n", $xRow['options']);  
        
    $recordFound = 1;
  } while (0);

  $templateName = 'plugins/testmanager/tpl/' . ($recordFound ? 'conf.form.editrow' : 'conf.notify') . '.tpl';

  $xt = $twig->loadTemplate($templateName);
  echo $xt->render($tVars);
}

// Edit question
function editTestRow(){
  global $mysql, $lang, $twig;

  // Check params
  $id       = intval($_REQUEST['test_id']);
  $tRowId   = $_REQUEST['name'];
  $editMode = intval($_REQUEST['edit']);
  $tVars    = array();

  $enabled = 0;
  do {
    // Check if form exists
    if (!is_array($trow = $mysql->record("select * from " . prefix . "_testmanager where id = " . $id))) {
      $tVars['content'] = "Указанный тест [" . $id . "] не существует!";
      break;
    }
    
    // Check if row id is not valid
    if (is_numeric(substr($tRowId, 0, 1)) || (strlen($tRowId) < 3) ) {
      $tVars['content'] = "Необходимо соблюдать правила формирования ID!";
      break;
    }

    $tVars['flags']['haveForm'] = 1;
    $tVars['testID']            = $trow['id'];
    $tVars['testName']          = $trow['name'];

    // Unpack form data
    $tData = unserialize($trow['struct']);
    if (!is_array($tData)) $tData = array();

    // Check if form's row exists
    if ($editMode && !isset($tData[$tRowId])) {
      $tVars['content'] = "Указанный вопрос [" . $id . "][" . $tRowId . "] не существует!";
      break;
    }

    // For "add" mode - check if field already exists
    if (!$editMode && isset($tData[$tRowId])) {
      $tVars['content'] = "Указанный вопрос [" . $id . "][" . $tRowId . "] уже существует!";
      break;
    }

    // Проверка корректности символов в имени [ только латница и цифры ]
    if (!$editMode && !preg_match('#^[a-z0-9]+$#', $tRowId)) {
      $tVars['content'] = "Номер вопроса содержит запрещенные символы. Допустимы маленькие латинские буквы и цифры!";
      break;
    }

    $tVars['flags']['haveField']  = 1;
    $tVars['fieldName']           = $tRowId;
    
    $enabled = 1;

    // Fill field's params
    $fld = array('name' => $tRowId, 'title' => $_REQUEST['title']);
    
    $fld['type'] = 'select';
    $fld['options'] = array();
    
    // fill select options
    foreach (explode("\n", $_REQUEST['select_options']) as $row) {
      if (!strlen(trim($row)))
        continue;
      $fld['options'][] = trim($row);
    }
    if (count($fld['options']) < 2) {
      $tVars['content'] = "Нужно указать несколько вариантов ответа!";
      break;
    }
    // fill correct answer
    $fld['answer'] = $_REQUEST['select_answer'];

    if (empty($fld['answer'])) {
      $tVars['content'] = "Необходимо указать правильный ответ!";
      break;
    }
    if (!in_array($fld['answer'], $fld['options'])) {
      $tVars['content'] = "Правильный ответ должен присутствовать в списке!";
      break;
    }

    // Everything is correct. Let's update field data
    $tData[$tRowId] = $fld;
    $mysql->query("update " . prefix . "_testmanager set struct = " . db_squote(serialize($tData)) . " where id = " . $trow['id']);

    $tVars['content'] = "Вопрос успешно отредактирован";
  } while (0);

  // Show template
  $xt = $twig->loadTemplate('plugins/testmanager/tpl/conf.notify.tpl');
  echo $xt->render($tVars);
}


function doUpdate() {
  global $mysql, $twig;

  // Check params
  $id    = intval($_REQUEST['id']);
  $tRowId  = $_REQUEST['name'];

  $enabled = 0;
  $tVars = array();
  do {
    // Check if form exists
    if (!is_array($trow = $mysql->record("select * from " . prefix . "_testmanager where id = " . $id))) {
      $tVars['content'] = "Указанный тест [" . $id . "] не существует!";
      break;
    }

    $tVars['flags']['haveForm'] = 1;
    $tVars['testID']            = $trow['id'];
    $tVars['testName']          = $trow['name'];

    // Unpack form data
    $tData = unserialize($trow['struct']);
    if (!is_array($tData)) $tData = array();

    // Check if form's row exists
    if (!isset($tData[$tRowId])) {
      $tVars['content'] = "Указанный вопрос [" . $id . "][" . $tRowId . "] не существует!";
      break;
    }
    $enabled = 1;
  } while(0);

  if (!$enabled) {
    // Show template
    $xt = $twig->loadTemplate('plugins/testmanager/tpl/conf.notify.tpl');
    echo $xt->render($tVars);

    return false;
  }

  // Now make an action
  switch ($_REQUEST['subaction']) {
    case 'del':   unset($tData[$tRowId]);
                  break;
    case 'up':    array_key_move($tData, $tRowId, -1);
                  break;
    case 'down':  array_key_move($tData, $tRowId, 1);
                  break;
  }

  $mysql->query("update " . prefix . "_testmanager set struct = " . db_squote(serialize($tData)) . " where id = " . $trow['id']);
  return true;
}


// Delete test
function delTest() {
  global $mysql, $lang;

  $mysql->query("delete from " . prefix . "_testmanager where id = " . intval($_REQUEST['id']));
}

function array_key_move(&$arr, $key, $offset) {
 $keys = array_keys($arr);
 $index = -1;
 foreach ($keys as $k => $v) if ($v == $key) { $index = $k; break; }
 if ($index == -1) return 0;
 $index2 = $index + $offset;
 if ($index2 < 0) $index2 = 0;
 if ($index2 > (count($arr)-1)) $index2 = count($arr)-1;
 if ($index == $index2)  return 1;

 $a = min($index, $index2);
 $b = max($index, $index2);

 $arr = array_slice($arr, 0, $a) +
   array_slice($arr, $b, 1) +
   array_slice($arr, $a+1, $b-$a) +
   array_slice($arr, $a, 1) +
   array_slice($arr, $b, count($arr) - $b);
}