<?php
include_once 'functions.php';

if (isset($_POST['origPartNumber']) && isset($_POST['partNumber'])) {
	$origNumber = sanitizeString($_POST['origPartNumber']);
	$newNumber = sanitizeString($_POST['partNumber']);
	
	$exists = mysql_num_rows(queryMysql("SELECT * FROM master_list WHERE number='$newNumber'"));

	if($exists && $newNumber != $origNumber) {
		echo "<span class='unavailable'>&nbsp;&#x2716; Part already exists</span>";
	} else {
		echo "<span class='available'>&nbsp;&#x2714;</span>";
	}
}
?>
