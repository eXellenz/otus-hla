<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/tools.db.php
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
function db_connect(&$mysqliArr)
{
	$host	= '';
	$port	= '';

	foreach ($mysqliArr as $key => $value)
	{
		switch ($key)
		{
			case 'write':
			{
				$host	= DB_MASTER_HOST;
				$port	= DB_MASTER_PORT;
				break;
			}
			case 'read':
			{
				$host	= DB_SLAVE_HOST;
				$port	= DB_SLAVE_PORT;
				break;
			}
			case 'sqlite':
			{
				// nothing
				break;
			}
			default:
			{
				return array('result' => false, 'payload' => 'Неизвестный тип БД: ' . $key);
			}
		}
		// Connecting to db
		$mysqliArr[$key]	= mysqli_connect($host . ':' . $port, DB_USER, DB_PASS);
		$result				= mysqli_connect_errno();
		if ($result !== 0)
		{
			// Get mysqli error description
			$queryError	= mysqli_connect_error();
			// Close handle to db
			db_close($mysqliArr);
			// Return error description
			return array('result' => false, 'payload' => 'Ошибка подключения к [' . $key . '] БД. Описание: '. $queryError);
		}
		// Charset setting
		$result	= mysqli_set_charset($mysqliArr[$key], DB_CHARSET);
		if ($result === false)
		{
			// Get mysqli error description
			$queryError	= mysqli_error($mysqliArr[$key]);
			// Close handle to db
			db_close($mysqliArr);
			// Return error description
			return array('result' => false, 'payload' => 'Ошибка установка кодировки [' . $key . '] БД. Описание: '. $queryError);
		}
		// db select
		$result	= mysqli_select_db($mysqliArr[$key], DB_NAME);
		if ($result === false)
		{
			// Get mysqli error description
			$queryError	= mysqli_error($mysqliArr[$key]);
			// Close handle to db
			db_close($mysqliArr);
			// Return error description
			return array('result' => false, 'payload' => 'Ошибка подключения к [' . $key . ']['. DB_NAME .']. Описание: '. $queryError);
		}
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_close(&$mysqliArr)
{
	foreach ($mysqliArr as $key => $value)
	{
		if ($mysqliArr[$key])
		{
			if ($key === 'sqlite')
			{
				sqlite_close($mysqliArr[$key]);
			}
			else
			{
				mysqli_close($mysqliArr[$key]);
			}

			$mysqliArr[$key]	= null;
		}
	}
}

function db_table_init($mysqli)
{
	// Init table list
	$dbTableList	= array(
						DB_TABLE_USERS,
						DB_TABLE_SESSIONS,
						DB_TABLE_FRIENDS,
						DB_TABLE_POSTS,
						DB_TABLE_DIALOGS
				);
	$dbTableListCnt	= count($dbTableList);
	// Table list walker
	for ($i = 0; $i < $dbTableListCnt; $i ++)
	{
		$myQueryCheck	= '';
		$myQueryCreate	= '';
		$dbName			= $dbTableList[$i];
		// Create query string
		switch($dbName)
		{
			case DB_TABLE_USERS:
			{
				$myQueryCreate	= "CREATE TABLE `" . $dbName . "` (" .
								"`uid` integer NOT NULL AUTO_INCREMENT COMMENT 'User ID'," .
								"`login` varchar(64) NULL COMMENT 'User name'," .
								"`password` varchar(64) NULL COMMENT 'User lastname'," .
								"`name` varchar(64) NULL COMMENT 'User name'," .
								"`lastname` varchar(64) NULL COMMENT 'User lastname'," .
								"`age` integer NULL COMMENT 'User age'," .
								"`gender` varchar(12) NULL COMMENT 'User gender'," .
								"`city` varchar(64) NULL COMMENT 'User city'," .
								"`about` varchar(256) NULL COMMENT 'About user'," .
								"PRIMARY KEY (uid)) ENGINE = INNODB;";
				break;
			}
			case DB_TABLE_SESSIONS:
			{
				$myQueryCreate	= "CREATE TABLE `" . $dbName . "` (" .
								"`id` integer NOT NULL AUTO_INCREMENT COMMENT 'ID записи'," .
								"`uid` integer NULL COMMENT 'User ID'," .
								"`timestamp` varchar(12) NULL COMMENT 'Session timestamp'," .
								"`token` varchar(36) NULL COMMENT 'Session token'," .
								"PRIMARY KEY (id)) ENGINE = INNODB;";
				break;
			}
			case DB_TABLE_FRIENDS:
			{
				$myQueryCreate	= "CREATE TABLE `" . $dbName . "` (" .
								"`id` integer NOT NULL AUTO_INCREMENT COMMENT 'ID записи'," .
								"`timestamp` varchar(12) NULL COMMENT 'Add timestamp'," .
								"`uid` integer NULL COMMENT 'User ID'," .
								"`fid` integer NULL COMMENT 'Friend ID'," .
								"PRIMARY KEY (id)) ENGINE = INNODB;";
				break;
			}
			case DB_TABLE_POSTS:
			{
				$myQueryCreate	= "CREATE TABLE `" . $dbName . "` (" .
								"`id` integer NOT NULL AUTO_INCREMENT COMMENT 'ID записи'," .
								"`timestamp` varchar(12) NULL COMMENT 'Add timestamp'," .
								"`uid` integer NULL COMMENT 'User ID'," .
								"`title` varchar(128) NULL COMMENT 'Post title'," .
								"`post` text NULL COMMENT 'Post content'," .
								"PRIMARY KEY (id)) ENGINE = INNODB;";
				break;
			}
			case DB_TABLE_DIALOGS:
			{
				$myQueryCreate	= "CREATE TABLE `" . $dbName . "` (" .
								"`id` integer NOT NULL AUTO_INCREMENT COMMENT 'ID записи'," .
								"`timestamp` varchar(12) NULL COMMENT 'Add timestamp'," .
								"`uid` integer NULL COMMENT 'User ID'," .
								"`tid` integer NULL COMMENT 'Target ID'," .
								"`message` text NULL COMMENT 'Message content'," .
								"PRIMARY KEY (id)) ENGINE = INNODB;";
				break;
			}
			default:
			{
				return array('result' => false, 'payload' => 'Ошибка проверки ['. $dbName .']. Описание: Имя БД не известно.');
			}
		}
		// Check for exist
		$myQueryCheck	= "SELECT 1 FROM `". $dbName ."` WHERE 0";
		$result			= mysqli_query($mysqli, $myQueryCheck);
		// If table not exist
		if ($result === false)
		{
			// Request for create table
			$result		= mysqli_query($mysqli, $myQueryCreate);
			if ($result === false)
			{
				// Get mysqli error description
				$queryError	= mysqli_error($mysqli);
				// Close handle to db
				if ($mysqli) db_close($mysqli);
				// Return error description
				return array('result' => false, 'payload' => 'Ошибка создания ['. $dbName .']. Описание: '. $queryError);
			}
		}
		// Db request result freeing
		if (is_a($result, 'mysqli_result'))
		{
			mysqli_free_result($result);
		}
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_add_user($mysqli, $rawLogin, $rawPassword, $rawName, $rawLastname, $rawAge, $rawGender, $rawCity, $rawAbout)
{
	$login		= mysqli_real_escape_string($mysqli, $rawLogin);
	$password	= md5($rawPassword);
	$name		= mysqli_real_escape_string($mysqli, $rawName);
	$lastname	= mysqli_real_escape_string($mysqli, $rawLastname);
	$age		= intval($rawAge);
	$gender		= mysqli_real_escape_string($mysqli, $rawGender);
	$city		= mysqli_real_escape_string($mysqli, $rawCity);
	$about		= mysqli_real_escape_string($mysqli, $rawAbout);
	// Request for insert data to table
	$myQuery	= "INSERT INTO `" . DB_TABLE_USERS . "` (" .
				"`login`, " .
				"`password`, " .
				"`name`, " .
				"`lastname`, " .
				"`age`, " .
				"`gender`, " .
				"`city`, " .
				"`about`" .
				") VALUES (" .
				"'" . $login . "', " .
				"'" . $password . "', " .
				"'" . $name . "', " .
				"'" . $lastname . "', " .
				$age . ", " .
				"'" . $gender . "', " .
				"'" . $city . "', " .
				"'" . $about . "'" .
				")";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка вставки данных в ['. DB_TABLE_USERS .']. Описание: '. $queryError);
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_get_user_by_login($mysqli, $rawLogin)
{
	$data	= array();
	$login	= mysqli_real_escape_string($mysqli, $rawLogin);
	// Request for getting data from table
	$myQuery	= "SELECT `uid`, `login`, `password`, `name`, `lastname`, `age`, `gender`, `city`, `about` " .
				"FROM `". DB_TABLE_USERS ."` " .
				"WHERE `login` = '" . $login . "' " .
				"ORDER BY `uid`";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_USERS .']. Описание: '. $queryError);
	}
	// If data exist
	if($result->num_rows > 0)
	{
		// Transform object data to associative array
		while (true)
		{
			$row	= mysqli_fetch_assoc($result);
			if ($row)
			{
				$data[]	= $row;
			}
			else
			{
				break;
			}
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => $data);
}

function db_get_user_by_uid($mysqli, $rawUid)
{
	$data	= array();
	// Request for getting data from table
	if (is_array($rawUid))
	{
		$uidCnt	= count($rawUid);
		if ($uidCnt > 0)
		{
			$myQuery	= "SELECT `uid`, `login`, `password`, `name`, `lastname`, `age`, `gender`, `city`, `about` " .
						"FROM `". DB_TABLE_USERS ."` " .
						"WHERE ";
			for ($i = 0; $i < $uidCnt; $i ++)
			{
				$uid	= mysqli_real_escape_string($mysqli, $rawUid[$i]);
				if ($i == 0)
				{
					$myQuery	.= "`uid` = '" . $uid . "' ";
				}
				else
				{
					$myQuery	.= "OR `uid` = '" . $uid . "' ";
				}
			}
			$myQuery	.= "ORDER BY `uid`";
		}
		else
		{
			// Return error description
			return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_USERS .']. Описание: Не передан User ID.');
		}
	}
	else
	{
		$uid		= mysqli_real_escape_string($mysqli, $rawUid);
		$myQuery	= "SELECT `uid`, `login`, `password`, `name`, `lastname`, `age`, `gender`, `city`, `about` " .
					"FROM `". DB_TABLE_USERS ."` " .
					"WHERE `uid` = '" . $uid . "' " .
					"ORDER BY `uid`";
	}
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_USERS .']. Описание: '. $queryError);
	}
	// If data exist
	if($result->num_rows > 0)
	{
		// Transform object data to associative array
		while (true)
		{
			$row	= mysqli_fetch_assoc($result);
			if ($row)
			{
				$data[]	= $row;
			}
			else
			{
				break;
			}
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => $data);
}

function db_get_user_by_name_or_lastname($mysqli, $str)
{
	// Check $str lenght
	if (mb_strlen($str) < 3)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_USERS .']. Описание: The request is too short. 3 and more symbols needed.');
	}
	// Variable declaration
	$data	= array();
	$search	= mysqli_real_escape_string($mysqli, $str);
	// Request for getting data from table
	$myQuery	= "SELECT `uid`, `login`, `password`, `name`, `lastname`, `age`, `gender`, `city`, `about` " .
				"FROM `". DB_TABLE_USERS ."` " .
				"WHERE `name` LIKE '" . $search . "%' OR `lastname` LIKE '" . $search . "%' " .
				"ORDER BY `uid` " .
				"LIMIT 100";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_USERS .']. Описание: '. $queryError);
	}
	// If data exist
	if($result->num_rows > 0)
	{
		// Transform object data to associative array
		while (true)
		{
			$row	= mysqli_fetch_assoc($result);
			if ($row)
			{
				$data[]	= $row;
			}
			else
			{
				break;
			}
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => $data);
}

