<?php
include_once 'header.php';

echo "<div class='header'>Log Out</div><br />";

if (isset($_SESSION['email'])) {
	if (isset($_POST['confirm'])) {
		destroySession();
		echo "You have been logged out. please <a href='index.php'>click here</a> to refresh the screen.";
		echo "<br /><br />";
		header('Location: /index.php');
	} else	{
		echo "Are you sure you would like to log out?";
echo <<<_END
<form method='post' action='logout.php'>$error
<input type='hidden' name='confirm' value='1'>
<input type='submit' value='Confirm' />
</form>
_END;
	}
} else {
echo "You are not logged in.";
}

include_once 'footer.php';
?>
