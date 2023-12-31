﻿<?php
/**
*	PHP | OTUS HLA | UTF8 | index.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2023-12-25
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
ini_set('error_log',				'index.error.log');

//====================================================================== CONSTANTS
define('ENDL',	chr(0x0D) . chr(0x0A));
define('ROOT',	'82832e53-7524-fa24-8519-b82f7d1d451f');

//====================================================================== DEPENDENCIES
require_once '../../../include/aliases.php';
require_once '../../../include/cURL.php';
require_once 'index.config.php';
require_once 'inc/tools.php';

//====================================================================== VARIABLES
$dbHandle		= null;
$arrDbRes		= array('result' => false, 'payload' => 'initial');
$responseMsg	= '';
$responseHdr	= array();
$tokenCookie	= $_COOKIE['token'];
$tokenDb		= '';
$token			= '';
$userId			= 0;
$usersCount		= 0;
$timeStamp		= time();
$sessionId		= 0;
$sessionsCount	= 0;

//====================================================================== MAIN
// Connect to db
$arrDbRes	= db_connect($dbHandle);
if ($arrDbRes['result'] === false)
{
	page_break($dbHandle, '500 Internal Server Error', $arrDbRes['payload']);
}
// Get user id by coockie token
$arrDbRes	= db_get_session_by_token($dbHandle, (empty($tokenCookie) ? 'nothing' : $tokenCookie));
if ($arrDbRes['result'] === false)
{
	page_break($dbHandle, '400 Bad Request', $arrDbRes['payload']);
}
$sessionsCount	= count($arrDbRes['payload']);
if ($sessionsCount > 0)
{
	$userId	= intval($arrDbRes['payload'][0]['uid']);
}
// Proccess GET param
if (isset($_GET['login']))
{
	include 'inc/method.login.php';
}
else if (isset($_GET['adduser']))
{
	include 'inc/method.adduser.php';
}
else if (isset($_GET['uid']) && $userId !== 0)
{
	include 'inc/method.uid.php';
}
else
{
	if ($userId === 0)
	{
		// Move to ?login
		page_move_to($dbHandle, $_SERVER['SCRIPT_NAME'] . '?login');
	}
	else
	{
		// Move to ?uid=
		page_move_to($dbHandle, $_SERVER['SCRIPT_NAME'] . '?uid=' . $userId);
	}
}
// Close handle to db
if ($dbHandle) db_close($dbHandle);
?>
