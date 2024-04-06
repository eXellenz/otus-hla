<?php
/**
*	PHP | OTUS HLA | UTF8 | api.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-04-06
*/

/*
*	method allow:
*	getSessionToken
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
ini_set('error_log',				'api.error.log');

//====================================================================== CONSTANTS
define('ENDL',	chr(0x0D) . chr(0x0A));
define('ROOT',	'82832e53-7524-fa24-8519-b82f7d1d451f');

//====================================================================== DEPENDENCIES
require_once '../../../include/aliases.php';
require_once '../../../include/cURL.php';
require_once 'api.config.php';
require_once 'inc/tools.php';

//====================================================================== VARIABLES
$dbHandles			= array('write' => null, 'read' => null, 'sqlite' => null);
$arrDbRes			= array('result' => false, 'payload' => 'initial');
$requestMethod		= $_GET['method'];
$httpContentType	= $_SERVER['CONTENT_TYPE'];
$httpContentLength	= $_SERVER['CONTENT_LENGTH'];
$phpData			= file_get_contents('php://input');
$request			= array();
$userLogin			= '';
$userPassword		= '';
$userId				= 0;
$sessionToken		= '';
$json				= '';
$answerArr			= array('uid' => $userId, 'token' => $sessionToken);


//====================================================================== MAIN
// Set internal character encoding to UTF-8
mb_internal_encoding("UTF-8");
// Check content type
if ($httpContentType !== 'application/json')
{
	page_break($dbHandles, '400 Bad Request', 'Wrong content type.');
}
// Check method
if ($requestMethod !== 'getSessionToken' && requestMethod !== 'postGet')
{
	page_break($dbHandles, '405 Method Not Allowed', 'Method (' . $requestMethod . ') not allow.');
}
// json data decode
$request	= json_decode($phpData, true);
// Check json decode error
if (json_last_error() !== JSON_ERROR_NONE)
{
	page_break($dbHandles, '400 Bad Request', 'Content decoding failed with error: ' . json_last_error_msg() . '.');
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
// Proccess GET param
if ($requestMethod === 'getSessionToken')
{
	$userLogin			= $request['login'];
	$userPassword		= $request['password'];
	// Check login:password existing
	if (empty($userLogin) || empty($userPassword))
	{
		page_break($dbHandles, '401 Unauthorized', 'For this method you must specify authentication credentials.');
	}
	// Get user by login
	$arrDbRes	= db_get_user_by_login($dbHandles['read'], $userLogin);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}
	$usersCount	= count($arrDbRes['payload']);
	// Check for auth user login exist in db
	if ($usersCount == 0)
	{
		page_break($dbHandles, '401 Unauthorized', 'Unknown user with specified login::password.');
	}
	else if ($usersCount > 1)
	{
		page_break($dbHandles, '401 Unauthorized', 'Fatal error! Please report site admin.');
	}
	else
	{
		// Check for auth user password equal password in db
		if ($arrDbRes['payload'][0]['password'] !== md5($userPassword))
		{
			page_break($dbHandles, '401 Unauthorized', 'Unknown user with specified login:password.');
		}
	}
	// Save found user ID
	$userId	= intval($arrDbRes['payload'][0]['uid']);
	// Get Session token
	if (SQLITE_USE)
	{
		$arrDbRes	= sqlite_get_session($dbHandles['sqlite'], '', $userId);
	}
	else
	{
		$arrDbRes	= db_get_session($dbHandles['read'], '', $userId);
	}
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}
	// Add or update session id in db
	$sessionsCount	= count($arrDbRes['payload']);
	// Check for session token exist in db
	if ($sessionsCount == 0)
	{
		page_break($dbHandles, '401 Unauthorized', 'Session token not found! Please use auth form on the web site.');
	}
	else if ($sessionsCount > 1)
	{
		page_break($dbHandles, '401 Unauthorized', 'Fatal error! Please report site admin.');
	}
	// Save found token
	$sessionToken	= $arrDbRes['payload'][0]['token'];
	// Send session token by json
	$answerArr		= array('uid' => $userId, 'token' => $sessionToken);
	$json			= json_encode($answerArr);
	// Response to client and close connecting
	$answerHdr		= array(
							$_SERVER['SERVER_PROTOCOL'] . ' 200 OK',
							'Content-Type: application/json',
							'Content-Length: ' . strlen($json)
					);
	// Response to client and close connecting
	response_and_close_connect($json, $answerHdr);
}
// Close handle to db
db_close($dbHandles);
?>
