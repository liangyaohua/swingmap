<?php
	function get_total_device($_device){
		global $connection, $device_array, $server_array;
		
		if(in_array($_device, $device_array)){
			$sql = "select count(distinct idDevice) as total from marker where device = :device group by idServer";
			$result = $connection->prepare($sql);
			$result->bindValue(':device', $_device, PDO::PARAM_STR);
		}else{
			die("Device or server not exist!");
		}
		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		$total_by_device = array();
		$total_device = 0;
		for($i = 0; $i < sizeof($server_array) && $row = $result->fetch(); $i++){
			$total_by_device[$server_array[$i]] = $row->total;
			$total_device += $row->total;
		}
		$total_by_device['total'] = $total_device;
		return $total_by_device;
	}
?>