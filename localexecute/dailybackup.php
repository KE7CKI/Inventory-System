<?php
$dbhost = 'localhost';
$dbname = 'defy_inventory';
$dbuser = 'defyproducts';
$dbpass = 'MiracleDay01';

mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

$result = queryMysql("SELECT * FROM daily_log");
$resultSize = mysql_num_rows($result);

for($i = 0;$i < $resultSize;$i++) {
	$part = mysql_result($result, $i, 'number');
	$qty = mysql_result($result, $i, 'count');

	$response = queryMysql("UPDATE master_list SET count=count+$qty WHERE number='$part'");	

	echo "$response";
}

//queryMysql("DROP TABLE daily_log");

function queryMysql($query) {
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}
?>
