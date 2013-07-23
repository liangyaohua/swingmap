<?php
	date_default_timezone_set('Europe/Paris');
	include_once('../model/db_connection.php');
	$max = (isset($_GET['max']) && $_GET['max'] != "")?$_GET['max']:100;
		
	$query = "insert into marker (time,lat,lng,ip,device,idDevice,idClient,idServer,volume) values ";

	for($i = 0; $i < $max; $i++){
		$time = date("Y-m-d H:i:s");
		$latlng = genLatlng();
		$lat = $latlng[0];
		$lng = $latlng[1];
		$ip = mt_rand(0,223).'.'.mt_rand(0,255).'.'.mt_rand(0,255).'.'.mt_rand(0,255);
		$device = genDevice();
		$idDevice = $device.mt_rand(1,9999);
		$idClient = genClient().mt_rand(1,9999).genServer();
		$idServer = genServer();
		$volume = mt_rand(1024,102400);

		$query .= "('".$time."','".$lat."','".$lng."','".$ip."','".$device."','".$idDevice."','".$idClient."','".$idServer."','".$volume."'), ";
	}
	$query = substr($query,0,-2);
	//die($query);
	try {
		$result = $connection->exec($query);
	}catch (PDOException $e) {
		die('Insertion failed: '.$e->getMessage()."\n");
	}
	echo "Insertion success: ".$result." messages";

	function genDevice(){
		$num = rand(1,100);
		if($num <=30)
			return "ios";
		else if($num > 35 && $num <=78)
			return "android";
		else if($num > 78 && $num <=91)
			return "wp";
		else
			return "server";
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
	function genLatlng(){
		$num = rand(1,100);
		if($num < 10){
			return array(0,0);
		}else{
			return array((float)(mt_rand(43, 53).'.'.mt_rand(0,999999)),(float)(mt_rand(-5, 23).'.'.mt_rand(0,999999)));
		}
	}
?>