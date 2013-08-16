<?php
	include_once('../model/db_connection.php');
	include_once('../config.php');
	date_default_timezone_set('Europe/Paris');
	
	$_device = isset($_GET['device'])?$_GET['device']:"";
	$_server = isset($_GET['server'])?$_GET['server']:"";
	$_interval = isset($_GET['interval'])?$_GET['interval']:"60";
	$_datetime = (isset($_GET['datetime'])&&$_GET['datetime']!="")?$_GET['datetime']:date("Y-m-d H:i:s");

	$sql = "SELECT idClient, idDevice FROM marker WHERE `time` BETWEEN DATE_SUB(:datetime, INTERVAL :interval SECOND) AND :datetime";
	
	if(in_array($_device, $device_array) && in_array($_server, $server_array)) {
		$sql .= " AND device = :device AND idServer = :idServer";
		$sql = "SELECT tmp.idClient as idClient, COUNT(*) AS count FROM (".$sql." GROUP BY idClient, idDevice) AS tmp GROUP BY tmp.idClient";
		$result = $connection->prepare($sql);
		$result->bindValue(':device', $_device, PDO::PARAM_STR);
		$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
	} else if(in_array($_device, $device_array) && $_server == "") {
		$sql .= " AND device = :device";
		$sql = "SELECT tmp.idClient as idClient, COUNT(*) AS count FROM (".$sql." GROUP BY idClient, idDevice) AS tmp GROUP BY tmp.idClient";
		$result = $connection->prepare($sql);
		$result->bindValue(':device', $_device, PDO::PARAM_STR);
	} else if($_device == "" && in_array($_server, $server_array)) {
		$sql .= " AND idServer = :idServer";
		$sql = "SELECT tmp.idClient as idClient, COUNT(*) AS count FROM (".$sql." GROUP BY idClient, idDevice) AS tmp GROUP BY tmp.idClient";
		$result = $connection->prepare($sql);
		$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
	} else {
		$sql = "SELECT tmp.idClient as idClient, COUNT(*) AS count FROM (".$sql." GROUP BY idClient, idDevice) AS tmp GROUP BY tmp.idClient";
		$result = $connection->prepare($sql);
	}

	//die($sql);
	
	$result->bindValue(':datetime', $_datetime, PDO::PARAM_STR);
	$result->bindValue(':interval', $_interval, PDO::PARAM_STR);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	while($row = $result->fetch()) {
		$idClient = $row->idClient;
		$count = $row->count;
		$clientList .= "<span class='text-info'><strong>".$idClient."</strong></span>:<span class='text-warning'>".$count."</span> ";
	}
	
	echo $clientList;
	$connection = null;
?>