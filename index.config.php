<?php
/**
*	PHP | OTUS HLA | UTF8 | index.config.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2023-12-25
*/

//====================================================================== CHECK
if (!defined('ROOT'))
{
	print 'Access denided.';
	exit(0);
}

//====================================================================== CONSTANTS
define('DB_HOST',			'localhost');				// The host/ip to your SQL server
define('DB_PORT',			'3306');					// The SQL port (Default: 3306)
define('DB_USER',			'user');				// The username
define('DB_PASS',			'usersecret');		// The password
define('DB_NAME',			'otushlahwdb');				// Database name
define('DB_PREFIX',			'otushlahw_');				// The table prefix
define('DB_CHARSET',		'utf8');					// Database charset
define('DB_TABLE_USERS',	DB_PREFIX . 'users');		// Table name
define('DB_TABLE_SESSIONS',	DB_PREFIX . 'sessions');	// Table name
?>