<?php
	include_once('../model/db_connection.php');
	include_once('../model/get_geojson.php');
	
	date_default_timezone_set('Europe/Paris');
	
	$_device = isset($_GET['device'])?$_GET['device']:"";
	$_server = isset($_GET['server'])?$_GET['server']:"";
	$_interval = isset($_GET['interval'])?$_GET['interval']:"60";
	$_datetime = (isset($_GET['datetime'])&&$_GET['datetime']!="")?$_GET['datetime']:date("Y-m-d H:i:s");
	$_client = isset($_GET['client'])?$_GET['client']:"";
	
	if(!in_array($_device, $device_array) && $_device != "") {
		die("Device not exist: ".$_device);
	}
	if(!in_array($_server, $server_array) && $_server != "") {
		die("Server not exist: ".$_server);
	}
	
	$geojson = get_geojson($_device, $_server, $_interval, $_datetime, $_client);
	echo $geojson;
	$connection = null;
?>