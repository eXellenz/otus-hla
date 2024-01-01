<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/method.adduser.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2023-12-25
*/

//====================================================================== CHECK
if (!defined('ROOT'))
{
	print 'Access denided.';
	exit(0);
}

//====================================================================== VARIABLES
$rawLogin		= $_POST['reg-login'];
$rawPassword	= $_POST['reg-password'];
$rawName		= $_POST['reg-name'];
$rawLastname	= $_POST['reg-lastname'];
$rawAge			= $_POST['reg-age'];
$rawGender		= $_POST['reg-gender'];
$rawCity		= $_POST['reg-city'];
$rawAbout		= $_POST['reg-about'];

//====================================================================== MAIN
if (
	!empty($rawLogin) &&
	!empty($rawPassword) &&
	!empty($rawName) &&
	!empty($rawLastname) &&
	!empty($rawAge) &&
	!empty($rawGender) &&
	!empty($rawCity) &&
	!empty($rawAbout)
)
{
	$arrDbRes	= db_get_user_by_login($dbHandle, $rawLogin);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandle, '400 Bad Request', $arrDbRes['payload']);
	}
	$usersCount	= count($arrDbRes['payload']);
	// Check for registering user login exist in db
	if ($usersCount > 0)
	{
		page_move_to($dbHandle, $_SERVER['SCRIPT_NAME'] . '?adduser');
	}
	else
	{
		$arrDbRes	= db_add_user($dbHandle, $rawLogin, $rawPassword, $rawName, $rawLastname, $rawAge, $rawGender, $rawCity, $rawAbout);
		if ($arrDbRes['result'] === false)
		{
			page_break($dbHandle, '400 Bad Request', $arrDbRes['payload']);
		}
		else
		{
			page_move_to($dbHandle, $_SERVER['SCRIPT_NAME'] . '?login');
		}
	}
}
else
{
	include 'tpl/adduser.tpl.php';
}
?>
