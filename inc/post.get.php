<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/post.get.php
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
$arrDbRes	= db_get_post_by_uid($dbHandles['read'], (!empty($_GET['uid']) ? $_GET['uid'] : $userId), (!empty($_GET['id']) ? $_GET['id'] : ''));
if ($arrDbRes['result'] === false)
{
	page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
}
echo '<pre>' . ENDL . print_r($arrDbRes['payload'], true) . ENDL . '</pre>';
?>
