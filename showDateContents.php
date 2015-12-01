<?php
include_once 'header.php';
$dateCheck = "";
if(isset($_POST['dateToCheck'])) {
	$dateCheck = sanitizeString($_POST['dateToCheck']);
}

echo "<div class='header'>Daily Log ($dateCheck)</div>";

if($loggedin && $isActive) {
	$slashlessDateCheck = str_replace('-', '', $dateCheck);
	$slashlessDateCheck = str_replace('/', '', $slashlessDateCheck);
	$fr = fopen("inventory_logs/$slashlessDateCheck.dailylog.txt", "r");

	if(!$fr) {
		echo "No file found for date specified.";
	} else {
		echo "<table class='sortable'>";
		do {
			echo "<tr>";
			$data = fgets($fr);
			$data_array = explode("\t", $data);
			echo "<td>" . $data_array[0] . "</td><td>" .$data_array[1]  . "</td><td>" . $data_array[2] . "</td>";
			echo "</tr>";
		} while ($data);
		echo "</table>";

		fclose($fr);

		echo "<br /><a href='inventory_logs/$slashlessDateCheck.dailylog.txt'>$slashlessDateCheck.dailylog.txt</a><br />";
	}
} else {

}

include_once 'footer.php';
?>
