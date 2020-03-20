<?php

/**
 *
 */
class ImageUpload
{
	private $content, $type, $user, $tag;
	function __construct($imagePath, User $user, $tag)
	{
		if($tag != ''){
			$this->tag = $tag;
		}
		else{
			$this->tag = null;
		}
		//сохраняем пользователя
		$this->user = $user;
		//сохраняем контент
		$this->content = file_get_contents($imagePath);
		//определяем тип файла
		switch (exif_imagetype($imagePath)) {
		    case 1:
		        $this->$type = ".gif";
		        break;
		    case 2:
		        $this->$type = ".jpg";
		        break;
		    case 3:
		        $this->$type = ".png";
		        break;
		    case 4:
		        $this->$type = ".swf";
		        break;
		    case 5:
		        $this->$type = ".psd";
		        break;
		    case 6:
		        $this->$type = ".bmp";
		        break;
		    case 7:
		        $this->$type = ".tif";
		        break;
		    case 8:
		        $this->$type = ".tiff";
		        break;
		    case 9:
		        $this->$type = ".jpc";
		        break;
		    case 10:
		        $this->$type = ".jp2";
		        break;
		    case 11:
		        $this->$type = ".jpx";
		        break;
		    case 12:
		        $this->$type = ".jb2";
		        break;
		    case 13:
		        $this->$type = ".swc";
		        break;
		    case 14:
		        $this->$type = ".iff";
		        break;
		    case 15:
		        $this->$type = ".wbmp";
		        break;
		    case 16:
		        $this->$type = ".xbm";
		        break;
		    case 17:
		        $this->$type = ".ico";
		        break;
		    case 18:
		        $this->$type = ".webp";
		        break;
		    default:
		    	$this->$type = null;
		}

	}

	//загрузка данных файла в БД и сохранения контента в файл
	public function upload($pdo){
		//сохраняем в файл и получаем директорию
		$filename = $this->uploadContentToFile();

		//запрос вставки инфо изображения
		$insertQuery = 'INSERT INTO image(date_reg, idUser, countView, image, tag) VALUES(?,?,?,?,?)';
		$statement = $pdo->prepare($insertQuery);

		$success = $statement->execute([
			date("Y-m-d"),
			$this->user->config->id,
			0,
			$filename,
			$pdo->quote($this->tag)
		]);
		return $success;
	}

	protected function uploadContentToFile(){
		//готовим общую директорию
		if (!file_exists('image')) {
				if(!mkdir('image')){
					echo "Not created - direct 'test'".PHP_EOL;
					return false;
				}
		}
		//готовим папку для изображений конкретного пользователя
		$dir = 'image/'.$this->user->config->name;
		if (!file_exists($dir)) {
				if(!mkdir($dir)){
					echo "Not created - direct ".$dir;
					return false;
				}
		}
		//готовим в папке пользователя папку по дате
		$dir = 'image/'.$this->user->config->name.'/'.date("Y-m-d");
		if (!file_exists($dir)) {
				if(!mkdir($dir)){
					echo "Not created - direct ".$dir;
					return false;
				}
		}

		//готовим файл и загружаем контент
		$i=1;
		while (true) {
			$filename = $dir."/". $this->user->config->name.$i.$this->$type;
			if(!file_exists($filename)) {
				touch($filename);
				file_put_contents($filename, $this->content);
				return $filename;
			}
			$i++;
		}
		echo "Err";
		return;
	}


}