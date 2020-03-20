<?php
	require ('start.php');
	//обработчик формы авторизации
	if (!empty($_POST['name']) and !empty($_POST['password'])) {
		try {
			//создаем конфиг и пользователя
			$userConfig = new UserConfig(null, $_POST['name'], null, $_POST['password']);
			$user = User::instUser($userConfig, $pdo);
			//если true, пользователь авторизирован
			$res = $user->authorization();
			if($res){
				header('Location: index.php');
			}
		}
		catch(Exception $e) {
			echo $e->getMessage(), "\n<br>";
			return false;
		}
	}
	else{
		echo "Введите логин и пароль.";
	}