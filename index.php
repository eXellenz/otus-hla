<?php
/**
*	PHP | OTUS HLA | UTF8 | index.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-04-06
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
$dbHandles		= array('write' => null, 'read' => null, 'sqlite' => null);
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
// Init tables
if (INSTALL === true)
{
	$arrDbRes	= db_table_init($dbHandles['write']);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '500 Internal Server Error', $arrDbRes['payload']);
	}

	sqlite_init($dbHandles['read']);
	sqlite_backup();
}
// Connect to sqlite
if (SQLITE_USE)
{
	$arrDbRes	= sqlite_connect($dbHandles);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '500 Internal Server Error', $arrDbRes['payload']);
	}
}
// Get user id by coockie token
if (!empty($tokenCookie))
{
	if (SQLITE_USE)
	{
		$arrDbRes	= sqlite_get_session($dbHandles['sqlite'], $tokenCookie);
	}
	else
	{
		$arrDbRes	= db_get_session($dbHandles['read'], $tokenCookie);
	}
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}

	$sessionsCount	= count($arrDbRes['payload']);
	if ($sessionsCount === 1)
	{
		$userId	= intval($arrDbRes['payload'][0]['uid']);
	}
	else if ($sessionsCount > 1)
	{
		page_break($dbHandles, '500 Internal Server Error', 'Found more when one user session!');
	}
}
// Proccess GET param
if (isset($_GET['login']))
{
	include 'inc/index.login.php';
}
else if (isset($_GET['adduser']))
{
	include 'inc/index.adduser.php';
}
else if (isset($_GET['search']) && $userId !== 0)
{
	include 'inc/index.search.php';
}
else if (isset($_GET['uid']) && $userId !== 0)
{
	include 'inc/index.uid.php';
}
else
{
	if ($userId === 0)
	{
		// Move to ?login
		page_move_to($dbHandles, $_SERVER['SCRIPT_NAME'] . '?login');
	}
	else
	{
		// Move to ?uid=
		page_move_to($dbHandles, $_SERVER['SCRIPT_NAME'] . '?uid=' . $userId);
	}
}
// Close handle to db
db_close($dbHandles);
?>
