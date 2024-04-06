<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/tools.sqlite.php
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
function sqlite_connect(&$mysqliArr)
{
	try
	{
		// Connect to sqlite db
		$mysqliArr['sqlite']	= new SQLite3(SQLITE_DB, SQLITE3_OPEN_READWRITE);
	}
	catch(Exception $ex)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка подключения к [' . SQLITE_DB . ']. Описание: ' . $ex->getMessage());
	}
}

function sqlite_close($sqlite)
{
	$sqlite->close();
}

function sqlite_escape_string($str)
{
	return str_replace(array('`', '\'', '[', ']'), '', $str);
}

function sqlite_set_session($sqlite, $uid, $timestamp, $token, $id = 0)
{
	// Request for insert or update data to table
	if ($id === 0)
	{
		$query	= 'INSERT INTO ' . DB_TABLE_SESSIONS . ' (' .
				'uid, ' .
				'timestamp, ' .
				'token' .
				') VALUES (' .
				intval($uid) . ', ' .
				"'" . $timestamp . "', " .
				"'" . $token . "'" .
				');';
	}
	else
	{
		$query	= 'UPDATE ' . DB_TABLE_SESSIONS . ' ' .
				'SET ' .
				'uid = ' . intval($uid) . ', ' .
				"timestamp = '" . $timestamp . "', " .
				"token = '" . $token . "' " .
				'WHERE id = ' . intval($id) . ';';
	}
	$result		= $sqlite->exec($query);
	if ($result === false)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка вставки данных в [' . DB_TABLE_SESSIONS . '] [' . SQLITE_DB . ']. Описание: ' . $sqlite->lastErrorMsg());
	}
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function sqlite_get_session($sqlite, $rawToken = '', $rawUid = '')
{
	$data	= array();
	$query	= 'SELECT id, uid, timestamp, token ' .
			'FROM ' . DB_TABLE_SESSIONS;
	// Check selection filter
	if (empty($rawToken) && empty($rawUid))
	{
		$query	.=  'ORDER BY id ASC';
	}
	else if (!empty($rawToken) && !empty($rawUid))
	{
		$query	.= " WHERE token = '" . sqlite_escape_string($rawToken) . "'" .
					' AND uid = ' . intval($rawUid);
	}
	else if (!empty($rawToken))
	{
		$query	.= " WHERE token = '" . sqlite_escape_string($rawToken) . "'";
	}
	else if (!empty($rawUid))
	{
		$query	.= ' WHERE uid = ' . intval($rawUid);
	}
	$query	.= ';';
	// Request for getting data from table
	$result		= $sqlite->query($query);
	if ($result === false)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка получения данных из [' . DB_TABLE_SESSIONS . '] [' . SQLITE_DB . ']. Описание: ' . $sqlite->lastErrorMsg());
	}
	// If data exist
	if($result->numColumns() > 0)
	{
		// Transform object data to associative array
		while ($row = $result->fetchArray())
		{
			$data[]	= $row;
		}
	}
	// Db request result freeing
	$result->finalize();
	// Return success
	return array('result' => true, 'payload' => $data);
}

function sqlite_init($mysqli)
{
	$arrDbRes	= array();
	$data		= array();
	$dataCnt	= 0;
	$result		= false;
	$sqlite		= null;
	$query		= '';
	// Connect to sqlite db
	try
	{
		$sqlite	= new SQLite3(SQLITE_DB, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
	}
	catch(Exception $ex)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка подключения к [' . SQLITE_DB . ']. Описание: ' . $ex->getMessage());
	}
	// Create user sessions table
	$query	= 'CREATE TABLE [' . DB_TABLE_SESSIONS . '] (' .
			'[id] INTEGER NOT NULL PRIMARY KEY,' .
			'[uid] INTEGER NULL,' .
			'[timestamp] VARCHAR(12) NULL,' .
			'[token] VARCHAR(36) NULL' .
			');';
	$result	= $sqlite->exec($query);
	if ($result === false)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка создания [' . DB_TABLE_SESSIONS . '] в [' . SQLITE_DB . ']. Описание: ' . $sqlite->lastErrorMsg());
	}
	// Get all data from sessions table
	$arrDbRes	= db_get_session($mysqli);
	if ($arrDbRes['result'] === false)
	{
		// Return error description
		return array('result' => false, 'payload' => $arrDbRes['payload']);
	}
	$data		= $arrDbRes['payload'];
	// Import data from mysql table to sqlite
	$dataCnt	= count($data);
	if ($dataCnt > 0)
	{
		for ($i = 0; $i < $dataCnt; $i ++)
		{
			$id			= intval($data[$i]['id']);
			$uid		= intval($data[$i]['uid']);
			$timestamp	= $data[$i]['timestamp'];
			$token		= $data[$i]['token'];
			$query		= 'INSERT INTO ' . DB_TABLE_SESSIONS . ' VALUES(' . $id . ', ' . $uid . ', \'' . $timestamp . '\', \'' . $token . '\');';
			$result		= $sqlite->exec($query);
			if ($result === false)
			{
				// Return error description
				return array('result' => false, 'payload' => 'Ошибка вставки в [' . DB_TABLE_SESSIONS . '] [' . SQLITE_DB . ']. Описание: ' . $sqlite->lastErrorMsg());
			}
		}
	}
	// Close connect to db
	$sqlite->close();
	// Return success
	return array('result' => true, 'payload' => 'done');
}

function sqlite_backup()
{
	$result		= false;
	// Connect to sqlite db
	try
	{
		$sqlite	= new SQLite3(SQLITE_DB, SQLITE3_OPEN_READWRITE);
	}
	catch(Exception $ex)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка подключения к [' . SQLITE_DB . ']. Описание: ' . $ex->getMessage());
	}
	// Connect or create sqlite backup db
	try
	{
		$backup	= new SQLite3(SQLITE_BACKUP, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
	}
	catch(Exception $ex)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка подключения к [' . SQLITE_BACKUP . ']. Описание: ' . $ex->getMessage());
	}
	// Init/Reinit table
	// Drop table if exist
	$query	= 'DROP TABLE IF EXISTS ' . DB_TABLE_SESSIONS . ';';
	$result	= $backup->exec($query);
	if ($result === false)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка удаления [' . DB_TABLE_SESSIONS . '] в [' . SQLITE_BACKUP . ']. Описание: ' . $backup->lastErrorMsg());
	}
	// Create table
	$query	= 'CREATE TABLE [' . DB_TABLE_SESSIONS . '] (' .
			'[id] INTEGER NOT NULL PRIMARY KEY,' .
			'[uid] INTEGER NULL,' .
			'[timestamp] VARCHAR(12) NULL,' .
			'[token] VARCHAR(36) NULL' .
			');';
	$result	= $backup->exec($query);
	if ($result === false)
	{
		// Return error description
		return array('result' => false, 'payload' => 'Ошибка создания [' . DB_TABLE_SESSIONS . '] в [' . SQLITE_BACKUP . ']. Описание: ' . $backup->lastErrorMsg());
	}
	// Close connect to backup db
	$backup->close();
	// Backup db
	$sqlite->exec("ATTACH DATABASE '" . SQLITE_BACKUP . "' AS backup;");
	$sqlite->exec('INSERT INTO backup.' . DB_TABLE_SESSIONS . ' SELECT * FROM ' . DB_TABLE_SESSIONS . ';');
	$sqlite->exec('DETACH DATABASE backup;');
	// Close connect to db
	$sqlite->close();
	// Return success
	return array('result' => true, 'payload' => 'done');
}
?>