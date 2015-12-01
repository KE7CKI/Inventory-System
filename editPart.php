<?php
include_once 'header.php';
echo "<div class='header'>Edit Part</div>";

$part = "";
$response = "";

if(isset($_POST['readyForEdit'])) {
	$isReadyEdit = sanitizeString($_POST['readyForEdit']);
	unset($_POST['readyForEdit']);
	$origPartNumber = $partNumberEdit = $descriptionEdit = $countEdit = $dateEdit = $costEdit = $vendorEdit = $urlEdit = $shelfEdit = $binEdit = '';
	if($isReadyEdit == 'true') {
		if(isset($_POST['origPartNumber']))
			$origPartNumber = sanitizeString($_POST['origPartNumber']);
		if(isset($_POST['partNumberEdit']))
			$partNumberEdit = sanitizeString($_POST['partNumberEdit']);
		if(isset($_POST['descriptionEdit']))
			$descriptionEdit = sanitizeString($_POST['descriptionEdit']);
		if(isset($_POST['countEdit']))
			$countEdit = sanitizeString($_POST['countEdit']);	
		if(isset($_POST['dateEdit']))
			$dateEdit = sanitizeString($_POST['dateEdit']);
		if(isset($_POST['costEdit']))
			$costEdit = sanitizeString($_POSt['costEdit']);
		if(isset($_POST['vendorEdit']))
			$vendorEdit = sanitizeString($_POST['vendorEdit']);
		if(isset($_POST['shelfEdit']))
			$shelfEdit = sanitizeString($_POST['shelfEdit']);
		if(isset($_POST['binEdit'])) 
			$binEdit = sanitizeString($_POST['binEdit']);
		
	}

//	echo "$origPartNumber<br />$partNumberEdit<br />$descriptionEdit<br />$countEdit</br>$dateEdit<br />$costEdit<br />$vendorEdit<br />$shelfEdit<br />$binEdit<br />";
	$query = "UPDATE master_list SET number='$partNumberEdit', description='$descriptionEdit', count='$countEdit', lastpurchase='$dateEdit', cost='$costEdit', url='$vendorEdit', shelf='$shelfEdit', bin='$binEdit' WHERE number='$origPartNumber'";
	$result = queryMysql($query);
	
	if($result)
		$response = "Part editted!";
	else
		$response = "Part edit failure!";

	$part = $partNumberEdit;
}

if(isset($_POST['partNumber'])) {
	$part = sanitizeString($_POST['partNumber']);
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
<form method='post' action='edit.php' name='editPartForm'><span class="response">$response</span><br />
<span class='fieldname'>Part Number: </span>
<input type='text' maxlength='10' name='partNumberEdit' onBlur='checkPart("$partNumber", this)' value='$partNumber' /><span id='partNumberInfo'></span><br />
<span class='fieldname'>Description: </span>
<input type='text' maxlength='25' name='descriptionEdit' value='$description' /><br />
<span class='fieldname'>Count: </span>
<input type='text' name='countEdit' value='$count' /><br />
<span class='fieldname'>Last Purchase Date: </span>
<input type='date' name='dateEdit' value='$lastPurchaseDate' /><br />
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
