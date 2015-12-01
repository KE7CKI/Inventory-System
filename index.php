<?php
include_once 'header.php';

echo "<div class='header'>Home</div>";

if ($loggedin && $isActive) {
	echo "You are logged in as $forname $surname, and your account is active. <br />Your user level is $userLevel";
} else {
	echo "You are not logged in or not active.";
}

echo "<br /><br />";

include_once 'footer.php';
?>
