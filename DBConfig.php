<?php

/**
 * Класс конфигурации базы данных
 */
class DBConfig
{
	protected const driver = 'mysql';
	protected const host = '127.0.0.1';
	protected const port = '3306';
	protected const dbname = 'image_service';
	protected const user = 'root';
	protected const password = '';

	public function getDSN()
	{
		return self::driver.':host=' .self::host. ':' .self::port. ';dbname=' .self::dbname;
	}

	public function getUser()
	{
		return self::user;
	}

	public function getPassword()
	{
		return self::password;
	}
}