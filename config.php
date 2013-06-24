<?php
	error_reporting(E_ERROR);
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');
	define('VIEW',ROOT_PATH.'view');
	define('HOST','http://'.$_SERVER['HTTP_HOST'].'/swingmap');
	define('DEBUG',true);
	date_default_timezone_set('Europe/Paris');
?>