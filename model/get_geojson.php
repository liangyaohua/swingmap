<?php
function get_geojson($_device, $_server, $_interval, $_datetime){
	global $connection;
	$device_array = array("ios", "android", "wp", "server");
	$server_array = array("A", "B", "C", "D");
	
	if(in_array($_device, $device_array) && in_array($_server, $server_array)){
		$sql = "select time, lat, lng, ip, device, idClient, idServer, volume from marker where device = :device and idServer = :idServer and time > DATE_SUB(:datetime, INTERVAL :interval SECOND) and time <= :datetime";
		$result = $connection->prepare($sql);
		$result->bindValue(':device', $_device, PDO::PARAM_STR);
		$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
	}else if(in_array($_device, $device_array) && $_server == ""){
		$sql = "select time, lat, lng, ip, device, idClient, idServer, volume from marker where device = :device and time > DATE_SUB(:datetime, INTERVAL :interval SECOND) and time <= :datetime";
		$result = $connection->prepare($sql);
		$result->bindValue(':device', $_device, PDO::PARAM_STR);
	}else if($_device == "" && in_array($_server, $server_array)){
		$sql = "select time, lat, lng, ip, device, idClient, idServer, volume from marker where idServer = :idServer and time > DATE_SUB(:datetime, INTERVAL :interval SECOND) and time <= :datetime";
		$result = $connection->prepare($sql);
		$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
	}else{
		$sql = "select time, lat, lng, ip, device, idClient, idServer, volume from marker where time > DATE_SUB(:datetime, INTERVAL :interval SECOND) and time <= :datetime";
		$result = $connection->prepare($sql);
	}
	$result->bindValue(':datetime', $_datetime, PDO::PARAM_STR);
	$result->bindValue(':interval', $_interval, PDO::PARAM_STR);
	$result->execute();
	
	//die(var_dump($result));
	
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	$features = array();
	$feature = array();
	while ($row = $result->fetch()){
		$feature = array("type" => "Feature",
						"geometry" => array("type" => "Point", "coordinates" => array((float)$row->lng, (float)$row->lat)),
						"properties" => array(
											"time" => $row->time,
											"ip" => $row->ip,
											"device" => $row->device,
											"idClient" => $row->idClient,
											"idServer" => $row->idServer,
											"volume" => $row->volume
										)
					);
		array_push($features, $feature);
	}
	$Markers = array("type" => "FeatureCollection", "features" => $features);
	$geojson = json_encode($Markers);
	return $geojson;
}
?>