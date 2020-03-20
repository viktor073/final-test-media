<?php
/**
 *
 */
class Images
{
	private $images, $pdo, $idUser;

	function __construct(PDO $pdo, $where, $idUser)
	{
		$this->idUser = $idUser;
		$this->pdo = $pdo;
		if ($where !== null and $where != "") {
			$where = " WHERE ".$where;
		}
		$selectQuery = 'SELECT * FROM image '.$where;
		$this->images = $this->pdo->query($selectQuery)->fetchAll(PDO::FETCH_ASSOC);
	}

	//вывод списка изображений
	public function print($width)
	{
		foreach ($this->images as $key => $value) {
			if(trim($value['tag'], "'") != ""){
				printf('<div><div>
					Тег:<a href="index.php?tag=%s">%s</a>
				</div>', $value['tag'],  $value['tag']);
			}

			printf('Просмотров - %s<br>
				<div>
				<a href="index.php?id=%s"><img src="%s" alt="%s" title="%s" width="%s"></a>
			</div><br>', $value['countView'], $value['id'], $value['image'], $value['tag'],  $value['tag'], $width);

			if ($this->idUser == $value['idUser']) {
				printf('<div>
				<a href="index.php?deleteId=%s">Удалить</a>
			</div></div><br><br>',$value['id']);
			}
		}
	}

	//вывод одного изображения
	public function printOneImage($width)
	{

		if(trim($this->images[0]['tag'], "'") != ""){
			printf('<div>
				Тег:<a href="index.php?tag=%s">%s</a>
			</div>', $this->images[0]['tag'],  $this->images[0]['tag']);
		}

		printf('Просмотров - %s<br>
			<div>
			<a href="index.php?id=%s"><img src="%s" alt="%s" title="%s" width="%s"></a>
		</div><br><br>', $this->images[0]['countView'], $this->images[0]['id'], $this->images[0]['image'], $this->images[0]['tag'],  $this->images[0]['tag'], $width);

		$this->countView($this->images[0]['id'], $this->images[0]['countView']);

		if ($this->idUser == $this->images[0]['idUser']) {
				printf('<div>
				<a href="index.php?deleteId=%s">Удалить</a>
			</div><br><br>', $this->images[0]['id']);
		}
	}

	//вывод фильтра тэгов изображений
	public function printTag()
	{
		print("Фильтер по тегам:");
		foreach (array_unique(array_column($this->images, 'tag')) as $key=>$value) {
			if(trim($value, "'") != ""){
				printf('<div>
					<a href="index.php?tag=%s">%s</a>
				</div>', $value,  $value);
			}
		}
	}

	//подсчет просмотра отдельного изображения
	private function countView($idImage, $countView)
	{
		$updateQuery = 'UPDATE image SET countView=? WHERE id=?';
		$statement = $this->pdo->prepare($updateQuery);

		$success = $statement->execute([
			$countView+1,
			$idImage
		]);
		return $success;
	}

	//удаление изображения
	public function deleteImage($idImage)
	{
		if ($this->idUser === $this->images[0]['idUser']) {
			$deleteQuery = 'DELETE FROM image WHERE id=? AND idUser=?';
			$statement = $this->pdo->prepare($deleteQuery);

			$success = $statement->execute([
				$idImage,
				$this->idUser
			]);
			return $success;
		}
	}

}