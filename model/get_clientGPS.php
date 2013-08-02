<?php
	function get_clientGPS($page, $line) {
		global $connection;
		$start = intval($page)*intval($line);
		
		$sql = "select idClient,lat,lng from `client_coordinate` limit ".$start.",".$line;
		$result = $connection->prepare($sql);

		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		$clientGPS = array();
		for($i = 0; $row = $result->fetch(); $i++) {
			$clientGPS[$i]['idClient'] = $row->idClient;
			$clientGPS[$i]['lat'] = $row->lat;
			$clientGPS[$i]['lng'] = $row->lng;
		}
		return $clientGPS;
	}
?>