function db_set_session($mysqli, $uid, $timestamp, $token, $id = 0)
{
	// Request for insert or update data to table
	if ($id === 0)
	{
		$myQuery	= "INSERT INTO `" . DB_TABLE_SESSIONS . "` (" .
					"`uid`, " .
					"`timestamp`, " .
					"`token`" .
					") VALUES (" .
					"'" . $uid . "', " .
					"'" . $timestamp . "', " .
					"'" . $token . "'" .
					")";
	}
	else
	{
		$myQuery	= "UPDATE `" . DB_TABLE_SESSIONS . "` " .
					"SET " .
					"`uid` = '" . $uid . "', " .
					"`timestamp` = '" . $timestamp . "', " .
					"`token` = '" . $token . "' " .
					"WHERE `id` = " . $id;
	}
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка вставки данных в ['. DB_TABLE_SESSIONS .']. Описание: '. $queryError);
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_get_session($mysqli, $rawToken = '', $rawUid = '')
{
	$data		= array();
	$myQuery	= "SELECT `id`, `uid`, `timestamp`, `token` " .
				"FROM `" . DB_TABLE_SESSIONS . "`";
	// Check selection filter
	if (empty($rawToken) && empty($rawUid))
	{
		$myQuery	.= " ORDER BY `id` ASC";
	}
	else if (!empty($rawToken) && !empty($rawUid))
	{
		$myQuery	.= " WHERE `token` = '" . mysqli_real_escape_string($mysqli, $rawToken) . "'" .
					" AND `uid` = '" . mysqli_real_escape_string($mysqli, $rawUid) . "'";
	}
	else if (!empty($rawToken))
	{
		$myQuery	.= " WHERE `token` = '" . mysqli_real_escape_string($mysqli, $rawToken) . "'";
	}
	else if (!empty($rawUid))
	{
		$myQuery	.= " WHERE `uid` = '" . mysqli_real_escape_string($mysqli, $rawUid) . "'";
	}
	// Request for getting data from table
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_SESSIONS .']. Описание: '. $queryError);
	}
	// If data exist
	if($result->num_rows > 0)
	{
		// Transform object data to associative array
		while (true)
		{
			$row	= mysqli_fetch_assoc($result);
			if ($row)
			{
				$data[]	= $row;
			}
			else
			{
				break;
			}
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => $data);
}

