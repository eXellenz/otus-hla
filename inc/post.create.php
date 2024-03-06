<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/post.create.php
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
$rawPostTitle	= $_POST['post-title'];
$rawPostText	= $_POST['post-text'];
$pageWarnMsg	= '';

//====================================================================== MAIN
if (
	!empty($rawPostTitle) &&
	!empty($rawPostText)
)
{
	$arrDbRes	= db_add_post_by_uid($dbHandles['write'], $userId, $rawPostTitle, $rawPostText);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}
	else
	{
		$arrDbRes	= mqtt_publish_event_post($userId, $rawPostTitle, $rawPostText);
		if ($arrDbRes['result'] === false)
		{
			page_break($dbHandles, '500 Internal Server Error', $arrDbRes['payload']);
		}
		// Move to index.php
		page_move_to($dbHandles, $_SERVER['SCRIPT_NAME'] . '?get');
	}
}
else
{
	include 'tpl/post.tpl.php';
}
?>
