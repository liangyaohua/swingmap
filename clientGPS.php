<?php
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');

	include_once(ROOT_PATH.'config.php');	
	include_once(ROOT_PATH.'model/db_connection.php');
	
	$line = 20;
	
	$sql = "select count(*) as total from client_coordinate";
	$result = $connection->prepare($sql);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	$row = $result->fetch();
	$total = $row->total;
	$totalPage = ceil($total / $line);
	//die($totalPage);
	include_once(ROOT_PATH.'view/clientGPS.htm');
	$connection = null;
?>