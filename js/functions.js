function O(obj) {
	if (typeof obj == 'object') return obj;
	else return document.getElementById(obj);
}

function createCookie(name, value, days) {
	if (days) {
		var myDate = new Date();
		myDate.setTime(myDate.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+myDate.toGMTString();
	} else {
		var expires = "";
	}
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');

	for(var i = 0; 1 < ca; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') {
			c = c.substring(1, c.length);
		}
		if (c.indexOf(nameEQ) == 0) {
			return c.substring(nameEQ.length, c.length);
		}
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name, "", -1);
}

function ajaxRequest() {
	try { //Non-IE browser?
		var request = new XMLHttpRequest();
	} catch (e1) {
		try { //IE 6++?
			request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e2) {
			try { //IE 5?
				request = new ActiveXObject("Microsoft.XMLHHTP");
			} catch (e3) { //No AJAX support
				request = false;
			}
		}
	}
	return request;
}

function checkUser(user) {
	if (user.value == '') {
		O('userInfo').innerHTML = '';
		return;
	}

	params = "user=" + user.value;
	request = new ajaxRequest();
	request.open("POST", "checkuser.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if(this.status == 200)
				if (this.responseText != null)
					O('userInfo').innerHTML = this.responseText;
	}
	request.send(params);
}

function checkPass() {
	var pass1 = document.signupForm.pass.value;
	var pass2 = document.signupForm.pass2.value;

	params = "pass1=" + pass1 + "&pass2=" + pass2;
	request = new ajaxRequest();
	request.open("POST", "checkpass.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if(this.status == 200)
				if (this.responseText != null)
					O('passwordConfirmInfo').innerHTML = this.responseText;
	}
	request.send(params);
}

function addPartToList() {
	var action = document.partEntryForm.action.value;
	var quantity = document.partEntryForm.quantity.value;
	var part = document.partEntryForm.part.value.toUpperCase();
	
	var exists = false;

	var table = document.getElementById("partsCheckout");
	var id = table.rows.length;
	
	params = "action=" + action + "&part=" + part + "&qty=" + quantity;
	request = new ajaxRequest();
	request.open("POST", "itemExists.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");

	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if (this.status == 200)
				if (this.responseText != null) {
					responseCase = this.responseText;
					
					if (responseCase == 0) {
						var row = table.insertRow(id);

						var cell1 = row.insertCell(0);
						var cell2 = row.insertCell(1);
						var cell3 = row.insertCell(2);
						var cell4 = row.insertCell(3);

						cell1.innerHTML = action;
						cell2.innerHTML = quantity;
						cell3.innerHTML = part;
						cell4.innerHTML = '<input class="itemSelection" type="checkbox"/>delete';
					} else if(responseCase == 1) {
						alert("Error: Item number mis-match.");
					} else if(responseCase == 2) {
						alert("Error: Quantity less than current stock.");
					} else {
						alert("Error: Unknown error");
					}
				}
	}
	request.send(params);
		
	document.partEntryForm.part.focus();
	document.partEntryForm.quantity.value = '1';
	document.partEntryForm.part.value = '';
}

function selectAllFromAlter() {
	var list = document.getElementsByClassName('itemSelection');

	for(var i = 0; i < list.length; i++) {
		if(!list[i].checked) {
			list[i].checked = true;
		}
	}
}

function deleteSelectedFromAlter() {
	var table = document.getElementById("partsCheckout");

	if(table.rows.length > 0) {	
		var rows = table.rows.length;
		var cols = table.rows[0].cells.length;

		var list = document.getElementsByClassName('itemSelection');

		var deleted = 0;
		for(var i = 0; i < rows; i++) {
			var currentIndex = i-deleted;
			var isChecked = list[currentIndex].checked;

			if(isChecked) {
				table.deleteRow(currentIndex);
				deleted++;
			}
		}
		
		if(deleted < 2)
			alert("1 item deleted.");
		else
			alert(deleted + " items deleted.");
	}
}

