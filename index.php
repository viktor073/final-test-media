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
		//проверяем статус авторизации.
		if($session->getStatus()){
			//создаем конфиг с ID пользователя
			$userConfig = new UserConfig($_SESSION['id_user'], null, null, null);
			//создаем пользователя с конфигом
			$user = User::instUser($userConfig, $pdo);
			//добавляем данные из базы в конфиг
			$user->setConfigUserById();
			//выводим инфу о пользователе
			printf("
				<div>
					<lable>ID </lable><lable>%s</lable><br>
					<lable>Логин </lable><lable>%s</lable><br>
					<lable>Имя </lable><lable>%s</lable><br>
					<lable>Дата регистрации </lable><lable>%s</lable><br>
				</div><br>
				", $user->config->id, $user->config->name, $user->config->nameFull, $user->config->date_reg);
			//кнопка выхода пользователя
			print('<a href="exit_user.php">Выход</a><br><br>');
			//форма загрузки изображений
			print('<div>
					<form method="post" enctype="multipart/form-data" action="active_upload_image.php">
						<lable>Загрузка изображения - </lable>
						<input type="file" name="files[]" accept="image/*" multiple><br><br>
						<lable>Тэг изображения - </lable>
						<input name="tag" type="text"><br><br>
						<input type="submit" name="btn_active_form" value="Загрузить">
					</form>
				</div><br><br>');
			//ссылка на показ изображений по пользователю
			printf('<div>
					<a href="index.php?idUser=%s">Показать изображения пользователя</a>
				</div><br><br>', $user->config->id);

			//удаляем изображение, если был запрос на удаление
			if (!empty($_GET['deleteId']) and $_GET['deleteId'] != '') {
				$images = new Images($pdo, $where, $user->config->id);
				$images->deleteImage($_GET['deleteId']);
			}
		}
		//если статус регистрации false выводим форму регистрации
		else{
			 print('<form method="post" enctype="multipart/form-data" action="active_authorization.php">
						<input name="name" type="text"><br>
						<input name="password" type="password"><br>
						<input type="submit" name="btn_active_form" value="Войти">
				</form>
				<a href="registration.php">регистрация</a><br><br><br>');
		}

		//если статус true показываем отдельное изображение
		if (!empty($_GET['id']) and $_GET['id'] != '') {
			$where = " id=".$where.$_GET['id'];
			$images = new Images($pdo, $where, $user->config->id);
			$images->printOneImage(1000);
		}
		//готовим фильтр списока изображений
		else{
			//изображения пользователя
			if(!empty($_GET['idUser']) and $_GET['idUser'] != '') {
				$where = $where." idUser=".$_GET['idUser'];
			}
			//изображения по тэгу
			if (!empty($_GET['tag']) and $_GET['tag'] != '') {
				$where = $where.' tag="'.$_GET['tag']. '"';

			}
			//загружаем в класс список изображений по фильтру
			$images = new Images($pdo, $where, $user->config->id);
			//выводим список изображений
			$images->print(300);
			//выводим тэги изображений
			$images->printTag();
		}

	?>
</body>