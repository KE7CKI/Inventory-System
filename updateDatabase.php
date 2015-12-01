<?php
include_once 'functions.php';

if (isset($_POST['jsondata'])) {
	$array = json_decode($_POST['jsondata']);
	$length = count($array);	
	
	for($i = 0; $i < $length; $i++) {
		$action = $array[$i][0];
		$quantity = $array[$i][1];
		$part = $array[$i][2];
	
		alterDatabase($action, $quantity, $part);	
	}
}
?>
