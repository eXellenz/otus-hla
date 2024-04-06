<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/index.uid.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-04-06
*/

//====================================================================== CHECK
if (!defined('ROOT'))
{
	print 'Access denided.';
	exit(0);
}

//====================================================================== VARIABLES
$userIdGet		= intval($_GET['uid']);
$userName		= 'User not found';
$userLastName	= 'User not found';
$userAge		= 'User not found';
$userGender		= 'User not found';
$userCity		= 'User not found';
$userAbout		= 'User not found';
$userFriends	= array();

//====================================================================== MAIN
$arrDbRes	= db_get_user_by_uid($dbHandles['read'], $userIdGet);
if ($arrDbRes['result'] === false)
{
	page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
}
$usersCount	= count($arrDbRes['payload']);
// Check for requested user id exist in db
if ($usersCount > 0)
{
	$userName		= $arrDbRes['payload'][0]['name'];
	$userLastName	= $arrDbRes['payload'][0]['lastname'];
	$userAge		= $arrDbRes['payload'][0]['age'];
	$userGender		= $arrDbRes['payload'][0]['gender'];
	$userCity		= $arrDbRes['payload'][0]['city'];
	$userAbout		= $arrDbRes['payload'][0]['about'];
	$userFriends	= cache_get_friends($dbHandles['read'], $userIdGet);
}
include 'tpl/uid.tpl.php';
?>
