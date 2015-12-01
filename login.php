<?php
include_once 'header.php';

echo "<div class='header'>Log In</div>";

echo "<h3>Please enter your details to log in.</h3>";

$error = $email = $pass = $forname = $surname = $isActive = $userLevel = "";

if (isset($_POST['email']))
{
	$email = sanitizeString($_POST['email']);
	$pass = sanitizeString($_POST['pass']);

	if ($email == "" || $pass == "")
	{
		$error = "Not all fields were entered<br />";
	}
	else
	{
		$hashed = hashPass("$pass");
		$query = "SELECT email,password,forname,surname,isActive,userLevel FROM users where email='$email' AND password='$hashed'";

		$result = queryMysql($query);
		if (mysql_num_rows($result) == 0) {
			$error = "<span class='error'>Email/Password invalid</span><br /><br />";
		} else {
			$_SESSION['email'] = $email;
			$_SESSION['pass'] = $hashed;
			$_SESSION['forname'] = mysql_result($result, 0, 'forname');
			$_SESSION['surname'] = mysql_result($result, 0, 'surname');
			$_SESSION['isActive'] = mysql_result($result, 0, 'isActive');
			$_SESSION['userLevel'] = mysql_result($result, 0, 'userLevel');
			
			header('Location: /index.php');
			
			echo "You are now logged in. Please <a href='index.php'>click here</a> to continue<br /><br />";

			echo <<<_END
<script type="text/javascript" language="JavaScript">
window.location = 'index.php';
</script>
_END;
		}
	}
}

echo <<<_END
<form method='post' action='login.php'>$error
<span class='fieldname'>Email</span>
<input type='email' maxlength='64' name='email' value='$email' /><br />
<span class='fieldname'>Password</span>
<input type='password' maxlength='128' name='pass' value='$pass' /><br />
<span class='fieldname'>&nbsp;</span>
<input type='submit' value='Login' />
</form>
_END;

include_once 'footer.php';
?>
