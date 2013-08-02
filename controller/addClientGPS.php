<?php
	date_default_timezone_set('Europe/Paris');
	include_once('../model/db_connection.php');

	if(isset($_GET['data']) && $_GET['data'] != "" && $_GET['data'] != "[]") {
		$data = $_GET['data'];
		$clientGPS = json_decode($data);
		if($clientGPS == null)
			die("<div class='alert alert-error'>".$data." is not a valid json string</div>");
	} else {
		die("<div class='alert alert-error'>data is empty</div>");
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
			echo "<div class='alert alert-success'>Insertion success: ".$result." messages</div>";
		if(sizeof($fail) > 0) {
			echo "<div class='alert alert-error'>Failed: please check if the following message is correct<br/>";
			foreach($fail as $value) {
				echo "message ".$value.": ".json_encode($clientGPS[$value])."<br/>";
			}
			echo "</div>";
		}
	} catch (PDOException $e) {
		die("<div class='alert alert-error'>Insertion failed: ".$e->getMessage()."</div>");
	}
?>