<?php
	date_default_timezone_set('Europe/Paris');
	include_once('../model/db_connection.php');
	$max = (isset($_GET['max']) && $_GET['max'] != "")?$_GET['max']:100;
		
	$query = "insert into marker (time,lat,lng,ip,device,idDevice,idClient,idServer,volume) values ";

	for($i = 0; $i < $max; $i++) {
		$time = date("Y-m-d H:i:s");
		$latlng = genLatlng();
		$lat = $latlng[0];
		$lng = $latlng[1];
		$ip = mt_rand(0,223).'.'.mt_rand(0,255).'.'.mt_rand(0,255).'.'.mt_rand(0,255);
		$device = genDevice();
		$idDevice = $device.mt_rand(1,9999);
		$idClient = genClient().mt_rand(1,9999);
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

	function genDevice() {
		$num = rand(1,100);
		if($num <= 15)
			return "iPhone";
		else if($num > 15 && $num <= 30)
			return "iPad";
		else if($num > 30 && $num <= 52)
			return "Android";
		else if($num > 52 && $num <= 70)
			return "W32";
		else if($num > 70 && $num <= 79)
			return "Server";
		else
			return "Undetermined";
	}
	function genServer() {
		$num = rand(1,10);
		switch($num)
		{
			case 1:	return "SRV-DEV"; break;
			case 2: return "SRV-WEB10"; break;
			case 3: return "SWING-WEB11"; break;
			case 4: return "SWING-WEB8"; break;
			case 5: return "SRV-SWINGBOX"; break;
			case 6: return "SWING-WEB7-DE"; break;
			case 7: return "SRV-UNA77"; break;
			case 8: return "SWING-WEB9"; break;
			case 9: return "SWING-DEMO"; break;
			case 10: return "SRV-POCKET-FRON"; break;
		}
	}
	function genClient() {
		$num = rand(1,4);
		switch($num)
		{
			case 1:	return "SB"; break;
			case 2: return "SA"; break;
			case 3: return "SS"; break;
			case 4: return "SG"; break;
		}
	}
	function genLatlng() {
		$num = rand(1,100);
		if($num < 10) {
			return array(0,0);
		} else {
			return array((float)(mt_rand(43, 53).'.'.mt_rand(0,999999)),(float)(mt_rand(-5, 23).'.'.mt_rand(0,999999)));
		}
	}
?>