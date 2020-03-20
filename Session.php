<?php


	/**
	 *класс сессии
	 */
	class Session
	{
		protected $status;

		function __construct()
		{
			ini_set('session.gc_maxlifetime', 100000);
			ini_set('session.cookie_lifetime', 100000);
			session_start();

			if (!isset($_SESSION['HTTP_USER_AGENT']) or !isset($_SESSION['id_user'])) {
			    $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			    $_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ;
			    $_SESSION['HTTP_X_FORWARDED_FOR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
			    $this->status = false;
			}
			elseif ($_SESSION['HTTP_USER_AGENT'] === $_SERVER['HTTP_USER_AGENT'] and $_SESSION['REMOTE_ADDR'] === $_SERVER['REMOTE_ADDR']
			    and $_SESSION['HTTP_X_FORWARDED_FOR'] === $_SERVER['HTTP_X_FORWARDED_FOR']) {
				if(isset($_SESSION['id_user'])){
					$this->status = true;
				}
			}
			else{
				$this->status = false;
			}
		}

		function getStatus(){
			return $this->status;
		}
	}