<?php
include_once 'header.php';
echo "<div class='header'>Alter Inventory</div>";

if ($loggedin && $isActive) {
echo <<<_END
<form method='post' name='partEntryForm'>$error
<table>
<tr>
<th>Action</th>
<th>Quantity</th>
<th>Part</th>
<th>&nbsp;</th>
</tr><tr>
<td><select name='action'><option value='out'>out</option><option value='in'>in</option></select></td>
<td><input type='number' name='quantity' value='1' /></td>
<td><input type='text' name='part'></td>
<td><input type='button' value='submit' onClick='addPartToList()' /></td>
</tr>
</table>
</form>

<br />

<table class='partsTable'>
<tr><th>Action</th><th>Quantity</th><th>Part</th></tr>
</table>

<div class='partsCheckoutDiv'>
<table class='partsTable' id='partsCheckout'>
</table>
</div>

<div class='alterButtons'>
<button type='button' onClick='selectAllFromAlter()'>Select All</button>
<button type='button' onClick='deleteSelectedFromAlter()'>Delete</button>
<button type='button' onClick='applyAlter()'>Apply List</button>
</div>
_END;

} else {
echo "Logged out or inactive.";
}

echo <<<_END
<script type="text/javascript" language="JavaScript">
document.partEntryForm.part.focus();
</script>
_END;

include_once 'footer.php';
?>
