<?php
	$dbConfig = new DBConfig();
	$pdo = new PDO($dbConfig->getDSN(), $dbConfig->getUser(), $dbConfig->getPassword());
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);