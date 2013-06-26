<?php
	date_default_timezone_set('Europe/Paris');
	include_once('../model/db_connection.php');
	$max = (isset($_GET['max']) && $_GET['max'] != "")?$_GET['max']:100;
		
	$query = "insert into marker (time,lat,lng,ip,device,idClient,idServer,volume,idIP_Coordinate,idClient_Coordinate) values ";

	for($i = 0; $i < $max; $i++){
		$time = date("Y-m-d H:i:s");
		$lat = (float)(mt_rand(43, 53).'.'.mt_rand(0,999999));
		$lng = (float)(mt_rand(-5, 23).'.'.mt_rand(0,999999));
		$ip = mt_rand(0,223).'.'.mt_rand(0,255).'.'.mt_rand(0,255).'.'.mt_rand(0,255);
		$device = genDevice();
		$idClient = genClient().mt_rand(1,9999).genServer();
		$idServer = genServer();
		$volume = mt_rand(1024,102400);
		$idIP_Coordinate = mt_rand(1,1000);
		$idClient_Coordinate = mt_rand(1,1000);

		$query .= "('".$time."','".$lat."','".$lng."','".$ip."','".$device."','".$idClient."','".$idServer."','".$volume."','".$idIP_Coordinate."','".$idClient_Coordinate."'), ";
	}
	$query = substr($query,0,-2);
	//die($query);
	try {
		$result = $connection->exec($query);
	}catch (PDOException $e) {
		die('Insertion failed: '.$e->getMessage()."\n");
	}
	echo "<script language='Javascript'> alert('Insertion success: ".$result." messages'); </script>";

	function genDevice(){
		$num = rand(1,4);
		switch($num)
		{
			case 1:	return "ios"; break;
			case 2: return "android"; break;
			case 3: return "wp"; break;
			case 4: return "server"; break;
		}
	}
	function genServer(){
		$num = rand(1,4);
		switch($num)
		{
			case 1:	return "A"; break;
			case 2: return "B"; break;
			case 3: return "C"; break;
			case 4: return "D"; break;
		}
	}
	function genClient(){
		$num = rand(1,4);
		switch($num)
		{
			case 1:	return "SB"; break;
			case 2: return "SA"; break;
			case 3: return "SS"; break;
			case 4: return "SG"; break;
		}
	}
?>