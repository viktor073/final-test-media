<?php
	require ('start.php');
	if($session->getStatus()){
		$userConfig = new UserConfig($_SESSION['id_user'], null, null, null);
		$user = User::instUser($userConfig, $pdo);
		$user->exit();
		unset($user);
		header('Location: index.php');
	}
	else{
		echo "Чтобы выйти надо войти.....";
	}