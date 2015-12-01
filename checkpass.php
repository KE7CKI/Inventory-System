<?php
include_once 'functions.php';

if (isset($_POST['pass1']) && isset($_POST['pass2'])) {
	$pass1 = sanitizeString($_POST['pass1']);
	$pass2 = sanitizeString($_POST['pass2']);

	if($pass1 == $pass2) {
		echo "<span class='available'>&nbsp;&#x2714;</span>";
	} else {
		echo "<span class='exists'>&nbsp;&#x2716; Password mis-match</span>";
	}
}
?>
