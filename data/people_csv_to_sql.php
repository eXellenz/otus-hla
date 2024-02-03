<?php
/**
*	PHP | OTUS HLA | UTF8 | people_csv_to_sql.php
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
ini_set('error_log',				'people_csv_to_sql.error.log');

//====================================================================== CONSTANTS
define('ENDL',	chr(0x0D) . chr(0x0A));

//====================================================================== DEPENDENCIES


//====================================================================== VARIABLES
$requestUri		= $_SERVER['REQUEST_URI'];
$csvNameSrc		= 'people.csv';	// https://raw.githubusercontent.com/OtusTeam/highload/master/homework/people.csv
$csvArray		= array();
$csvArrayCnt	= 0;
$csvName		= 'otushlahw_users.add.csv';
$sqlName		= 'otushlahw_users.add.sql';
$myQueryArray	= array();
$myQuery		= "INSERT INTO `otushlahw_users` (" .
				"`login`, " .
				"`password`, " .
				"`name`, " .
				"`lastname`, " .
				"`age`, " .
				"`gender`, " .
				"`city`, " .
				"`about`" .
				") VALUES (" .
				"'%s', " .
				"'%s', " .
				"'%s', " .
				"'%s', " .
				"%d, " .
				"'%s', " .
				"'%s', " .
				"'%s'" .
				")";
$csvStr			= '"%s","%s","%s","%s","%d","%s","%s","%s"';
$userPassword	= 'c3f9706602801cffec2cfb246bee0a85';
$userGender		= 'VARIOUS';
$userAbout		= 'Lorem ipsum dolor sit amet.';

//====================================================================== MAIN
// Run over CLI
if (empty($requestUri))
{
	// Read csv data
	print 'Read csv data...' . ENDL;
	$csvArray		= file($csvNameSrc);
	$csvArrayCnt	= count($csvArray);
	// Proccess csv data
	print 'Proccess csv data...' . ENDL;
	for ($i = 0; $i < $csvArrayCnt; $i ++)
	{
		$j				= 0;
		$rowArray		= explode(',', $csvArray[$i]);
		$fullName		= trim($rowArray[0]);
		$nameArray		= explode(' ', $fullName);
		$userName		= $nameArray[1];
		$userLastname	= $nameArray[0];
		$userLoginOld	= translit($userLastname) . '.' . translit($userName);
		$userLogin		= $userLoginOld;
		$userAge		= intval($rowArray[1]);
		$userCity		= trim($rowArray[2]);

		while (array_key_exists($userLogin, $myQueryArray))
		{
			$j ++;
			$userLogin	= $userLoginOld . '.' . $j;
		}

		$myQueryArray[$userLogin]	= true;

		$index ++;

		file_put_contents($sqlName, sprintf($myQuery, $userLogin, $userPassword, $userName, $userLastname, $userAge, $userGender, $userCity, $userAbout) . ENDL, FILE_APPEND);
		file_put_contents($csvName, sprintf($csvStr, $userLogin, $userPassword, $userName, $userLastname, $userAge, $userGender, $userCity, $userAbout) . ENDL, FILE_APPEND);
	}
}
else
{
	print 'Access denided.';
	exit(0);
}

prompt_ex('Press ENTER for exit ...');
//====================================================================== FUNCTIONS
function translit($value)
{
	$converter	= array(
		'а' => 'a',		'б' => 'b',		'в' => 'v',		'г' => 'g',		'д' => 'd',
		'е' => 'e',		'ё' => 'e',		'ж' => 'zh',	'з' => 'z',		'и' => 'i',
		'й' => 'y',		'к' => 'k',		'л' => 'l',		'м' => 'm',		'н' => 'n',
		'о' => 'o',		'п' => 'p',		'р' => 'r',		'с' => 's',		'т' => 't',
		'у' => 'u',		'ф' => 'f',		'х' => 'h',		'ц' => 'c',		'ч' => 'ch',
		'ш' => 'sh',	'щ' => 'sch',	'ь' => '',		'ы' => 'y',		'ъ' => '',
		'э' => 'e',		'ю' => 'yu',	'я' => 'ya',

		'А' => 'A',		'Б' => 'B',		'В' => 'V',		'Г' => 'G',		'Д' => 'D',
		'Е' => 'E',		'Ё' => 'E',		'Ж' => 'Zh',	'З' => 'Z',		'И' => 'I',
		'Й' => 'Y',		'К' => 'K',		'Л' => 'L',		'М' => 'M',		'Н' => 'N',
		'О' => 'O',		'П' => 'P',		'Р' => 'R',		'С' => 'S',		'Т' => 'T',
		'У' => 'U',		'Ф' => 'F',		'Х' => 'H',		'Ц' => 'C',		'Ч' => 'Ch',
		'Ш' => 'Sh',	'Щ' => 'Sch',	'Ь' => '',		'Ы' => 'Y',		'Ъ' => '',
		'Э' => 'E',		'Ю' => 'Yu',	'Я' => 'Ya',
	);
	$value		= strtr($value, $converter);
	
	return $value;
}

function prompt_ex($msg) 
{
	print $msg . EOL;
	ob_flush();
	$in = fgets(fopen('php://stdin', 'r'), 1024);
	fclose(fopen('php://stdin', 'r'));
	exit();
}
?>
