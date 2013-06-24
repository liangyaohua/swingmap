<?php
	include_once('./dbinfo.php');
	
	$_device = isset($_GET['device'])?$_GET['device']:"";
	$_server = isset($_GET['server'])?$_GET['server']:"";
	$_interval = isset($_GET['interval'])?$_GET['interval']:"60";
	
	$device_array = array("ios", "android", "wp");
	$server_array = array("A", "B", "C", "D");
	
	if(!in_array($_device, $device_array) && $_device != ""){
		die("Device not exist: ".$_device);
	}
	if(!in_array($_server, $server_array) && $_server != ""){
		die("Server not exist: ".$_server);
	}
	
	// Opens a connection to a MySQL server
	try {
		$connection = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$database, $username, $password);
	} catch (PDOException $e) {
		die('Connection failed: '.$e->getMessage()."\n");
	}
	
	if(in_array($_device, $device_array) && in_array($_server, $server_array)){
		$sql = "select time, lat, lng, ip, device, idClient, idServer, volume from marker where device = :device and idServer = :idServer and time > DATE_SUB(now(), INTERVAL :interval SECOND)";
		$result = $connection->prepare($sql);
		$result->bindValue(':device', $_device, PDO::PARAM_STR);
		$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
	}else if(in_array($_device, $device_array) && $_server == ""){
		$sql = "select time, lat, lng, ip, device, idClient, idServer, volume from marker where device = :device and time > DATE_SUB(now(), INTERVAL :interval SECOND)";
		$result = $connection->prepare($sql);
		$result->bindValue(':device', $_device, PDO::PARAM_STR);
	}else if($_device == "" && in_array($_server, $server_array)){
		$sql = "select time, lat, lng, ip, device, idClient, idServer, volume from marker where idServer = :idServer and time > DATE_SUB(now(), INTERVAL :interval SECOND)";
		$result = $connection->prepare($sql);
		$result->bindValue(':idServer', $_server, PDO::PARAM_STR);
	}else{
		$sql = "select time, lat, lng, ip, device, idClient, idServer, volume from marker where time > DATE_SUB(now(), INTERVAL :interval SECOND)";
		$result = $connection->prepare($sql);
	}
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
	echo "setMarkers(".json_encode($Markers).")";
?>