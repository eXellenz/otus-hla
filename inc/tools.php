<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/tools.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-02-27
*/

//====================================================================== CHECK
if (!defined('ROOT'))
{
	print 'Access denided.';
	exit(0);
}

//====================================================================== DEPENDENCIES
require_once 'inc/tools.db.php';
require_once 'inc/tools.cache.php';
require_once 'inc/tools.mqtt.php';

//====================================================================== FUNCTIONS
function page_move_to(&$mysqliArr, $url)
{
	// Close handle to db
	db_close($mysqliArr);
	// Move to $url
	header('Location: ' . $url);
}

function page_break(&$mysqliArr, $httpFullCode, $message)
{
	$responseHdr	= array(
							$_SERVER['SERVER_PROTOCOL'] . ' ' . $httpFullCode,
							'Content-Encoding: none',
							'Content-Type: text/html; charset=utf-8'
					);
	$responseMsg	= $message;
	// Close handle to db
	db_close($mysqliArr);
	// Response to client and close connecting
	response_and_close_connect($responseMsg, $responseHdr);
	// Exit code proccess
	exit(0);
}

function get_unique()
{
	return rnd_str(8, 'eng', 'nh') . '-' . rnd_str(4, 'eng', 'nh') . '-' . rnd_str(4, 'eng', 'nh') . '-' . rnd_str(4, 'eng', 'nh') . '-' . rnd_str(12, 'eng', 'nh');
}
?>