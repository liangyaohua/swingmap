<?php
	date_default_timezone_set('Europe/Paris');
	include_once('model/db_connection.php');

	if(isset($_GET['data']) && $_GET['data'] != "") {
		$data = $_GET['data'];
		$markersArray = json_decode($data);
		if($markersArray == null)
			die($data."<br>data is not a valid json string ");
	} else {
		die("data is empty");
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
		if($time != "" && filter_var($ip, FILTER_VALIDATE_IP) && $device != "" && $idDevice != "" && $idClient != "" && $idServer != "") {
			$query .= "('".$time."','".$lat."','".$lng."','".$ip."','".$device."','".$idDevice."','".$idClient."','".$idServer."','".$volume."'), ";
		} else {
			array_push($fail, $i);
		}
	}
	$query = substr($query,0,-2); // remove the last comma
	//die($query);
	
	try {
		$result = $connection->exec($query);
		if($result)
			echo "Insertion success: ".$result." messages<br>";
		if(sizeof($fail) > 0) {
			echo "Failed: please check if the following message is correct"."<br>";
			foreach($fail as $value) {
				echo "message ".$value.": ".json_encode($markersArray[$value])."<br>";
			}
		}
	} catch (PDOException $e) {
		die('Insertion failed: '.$e->getMessage()."\n");
	}
?>