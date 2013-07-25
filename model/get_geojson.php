<?php
	include_once('../config.php');
	
	function get_geojson($_device, $_server, $_interval, $_datetime) {
		global $connection, $device_array, $server_array;
		
		if(in_array($_device, $device_array) && in_array($_server, $server_array)) {
			$sql = "select time, lat, lng, ip, device, idDevice, idClient, idServer, volume from marker where device = :device and idServer = :idServer and time > DATE_SUB(:datetime, INTERVAL :interval SECOND) and time <= :datetime";
			$result = $connection->prepare($sql);
			$result->bindValue(':device', $_device, PDO::PARAM_STR);
			$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
		} else if(in_array($_device, $device_array) && $_server == "") {
			$sql = "select time, lat, lng, ip, device, idDevice, idClient, idServer, volume from marker where device = :device and time > DATE_SUB(:datetime, INTERVAL :interval SECOND) and time <= :datetime";
			$result = $connection->prepare($sql);
			$result->bindValue(':device', $_device, PDO::PARAM_STR);
		} else if($_device == "" && in_array($_server, $server_array)) {
			$sql = "select time, lat, lng, ip, device, idDevice, idClient, idServer, volume from marker where idServer = :idServer and time > DATE_SUB(:datetime, INTERVAL :interval SECOND) and time <= :datetime";
			$result = $connection->prepare($sql);
			$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
		} else {
			$sql = "select time, lat, lng, ip, device, idDevice, idClient, idServer, volume from marker where time > DATE_SUB(:datetime, INTERVAL :interval SECOND) and time <= :datetime";
			$result = $connection->prepare($sql);
		}
		$result->bindValue(':datetime', $_datetime, PDO::PARAM_STR);
		$result->bindValue(':interval', $_interval, PDO::PARAM_STR);
		$result->execute();
		
		//die(var_dump($result));
		
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		$features = array();
		$feature = array();
		while ($row = $result->fetch()) {
			// if gps not exist, idClient to gps, ip to gps
			if((float)$row->lat == 0 && (float)$row->lng == 0) { 
				if(!($latlng = get_client_coords($row->idClient)))
					$latlng = get_ip_coords($row->ip);
				$row->lat = $latlng[0];
				$row->lng = $latlng[1];
			}
			$feature = array("type" => "Feature",
							"geometry" => array("type" => "Point", "coordinates" => array((float)$row->lng, (float)$row->lat)),
							"properties" => array(
												"time" => $row->time,
												"ip" => $row->ip,
												"device" => $row->device,
												"idDevice" => $row->idDevice,
												"idClient" => $row->idClient,
												"idServer" => $row->idServer,
												"volume" => (int)$row->volume
											)
						);
			array_push($features, $feature);
		}
		$Markers = array("type" => "FeatureCollection", "features" => $features);
		$geojson = json_encode($Markers);
		return $geojson;
	}
	// get coordinates by idClient
	function get_client_coords($idClient) {
		global $connection;
		$sql = "select lat, lng from client_coordinate where idClient = :idClient";
		$result = $connection->prepare($sql);
		$result->bindValue(':idClient', $idClient, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		if($client_coords = $result->fetch()) {
			$lat = $client_coords->lat;
			$lng = $client_coords->lng;
			return array($lat,$lng);
		} else {
			return false;
		}
	}
	// get coordinates by ip
	function get_ip_coords($ip) {
		global $connection;
		$sql = "select lat, lng from ip_coordinate where ip = :ip";
		$result = $connection->prepare($sql);
		$result->bindValue(':ip', $ip, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		if($ip_coords = $result->fetch()) {
			$lat = $ip_coords->lat;
			$lng = $ip_coords->lng;
			return array($lat,$lng);
		} else {
			$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip.'?fields=lat,lon,status'));
			if($query && $query['status'] == 'success') {
				$lat = $query['lat'];
				$lng = $query['lon'];
				$date = date("Y-m-d H:i:s");
				$sql = "insert into ip_coordinate (ip,lat,lng,date) values ('".$ip."','".$lat."','".$lng."','".$date."')";
				$connection->exec($sql);
				return array($lat,$lng);
			} else {
				return false;
			}
		}
	}
?>