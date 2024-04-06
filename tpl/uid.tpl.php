<?php
/**
*	PHP | OTUS HLA | UTF8 | tpl/uid.tpl.php
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
?>
<!DOCTYPE html>
<html lang="ru-RU">
	<head>
		<title>User Info</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex,nofollow">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	</head>
	<body>
		<div>
			<p>
				<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">Домашняя</a>
				<a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?search'; ?>">Поиск пользователя</a>
				<a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?login'; ?>">Авторизация</a>
			</p>
		</div>
		<div>
			<table>
				<tr>
					<td>Имя: </td>
					<td><?php echo $userName; ?></td>
				</tr>
				<tr>
					<td>Фамилия: </td>
					<td><?php echo $userLastName; ?></td>
				</tr>
				<tr>
					<td>Возраст: </td>
					<td><?php echo $userAge; ?></td>
				</tr>
				<tr>
					<td>Пол: </td>
					<td><?php echo $userGender; ?></td>
				</tr>
				<tr>
					<td>Город: </td>
					<td><?php echo $userCity; ?></td>
				</tr>
				<tr>
					<td>Интересы: </td>
					<td><?php echo $userAbout; ?></td>
				</tr>
				<tr>
					<td>Друзья: </td>
					<td>
						<table>
								<?php
								foreach ($userFriends as $key => $value)
								{
									echo	'							<tr>' . ENDL .
											'								<td>' . $key . '</td>' . ENDL .
											'								<td>' . $value . '</td>'  . ENDL .
											'							</tr>'  . ENDL;
								}
								?>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>