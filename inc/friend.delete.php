<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/friend.delete.php
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

//====================================================================== MAIN
if (isset($_GET['id']))
{
	$arrDbRes	= db_delete_friend_from_uid($dbHandles['write'], $userId, $_GET['id']);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}
	else
	{
		// Move to index.php
		page_move_to($dbHandles, str_replace('friend.php', 'index.php', $_SERVER['SCRIPT_NAME']));
	}
}
else
{
	page_break($dbHandles, '400 Bad Request', 'Expected id param.');
}
?>
