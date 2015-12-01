<?php
include_once 'header.php';
echo "<div class='header'>Edit Assembly</div>";

$assembly = "";

$response = "";
if(isset($_POST['readyForNew'])) {
	$isReadyAdd = sanitizeString($_POST['readyForNew']);
	unset($_POST['readyForNew']);
	$assemblyNumberAdd = $assemblyDescriptionAdd = $assemblyCountAdd = $assemblyShelfAdd = $assemblyBinAdd = '';
	if($isReadyAdd == 'true') {
		if(isset($_POST['assemblyNumberAdd']))
			$assemblyNumberAdd = sanitizeString($_POST['assemblyNumberAdd']);
		if(isset($_POST['assemblyDescriptionAdd']))
			$assemblyDescriptionAdd = sanitizeString($_POST['assemblyDescriptionAdd']);
		if(isset($_POST['assemblyCountAdd']))
			$assemblyCountAdd = sanitizeString($_POST['assemblyCountAdd']);
		if(isset($_POST['assemblyShelfAdd']))
			$assemblyShelfAdd = sanitizeString($_POST['assemblyShelfAdd']);
		if(isset($_POST['assemblyBinAdd']))
			$assemblyBinAdd = sanitizeString($_POST['assemblyBinAdd']);
	}

	$response = queryMysql("SELECT MAX(id) FROM assemblies_master_list");
	$id = mysql_result($response, 0) + 1;
//	echo "0:$id<br />1:$assemblyNumberAdd<br />2:$assemblyDescriptionAdd<br />3:$assemblyCountAdd</br>4:$assemblyShelfAdd<br />5:$assemblyBinAdd<br />";
	$query = "INSERT INTO assemblies_master_list (id, number, description, count, shelf, bin) VALUES ('$id', '$assemblyNumberAdd', '$assemblyDescriptionAdd', '$assemblyCountAdd', '$assemblyShelfAdd', '$assemblyBinAdd')";
	$result = queryMysql($query);
	$query2 = "CREATE TABLE `$assemblyNumberAdd` (`id` INT(11) NOT NULL AUTO_INCREMENT, `partNumber` VARCHAR(15) NOT NULL, `partQty` INT(11) UNSIGNED NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$result2 = queryMysql($query2);
	
	if($result && $result2) 
		$response = "Assembly Added!";
	else
		$response = "Assembly addition failed!";
	$assembly = $assemblyNumberAdd;
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
	//}
} else {
	echo "Access Denied.";
}

include_once 'footer.php';
?>
