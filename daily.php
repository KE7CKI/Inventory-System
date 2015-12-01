<?php
include_once 'header.php';
echo "<div class='header'>Daily Log</div>";

if($loggedin && $isActive) {
echo <<<_END
<script src='js/sorttable.js'></script>
<div class='search'>
<form method='get' action='daily.php' name='searchForm'>$error
<input type='text' maxlength='128' name='search' />
<input type='submit' value='Search' />
</form>
</div>
<br />
_END;

	if(isset($_GET['search'])) {
		$search = sanitizeString($_GET['search']);
		$query = "SELECT * FROM daily_log WHERE number LIKE '%".$search."%'";
		unset($_GET['search']);
	} else {
		$query = "SELECT * FROM daily_log";
	}
	$result = queryMysql($query);
	$numRows = mysql_num_rows($result);
	
	if($numRows) {
		echo "<table class='sortable'>";
		echo "<col width='30px'><col width='150px'><col width='100px'>";
		echo "<tr><th></th><th>Part Number</th><th>Inv.</th></tr>";

		for($i = 0; $i < $numRows; $i++) {
			$partNumber = mysql_result($result, $i, 'number');
			$count = mysql_result($result, $i, 'count');
			$j = $i+1;
			
			if($i%2==0) {
				$rowColor = '#909090';
			} else {
				$rowColor = '';
			}

			echo "<tr style='background-color:$rowColor'><td>$j</td><td>$partNumber</td><td>$count</td></tr>";
		}
		echo "</table>";
	} else {
		echo "No entries made or no match found.";
	}
} else {
	echo "Logged out or inactive.";
}

echo <<<_END
<script type="text/javascript" language="JavaScript">
document.searchForm.search.focus();
</script>
_END;

include_once 'footer.php';
?>
