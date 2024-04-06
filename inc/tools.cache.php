<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/tools.cache.php
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

//====================================================================== FUNCTIONS
function cache_get_friends($mysqli, $uid)
{
	$key		= 'otus-hla-friends-' . $uid;
	$time		= time();
	// cache record exist check
	$keyInfo	= apcu_key_info($key);
	// record not exist
	if ($keyInfo === null)
	{
		$dbRes	= cache_update_friends($mysqli, $uid);
	}
	else
	{
		$lifetime	= $keyInfo['creation_time'] + $keyInfo['ttl'];
		if ($time >= $lifetime)
		{
			$dbRes	= cache_update_friends($mysqli, $uid);
		}
	}
	// Check result
	if ($dbRes['result'] === false)
	{
		return array();
	}
	// Cached record return
	return apcu_fetch($key);
}

function cache_update_friends($mysqli, $uid)
{
	$data		= array();
	$dbRes		= array();
	$key		= 'otus-hla-friends-' . $uid;
	$ttl		= 3600 * 24;
	// Get Friend ID for User ID
	$dbRes	= db_get_friend_by_uid($mysqli, $uid);
	// Check result
	if ($dbRes['result'] === false)
	{
		return $dbRes;
	}
	if (count($dbRes['payload']) > 0)
	{
		// Create Friend ID list array
		foreach ($dbRes['payload'] as $value)
		{
			$data[]	= $value['fid'];
		}
		// Get user data for Friend ID list array
		$dbRes	= db_get_user_by_uid($mysqli, $data);
		// Check result
		if ($dbRes['result'] === false)
		{
			return $dbRes;
		}
		// Create friends list array
		$data	= array();
		foreach ($dbRes['payload'] as $value)
		{
			$data[$value['uid']]	= $value['lastname'] . ' ' . $value['name'];
		}
		// Store friends list array to cache
		apcu_store($key, $data, $ttl);
	}
	else
	{
		return array('result' => false, 'payload' => 'none');
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}
?>