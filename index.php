<?php
	define('ROOT_PATH', preg_replace("/\\\/",'/', dirname(__FILE__)).'/');

	include_once(ROOT_PATH.'config.php');
	
	$style = (isset($_GET['style']) && $_GET['style'] != "")?$_GET['style']:"s2";

	include_once(ROOT_PATH.'view/swingmap.htm');
?>