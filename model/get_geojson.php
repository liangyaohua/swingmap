<?php
	include_once('../config.php');
	
	function get_geojson($_device, $_server, $_interval, $_datetime) {
		global $connection, $device_array, $server_array;
		
		$sql = "SELECT MAX(m.`time`) AS time, MAX(m.`lat`) AS lat_marker, MAX(m.`lng`) AS lng_marker, MAX(cc.`lat`) AS lat_cc, MAX(cc.`lng`) AS lng_cc, MAX(ip.`lat`) AS lat_ip, MAX(ip.`lng`) AS lng_ip, MAX(m.`ip`) AS ip, MAX(m.`device`) AS device, m.`idDevice`, m.`idClient`, MAX(m.`idServer`) AS idServer, SUM(m.`volume`) AS volume FROM `marker` m LEFT JOIN `client_coordinate` cc ON cc.`idClient`= m.`idClient` LEFT JOIN `ip_coordinate` ip ON ip.`ip`= m.`ip` WHERE `time` BETWEEN DATE_SUB(:datetime, INTERVAL :interval SECOND) AND :datetime GROUP BY m.`idDevice`, m.`idClient`";
		
		if(in_array($_device, $device_array) && in_array($_server, $server_array)) {
			$sql .= " WHERE device = :device AND idServer = :idServer";
			$result = $connection->prepare($sql);
			$result->bindValue(':device', $_device, PDO::PARAM_STR);
			$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
		} else if(in_array($_device, $device_array) && $_server == "") {
			$sql .= " WHERE device = :device";
			$result = $connection->prepare($sql);
			$result->bindValue(':device', $_device, PDO::PARAM_STR);
		} else if($_device == "" && in_array($_server, $server_array)) {
			$sql .= " WHERE idServer = :idServer";
			$result = $connection->prepare($sql);
			$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
		} else {
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
			$lat = $row->lat_marker;
			$lng = $row->lng_marker;
			$tag = "";
			// if gps not exist, idClient to gps, ip to gps
			if((float)$lat == 0 && (float)$lng == 0) { 
				if($row->lat_cc != NULL && $row->lng_cc != NULL) {
					$lat = $row->lat_cc;
					$lng = $row->lng_cc;
					$tag = "#";
				} else if($row->lat_ip != NULL && $row->lng_ip != NULL) {
						$lat = $row->lat_ip;
						$lng = $row->lng_ip;
						$tag = "*";
				} else {
					$ip = $row->ip;
					$latlng = ip_geocoding($ip);
					if($latlng) {
						$lat = $latlng[0];
						$lng = $latlng[1];
						$tag = "*";
						$date = date("Y-m-d H:i:s");
						$sql = "INSERT INTO `ip_coordinate` (`ip`,`lat`,`lng`,`date`) VALUES ('".$ip."','".$lat."','".$lng."','".$date."')";
						$connection->exec($sql);
					}
					$tag = "^";
				}	
			}
			$feature = array("type" => "Feature",
							"geometry" => array("type" => "Point", "coordinates" => array((float)$lng, (float)$lat)),
							"properties" => array(
												"time" => $row->time,
												"ip" => $row->ip,
												"device" => $row->device,
												"idDevice" => $row->idDevice,
												"idClient" => $row->idClient,
												"idServer" => $row->idServer,
												"volume" => (int)$row->volume,
												"tag" => $tag
											)
						);
			array_push($features, $feature);
		}
		$Markers = array("type" => "FeatureCollection", "features" => $features);
		$geojson = json_encode($Markers);
		return $geojson;
	}
	// ip geocoding
	function ip_geocoding($ip) {
		$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip.'?fields=lat,lon,status'));
		if($query && $query['status'] == 'success') {
			$lat = $query['lat'];
			$lng = $query['lon'];
			return array($lat,$lng);
		} else {
			return false;
		}
	}
	/* if file_get_contents function is off
	function ip_geocoding($ip) {
		$query = get_data('http://ip-api.com/json/'.$ip.'?fields=lat,lon,status');
		$query = json_decode($query);
		if($query && $query->status == 'success') {
			$lat = $query->lat;
			$lng = $query->lon;
			return array($lat,$lng);
		} else {
			return false;
		}
	}
	function get_data($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}*/
?>