function applyAlter() {
	var table = document.getElementById("partsCheckout");
	var jsonString;

	if(table.rows.length > 0) {
		var rows = table.rows.length;
		var cols = table.rows[0].cells.length;

		var list = [];

		for(var i = 0; i < rows; i++) {
			var selectedRow = table.rows[i];
			var action = selectedRow.cells[0].innerHTML;
			var quantity = selectedRow.cells[1].innerHTML;
			var part = selectedRow.cells[2].innerHTML;
			var entry = [action, quantity, part];
			list[i] = entry;
		}

//		for(var j = 0; j < list.length; j++) {
//			alert("Action: " + list[j][0] + "\nQuantity: " + list[j][1] + "\nPart: " + list[j][2]);
//		}
		jsonString = JSON.stringify(list);
	} else {
		alert("Not enough items in list: " + table.rows.length);
	}

	params = "jsondata=" + jsonString;
	request = new ajaxRequest();
	request.open("POST", "updateDailyDatabase.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if(this.status == 200)
				if (this.responseText != null) {
					var amountDeleted = 0;
					for(var i = 0; i < rows; i++) {
						var currentIndex = i-amountDeleted;
						amountDeleted=amountDeleted+1;
						table.deleteRow(currentIndex);
					}
				}				
	}
	request.send(params);
}

function deletePartFromDatabase(partNumber) {
	if(confirm("Are you sure you want to delete " + partNumber)) {
		params = "part=" + partNumber;
		request = new ajaxRequest();
		request.open("POST", "deleteFromDatabase.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.setRequestHeader("Content-length", params.length);
		request.setRequestHeader("Connection", "close");
	
		request.onreadystatechange = function() {
			if (this.readyState == 4)
				if (this.status == 200)
					if (this.responseText != null) {
						var result = this.responseText;	
						O('itemResponse').innerHTML = result;
					}
		}
		request.send(params);
	}
}

function editPartInDatabase(partNumber) {
	var url = '/editPart.php';
	var form= $('<form action="' + url + '" method="post">' +
			'<input type="hidden" name="partNumber" value="' + partNumber + '" />' +
			'</form>');
	$('body').append(form);
	form.submit();
}

function deleteAssemblyFromDatabase(assemblyNumber) {
	if(confirm("Are you sure you want to delete " + assemblyNumber)) {
		params = "assembly=" + assemblyNumber;
		request = new ajaxRequest();
		request.open("POST", "deleteAssemblyFromDatabase.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.setRequestHeader("Content-length", params.length);
		request.setRequestHeader("Connection", "close");
	
		request.onreadystatechange = function() {
			if (this.readyState == 4)
				if (this.status == 200)
					if (this.responseText != null) {
						var result = this.responseText;	
						O('itemResponse').innerHTML = result;
					}
		}
		request.send(params);
	}
}

function editAssemblyInDatabase(assemblyNumber) {
	var url = '/editAssembly.php';
	var form= $('<form action="' + url + '" method="post">' +
			'<input type="hidden" name="assemblyNumber" value="' + assemblyNumber + '" />' +
			'</form>');
	$('body').append(form);
	form.submit();
}

function checkPart(origPart, partNumber) {
	if (partNumber.value == '') {
		O('partNumberInfo').innerHTML = '';
		return;
	}

	params = "origPartNumber=" + origPart + "&partNumber=" + partNumber.value;
	request = new ajaxRequest();
	request.open("POST", "checkPartNumber.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if(this.status == 200)
				if (this.responseText != null)
					O('partNumberInfo').innerHTML = this.responseText;
				else
					O('partNumberInfo').innerHTML = "No response";
	}
	request.send(params);
}

function checkAssembly(origAssembly, assemblyNumber) {
	if (assemblyNumber.value == '') {
		O('assemblyNumberInfo').innerHTML = '';
		return;
	}

	params = "origAssemblyNumber=" + origAssembly + "&assemblyNumber=" + assemblyNumber.value;
	request = new ajaxRequest();
	request.open("POST", "checkAssemblyNumber.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if(this.status == 200)
				if (this.responseText != null)
					O('assemblyNumberInfo').innerHTML = this.responseText;
				else
					O('assemblyNumberInfo').innerHTML = "No response";
	}
	request.send(params);
}

function checkDate(dateValue) {
	if (dateValue.value == '') {
		O('dateInfo').innerHTML = '';
		return;
	}

	params = "dateValue=" + dateValue.value;
	request = new ajaxRequest();
	request.open("POST", "checkDate.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if(this.status == 200)
				if (this.responseText != null)
					O('dateInfo').innerHTML = this.responseText;
				else
					O('dateInfo').innerHTML = "No response";
	}
	request.send(params);
}

function checkDatePreviousLog(dateValue) {
	if (dateValue.value == '') {
		O('dateInfo').innerHTML = '';
		return;
	}

	params = "dateValue=" + dateValue.value;
	request = new ajaxRequest();
	request.open("POST", "checkDate.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if(this.status == 200)
				if (this.responseText != null)
					O('checkDatePreviousLogInfo').innerHTML = this.responseText;
				else
					O('checkDatePreviousLogInfo').innerHTML = "No response";
	}
	request.send(params);
}
