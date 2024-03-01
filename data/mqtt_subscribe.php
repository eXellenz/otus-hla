<?php
//====================================================================== INIT
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Asia/Dubai');
system('title MQTT subscribe');
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
ini_set('error_log',				'subscribe.error.log');

//====================================================================== DEPENDENCIES
require '../inc/phpMQTT/phpMQTT.php';

//====================================================================== MAIN
$requestUri		= $_SERVER['REQUEST_URI'];

if (!empty($requestUri))
{
	print 'Access denided.';
	exit(0);
}

$server			= 'localhost';				// change if necessary
$port			= 1883;                     // change if necessary
$username		= '';						// set your username
$password		= '';						// set your password
$topic			= 'phpMQTT/otus-hla/post/2';
$topics[$topic]	= array('qos' => 0, 'function' => 'proc_msg');
$client			= 'phpMQTT-' . uniqid();	// make sure this is unique for connecting to sever - you could use uniqid()
$mqtt			= new phpMQTT($server, $port, $client);
$mqtt->debug	= true;
$result			= $mqtt->connect(true, NULL, $username, $password);
$loop			= true;

if($result)
{
	$mqtt->subscribe($topics, 0);

	while($mqtt->proc(true))
	{
		if (!$loop)
		{
			break;
		}
	}

	$mqtt->close();
}
else
{
    print 'Failed connect to broker!' . "\n";
}

prompt_ex('Press ENTER for exit ...');

//====================================================================== FUNCTIONS
function proc_msg($topic, $msg)
{
	$ts	= date('Y.m.d H-i-s', time());

	print 'Recieved in ' . $ts . ': ' . "\n";
	print 'Topic: ' . $topic . "\n";
	print 'Message: ' . $msg . "\n\n";

	if ($msg === 'exit')
	{
		break_loop();
	}
}

function break_loop()
{
	global $loop;

	$loop	= false;
}

function prompt_ex($msg) 
{
	print $msg . "\n";
	ob_flush();
	$in	= fgets(fopen('php://stdin', 'r'), 1024);
	fclose(fopen('php://stdin', 'r'));
	exit();
}
?>
