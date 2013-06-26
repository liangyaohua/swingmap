<?php
	include_once('../model/db_connection.php');
	include_once('../model/get_geojson.php');
	
	$_device = isset($_GET['device'])?$_GET['device']:"";
	$_server = isset($_GET['server'])?$_GET['server']:"";
	$_interval = isset($_GET['interval'])?$_GET['interval']:"60";
	
	$device_array = array("ios", "android", "wp", "server");
	$server_array = array("A", "B", "C", "D");
	
	if(!in_array($_device, $device_array) && $_device != ""){
		die("Device not exist: ".$_device);
	}
	if(!in_array($_server, $server_array) && $_server != ""){
		die("Server not exist: ".$_server);
	}
	$geojson = get_geojson($_device, $_server, $_interval);
	echo $geojson;
?>