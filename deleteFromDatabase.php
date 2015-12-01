<?php
include_once 'functions.php';

if (isset($_POST['part'])){
	$part = sanitizeString($_POST['part']);
	
	$query = "DELETE FROM master_list WHERE number='$part'";
	$result = queryMysql($query);
	echo "$result";
}
?>
