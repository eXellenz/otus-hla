<?php
/**
*	PHP | OTUS HLA | UTF8 | index.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2023-12-25
*/

//====================================================================== PHP CUSTOM SETTINGS
error_reporting(E_ALL ^ E_NOTICE);

ini_set('memory_limit',				'512M');
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
define('OTUS',	'82832e53-7524-fa24-8519-b82f7d1d451f');

//====================================================================== DEPENDENCIES
require_once 'index.config.php';

//====================================================================== VARIABLES

//====================================================================== FUNCTION

//====================================================================== MAIN

?>
