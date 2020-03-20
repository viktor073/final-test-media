<?php
	try {
	require ('start.php');
	//обработчик формы загрузки изображений
		if($session->getStatus()){
			//создаем пользователя
			$userConfig = new UserConfig($_SESSION['id_user'], null, null, null);
			$user = User::instUser($userConfig, $pdo);
			$user->setConfigUserById($userConfig);
			//проверяем наличие файлов
			if (!empty($_FILES['files']['name'])) {
				$files = $_FILES['files'];
				foreach ($files['tmp_name'] as $index => $tmpPath) {
					if(!array_key_exists($index, $files['name'])){
						continue;
					}
					if(!empty($tmpPath)){
						if(exif_imagetype($tmpPath) !== false){
							//класс загрузки файлов
							$imageUpload = new ImageUpload($tmpPath, $user, $_POST['tag']);
							//загружаем файлы
							if($imageUpload->upload($pdo)){
								header('Location: index.php');
							}
						}
					}
				}
			}
		}
	}
	catch(Exception $e){
		echo $e->getMessage(), "\n<br>";
		return;
	}
