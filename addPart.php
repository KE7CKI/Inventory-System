<?php
include_once 'header.php';
echo "<div class='header'>Edit Part</div>";

$part = "";
$response = "";

if(isset($_POST['readyForNew'])) {
	$isReadyAdd = sanitizeString($_POST['readyForNew']);
	unset($_POST['readyForNew']);
	$partNumberAdd = $descriptionAdd = $countAdd = $dateAdd = $costAdd = $vendorAdd = $urlAdd = $shelfAdd = $binAdd = '';
	if($isReadyAdd == 'true') {
		if(isset($_POST['partNumberAdd']))
			$partNumberAdd = sanitizeString($_POST['partNumberAdd']);
		if(isset($_POST['descriptionAdd']))
			$descriptionAdd = sanitizeString($_POST['descriptionAdd']);
		if(isset($_POST['countAdd']))
			$countAdd = sanitizeString($_post['countAdd']);
		if(isset($_POST['dateAdd']))
			$dateAdd = sanitizeString($_POST['dateAdd']);
		if(isset($_POST['costAdd']))
			$costAdd = sanitizeString($_POST['costAdd']);
		if(isset($_POST['vendorAdd']))
			$vendorAdd = sanitizeString($_POST['vendorAdd']);
		if(isset($_POST['shelfAdd']))
			$shelfAdd = sanitizeString($_POST['shelfAdd']);
		if(isset($_POST['binAdd']))
			$binAdd = sanitizeString($_POST['binAdd']);
	}

	$response = queryMysql("SELECT MAX(id) FROM master_list");
	$id = mysql_result($response, 0) + 1;
//	echo "0:$id<br />1:$partNumberAdd<br />2:$descriptionAdd<br />3:$countAdd</br>4:$dateAdd<br />5:$costAdd<br />6:$vendorAdd<br />7:$shelfAdd<br />8:$binAdd<br />";
	$query = "INSERT INTO master_list (id, number, description, count, lastpurchase, cost, url, shelf, bin) VALUES ('$id', '$partNumberAdd', '$descriptionAdd', '$countAdd', '$dateAdd', '$costAdd', '$vendorAdd', '$shelfAdd', '$binAdd')";
	$result = queryMysql($query);
	if($result) 
		$response = "Part Added!";
	else
		$response = "Part addition failed!";
	$part = $partNumberAdd;
}

if($userLevel > 1) {
	//$part = "";

	//(isset($_POST['partNumber'] || isset($_POST['partNumberEdit']))){
	//	$part = sanitizeString($_POST['partNumber']);
		
		$result = queryMysql("Select * FROM master_list WHERE number='$part'");
		if(mysql_num_rows($result)) {
			$partNumber = mysql_result($result, 0, 'number');
			$description = mysql_result($result, 0, 'description');
			$count = mysql_result($result, 0, 'count');
			$lastPurchaseDate = mysql_result($result, 0, 'lastpurchase');
			$cost = mysql_result($result, 0, 'cost');
			$vendor = mysql_result($result, 0, 'url');
			$shelf = mysql_result($result, 0, 'shelf');
			$bin = mysql_result($result, 0, 'bin');
		}

echo <<<_END
<form method='post' action='editPart.php' name='editPartForm'><span class="response">$response</span><br />
<span class='fieldname'>Part Number: </span>
<input type='text' maxlength='10' name='partNumberEdit' onBlur='checkPart("$partNumber", this)' value='$partNumber' /><span id='partNumberInfo'></span><br />
<span class='fieldname'>Description: </span>
<input type='text' maxlength='25' name='descriptionEdit' value='$description' /><br />
<span class='fieldname'>Count: </span>
<input type='text' name='countEdit' value='$count' /><br />
<span class='fieldname'>Last Purchase Date: </span>
<input type='text' name='dateEdit' value='$lastPurchaseDate' /><br />
<span class='fieldname'>Cost: </span>
<input type='text' name='costEdit' value='$cost' /><br />
<span class='fieldname'>Vendor: </span>
<input type='text' name='vendorEdit' value='$vendor' /><br />
<span class='fieldname'>Shelf: </span>
<input type='text' maxlength='1' name='shelfEdit' value='$shelf' /><br />
<span class='fieldname'>Bin: </span>
<input type='text' maxlength='1' name='binEdit' value='$bin' /><br />
<span class='fieldname'>&nbsp;</span>
<input type='hidden' name='origPartNumber' value='$partNumber' />
<input type='hidden' name='readyForEdit' value='true' />
<input type='submit' value='Submit' />
</form>
_END;
	//}
} else {
	echo "Access Denied.";
}

include_once 'footer.php';
?>
