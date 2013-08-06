<?php
	include_once('../model/db_connection.php');
	
	$device_list = '';
	$server_list = '';
	
	$sql = "select distinct device from marker";
	$result = $connection->prepare($sql);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	while($row = $result->fetch()) {
		$device_list .= '"'.$row->device;
		$device_list .= '", ';
	}
	$device_list = substr($device_list, 0, -2);
	
	$sql = "select distinct idServer from marker order by idServer desc";
	$result = $connection->prepare($sql);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	while($row = $result->fetch()) {
		$server_list .= '"'.$row->idServer;
		$server_list .= '", ';
	}
	$server_list = substr($server_list, 0, -2);
	
	echo '('.$device_list.')<br>';
	echo '('.$server_list.')';
	$connection = null;
?>