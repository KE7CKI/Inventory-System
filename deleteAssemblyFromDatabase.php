<?php
include_once 'functions.php';

if (isset($_POST['assembly'])){
	$assembly = sanitizeString($_POST['assembly']);
	
	$query = "DROP TABLE $assembly";
	$result = queryMysql($query);
	
	$query = "DELETE FROM assemblies_master_list WHERE number='$assembly'";
	$result = queryMysql($query);
	echo "$result";
}
?>
