<?php
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');

	include_once(ROOT_PATH.'config.php');
	include_once(ROOT_PATH.'model/db_connection.php');
	
	$clientMap = true;
	
	if(!isset($_GET['id']) || $_GET['id'] == "" ) {
		die("id required");
	} else if(!isset($_GET['pwd']) || $_GET['pwd'] == "" ) {
		die("password required");
	} else {
		$id = $_GET['id'];
		$pwd = $_GET['pwd'];
	}
	
	$sql = "SELECT * FROM client WHERE idClient=:idClient and pwd=:pwd";
	$result = $connection->prepare($sql);
	$result->bindValue(':idClient', $id, PDO::PARAM_STR);
	$result->bindValue(':pwd', $pwd, PDO::PARAM_STR);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	$row = $result->fetch();
	if(!$row)
		die("id or password not correct or not exist");
	else if($row->status != "valid")
		die("your id is expired, please contact the administrator");
	else
		include_once(ROOT_PATH.'view/swingmap.htm');
?>