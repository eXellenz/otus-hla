<?php
//====================================================================== INIT
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Asia/Dubai');
system('title API Test');
system('chcp 1251');
pclose(popen('cls','w'));

//====================================================================== PHP CUSTOM SETTINGS
ini_set('memory_limit',				'64M');
ini_set('date.timezone',			'Asia/Dubai');
ini_set('displaydbgs',				'On');
ini_set('display_startupdbgs',		'On');
ini_set('logdbgs',					'On');
ini_set('display_errors',			'On');
ini_set('display_startup_errors',	'On');
ini_set('log_errors',				'On');
ini_set('error_log',				'apitest.error.log');

//====================================================================== CONSTANTS
define('URL',		'https://example.com/');
define('API',		URL . 'api.php?method=');
define('LOGIN',		'user');
define('PASSWORD',	'password');

//====================================================================== DEPENDENCIES
require_once '../../../../include/dionys/cURL.php';

//====================================================================== VARIABLES
$requestUri	= $_SERVER['REQUEST_URI'];
$json		= '';

//====================================================================== MAIN
if (!empty($requestUri))
{
	print 'Access denided.';
	exit(0);
}

$url		= API . 'getSessionToken';
$fields		= array(
					'login'		=> LOGIN,
					'password'	=> PASSWORD
			);
$postData	= json_encode($fields);
$headerData	= array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($postData)
			);
$data		= array(
					'method'	=> 'POST',
					'data'		=> $postData,
					'header'	=> $headerData
			);
$recv	= curl_recv_page($url, $data);

print	'status >>>' . PHP_EOL . $recv['status'] . PHP_EOL . PHP_EOL;
print	'headerOut >>>' . PHP_EOL . $recv['headerOut'] . PHP_EOL . PHP_EOL;
print	'headerIn >>>' . PHP_EOL . $recv['headerIn'] . PHP_EOL . PHP_EOL;
print	'data >>>' . PHP_EOL . $recv['data'] . PHP_EOL . PHP_EOL;

prompt_ex('Press ENTER for exit ...');

//====================================================================== FUNCTIONS
function prompt_ex($msg) 
{
	print $msg . "\n";
	ob_flush();
	$in	= fgets(fopen('php://stdin', 'r'), 1024);
	fclose(fopen('php://stdin', 'r'));
	exit();
}
?>
