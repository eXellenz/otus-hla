<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/index.friend.add.php
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

//====================================================================== MAIN
if (isset($_GET['id']))
{
	$arrDbRes	= db_add_friend_to_uid($dbHandles['write'], $userId, $_GET['id']);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}
	else
	{
		// Move to index.php
		page_move_to($dbHandles, str_replace('index.friend.php', 'index.php', $_SERVER['SCRIPT_NAME']));
	}
}
else
{
	page_break($dbHandles, '400 Bad Request', 'Expected id param.');
}
?>
