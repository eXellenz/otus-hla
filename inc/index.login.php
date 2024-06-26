<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/index.login.php
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
$rawLogin		= $_POST['auth-login'];
$rawPassword	= $_POST['auth-password'];

//====================================================================== MAIN
if (
	!empty($rawLogin) &&
	!empty($rawPassword)
)
{
	$arrDbRes	= db_get_user_by_login($dbHandles['read'], $rawLogin);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}
	$usersCount	= count($arrDbRes['payload']);
	// Check for auth user login exist in db
	if ($usersCount == 0)
	{
		page_move_to($dbHandles, $_SERVER['SCRIPT_NAME'] . '?login');
	}
	else
	{
		// Check for auth user password equal password in db
		if ($arrDbRes['payload'][0]['password'] !== md5($rawPassword))
		{
			page_move_to($dbHandles, $_SERVER['SCRIPT_NAME'] . '?login');
		}
		else
		{
			// Check for session id existing in db
			$userId		= intval($arrDbRes['payload'][0]['uid']);
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
			$sessionId		= ($sessionsCount == 0) ? 0 : intval($arrDbRes['payload'][0]['id']);
			$token			= get_unique();
			if (SQLITE_USE)
			{
				$arrDbRes	= sqlite_set_session($dbHandles['sqlite'], $userId, $timeStamp, $token, $sessionId);
			}
			else
			{
				$arrDbRes	= db_set_session($dbHandles['write'], $userId, $timeStamp, $token, $sessionId);
			}
			// Set coockie for user
			setcookie("token", $token, ($timeStamp + (3600 * 24)));
			// Move user to index page
			page_move_to($dbHandles, $_SERVER['SCRIPT_NAME']);
		}
	}
}
else
{
	include 'tpl/login.tpl.php';
}
?>
