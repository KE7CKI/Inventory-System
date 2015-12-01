<?php
session_set_cookie_params(0, '/','defy.titansp.com');
session_start();
echo "<!DOCTYPE html>\n<html><head>";

echo <<<_END
<script type="text/javascript" src="js/functions.js" ></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png" />
_END;

include 'functions.php';

$userstr = ' (Guest)';

if(isset($_SESSION['email'])) 
{
	$email = $_SESSION['email'];
	$forname = $_SESSION['forname'];
	$surname = $_SESSION['surname'];
	$loggedin = TRUE;
	$isActive = $_SESSION['isActive'];
	$userLevel = $_SESSION['userLevel'];
	$userstr = " ($forname $surname : $userLevel)";
} 
else $loggedin = FALSE;

echo <<<_END
	<title>$appname$userstr</title>
	<link rel='stylesheet' href='css/style.css' type='text/css' />
	<link rel='stylesheet' href='css/tabs.css' type='text/css' />
	<link rel='stylesheet' href='css/accordion.css' type='text/css' />
</head>
<body>
	<div class='appname'>$appname$userstr</div>
_END;

echo "<div class='main'>";

echo "<br /><ul class='menu'>";

if ($userLevel > 1) {
	echo "<li><a href='index.php'>Home</a></li>" .
	     "<li><a href='master.php'>Master List</a></li>" .
             "<li><a href='daily.php'>Daily Log</a></li>" .
	     "<li><a href='alter.php'>Check Out/In</a></li>" .
	     "<li><a href='reports.php'>Reports</a></li>" .
	     "<li><a href='pull.php'>Pull Request</a></li>" .
	     "<li><a href='admin.php'>Administration</a></li>" .
	     "<li><a href='logout.php'>Log Out</a></li>";
} else if ($userLevel > -1) {
	echo "<li><a href='index.php'>Home</a></li>" .
	     "<li><a href='master.php'>Master List</a></li>" .
             "<li><a href='daily.php'>Daily Log</a></li>" .
	     "<li><a href='alter.php'>Check Out/In</a></li>" .
	     "<li><a href='reports.php'>Reports</a></li>" .
	     "<li><a href='pull.php'>Pull Request</a></li>" .
	     "<li><a href='logout.php'>Log Out</a></li>";
} else {
	echo "<li><a href='index.php'>Home</a></li>" .
	     "<li><a href='signup.php'>Sign Up</a></li>" .
	     "<li><a href='login.php'>Log in</a></li>";
}
echo "</ul>";

echo "<div class='content'>";
?>