function db_get_session_by_token($mysqli, $rawToken)
{
	$token	= mysqli_real_escape_string($mysqli, $rawToken);
	$data	= array();
	// Request for getting data from table
	$myQuery	= "SELECT `id`, `uid`, `timestamp`, `token` " .
				"FROM `". DB_TABLE_SESSIONS ."` " .
				"WHERE `token` = '" . $token . "' " .
				"ORDER BY `uid`";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_SESSIONS .']. Описание: '. $queryError);
	}
	// If data exist
	if($result->num_rows > 0)
	{
		// Transform object data to associative array
		while (true)
		{
			$row	= mysqli_fetch_assoc($result);
			if ($row)
			{
				$data[]	= $row;
			}
			else
			{
				break;
			}
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => $data);
}

function db_get_friend_by_uid($mysqli, $rawUid, $rawFid = '')
{
	$uid	= mysqli_real_escape_string($mysqli, $rawUid);
	$data	= array();
	// Request for getting data from table
	$myQuery	= "SELECT `fid` " .
				"FROM `". DB_TABLE_FRIENDS ."` " .
				"WHERE `uid` = '" . $uid . "' " .
				(empty($rawFid) ? '' : "AND `fid` = '" . mysqli_real_escape_string($mysqli, $rawFid) . "' ") .
				"ORDER BY `fid`";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_FRIENDS .']. Описание: '. $queryError);
	}
	// If data exist
	if($result->num_rows > 0)
	{
		// Transform object data to associative array
		while (true)
		{
			$row	= mysqli_fetch_assoc($result);
			if ($row)
			{
				$data[]	= $row;
			}
			else
			{
				break;
			}
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => $data);
}

