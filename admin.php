<?php
include_once 'header.php';

if($userLevel > 1) {
echo <<<_END
<script>
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
		<li class="active"><a href="#tab1">Item Management</a></li>
		<li><a href="#tab2">Assemblies</a></li>
		<li><a href="#tab3">User Management</a></li>
	</ul>

	<div class="tab-content">
		<div id="tab1" class="tab active">
			<div class="tab-innercontent">
				<div class='header'>Item Management</div>
				<hr />
				<div class='adminTitle'>Edit/Delete</div>
				<form>
				<select id="partsDropDown">
					<option value="">Select Part:</option>
					
_END;
$query = "SELECT * FROM master_list";
$result = queryMysql($query);
$numRows = mysql_num_rows($result);
if($numRows) {
	for($i = 0; $i < $numRows; $i++) {
		$partNumber = mysql_result($result, $i, 'number');
		$description = mysql_result($result, $i, 'description');
		echo "<option value=\"$partNumber\">$partNumber,  $description</option>";
	}
}
echo <<<_END
				</select>
				<input type="button" onclick="editPartInDatabase(getElementById('partsDropDown').options[getElementById('partsDropDown').selectedIndex].value )" value="Edit" />
				<input type="button" onclick="deletePartFromDatabase(getElementById('partsDropDown').options[getElementById('partsDropDown').selectedIndex].value )" value="Delete" />
				<span id="itemResponse"></span>
				</form>
				<hr />
				<div class='adminTitle'>Add Part</div>
				<form method='post' action='addPart.php' name='editPartForm'><span class="response">$response</span><br />
				<span class='fieldname'>Part Number: </span>
				<input type='text' maxlength='10' name='partNumberAdd' onBlur='checkPart("", this)' /><span id='partNumberInfo'></span><br />
				<span class='fieldname'>Description: </span>
				<input type='text' maxlength='25' name='descriptionAdd' /><br />
				<span class='fieldname'>Count: </span>
				<input type='text' name='countAdd' /><br />
				<span class='fieldname'>Last Purchase Date: </span>
				<input type='date' onBlur='checkDate(this)' name='dateAdd' /><span id='dateInfo'></span><br />
				<span class='fieldname'>Cost: </span>
				<input type='text' name='costAdd' /><br />
				<span class='fieldname'>Vendor: </span>
				<input type='text' name='vendorAdd' /><br />
				<span class='fieldname'>Shelf: </span>
				<input type='text' maxlength='1' name='shelfAdd' /><br />
				<span class='fieldname'>Bin: </span>
				<input type='text' maxlength='1' name='binAdd' /><br />
				<span class='fieldname'>&nbsp;</span>
				<input type='hidden' name='readyForNew' value='true' />
				<input type='submit' value='Submit' />
				</form>
				<hr />
				<div class='adminTitle'>Part History</div>
				<br >
				<form method='post' action='showDateContents.php'>
				<span class='fieldname'>Date: </span>
				<input type='date' name='dateToCheck' onBlur='checkDatePreviousLog(this)'/><span id='checkDatePreviousLogInfo'></span><br />
				<span class='fieldname'>&nbsp;</span>
				<input type='submit' value='Submit' />
				</form>
			</div>
		</div>

		<div id="tab2" class="tab">
			<div class="tab-innercontent">
				<div class='header'>Assemblies</div>
				<hr />
				<div class='adminTitle'>Edit/Delete</div>
				<form>
				<select id="assembliesDropDown">
					<option value="">Select Assembly:</option>
					
_END;
$query = "SELECT * FROM assemblies_master_list";
$result = queryMysql($query);
$numRows = mysql_num_rows($result);
if($numRows) {
	for($i = 0; $i < $numRows; $i++) {
		$assemblyNumber = mysql_result($result, $i, 'number');
		$description = mysql_result($result, $i, 'description');
		echo "<option value=\"$assemblyNumber\">$assemblyNumber,  $description</option>";
	}
}
echo <<<_END
				</select>
				<input type="button" onclick="editAssemblyInDatabase(getElementById('assembliesDropDown').options[getElementById('assembliesDropDown').selectedIndex].value )" value="Edit" />
				<input type="button" onclick="deleteAssemblyFromDatabase(getElementById('assembliesDropDown').options[getElementById('assembliesDropDown').selectedIndex].value )" value="Delete" />
				<span id="assemblyResponse"></span>
				</form>
				<hr />
				<div class='adminTitle'>Add Assembly</div>
				<form method='post' action='addAssembly.php' name='editAssemblyForm'><span class="response">$response</span><br />
				<span class='fieldname'>Assembly Number: </span>
				<input type='text' maxlength='10' name='assemblyNumberAdd' onBlur='checkAssembly("", this)' /><span id='assemblyNumberInfo'></span><br />
				<span class='fieldname'>Description: </span>
				<input type='text' maxlength='25' name='assemblyDescriptionAdd' /><br />
				<span class='fieldname'>Count: </span>
				<input type='text' name='assemblyCountAdd' /><br />
				<span class='fieldname'>Shelf: </span>
				<input type='text' maxlength='1' name='assemblyShelfAdd' /><br />
				<span class='fieldname'>Bin: </span>
				<input type='text' maxlength='1' name='assemblyBinAdd' /><br />
				<span class='fieldname'>&nbsp;</span>
				<input type='hidden' name='readyForNew' value='true' />
				<input type='submit' value='Submit' />
				</form>
				<hr />
			</div>
		</div>

		<div id="tab3" class="tab">
			<div class="tab-innercontent">
				<div class='header'>User Management</div>
				<hr />
			</div>
		</div>
	</div>
</div>
_END;

} else {
	echo "Access Denied.";
}

echo "<br /><br /><br />";

include_once 'footer.php';
?>
