<?php
	error_reporting(E_ERROR);
	define('HOST','http://'.$_SERVER['HTTP_HOST'].'/swingmap');
	define('DEBUG',true); // set true for test, false for live
	date_default_timezone_set('Europe/Paris');
	
	$simulator = false;
	
	$device_array = array("iPhone", "iPad", "Android", "W32", "Server", "Undetermined");
	$server_array = array("SRV-DEV", "SRV-WEB10", "SWING-WEB11", "SWING-WEB8", "SRV-SWINGBOX", "SWING-WEB7-DE", "SRV-UNA77", "SWING-WEB9", "SWING-DEMO","SRV-POCKET-FRON");
?>