<?php
	include_once('../model/db_connection.php');
	include_once('../model/get_clientGPS.php');
	
	$page = isset($_GET['page'])?$_GET['page']:'0';
	$line = isset($_GET['line'])?$_GET['line']:'20';
	
	$clientGPS = get_clientGPS($page, $line);
	
	echo "<table class='table table-hover'><tr><th>idClient</th><th>lat</th><th>lng</th><th></th></tr>";
	foreach($clientGPS as $value) {
		echo "<tr><td>".$value['idClient']."</td><td>".$value['lat']."</td><td>".$value['lng']."</td><td><input type='checkbox'></td></tr>";
	}			
	echo "<table>";
	
	$connection = null;
?>