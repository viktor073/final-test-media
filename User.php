<?php

/**
 * Класс регистрации и авторизации пользоватлей
 */
class User
{
	private static $user;
	public $config;
	private $pdo;

	//проверка существования User
	public static function instUser(UserConfig $config, PDO $pdo){

		if(self::$user === null){
			return self::$user = new User($config, $pdo);
		}
		else{
				return self::$user;
		}
	}

	private function __construct(UserConfig $config, PDO $pdo)
	{
		$this->pdo = $pdo;
		$this->config = $config;
	}

	private function __clone(){}

	private function __wakeup(){}

	public function setConfigUserById(){
		if($this->config->id != null){
			$selectQuery = 'SELECT * FROM user WHERE id=' . $this->config->id . ' LIMIT 1';
			$query = $this->pdo->query($selectQuery);

			if($query !== false){
				$row = $query->fetch(PDO::FETCH_ASSOC);
				$this->config->name = trim($row['name'], "'");
				$this->config->nameFull = trim($row['nameFull'], "'");
				$this->config->date_reg = $row['date_reg'];
				return self::$user;
			}
		}
	}

	//авторизация существующего пользователя
	public function authorization(){
		if ($this->existNameUser()) {
			$selectQuery = 'SELECT * FROM user WHERE name="' . $this->pdo->quote($this->config->name) . '" LIMIT 1';
			$row = $this->pdo->query($selectQuery)->fetch(PDO::FETCH_ASSOC);
			if($row !== false){
				if($this->config->password === trim($row['password'], "'")){
					$this->config->id = $row['id'];
					$this->config->nameFull = trim($row['nameFull'], "'");
					$this->config->date_reg = $row['date_reg'];
					$_SESSION['id_user'] = $this->config->id;
					return true;
				}
				else{
					echo "Не верный пароль";
					return false;
				}
			}
			else{
				echo "Пользователь не найден";
				return false;
			}
		}
		else{
			echo "Пользователь не зарегистрирован";
			return false;
		}
	}

	//добавление нового пользователя
	public function registration(){
		if ($this->existNameUser()) {
			echo "Пользователь с таким именем зарегистрирован. <a href='index.php'>Войдите</a>.";
			return false;
		}
		else{
			$insertQuery = 'INSERT INTO user(name, nameFull, password, date_reg) VALUES(?,?,?,?)';
			$statement = $this->pdo->prepare($insertQuery);
			$this->config->date_reg = date("Y-m-d");

			$success = $statement->execute([
				$this->pdo->quote($this->config->name),
				$this->pdo->quote($this->config->nameFull),
				$this->pdo->quote($this->config->password),
				$this->config->date_reg
			]);

			$this->config->id = $this->pdo->lastInsertId();


			if($success){
				$_SESSION['id_user'] = $this->config->id;
				return true;
			}
			else{
				return false;
			}
		}
	}

	//проверка существования пользователя в базе
	protected function existNameUser(){
		$selectQuery = "SELECT name FROM user WHERE name=? LIMIT 1";
		$statement = $this->pdo->prepare($selectQuery);
		$success = $statement->execute([
				$this->pdo->quote($this->config->name)
			]);
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if(strcasecmp($row['name'], $this->pdo->quote($this->config->name)) == 0){
			return true;
		}
		else{
			return false;
		}
	}

	//выход пользователя
	public function exit(){
		if (ini_get("session.use_cookies")) {
		    setcookie(session_name(), '', time() - 42000);
		}
		session_destroy();
	}

}