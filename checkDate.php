<?php
include_once 'functions.php';

if(isset($_POST['dateValue'])) {
	$dateValue = sanitizeString($_POST['dateValue']);
	
	if((strlen($dateValue) > 0 && myCheckDate($dateValue)) || strlen($dateValue) == 0) {
		echo "<span class='available'>&nbsp;&#x2714;</span>";
	} else {
		echo "<span class='unavailable'>&nbsp&#x2716 Must be valid date in YYYY-MM-DD format</span>";
	}
}
?>
