<?php
	require ('start.php');
	//обработчик формы регистрации
	if (!empty($_POST['name']) and !empty($_POST['nameFull']) and !empty($_POST['password'])) {
		try {
			//создаем пользователя
			$userConfig = new UserConfig(null, $_POST['name'], $_POST['nameFull'], $_POST['password']);
			$user = User::instUser($userConfig, $pdo);
			//если true, зарегистрирован
			$res = $user->registration();
			if($res){
				header('Location: index.php');
			}
		}
		catch(Exception $e) {
			echo $e->getMessage(), "\n<br>";
			return false;
		}
	}