<?php
include_once 'functions.php';

if (isset($_POST['part']) && isset($_POST['qty']) && isset($_POST['action'])) {
	$action = sanitizeString($_POST['action']);
	$part = sanitizeString($_POST['part']);
	$qty = sanitizeString($_POST['qty']);

	$result = queryMysql("SELECT * FROM daily_log WHERE number='$part'");
	$currentDaily = mysql_result($result, 0, 'count');
	$result = queryMysql("SELECT * FROM master_list WHERE number='$part'");
	$currentCount = mysql_result($result, 0, 'count');

	if(mysql_num_rows($result) < 1) { //no item matches that part number
		echo 1;
	} else if($action == "out" && ($currentCount + $currentDaily) - $qty < 0) { //Cannot take out more than we have.
		echo 2;
	} else {
		echo 0;
	}
}
?>
