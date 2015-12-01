<?php
include_once 'functions.php';

if (isset($_POST['user'])) {
	$user = sanitizeString($_POST['user']);

	if(mysql_num_rows(queryMysql("SELECT * FROM users WHERE email='$user'"))) {
		echo "<span class='exists'>&nbsp;&#x2716; Account already exists</span>";
	} else if(mysql_num_rows(queryMysql("SELECT * FROM employees WHERE email='$user'"))) {
		echo "<span class='available'>&nbsp;&#x2714;</span>";
	} else {
		echo "<span class='unavailable'>&nbsp;&#x2716; Invalid email</span>";
	}
}
?>
