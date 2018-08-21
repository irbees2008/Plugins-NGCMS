<?php

// Инклюд параметров и класса обновлялки
function rpc_updater_load($params)
{
    global $userROW;

	// Load params
	$params = array_merge($params, include_once(root . '/plugins/update_to_git/params.php'));

    // Check for security token
    if ($params['token'] != genUToken('core.system.update')) {
		return ['status' => 0, 'errorCode' => 999, 'data' => 'wrong_security_code__from_rpc_updater_load'];
    }

	// Scan incoming params
	if (empty($params['user']) or empty($params['repository']) or empty($params['engine_version'])) {
		return ['status' => 0, 'errorCode' => 999, 'data' => 'wrong_params_type__from_rpc_updater_load'];
	}

    // Check for permissions
    if (!is_array($userROW) or !checkPermission(['plugin' => '#admin', 'item' => 'configuration'], null, 'modify')) {
		return ['status' => 0, 'errorCode' => 999, 'data' => 'perm_denied__from_rpc_updater_load'];
    }

	return $params;
}

// Проверяем и получаем список файлов
function rpc_updater_check($params = [])
{
	// Load params
	$params = rpc_updater_load($params);

	// Load library
	include_once(root . '/plugins/update_to_git/lib/CSystemUpdate.php');
	$updater = new CSystemUpdate($params);

	// Check for update
	$updater->check();

	// response
	return $updater->execute();
}

// Обновляем по одному файлу, который передается
function rpc_updater($params = [])
{
	// Load params
	$params = rpc_updater_load($params);

	// Scan incoming params
	if (empty($params['url']) or empty($params['name']) or empty($params['action'])) {
		return ['status' => 0, 'errorCode' => 999, 'data' => 'wrong_params_type__from_rpc_updater'];
	}

	// Load library
	include_once(root . '/plugins/update_to_git/lib/CSystemUpdate.php');
	$updater = new CSystemUpdate($params);

	// Update file
	$updater->download($params['url'], $params['name'], $params['action']);

	// response
	return $updater->execute();
}

rpcRegisterFunction('plugin.updater.check', 'rpc_updater_check');
rpcRegisterFunction('plugin.updater.execute', 'rpc_updater');
