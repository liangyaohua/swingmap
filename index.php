<?php
	error_reporting(E_ERROR);
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');
	define('HOST','http://'.$_SERVER['HTTP_HOST'].'/swingmap');
	define('DEBUG',true); // set true for test, false for live
	date_default_timezone_set('Europe/Paris');
	
	include_once(ROOT_PATH.'config.php');

	include_once(ROOT_PATH.'view/swingmap.htm');
?>