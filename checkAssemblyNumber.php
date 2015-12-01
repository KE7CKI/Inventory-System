<?php
include_once 'functions.php';

if (isset($_POST['origAssemblyNumber']) && isset($_POST['assemblyNumber'])) {
	$origNumber = sanitizeString($_POST['origAssemblyNumber']);
	$newNumber = sanitizeString($_POST['assemblyNumber']);
	
	$exists = mysql_num_rows(queryMysql("SELECT * FROM assemblies_master_list WHERE number='$newNumber'"));

	if($exists && $newNumber != $origNumber) {
		echo "<span class='unavailable'>&nbsp;&#x2716; Assembly already exists</span>";
	} else {
		echo "<span class='available'>&nbsp;&#x2714;</span>";
	}
}
?>
