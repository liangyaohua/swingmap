<?php
	error_reporting(E_ERROR);
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');
	define('HOST','http://'.$_SERVER['HTTP_HOST'].'/swingchart');
	define('DEBUG',true); // set true for test, false for live
	date_default_timezone_set('Europe/Paris');
	
	include_once(ROOT_PATH.'model/db_connection.php');
	include_once(ROOT_PATH.'model/get_total_device.php');
	
	$device_array = array("ios", "android", "wp", "server");
	$server_array = array("A", "B", "C", "D");
	
	$total_device = array();
	$total = 0;
	foreach($device_array as $device) {
		$total_device[$device] = get_total_device($device);
		$total += $total_device[$device]['total'];
	}
	$total_device['total'] = $total;
	//print_r($total_device);
	//die();

	include_once(ROOT_PATH.'view/index.htm');
?>