<?php
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');

	include_once(ROOT_PATH.'config.php');
	include_once(ROOT_PATH.'model/db_connection.php');
	include_once(ROOT_PATH.'model/get_total_device.php');
	
	$total_device = array();
	$total = 0;
	foreach($device_array as $device) {
		$total_device[$device] = get_total_device($device);
		$total += $total_device[$device]['total'];
	}
	$total_device['total'] = $total;
	//print_r($total_device);
	//die();

	include_once(ROOT_PATH.'view/swingchart.htm');
	$connection = null;
?>