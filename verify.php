<?php
include_once 'header.php';
echo "<br /><span class='main'>";

echo "<br /><div class='header'>Verify</div><br />";

if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
	$email = sanitizeString($_GET['email']);
	$hash = sanitizeString($_GET['hash']);

	$result = mysql_query("SELECT email,hash,isActive FROM users where email='$email' AND hash='$hash' AND isActive='0'") or die(mysql_error());
	$match = mysql_num_rows($result);

	if($match > 0) {
		$query="UPDATE users SET isActive='1' WHERE email='$email' AND hash='$hash' AND isActive='0'";
		mysql_query($query);
		$message="Your account has been activated. You are free to navigate about the site.";
	} else {
		$message="The information you've entered is not correct.";
	}
} else {
	$message="The email or hash is incorrect.";
}

echo "<div class='main'><h3>$message</h3></div>";

echo "</span><br />";

include_once 'footer.php';
?>
