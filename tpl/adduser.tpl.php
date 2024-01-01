<?php
/**
*	PHP | OTUS HLA | UTF8 | tpl/adduser.tpl.php
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
?>
<!DOCTYPE html>
<html lang="ru-RU">
	<head>
		<title>Register...</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex,nofollow">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	</head>
	<body>
		<div>
			<form id="reg-form" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['SCRIPT_NAME'] . '?adduser'; ?>">
				<table>
					<tr>
						<td>Логин: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="reg-login" name="reg-login" type="text" /></td>
					</tr>
					<tr>
						<td>Пароль: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="reg-password" name="reg-password" type="text" /></td>
					</tr>
					<tr>
						<td>Имя: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="reg-name" name="reg-name" type="text" /></td>
					</tr>
					<tr>
						<td>Фамилия: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="reg-lastname" name="reg-lastname" type="text" /></td>
					</tr>
					<tr>
						<td>Возраст: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="reg-age" name="reg-age" type="number" step="1" /></td>
					</tr>
					<tr>
						<td>Пол: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="reg-gender" name="reg-gender" type="text" /></td>
					</tr>
					<tr>
						<td>Город: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="reg-city" name="reg-city" type="text" /></td>
					</tr>
					<tr>
						<td>Интересы: </td>
						<td><span style="color: red;">*</span></td>
						<td><textarea  id="reg-about" name="reg-about" type="text"></textarea></td>
					</tr>
					<tr>
						<td colspan="3"><button id="reg-button" type="submit">Отправить</button></td>
					</tr>
				</table>
			</form>
		</div>
		<script src="js/scriptCheckForm.js"></script>
	</body>
</html>