function db_add_friend_to_uid($mysqli, $rawUid, $rawFid)
{
	// Check for friend exist
	$dbRes	= db_get_friend_by_uid($mysqli, $rawUid, $rawFid);
	// Check result
	if ($dbRes['result'] === false)
	{
		return $dbRes;
	}
	// Calculate result
	$count	= count($dbRes['payload']);
	if ($count > 0)
	{
		return array('result' => false, 'payload' => 'Ошибка добавления друга. Описание: Друг уже добавлен.');
	}
	// Check for user exist
	$dbRes	= db_get_user_by_uid($mysqli, $rawFid);
	// Check result
	if ($dbRes['result'] === false)
	{
		return $dbRes;
	}
	// Calculate result
	$count	= count($dbRes['payload']);
	if ($count === 0)
	{
		return array('result' => false, 'payload' => 'Ошибка добавления друга. Описание: Пользователь не существует.');
	}
	// Add friend
	$myQuery	= "INSERT INTO `" . DB_TABLE_FRIENDS . "` (" .
					"`timestamp`, " .
					"`uid`, " .
					"`fid`" .
					") VALUES (" .
					"'" . time() . "', " .
					"'" . mysqli_real_escape_string($mysqli, $rawUid) . "', " .
					"'" . mysqli_real_escape_string($mysqli, $rawFid) . "'" .
					")";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка вставки данных в ['. DB_TABLE_FRIENDS .']. Описание: '. $queryError);
	}
	// Update cache record
	$dbRes	= cache_update_friends($mysqli, $rawUid);
	// Check result
	if ($dbRes['result'] === false)
	{
		return $dbRes;
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_delete_friend_from_uid($mysqli, $rawUid, $rawFid)
{
	// Check for friend exist
	$dbRes	= db_get_friend_by_uid($mysqli, $rawUid, $rawFid);
	// Check result
	if ($dbRes['result'] === false)
	{
		return $dbRes;
	}
	// Calculate result
	$count	= count($dbRes['payload']);
	if ($count === 0)
	{
		return array('result' => false, 'payload' => 'Ошибка добавления друга. Описание: Друг не существует.');
	}
	// Delete friend
	$myQuery	= "DELETE FROM `" . DB_TABLE_FRIENDS . "` " .
				"WHERE `uid` = '" . mysqli_real_escape_string($mysqli, $rawUid) . "' AND `fid` = '" . mysqli_real_escape_string($mysqli, $rawFid) . "'";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка удаления данных из ['. DB_TABLE_FRIENDS .']. Описание: '. $queryError);
	}
	// Update cache record
	$dbRes	= cache_update_friends($mysqli, $rawUid);
	// Check result
	if ($dbRes['result'] === false)
	{
		return $dbRes;
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_add_post_by_uid($mysqli, $uid, $rawTitle, $rawText)
{
	// Add post
	$myQuery	= "INSERT INTO `" . DB_TABLE_POSTS . "` (" .
					"`timestamp`, " .
					"`uid`, " .
					"`title`, " .
					"`post`" .
					") VALUES (" .
					"'" . time() . "', " .
					"'" . $uid . "', " .
					"'" . mysqli_real_escape_string($mysqli, $rawTitle) . "', " .
					"'" . mysqli_real_escape_string($mysqli, $rawText) . "'" .
					")";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка вставки данных в ['. DB_TABLE_POSTS .']. Описание: '. $queryError);
	}
	// Send event to RabbitMQ
//	$dbRes	= ;
	// Check result
//	if ($dbRes['result'] === false)
//	{
//		return $dbRes;
//	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_get_post_by_uid($mysqli, $rawUid, $rawId = '')
{
	$uid	= mysqli_real_escape_string($mysqli, $rawUid);
	$data	= array();
	// Request for getting data from table
	$myQuery	= "SELECT `id`, `timestamp`, `uid`, `title`, `post` " .
				"FROM `". DB_TABLE_POSTS ."` " .
				"WHERE `uid` = '" . $uid . "' " .
				(empty($rawId) ? '' : "AND `id` = '" . mysqli_real_escape_string($mysqli, $rawId) . "' ") .
				"ORDER BY `id` DESC " .
				"LIMIT 10";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. DB_TABLE_POSTS .']. Описание: '. $queryError);
	}
	// If data exist
	if($result->num_rows > 0)
	{
		// Transform object data to associative array
		while (true)
		{
			$row	= mysqli_fetch_assoc($result);
			if ($row)
			{
				$data[]	= $row;
			}
			else
			{
				break;
			}
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => $data);
}
?>