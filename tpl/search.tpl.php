<?php
/**
*	PHP | OTUS HLA | UTF8 | tpl/search.tpl.php
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

//====================================================================== MAIN
?>
<!DOCTYPE html>
<html lang="ru-RU">
	<head>
		<title>Search...</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex,nofollow">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	</head>
	<body>
		<div>
			<p>
				<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">Домашняя</a>
				<a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?uid=' . $userId; ?>">Инфо о пользователе</a>
				<a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?login'; ?>">Авторизация</a>
			</p>
		</div>
		<div>
			<form id="search-form" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['SCRIPT_NAME'] . '?search'; ?>">
				<table>
					<tr>
						<td>Искать: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="search-user" name="search-user" type="text" /></td>
					</tr>
					<tr>
						<td colspan="3"><button id="search-button" type="submit">Отправить</button></td>
					</tr>
				</table>
			</form>
		</div>
		<div>
			<table>
				<?php echo $userTable; ?>
			</table>
		</div>
		<script src="js/scriptCheckForm.js"></script>
	</body>
</html>