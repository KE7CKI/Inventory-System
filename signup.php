<?php // signup.php
include_once 'header.php';

echo "<div class='header'>Sign Up</div>";

echo "<h3>Please enter your details to sign up.</h3>";

$error = $email = $pass = $pass2 = $forname = $surname = "";
if (isset($_SESSION['email'])) destroySession();

if (isset($_POST['email']))
{
	$email = sanitizeString($_POST['email']);
	$pass = sanitizeString($_POST['pass']);
	$pass2 = sanitizeString($_POST['pass2']);
	$forname = sanitizeString($_POST['forname']);
	$surname = sanitizeString($_POST['surname']);
	
	if ($email == "" || $pass == "" || $pass2 = "" || $forname == "" || $surname == "") {
		$error = "Please fill all fields<br /><br />";
	} else if (isset($_POST['pass']) && isset($_POST['pass2']) && $_POST['pass'] != $_POST['pass2']) {
		$error = "Password mismatch<br />";
	} else if (!(mysql_num_rows(queryMysql("SELECT * FROM employees WHERE email='$email'")))) {
		$error = "You must have a valid @defy-products.com email.<br />";
	} else if (mysql_num_rows(queryMysql("SELECT * FROM users WHERE email='$email'"))) {
		$error = "An account already exists with that email.<br /><br />";
	} else {	
		#insert user into database
		$hash = sha1(mt_rand());
		addUser($email, $pass, $forname, $surname, $hash);
		
		/*
		$to = $email;
		$subject = 'Signup | Verification';
		$message = '

		Thanks for signing up.
		
		Your account has been created. You can log in with the following email and password after you\'ve activated your account.

		--------------------
		Email: '.$email.'
		Password: '.$pass.'

		Please click this link to activate your account:
		http://www.titansp.com/verify.php?email='.$email.'&hash='.$hash.'

		';

		$headers = 'From:Admin@titansp.com' . "\r\n";
		mail($to, $subject, $message, $headers);
		*/
		die("<h4>Account created</h4>Please see your administrator about account activation.<br /><br />");
	}
}

echo <<<_END
<form method='post' action='signup.php' name='signupForm'>$error
<span class='fieldname'>Email: </span>
<input type='email' maxlength='64' name='email' value='$email' onBlur='checkUser(this)'/><span id='userInfo'></span><br />
<span class='fieldname'>Password: </span>
<input type='password' maxlength='128' name='pass' value='$pass' /><br />
<span class='fieldname'>Re-type Password: </span>
<input type='password' maxlength='128' name='pass2' value='$pass2' onBlur='checkPass()'/><span id='passwordConfirmInfo'></span><br />
<span class='fieldname'>First Name: </span>
<input type='text' maxlength='32' name='forname' value='$forname' /><br />
<span class='fieldname'>Last Name: </span>
<input type='text' maxlength='32' name='surname' value='$surname' /><br />

<span class='fieldname'>&nbsp;</span>
<input type='submit' value='Sign Up' />
</form>
_END;

include_once 'footer.php';
?>
