<?php
	date_default_timezone_set('Europe/Paris');
	include_once('../model/db_connection.php');

	if(isset($_GET['data']) && $_GET['data'] != "") {
		$data = $_GET['data'];
		$clientGPS = json_decode($data);
		if($clientGPS == null)
			die($data."<br>data is not a valid json string");
	} else {
		die("data is empty");
	}

	$query = "insert into client_coordinate (idClient,lat,lng) values ";
	$fail = array();
	
	for($i = 0; $i < sizeof($clientGPS); $i++) {
		$idClient = isset($clientGPS[$i]->idClient)?$clientGPS[$i]->idClient:"";
		$lat = isset($clientGPS[$i]->lat)?$clientGPS[$i]->lat:"";
		$lng = isset($clientGPS[$i]->lng)?$clientGPS[$i]->lng:"";
		if($idClient != "" && $lat != "" && $lng != "") {
			$query .= "('".$idClient."','".$lat."','".$lng."'), ";
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
				echo "message ".$value.": ".json_encode($clientGPS[$value])."<br>";
			}
		}
	} catch (PDOException $e) {
		die('Insertion failed: '.$e->getMessage()."\n");
	}
?>