<?php
include_once 'header.php';
echo "<div class='header'>Edit Assembly</div>";

$assembly = "";
$response = "";

if(isset($_POST['readyForEdit'])) {
	$isReadyEdit = sanitizeString($_POST['readyForEdit']);
	unset($_POST['readyForEdit']);
	$origAssemblyNumber = $assemblyNumberEdit = $assemblyDescriptionEdit = $assemblyCountEdit = $assemblyShelfEdit = $assemblyBinEdit = '';
	if($isReadyEdit == 'true') {
		if(isset($_POST['origAssemblyNumber']))
			$origAssemblyNumber = sanitizeString($_POST['origAssemblyNumber']);
		if(isset($_POST['assemblyNumberEdit']))
			$assemblyNumberEdit = sanitizeString($_POST['assemblyNumberEdit']);
		if(isset($_POST['assemblyDescriptionEdit']))
			$assemblyDescriptionEdit = sanitizeString($_POST['assemblyDescriptionEdit']);
		if(isset($_POST['assemblyCountEdit']))
			$assemblyCountEdit = sanitizeString($_POST['assemblyCountEdit']);	
		if(isset($_POST['assemblyShelfEdit']))
			$assemblyShelfEdit = sanitizeString($_POST['assemblyShelfEdit']);
		if(isset($_POST['assemblyBinEdit'])) 
			$assemblyBinEdit = sanitizeString($_POST['assemblyBinEdit']);
		
	}

//	echo "$origPartNumber<br />$partNumberEdit<br />$descriptionEdit<br />$countEdit</br>$dateEdit<br />$costEdit<br />$vendorEdit<br />$shelfEdit<br />$binEdit<br />";
	$query = "UPDATE assemblies_master_list SET number='$assemblyNumberEdit', description='$assemblyDescriptionEdit', count='$assemblyCountEdit', shelf='$assemblyShelfEdit', bin='$assemblyBinEdit' WHERE number='$origAssemblyNumber'";
	$result = queryMysql($query);
	
	if($result)
		$response = "Assembly editted!";
	else
		$response = "Assembly edit failure!";

	$assembly = $assemblyNumberEdit;
}

if(isset($_POST['assemblyNumber'])) {
	$assembly = sanitizeString($_POST['assemblyNumber']);
}

if($userLevel > 1) {
	//$part = "";

	//(isset($_POST['partNumber'] || isset($_POST['partNumberEdit']))){
	//	$part = sanitizeString($_POST['partNumber']);
		
		$result = queryMysql("Select * FROM assemblies_master_list WHERE number='$assembly'");
		if(mysql_num_rows($result)) {
			$assemblyNumber = mysql_result($result, 0, 'number');
			$assemblyDescription = mysql_result($result, 0, 'description');
			$assemblyCount = mysql_result($result, 0, 'count');
			$assemblyShelf = mysql_result($result, 0, 'shelf');
			$assemblyBin = mysql_result($result, 0, 'bin');
		}

echo <<<_END
<form method='post' action='editAssembly.php' name='editAssemblyForm'><span class="response">$response</span><br />
<span class='fieldname'>Assembly Number: </span>
<input type='text' maxlength='10' name='assemblyNumberEdit' onBlur='checkAssembly("$assemblyNumber", this)' value='$assemblyNumber' /><span id='assemblyNumberInfo'></span><br />
<span class='fieldname'>Description: </span>
<input type='text' maxlength='25' name='assemblyDescriptionEdit' value='$assemblyDescription' /><br />
<span class='fieldname'>Count: </span>
<input type='text' name='assemblyCountEdit' value='$assemblyCount' /><br />
<span class='fieldname'>Shelf: </span>
<input type='text' maxlength='1' name='assemblyShelfEdit' value='$assemblyShelf' /><br />
<span class='fieldname'>Bin: </span>
<input type='text' maxlength='1' name='assemblyBinEdit' value='$assemblyBin' /><br />
<span class='fieldname'>&nbsp;</span>
<input type='hidden' name='origAssemblyNumber' value='$assemblyNumber' />
<input type='hidden' name='readyForEdit' value='true' />
<input type='submit' value='Submit' />
</form>
_END;


echo <<<_END
<table class='partsInAssemblyTable'>
<tr><th>Part Number</th><th>Quantity</th><th></th></tr>
<tr><td>Fake Item 1</td><td>1</td><td><form method='post' action=''><input type='hidden' name='partNumber' value='Fake Item 1'/><input type='submit' value='Delete' /></form></td></tr>


<form method='post' name='addItemToAssemblyForm'>$error
<tr>
<td><input type='text' name='partNumberInAssembly' /></td>
<td><input type='number' name='quantityInAssembly' value='1' /></td>
<td><input type='button' value='Add' onClick='' /></td>
</tr>
</form>
</table>
		
<div class='alterButtons'>
<button type='button' onClick=''>Button</button>
<button type='button' onClick=''>Button</button>
<button type='button' onClick=''>Button</button>
</div>
_END;

} else {
	echo "Access Denied.";
}

include_once 'footer.php';
?>
