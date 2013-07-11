<?php
	// database info
	$host = "localhost";
	$port = 3306;
	$username = "yliang";
	$password = "112358";
	$database = "swingmap";
	
	// Opens a connection to a MySQL server
	try {
		$connection = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$database, $username, $password);
	} catch (PDOException $e) {
		die('Connection failed: '.$e->getMessage()."\n");
	}
?>
