<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/method.search.php
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

//====================================================================== VARIABLES
$rawSearch	= $_POST['search-user'];
$userTable	= '<tr>' . ENDL .
			'	<td>uid</td>' . ENDL .
			'	<td>name</td>' . ENDL .
			'	<td>lastname</td>' . ENDL .
			'	<td>age</td>' . ENDL .
			'	<td>gender</td>' . ENDL .
			'	<td>city</td>' . ENDL .
			'	<td>about</td>' . ENDL .
			'</tr>'. ENDL;

//====================================================================== MAIN
if (!empty($rawSearch))
{
	$arrDbRes	= db_get_user_by_name_or_lastname($dbHandles['read'], $rawSearch);
	if ($arrDbRes['result'] === false)
	{
		page_break($dbHandles, '400 Bad Request', $arrDbRes['payload']);
	}
	else
	{
		$tmpArray	= $arrDbRes['payload'];
		$tmpCount	= count($tmpArray);
		for ($i = 0; $i < $tmpCount; $i ++)
		{
			$userTable	.= '<tr>' . ENDL .
						'	<td>' . $tmpArray[$i]['uid'] . '</td>' . ENDL .
						'	<td>' . $tmpArray[$i]['name'] . '</td>' . ENDL .
						'	<td>' . $tmpArray[$i]['lastname'] . '</td>' . ENDL .
						'	<td>' . $tmpArray[$i]['age'] . '</td>' . ENDL .
						'	<td>' . $tmpArray[$i]['gender'] . '</td>' . ENDL .
						'	<td>' . $tmpArray[$i]['city'] . '</td>' . ENDL .
						'	<td>' . $tmpArray[$i]['about'] . '</td>' . ENDL .
						'</tr>'. ENDL;
		}
	}
}

include 'tpl/search.tpl.php';
?>
