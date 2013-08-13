<?php
	include_once('../model/db_connection.php');
	include_once('../config.php');
	date_default_timezone_set('Europe/Paris');
	
	$_device = isset($_GET['device'])?$_GET['device']:"";
	$_server = isset($_GET['server'])?$_GET['server']:"";
	$_interval = isset($_GET['interval'])?$_GET['interval']:"60";
	$_datetime = (isset($_GET['datetime'])&&$_GET['datetime']!="")?$_GET['datetime']:date("Y-m-d H:i:s");
	
	$sql = "SELECT DISTINCT idClient FROM marker WHERE `time` BETWEEN DATE_SUB(:datetime, INTERVAL :interval SECOND) AND :datetime";
	
	if(in_array($_device, $device_array) && in_array($_server, $server_array)) {
		$sql .= " AND device = :device AND idServer = :idServer";
		$result = $connection->prepare($sql);
		$result->bindValue(':device', $_device, PDO::PARAM_STR);
		$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
	} else if(in_array($_device, $device_array) && $_server == "") {
		$sql .= " AND device = :device";
		$result = $connection->prepare($sql);
		$result->bindValue(':device', $_device, PDO::PARAM_STR);
	} else if($_device == "" && in_array($_server, $server_array)) {
		$sql .= " AND idServer = :idServer";
		$result = $connection->prepare($sql);
		$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
	} else {
		$result = $connection->prepare($sql);
	}
	
	$result->bindValue(':datetime', $_datetime, PDO::PARAM_STR);
	$result->bindValue(':interval', $_interval, PDO::PARAM_STR);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	while($row = $result->fetch()) {
		$idClient = $row->idClient;
		$clientList .= "<span class='text-info'>".$idClient."</span> ";
	}
	
	echo $clientList;
	$connection = null;
?>