<?php
	date_default_timezone_set('Europe/Paris');
	include_once('../model/db_connection.php');
	
	$latLngs=array(array(-0.878906,45.767523),array( 
	-0.834961,46.437857),array( 
	-0.834961,46.55886),array( 
	-0.834961,46.769968),array( 
	-0.834961,46.890232),array( 
	-0.834961,47.100045),array( 
	-0.834961,47.279229),array( 
	-0.834961,47.487513),array( 
	-0.834961,47.665387),array( 
	-0.834961,47.842658),array( 
	-0.65918,47.517201),array( 
	-0.615234,47.428087),array( 
	-0.615234,47.309034),array( 
	-0.483398,47.189712),array( 
	-0.351562,47.070122),array( 
	-0.219727,46.860191),array( 
	-0.131836,46.679594),array( 
	-0.043945,46.55886),array( 
	0,46.377254),array( 
	0.087891,46.739861),array( 
	0.087891,46.950262),array( 
	0.087891,47.129951),array( 
	0.087891,47.338823),array( 
	0.087891,47.517201),array( 
	0.087891,47.724545),array( 
	0.043945,47.931066),array( 
	0.263672,47.694974),array( 
	0.263672,47.517201),array( 
	0.395508,47.368594),array( 
	0.351563,47.279229),array( 
	0.483398,47.15984),array( 
	0.483398,47.040182),array( 
	0.615234,46.860191),array( 
	0.703125,46.649436),array( 
	0.791016,46.407564),array( 
	0.878906,46.255847),array( 
	0.922852,46.13417),array( 
	0.966797,46.012224),array( 
	1.274414,46.830134),array( 
	1.625977,46.830134),array( 
	1.801758,47.010226),array( 
	1.801758,47.189712),array( 
	1.538086,47.338823),array( 
	1.230469,47.309034),array( 
	1.142578,47.189712),array( 
	1.142578,47.070122),array( 
	1.230469,46.980252),array( 
	1.450195,46.709736),array( 
	1.625977,46.589069),array( 
	1.889648,46.55886),array( 
	2.109375,46.649436),array( 
	2.109375,46.800059),array( 
	2.021484,47.724545),array( 
	2.241211,47.487513),array( 
	2.285156,47.338823),array( 
	2.285156,47.249407),array( 
	2.416992,47.100045),array( 
	2.460938,46.980252),array( 
	2.504883,46.890232),array( 
	2.636719,46.830134),array( 
	2.285156,47.635784),array( 
	2.460938,47.754098),array( 
	2.636719,47.783635),array( 
	3.55957,47.754098),array( 
	3.295898,47.872144),array( 
	3.164063,47.872144),array( 
	2.988281,47.665387),array( 
	2.944336,47.517201),array( 
	2.944336,47.279229),array( 
	3.032227,47.189712),array( 
	3.208008,47.010226),array( 
	3.47168,46.920255),array( 
	3.735352,46.920255),array( 
	3.867188,46.980252),array( 
	3.999023,47.100045),array( 
	4.130859,47.219568),array( 
	4.086914,48.019324),array( 
	4.306641,47.813155),array( 
	4.438477,47.694974),array( 
	4.482422,47.576526),array( 
	4.526367,47.457809),array( 
	4.570313,47.338823),array( 
	4.570313,47.249407),array( 
	4.658203,47.15984),array( 
	3.735352,48.370848),array( 
	3.867188,48.487486),array( 
	-0.85144,45.771355),array( 
	-0.85144,45.863238),array( 
	-0.856934,45.886185),array( 
	-0.856934,45.920587),array( 
	-0.856934,45.970243),array( 
	-0.856934,46.008409),array( 
	-0.856934,46.061797),array( 
	-0.856934,46.096091),array( 
	-0.856934,46.149394),array( 
	-0.856934,46.198844),array( 
	-0.862427,46.240652),array( 
	-0.862427,46.282428),array( 
	-0.862427,46.316584),array( 
	1.834717,47.61357),array( 
	1.878662,47.554287),array( 
	1.911621,47.487513),array(  
	1.988525,47.376035),array( 
	2.032471,47.331377)); 
	
	$max = sizeof($latLngs);
		
	$query = "insert into marker (time,lat,lng,ip,device,idDevice,idClient,idServer,volume) values ";

	for($i = 0; $i < $max; $i++) {
		$time = date("Y-m-d H:i:s");
		$latLng = $latLngs[$i];
		$lat = $latLng[1];
		$lng = $latLng[0];
		$ip = "";
		$device = "liang";
		$idDevice = "";
		$idClient = genClient().mt_rand(0,999999);
		$idServer = "";
		$volume = 0;

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
		$num = rand(1,3);
		switch($num)
		{
			case 1:	return "Merci"; break;
			case 2: return "Swing"; break;
			case 3: return "Michel"; break;
			//case 4: return "Je t\'aime"; break;
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