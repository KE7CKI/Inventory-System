<?php
include_once 'header.php';

if($loggedin && $isActive) {

	$partsHeader = $assembliesHeader = "";
	$queryParts = $queryAssemblies = "";
	$numRowsParts = $numRowsAssemblies = "";
	
	if(isset($_POST['searchParts']) && !isset($_POST['searchAssemblies'])) {
		$partsHeader = "<div id='tab1' class='tab active'>";
		$assembliesHeader = "<div id='tab2' class='tab'>";
		$tab1Active = "<li class='active'><a href='#tab1'>Master Item List</a></li>";
		$tab2Active = "<li><a href='#tab2'>Master Assemblies List</a></li>";
		
		$searchParts = sanitizeString($_POST['searchParts']);
		$queryParts = "SELECT * FROM master_list WHERE number LIKE '%".$searchParts."%' OR description LIKE '%".$searchParts."%'";
		unset($_POST['searchParts']);
		
		$queryAssemblies = "SELECT * FROM assemblies_master_list";
	} else if(isset($_POST['searchAssemblies']) && !isset($_POST['searchParts'])) {
		$partsHeader = "<div id='tab1' class='tab'>";
		$assembliesHeader = "<div id='tab2' class='tab active'>";
		$tab1Active = "<li><a href='#tab1'>Master Item List</a></li>";
		$tab2Active = "<li class='active'><a href='#tab2'>Master Assemblies List</a></li>";
		
		$queryParts = "SELECT * FROM master_list";
	
		$searchAssemblies = sanitizeString($_POST['searchAssemblies']);
		$queryAssemblies = "SELECT * FROM assemblies_master_list WHERE number LIKE '%".$searchAssemblies."%' OR description LIKE '%".$searchAssemblies."%'";
		unset($_POST['searchAssemblies']);
	} else {
		$partsHeader = "<div id='tab1' class='tab active'>";
		$assembliesHeader = "<div id='tab2' class='tab'>";
		$tab1Active = "<li class='active'><a href='#tab1'>Master Item List</a></li>";
		$tab2Active = "<li><a href='#tab2'>Master Assemblies List</a></li>";
		
		$queryParts = "SELECT * FROM master_list";
		$queryAssemblies = "SELECT * FROM assemblies_master_list";
	}

	$resultParts = queryMysql($queryParts);
	$numRowsParts = mysql_num_rows($resultParts);
	$resultAssemblies = queryMysql($queryAssemblies);
	$numRowsAssemblies = mysql_num_rows($resultAssemblies);

echo <<<_END
<script src='js/sorttable.js'></script>
<script type="text/javascript" language="JavaScript">
jQuery(document).ready(function() {
	jQuery('.tabs .tab-links a').on('click', function(e) {
		var currentAttrValue = jQuery(this).attr('href');
	
		jQuery('.tabs ' + currentAttrValue).show().siblings().hide();

		jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
		
		e.preventDefault();
	});
});
</script>

<div class="tabs">
	<ul class="tab-links">
		$tab1Active
		$tab2Active
	</ul>

	<div class="tab-content">
		$partsHeader
			<div class="tab-innercontent">
				<br />
				<div class='search'>
					<form method='post' action='master.php' name='searchForm'>$error
						<input type='text' maxlength='128' name='searchParts' />
						<input type='submit' value='Search' />
					</form>
				</div>
				<br />
_END;
	
	if($numRowsParts) {
		echo "<table class='sortable'>";
		echo "<col width='30px'><col width='75px'><col width='300px'><col width='50px'><col width='100px'><col width='50px'><col width='100px'><col width='15'><col width='15'>";
		echo "<tr><th></th><th>Part Number</th><th>Description</th><th>Inv.</th><th>Purchase Date</th><th>Cost</th><th>Vendor</th><th></th><th></th><th></th></tr>";

		for($i = 0; $i < $numRowsParts; $i++) {
			$partNumber = mysql_result($resultParts, $i, 'number');
			
			$resultDaily = queryMysql("SELECT * FROM daily_log WHERE number='$partNumber'");
			$dailyCount = 0;
			if(mysql_num_rows($resultDaily)) {
				$dailyCount = mysql_result($resultDaily, 0, 'count');
			}
			
			$description = mysql_result($resultParts, $i, 'description');
			$count = mysql_result($resultParts, $i, 'count')+$dailyCount;
			$lastPurchaseDate = mysql_result($resultParts, $i, 'lastpurchase');
			$cost = mysql_result($resultParts, $i, 'cost');
			$vendor = mysql_result($resultParts, $i, 'url');
			
			$url = (filter_var($vendor, FILTER_VALIDATE_URL) ? "<a href=$vendor target='_blank'>URL</a>" : "$vendor");
			$shelf = mysql_result($resultParts, $i, 'shelf');
			$bin = mysql_result($resultParts, $i, 'bin');
			$j = $i+1;
			
			if($count <= 5 ) {
				$rowColor = "#FF0000";
			} else if ($count <= 10) {
				$rowColor = "#FF7700";
			} else if ($count <= 15) {
				$rowColor = "#FFDD00";
			} else {
				if($i%2==0) {
					$rowColor = '#909090';
				} else {
					$rowColor = '';
				}
			}

			echo "<tr style='background-color:$rowColor'><td>$j</td><td>$partNumber</td><td>$description</td><td>$count</td><td>$lastPurchaseDate</td><td>$cost</td><td>$url</td><td>$shelf</td><td>$bin</td><td>";

			if($userLevel > 1) {
				echo "<form method='post' action='editPart.php'><input type='hidden' name='partNumber' value='$partNumber'/><input type='submit' value='edit'/></form>";
			}
			echo "</td></tr>";
		}
		echo "</table>";
	} else {
		echo "No match found.";
	}

echo <<<_END
			</div>
		</div>		
		$assembliesHeader
			<div class="tab-innercontent">
				<br />
				<div class='search'>
					<form method='post' action='master.php' name='assemblySearchForm'>$error
						<input type='text' maxlength='128' name='searchAssemblies' />
						<input type='submit' value='Search' />
					</form>
				</div>
				<br />
_END;

if($numRowsAssemblies) {
	echo "<table class='sortable'>";
	echo "<col width='30px'><col width='75px'><col width='300px'><col width='50px'><col width='15'><col width='15'>";
	echo "<tr><th></th><th>Part Number</th><th>Description</th><th>Inv.</th><th></th><th></th><th></th></tr>";

	for($i = 0; $i < $numRowsAssemblies; $i++) {
		$assemblyNumber = mysql_result($resultAssemblies, $i, 'number');
		$description = mysql_result($resultAssemblies, $i, 'description');
		$count = mysql_result($resultAssemblies, $i, 'count');
		$shelf = mysql_result($resultAssemblies, $i, 'shelf');
		$bin = mysql_result($resultAssemblies, $i, 'bin');
		$j = $i+1;
			
		//if($count <= 5 ) {
		//	$rowColor = "#FF0000";
		//} else if ($count <= 10) {
		//	$rowColor = "#FF7700";
		//} else if ($count <= 15) {
		//	$rowColor = "#FFDD00";
		//} else {
		if($i%2==0) {
			$rowColor = '#909090';
		} else {
			$rowColor = '';
		}
		//}

		echo "<tr style='background-color:$rowColor'><td>$j</td><td>$assemblyNumber</td><td>$description</td><td>$count</td><td>$shelf</td><td>$bin</td><td>";

		if($userLevel > 1) {
		echo "<form method='post' action='editAssembly.php'><input type='hidden' name='assemblyNumber' value='$assemblyNumber'/><input type='submit' value='edit'/></form>";
		}
		echo "</td></tr>";
		}
		echo "</table>";
	} else {
		echo "No match found.";
	}

echo <<<_END
			</div>
		</div>
	</div>
</div>
_END;

} else {
	echo "Logged out or inactive.";
}

include_once 'footer.php';
?>
