<?php
/**
*	PHP | OTUS HLA | UTF8 | api.config.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-02-24
*/

//====================================================================== CHECK
if (!defined('ROOT'))
{
	print 'Access denided.';
	exit(0);
}

//====================================================================== CONSTANTS
define('INSTALL',			false);	// First init
define('DB_MASTER_HOST',	'localhost');				// The host/ip to your SQL server
define('DB_MASTER_PORT',	'3306');					// The SQL port (Default: 3306)
define('DB_SLAVE_HOST',		'localhost');				// The host/ip to your SQL server
define('DB_SLAVE_PORT',		'3307');					// The SQL port (Default: 3306)
define('DB_USER',			'user');					// The username
define('DB_PASS',			'usersecret');				// The password
define('DB_NAME',			'otushlahwdb');				// Database name
define('DB_PREFIX',			'otushlahw_');				// The table prefix
define('DB_CHARSET',		'utf8');					// Database charset
define('DB_TABLE_USERS',	DB_PREFIX . 'users');		// Table name
define('DB_TABLE_SESSIONS',	DB_PREFIX . 'sessions');	// Table name
define('DB_TABLE_FRIENDS',	DB_PREFIX . 'friends');		// Table name
define('DB_TABLE_POSTS',	DB_PREFIX . 'posts');		// Table name
define('DB_TABLE_DIALOGS',	DB_PREFIX . 'dialogs');		// Table name
define('MQTT_HOST',			'localhost');	// The host/ip to your MQTT server
define('MQTT_PORT',			'1883');		// The MQTT server port (Default: 1883)
define('MQTT_USER',			'');			// The username
define('MQTT_PASS',			'');			// The password
define('SQLITE_USE',		true);										// Using SQLite?
define('SQLITE_NAME',		'otushlahwdb.sqlite');						// SQLite database name
define('SQLITE_DB',			'R:/sqlite/db/' . SQLITE_NAME);				// SQLite database path
define('SQLITE_BACKUP',		'C:/APP/SQLite/backup/db/' . SQLITE_NAME);	// SQLite database backup path
?>