<?php

/**
 * Класс конфигурации User
 */
class UserConfig
{
	public $id;
	public $name;
	public $nameFull;
	public $password;
	public $date_reg;

	public function __construct($id, $name, $nameFull, $password)
	{
		$this->id = $id;
		$this->name = $name;
		$this->nameFull = $nameFull;
		$this->password = $password;

	}
}