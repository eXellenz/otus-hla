<?php
/**
*	PHP | OTUS HLA | UTF8 | load_sql.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-03-06
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
define('ENDL',	chr(0x0D) . chr(0x0A));
define('ROOT',	'82832e53-7524-fa24-8519-b82f7d1d451f');

//====================================================================== DEPENDENCIES
require_once 'index.config.php';
require_once 'inc/tools.php';


//====================================================================== VARIABLES
$requestUri	= $_SERVER['REQUEST_URI'];
$time		= 0;
$curTime	= 0;
$cycleTime	= 30;
$mysqli		= null;
$sqlite		= null;
$result		= false;
$count		= 0;
$echo		= '';

//====================================================================== MAIN
// Run over CLI
if (empty($requestUri))
{
	$sqlite	= new SQLite3(SQLITE_DB, SQLITE3_OPEN_READWRITE);
	$result	= db_connect_local($mysqli);
	if ($result['result'] === false)
	{
		$echo	= $result['payload'] . ENDL;
		print $echo;
		file_put_contents('load_sql.error.log', $echo, FILE_APPEND);
	}
	else
	{
		// write test
	/*	$time		= time();
		$curTime	= time();
		$count		= 0;
		while ($curTime < ($time + $cycleTime))
		{
			$result	= db_add_record($mysqli);
			if ($result['result'] === false)
			{
				$echo	= $result['payload'] . ENDL;
				print $echo . PHP_EOL;
				file_put_contents('load_sql.error.log', $echo, FILE_APPEND);
			}

			$count ++;
			$curTime	= time();
		}*/
		// read mysql test
		$time		= time();
		$curTime	= time();
		$count		= 0;
		while ($curTime < ($time + $cycleTime))
		{
			$rnd	= rand(1, 4);
			$result	= db_get_session($mysqli, '', $rnd);
			if ($result['result'] === false)
			{
				$echo	= $result['payload'] . ENDL;
				print $echo . PHP_EOL;
				file_put_contents('load_sql.error.log', $echo, FILE_APPEND);
			}

			$count ++;
			$curTime	= time();
		}
		print 'mysql count: ' . $count . PHP_EOL;
		// read sqlite test
		$time		= time();
		$curTime	= time();
		$count		= 0;
		while ($curTime < ($time + $cycleTime))
		{
			$rnd	= rand(1, 4);
			$result	= sqlite_get_session($sqlite, '', $rnd);
			if ($result['result'] === false)
			{
				$echo	= $result['payload'] . ENDL;
				print $echo . PHP_EOL;
				file_put_contents('load_sql.error.log', $echo, FILE_APPEND);
			}

			$count ++;
			$curTime	= time();
		}
		print 'sqlite count: ' . $count . PHP_EOL;

		db_close_local($mysqli);
		sqlite_close($sqlite);
	}
}
else
{
	print 'Access denided.';
	exit(0);
}

prompt_ex('Press ENTER for exit ...');

//====================================================================== FUNCTIONS
function db_connect_local(&$mysqli)
{
	// Connecting to db
	$mysqli	= mysqli_connect(DB_MASTER_HOST . ':' . DB_MASTER_PORT, DB_USER, DB_PASS);
	$result	= mysqli_connect_errno();
	if ($result !== 0)
	{
		// Get mysqli error description
		$queryError	= mysqli_connect_error();
		// Close handle to db
		if ($mysqli) db_close_local($mysqli);
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
		if ($mysqli) db_close_local($mysqli);
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
		if ($mysqli) db_close_local($mysqli);
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
			if ($mysqli) db_close_local($mysqli);
			// Return error description
			return array('result' => false, 'payload' => 'Ошибка создания ['. DB_TABLE_TEST .']. Описание: '. $queryError);
		}
	}
	// Db request result freeing
	if (is_a($result, 'mysqli_result')) mysqli_free_result($result);
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function db_close_local(&$mysqli)
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
