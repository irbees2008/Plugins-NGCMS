<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

function currency_block($params) {
  global $twig, $config;

  // Generate cache file name [ we should take into account SWITCHER plugin ]
  $cacheFileName = md5('currency'.$config['theme'].$config['default_lang']).'.txt';

  if (pluginGetVariable('currency','cache')) {
    $cacheData = cacheRetrieveFile($cacheFileName, extra_get_param('currency','cacheExpire'), 'currency');
    if ($cacheData != false) {
      // We got data from cache. Return it and stop
      return $cacheData;
    }
  }

  try {
    $url = "http://www.nationalbank.kz/rss/rates_all.xml";

    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $url);
    $data = curl_exec($c);
    curl_close($c);

    $xml = new SimpleXMLElement($data);

    $entries = array();

    if ($xml) {
     foreach ($xml->channel->item as $item) {
        $entries[] = array(
          'title' => (string) $item->title,
          'description' => (string) $item->description,
          'quantity' => (float) $item->quant,
          'change' => (string) $item->change,
        );
      }
    }

    $tVars = array(
      'entries' => $entries,
      'success' => true
    );
  }
  catch (Exception $e) {
    $tVars = array(
      'entries' => array(),
      'success' => false
    );
  }

  $tpath = locatePluginTemplates(array('currency'), 'currency', pluginGetVariable('currency', 'localsource'));
  $xt = $twig->loadTemplate($tpath['currency'] . 'currency.tpl');

  $output = $xt->render($tVars);

  // Save data to cache
  if (extra_get_param('currency','cache') && $tVars['success'] === TRUE) {
    cacheStoreFile($cacheFileName, $output, 'currency');
  }

  return $output;
}

twigRegisterFunction('currency', 'show', currency_block);

?>
