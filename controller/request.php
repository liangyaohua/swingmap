<?php
	include_once('../model/db_connection.php');
	
	$cmd = (isset($_GET['cmd'])&&($_GET['cmd']!=''))?$_GET['cmd']:'delete'; // delete or update
	$idClient = isset($_GET['client'])?$_GET['client']:'';
	
	if($cmd == 'delete') {
		if($idClient != '')
			$sql = "delete from client_coordinate where idClient='".$idClient."'";
		else
			die("<div class='alert alert-error'>idClient is empty</div>");
	} else if($cmd == 'update') {
		$lat = isset($_GET['lat'])?$_GET['lat']:'';
		$lng = isset($_GET['lng'])?$_GET['lng']:'';
		if($idClient != '' && $lat != '' && $lng == '')
			$sql = "update client_coordinate set lat=".$lat." where idClient='".$idClient."'";
		else if($idClient != '' && $lat == '' && $lng != '')
			$sql = "update client_coordinate set lng=".$lng." where idClient='".$idClient."'";
		else if($idClient != '' && $lat != '' && $lng != '')
			$sql = "update client_coordinate set lat=".$lat.",lng=".$lng." where idClient='".$idClient."'";
		else
			die("<div class='alert alert-error'>lat and lng or idClient is empty</div>");
	} else {
		die("<div class='alert alert-danger'>forbidden request</div>");
	}
	//die($sql);
	$result = $connection->exec($sql);
	if($result)
		echo "<div class='alert alert-success'>done!</div>";
	else
		echo "<div class='alert alert-danger'>idClient not exist or nothing to be changed</div>";
	$connection = null;
?>