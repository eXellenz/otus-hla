<?php
/**
*	PHP | OTUS HLA | UTF8 | load_sql.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-01-27
*/

//====================================================================== INIT
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Asia/Dubai');
ob_implicit_flush(true);

//====================================================================== PHP CUSTOM SETTINGS
ini_set('max_execution_time',		0);
ini_set('memory_limit',				'512M');
ini_set('date.timezone',			'Asia/Dubai');
ini_set('displaydbgs',				'On');
ini_set('display_startupdbgs',		'On');
ini_set('logdbgs',					'On');
ini_set('display_errors',			'On');
ini_set('display_startup_errors',	'On');
ini_set('log_errors',				'On');
ini_set('error_log',				'load_sql.error.log');

//====================================================================== CONSTANTS
define('ENDL',				chr(0x0D) . chr(0x0A));
define('DB_HOST',			'localhost');			// The host/ip to your SQL server
define('DB_PORT',			'3306');					// The SQL port (Default: 3306)
define('DB_USER',			'user');				// The username
define('DB_PASS',			'usersecret');		// The password
define('DB_NAME',			'otushlahwdb');				// Database name
define('DB_PREFIX',			'otushlahw_');				// The table prefix
define('DB_CHARSET',		'utf8');					// Database charset
define('DB_TABLE_TEST',		DB_PREFIX . 'test');		// Table name

//====================================================================== DEPENDENCIES


//====================================================================== VARIABLES
$requestUri	= $_SERVER['REQUEST_URI'];
$time		= time();
$curTime	= time();
$cycleTime	= 30;
$mysqli		= null;
$result		= false;
$echo		= '';

//====================================================================== MAIN
// Run over CLI
if (empty($requestUri))
{
	$result	= db_connect($mysqli);
	if ($result['result'] === false)
	{
		$echo	= $result['payload'] . ENDL;
		print $echo;
		file_put_contents('load_sql.error.log', $echo, FILE_APPEND);
	}
	else
	{
		while ($curTime < ($time + $cycleTime))
		{
			$result	= db_add_record($mysqli);
			if ($result['result'] === false)
			{
				$echo	= $result['payload'] . ENDL;
				print $echo;
				file_put_contents('load_sql.error.log', $echo, FILE_APPEND);
			}

			$curTime	= time();
		}

		db_close($mysqli);
	}
}
else
{
	print 'Access denided.';
	exit(0);
}

prompt_ex('Press ENTER for exit ...');

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
	// Check for DB_TABLE_TEST exist
	$myQuery	= "SELECT 1 FROM `". DB_TABLE_TEST ."` WHERE 0";
	$result		= mysqli_query($mysqli, $myQuery);
	// If table not exist
	if ($result === false)
	{
		// Request for create table
		$myQuery	= "CREATE TABLE `" . DB_TABLE_TEST . "` (" .
					"`rid` integer NULL AUTO_INCREMENT COMMENT 'Record ID'," .
					"`str` varchar(64) NULL COMMENT 'User name'," .
					"`int` integer NULL COMMENT 'User age'," .
					"PRIMARY KEY (rid)) ENGINE = INNODB;";
		$result		= mysqli_query($mysqli, $myQuery);
		if ($result === false)
		{
			// Get mysqli error description
			$queryError	= mysqli_error($mysqli);
			// Close handle to db
			if ($mysqli) db_close($mysqli);
			// Return error description
			return array('result' => false, 'payload' => 'Ошибка создания ['. DB_TABLE_TEST .']. Описание: '. $queryError);
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

function db_add_record($mysqli)
{
	$str		= '028051ec-3406-f7fa-3107-4e58c54e02d2';
	$int		= 65535;
	// Request for insert data to table
	$myQuery	= "INSERT INTO `" . DB_TABLE_TEST . "` (" .
				"`str`, " .
				"`int`" .
				") VALUES (" .
				"'" . $str . "', " .
				$int . ")";
	$result		= mysqli_query($mysqli, $myQuery);
	if ($result === false)
	{
		// Get mysqli error description
		$queryError	= mysqli_error($mysqli);
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка вставки данных в ['. DB_TABLE_TEST .']. Описание: '. $queryError);
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function prompt_ex($msg) 
{
	print $msg . ENDL;
	ob_flush();
	$in = fgets(fopen('php://stdin', 'r'), 1024);
	fclose(fopen('php://stdin', 'r'));
	exit();
}
?>
