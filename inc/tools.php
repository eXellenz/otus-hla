<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/tools.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-01-27
*/

//====================================================================== CHECK
if (!defined('ROOT'))
{
	print 'Access denided.';
	exit(0);
}

//====================================================================== FUNCTIONS
function db_connect(&$mysqli)
{
	// Connecting to db
	$mysqli	= mysqli_connect(DB_HOST . ':' . DB_PORT, DB_USER, DB_PASS);
	$result	= mysqli_connect_errno();
	if ($result !== 0)
	{
		// Get mysqli error description
		$queryError	= mysqli_connect_error();
		// Close handle to db
		if ($mysqli) db_close($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка подключения к БД. Описание: '. $queryError);
	}
	// Charset setting
	$result	= mysqli_set_charset($mysqli, DB_CHARSET);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Close handle to db
		if ($mysqli) db_close($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка установка кодировки БД. Описание: '. $queryError);
	}
	// db select
	$result	= mysqli_select_db($mysqli, DB_NAME);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Close handle to db
		if ($mysqli) db_close($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка подключения к ['. DB_NAME .']. Описание: '. $queryError);
	}
	// Check for DB_TABLE_USERS exist
	$myQuery	= "SELECT 1 FROM `". DB_TABLE_USERS ."` WHERE 0";
	$result		= mysqli_query($mysqli, $myQuery);
	// If table not exist
	if ($result === false)
	{
		// Request for create table
		$myQuery	= "CREATE TABLE `" . DB_TABLE_USERS . "` (" .
					"`uid` integer NULL AUTO_INCREMENT COMMENT 'User ID'," .
					"`login` varchar(64) NULL COMMENT 'User name'," .
					"`password` varchar(64) NULL COMMENT 'User lastname'," .
					"`name` varchar(64) NULL COMMENT 'User name'," .
					"`lastname` varchar(64) NULL COMMENT 'User lastname'," .
					"`age` integer NULL COMMENT 'User age'," .
					"`gender` varchar(12) NULL COMMENT 'User gender'," .
					"`city` varchar(64) NULL COMMENT 'User city'," .
					"`about` varchar(256) NULL COMMENT 'About user'," .
					"PRIMARY KEY (uid)) ENGINE = INNODB;";
		$result		= mysqli_query($mysqli, $myQuery);
		if ($result === false)
		{
			// Get mysqli error description
			$queryError	= mysqli_error($mysqli);
			// Close handle to db
			if ($mysqli) db_close($mysqli);
			// Return error description
			return array('result' => false, 'payload' => 'Ошибка создания ['. DB_TABLE_USERS .']. Описание: '. $queryError);
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Check for DB_TABLE_SESSIONS exist
	$myQuery	= "SELECT 1 FROM `". DB_TABLE_SESSIONS ."` WHERE 0";
	$result		= mysqli_query($mysqli, $myQuery);
	// If table not exist
	if ($result === false)
	{
		// Request for create table
		$myQuery	= "CREATE TABLE `" . DB_TABLE_SESSIONS . "` (" .
					"`id` integer NULL AUTO_INCREMENT COMMENT 'ID записи'," .
					"`uid` integer NULL COMMENT 'User ID'," .
					"`timestamp` varchar(12) NULL COMMENT 'Session timestamp'," .
					"`token` varchar(36) NULL COMMENT 'Session token'," .
					"PRIMARY KEY (id)) ENGINE = MYISAM;";
		$result		= mysqli_query($mysqli, $myQuery);
		if ($result === false)
		{
			// Get mysqli error description
			$queryError	= mysqli_error($mysqli);
			// Close handle to db
			if ($mysqli) db_close($mysqli);
			// Return error description
			return array('result' => false, 'payload' => 'Ошибка создания ['. DB_TABLE_SESSIONS .']. Описание: '. $queryError);
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_close(&$mysqli)
{
	mysqli_close($mysqli);
	$mysqli	= null;
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

function db_get_user_by_uid($mysqli, $uid)
{
	$data	= array();
	// Request for getting data from table
	$myQuery	= "SELECT `uid`, `login`, `password`, `name`, `lastname`, `age`, `gender`, `city`, `about` " .
				"FROM `". DB_TABLE_USERS ."` " .
				"WHERE `uid` = '" . $uid . "' " .
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

function db_get_user_by_name_n_lastname($mysqli, $str)
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
				"WHERE `name` LIKE '" . $search . "%' AND `lastname` LIKE '" . $search . "%' " .
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

function db_get_session_by_uid($mysqli, $uid)
{
	$data	= array();
	// Request for getting data from table
	$myQuery	= "SELECT `id`, `uid`, `timestamp`, `token` " .
				"FROM `". DB_TABLE_SESSIONS ."` " .
				"WHERE `uid` = " . $uid . " " .
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

function db_delete_by_id($id, $tableName)
{
	// Check for data exist
	$myQuery	= "SELECT id FROM `" . $tableName . "` WHERE `id` = " . $id;
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из ['. $tableName .']. Описание: '. $queryError);
	}
	// If data exist
	if($result->num_rows > 0)
	{
		// Db request result freeing
		if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
		// Request for deleting data from table
		$myQuery	= "DELETE FROM `" . $tableName . "` WHERE `id` = " . $id;
		$result		= mysqli_query($mysqli, $myQuery);
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function page_move_to(&$mysqli, $url)
{
	// Close handle to db
	if ($mysqli) db_close($mysqli);
	// Move to $url
	header('Location: ' . $url);
}

function page_break(&$mysqli, $httpFullCode, $message)
{
	$responseHdr	= array(
							$_SERVER['SERVER_PROTOCOL'] . ' ' . $httpFullCode,
							'Content-Encoding: none',
							'Content-Type: text/html; charset=utf-8'
					);
	$responseMsg	= $message;
	// Close handle to db
	if ($mysqli) db_close($mysqli);
	// Response to client and close connecting
	response_and_close_connect($responseMsg, $responseHdr);
	// Exit code proccess
	exit(0);
}

function get_unique()
{
	return rnd_str(8, 'eng', 'nh') . '-' . rnd_str(4, 'eng', 'nh') . '-' . rnd_str(4, 'eng', 'nh') . '-' . rnd_str(4, 'eng', 'nh') . '-' . rnd_str(12, 'eng', 'nh');
}
?>