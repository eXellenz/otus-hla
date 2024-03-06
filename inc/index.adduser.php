<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/index.adduser.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-03-06
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
$existWarning	= '';

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
	$arrDbRes	= db_get_user_by_login($dbHandles['read'], $rawLogin);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}
	$usersCount	= count($arrDbRes['payload']);
	// Check for registering user login exist in db
	if ($usersCount > 0)
	{
		page_move_to($dbHandles, $_SERVER['SCRIPT_NAME'] . '?adduser&exist');
	}
	else
	{
		$arrDbRes	= db_add_user($dbHandles['write'], $rawLogin, $rawPassword, $rawName, $rawLastname, $rawAge, $rawGender, $rawCity, $rawAbout);
		if ($arrDbRes['result'] === false)
		{
			page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
		}
		else
		{
			page_move_to($dbHandles, $_SERVER['SCRIPT_NAME'] . '?login');
		}
	}
}
else
{
	if (isset($_GET['exist']))
	{
		$existWarning	= 'Пользователь с таким логином существует!';
	}
	include 'tpl/adduser.tpl.php';
}
?>
