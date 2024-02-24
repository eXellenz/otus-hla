﻿<?php
/**
*	PHP | OTUS HLA | UTF8 | friend.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-02-24
*/

//====================================================================== INIT
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Asia/Dubai');
ob_implicit_flush(true);

//====================================================================== PHP CUSTOM SETTINGS
ini_set('memory_limit',				'64M');
ini_set('date.timezone',			'Asia/Dubai');
ini_set('displaydbgs',				'On');
ini_set('display_startupdbgs',		'On');
ini_set('logdbgs',					'On');
ini_set('display_errors',			'On');
ini_set('display_startup_errors',	'On');
ini_set('log_errors',				'On');
ini_set('error_log',				'friend.error.log');

//====================================================================== CONSTANTS
define('ENDL',	chr(0x0D) . chr(0x0A));
define('ROOT',	'82832e53-7524-fa24-8519-b82f7d1d451f');

//====================================================================== DEPENDENCIES
require_once '../../../include/aliases.php';
require_once '../../../include/cURL.php';
require_once 'index.config.php';
require_once 'inc/tools.php';

//====================================================================== VARIABLES
$dbHandles		= array('write' => null, 'read' => null);
$arrDbRes		= array('result' => false, 'payload' => 'initial');
$tokenCookie	= $_COOKIE['token'];
$userId			= 0;
$apcuAvailabe	= false;

//====================================================================== MAIN
// Set internal character encoding to UTF-8
mb_internal_encoding("UTF-8");
// Check for APCu available
if (function_exists('apcu_enabled'))
{
	$apcuAvailabe	= apcu_enabled();
}
if($apcuAvailabe !== true)
{
	page_break($dbHandles, '500 Internal Server Error', 'APCu not available.');
}
// Connect to db
$arrDbRes	= db_connect($dbHandles);
if ($arrDbRes['result'] === false)
{
	page_break($dbHandles, '500 Internal Server Error', $arrDbRes['payload']);
}
// Get user id by coockie token
$arrDbRes	= db_get_session_by_token($dbHandles['read'], (empty($tokenCookie) ? 'nothing' : $tokenCookie));
if ($arrDbRes['result'] === false)
{
	page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
}
$sessionsCount	= count($arrDbRes['payload']);
if ($sessionsCount > 0)
{
	$userId	= intval($arrDbRes['payload'][0]['uid']);
}
// Session for user exist?
if ($userId === 0)
{
	// Move to index.php
	page_move_to($dbHandles, str_replace('friend.php', 'index.php', $_SERVER['SCRIPT_NAME']));
}
// Proccess GET param
if (isset($_GET['add']))
{
	if (isset($_GET['id']))
	{
		$arrDbRes	= db_add_friend_to_uid($dbHandles['write'], $userId, $_GET['id']);
		if ($arrDbRes['result'] === false)
		{
			page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
		}
		else
		{
			// Move to index.php
			page_move_to($dbHandles, str_replace('friend.php', 'index.php', $_SERVER['SCRIPT_NAME']));
		}
	}
	else
	{
		page_break($dbHandles, '400 Bad Request', 'Expected id param.');
	}
}
else if (isset($_GET['delete']))
{
	if (isset($_GET['id']))
	{
		$arrDbRes	= db_delete_friend_from_uid($dbHandles['write'], $userId, $_GET['id']);
		if ($arrDbRes['result'] === false)
		{
			page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
		}
		else
		{
			// Move to index.php
			page_move_to($dbHandles, str_replace('friend.php', 'index.php', $_SERVER['SCRIPT_NAME']));
		}
	}
	else
	{
		page_break($dbHandles, '400 Bad Request', 'Expected id param.');
	}
}
else
{
	page_break($dbHandles, '400 Bad Request', 'Expected add or delete param.');
}
// Close handle to db
db_close($dbHandles);
?>