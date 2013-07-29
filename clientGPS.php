<?php
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');

	include_once(ROOT_PATH.'config.php');
	include_once(ROOT_PATH.'model/db_connection.php');
	
	
	function get_clientGPS() {
		global $connection;
		$sql = "select * from client_coordinate";
		$result = $connection->prepare($sql);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		$clientGPS = array();
		
		while($row = $result->fetch()) {
				$clientGPS[$row->idClient_Coordinate]['idClient'] = $row->idClient;
				$clientGPS[$row->idClient_Coordinate]['lat'] = $row->lat;
				$clientGPS[$row->idClient_Coordinate]['lng'] = $row->lng;
		}
		return $clientGPS;
	}
	
	$clientGPS = get_clientGPS();
	//print_r($clientGPS);
	//die();
	
	include_once(ROOT_PATH.'view/clientGPS.htm');
?>