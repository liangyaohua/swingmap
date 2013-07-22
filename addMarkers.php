<?php
	date_default_timezone_set('Europe/Paris');
	include_once('model/db_connection.php');

	if(isset($_GET['data']) && $_GET['data'] != "") {
		$data = $_GET['data'];
		$markersArray = json_decode($data);
	} else {
		die("error");
	}
	
	$query = "insert into marker (time,lat,lng,ip,device,idDevice,idClient,idServer,volume) values ";
	$fail = array();
	
	for($i = 0; $i < sizeof($markersArray); $i++) {
		$time = isset($markersArray[$i]->time)?$markersArray[$i]->time:date("Y-m-d H:i:s");
		$lat = isset($markersArray[$i]->lat)?$markersArray[$i]->lat:0;
		$lng = isset($markersArray[$i]->lng)?$markersArray[$i]->lng:0;
		$ip = isset($markersArray[$i]->ip)?$markersArray[$i]->ip:"";
		$device = isset($markersArray[$i]->device)?$markersArray[$i]->device:"";
		$idDevice = isset($markersArray[$i]->idDevice)?$markersArray[$i]->idDevice:"";
		$idClient = isset($markersArray[$i]->idClient)?$markersArray[$i]->idClient:"";
		$idServer = isset($markersArray[$i]->idServer)?$markersArray[$i]->idServer:"";
		$volume = isset($markersArray[$i]->volume)?$markersArray[$i]->volume:0;
		if(isValidDateTime($time) && filter_var($ip, FILTER_VALIDATE_IP) && $device != "" && $idDevice != "" && $idClient != "" && $idServer != "" && $volume != 0) {
			$query .= "('".$time."','".$lat."','".$lng."','".$ip."','".$device."','".$idDevice."','".$idClient."','".$idServer."','".$volume."'), ";
		} else {
			array_push($fail, $i);
		}
	}
	$query = substr($query,0,-2);
	//die($query);
	try {
		$result = $connection->exec($query);
	}catch (PDOException $e) {
		die('Insertion failed: '.$e->getMessage()."\n");
	}
	echo "Insertion success: ".$result." messages<br>";
	echo "Insertion failed: ";
	foreach($fail as $value) {
		print_r($markersArray[$value]);
	}
	
	function isValidDateTime($dateTime) 
	{ 
		if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) { 
			if (checkdate($matches[2], $matches[3], $matches[1])) { 
				return true; 
			} 
		} 
		return false; 
	} 
?>