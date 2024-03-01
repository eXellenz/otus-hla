<?php
/**
*	PHP | OTUS HLA | UTF8 | tpl/login.tpl.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-02-27
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
		<title>Login...</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex,nofollow">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	</head>
	<body>
		<div>
			<p>
				<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">Домашняя</a>
				<a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?adduser'; ?>">Регистрация</a>
			</p>
		</div>
		<div>
			<form id="auth-form" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['SCRIPT_NAME'] . '?login'; ?>">
				<table>
					<tr>
						<td>Логин: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="auth-login" name="auth-login" type="text" /></td>
					</tr>
					<tr>
						<td>Пароль: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="auth-password" name="auth-password" type="text" /></td>
					</tr>
					<tr>
						<td colspan="3"><button id="auth-button" type="submit">Отправить</button></td>
					</tr>
				</table>
			</form>
		</div>
		<script src="js/scriptCheckForm.js"></script>
	</body>
</html>