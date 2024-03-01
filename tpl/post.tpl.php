<?php
/**
*	PHP | OTUS HLA | UTF8 | tpl/post.tpl.php
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
?>
<!DOCTYPE html>
<html lang="ru-RU">
	<head>
		<title>Post...</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex,nofollow">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	</head>
	<body>
		<div>
			<p>
				<a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?get'; ?>">Список</a>
			</p>
		</div>
		<div>
			<form id="post-form" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['SCRIPT_NAME'] . '?create'; ?>">
				<table>
					<tr>
						<td>Тема: </td>
						<td><span style="color: red;">*</span></td>
						<td><input id="post-title" name="post-title" type="text" /></td>
					</tr>
					<tr>
						<td>Сообщение: </td>
						<td><span style="color: red;">*</span></td>
						<td><textarea  id="post-text" name="post-text" type="text"></textarea></td>
					</tr>
					<tr>
						<td colspan="3"><button id="post-button" type="submit">Отправить</button></td>
					</tr>
				</table>
			</form>
		</div>
		<div>
			<p>
				<?php echo $pageWarnMsg; ?>
			</p>
		</div>
		<script src="js/scriptCheckForm.js"></script>
	</body>
</html>