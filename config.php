<?php
	error_reporting(E_ERROR);
	define('HOST','http://'.$_SERVER['HTTP_HOST'].'/swingmap');
	define('DEBUG',true); // set true for test, false for live
	date_default_timezone_set('Europe/Paris');
	
	$simulator = false;
	
	$device_array = array("ios", "android", "wp", "server");
	$server_array = array("A", "B", "C", "D");
?>