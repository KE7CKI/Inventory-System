<?php
include_once 'header.php';
echo "<div class='header'>Reports</div>";


if($loggedin && $isActive) {
	echo "Logged in and active.";
} else {
	echo "Logged out or inactive.";
}

echo "<br /><br /><br />";

include_once 'footer.php';
?>
