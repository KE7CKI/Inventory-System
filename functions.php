<?php
include_once('/var/www/mysql_login.php');

$appname = 'Defy Inventory System';

mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

function queryMysql($query) {
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}

function destroySession() {
	$_SESSION=array();

	if (session_id() == "" || isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-2592000, '/');

	session_destroy();
}

function sanitizeString($var) {
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return mysql_real_escape_string($var);
}

function hashPass($var) {
	$hash = sha1("$salt1$var$salt2");
	return $hash;
}

function addUser($email, $password, $forname, $surname, $hash){
	$hashed = hashPass("$password");
	queryMysql("INSERT INTO users VALUES('$email', '$hashed', '$forname', '$surname', '$hash', 0)");
}

function isURL($var) {
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
	$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
	$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
	$regex .= "(\:[0-9]{2,5})?"; // Port 
	$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
	$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
	$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 

	if(preg_match("/^$regex$/", $var)) 
       	{ 
        	return true; 
       	} 
}

function alterDailyDatabase($act, $qty, $part) {
	$result = queryMysql("SELECT * FROM master_list WHERE number='$part'");
	$partNumberDaily = mysql_result($result, 0, 'number');
	$countMaster = mysql_result($result, 0, 'count');

	$result = queryMysql("SELECT * FROM daily_log WHERE number='$part'");
	$partExists = mysql_num_rows($result);
	$partNumberDaily = mysql_result($result, 0, 'number');
	$countDaily = mysql_result($result, 0, 'count');


	if($act == "in") {
		if($partExists) {
			$query = "UPDATE daily_log SET count=count+$qty WHERE number='$part'";
		} else {
			$sizeOfDailyLog = mysql_num_rows(queryMysql("SELECT * FROM daily_log"));
			$query = "INSERT INTO daily_log VALUES('$sizeOfDailyLog', '$part', '$qty')";
		}		
	} else if($act == "out") {
		if($countMaster+$countDaily-$qty < 0) {
			return 0;
		} else {
			if($partExists) {
				$query = "UPDATE daily_log SET count=count-$qty WHERE number='$part'";
			} else {
				$sizeOfDailyLog = mysql_num_rows(queryMysql("SELECT * FROM daily_log"));
				$query = "INSERT INTO daily_log VALUES('$sizeOfDailyLog', '$part', '-$qty')";
			}
		}
	}
	queryMysql($query);
	return 1;
}

function myCheckDate($dateValue) {
	$response = false;
	$formatMatch = preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $dateValue);
	
	if($formatMatch) {
		$dateParts = explode("-", $dateValue);
		$response = checkDate($dateParts[1], $dateParts[2], $dateParts[0]);	
	}
	return $response;
}
?>
