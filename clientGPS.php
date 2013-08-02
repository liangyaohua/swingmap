<?php
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');

	include_once(ROOT_PATH.'config.php');	
	include_once(ROOT_PATH.'model/db_connection.php');
	include_once(ROOT_PATH.'model/get_clientGPS.php');
	
	$page = isset($_GET['page'])?$_GET['page']:'0';
	$line = isset($_GET['line'])?$_GET['line']:'20';
	
	$clientGPS = get_clientGPS($page, $line);
	$sql = "select count(*) as total from client_coordinate";
	$result = $connection->prepare($sql);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	$row = $result->fetch();
	$total = $row->total;
	$totalPage = $total / $line;
	
	include_once(ROOT_PATH.'view/clientGPS.htm');
	$connection = null;
?>