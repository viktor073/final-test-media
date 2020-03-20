<?php
	require ('start.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Итоговое задание - сервис загрузки изображений</title>
</head>
<body align="center">
	<?php
		//форма регистрации
		if(!$session->getStatus()){
			print('<h1>Регистрация</h1>
				<div>
				<form method="post" enctype="multipart/form-data" action="active_registration.php">
						<lable>Логин</lable>
						<input name="name" type="text"><br><br>
						<lable>ФИО<lable>
						<input name="nameFull" type="text"><br><br>
						<lable>Пароль</lable>
						<input name="password" type="password"><br><br>
						<input type="submit" value="Войти">
				</form>
			</div>');
			}
		else{
			 echo "Сначало надо выйти";
		}


	?>
</body>