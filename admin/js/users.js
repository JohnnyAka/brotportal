/*This file contains event handlers for click events and form-submit events*/

//create product form submit
$(function() {
    // Get the form.
    var form = $('#createUserForm');

    // Get the messages div.
    var messages = $('#messages');

	// Set up an event listener for the createProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();

		// Serialize the form data.
		var formData = $(form).serialize();
		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData
		}).done(function(response) {

			// Set the message text.
			$(messages).text(response);

			// Clear the form.
			$('#customerid').val('');
			$('#name').val('');
			$('#password').val('');
			$('#customerCategory').val('');
			$('#mailAdressTo').val('');
			$('#mailAdressReceive').val('');
			$('#telephone1').val('');
			$('#telephone2').val('');
			$('#fax').val('');
			$('#storeAdress').val('');
			$('#whereToPutOrder').val('');
			
			//close modal
			$("#createUser").modal("hide");
			//reload page to show new article
			location.reload(); 
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Benutzer konnte nicht erstellt werden.');
			}
		});
	});
});

//update product form submit
$(function() {
    // Get the form.
    var form = $('#updateUserForm');

    // Get the messages div.
    var messages = $('#messages');

	// Set up an event listener for the updateProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();

		// Serialize the form data.
		var formData = $(form).serialize();
		
		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData
		}).done(function(response) {

			// Set the message text.
			$(messages).text(response);

			// Clear the form.
			$('#customeridUp').val('');
			$('#nameUp').val('');
			$('#passwordUp').val('');
			$('#customerCategoryUp').val('');
			$('#mailAdressToUp').val('');
			$('#mailAdressReceiveUp').val('');
			$('#telephone1Up').val('');
			$('#telephone2Up').val('');
			$('#faxUp').val('');
			$('#storeAdressUp').val('');
			$('#whereToPutOrderUp').val('');
			
			//close modal
			$("#updateUser").modal("hide");
			//reload page to show new article
			location.reload(); 
		}).fail(function(data) {

			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Benutzer konnte nicht geändert werden.');
			}
		});
	});
});

			
//main function for click event handlers
var main = function(){

	// click-event to retrieve data-id and alert
	$('ul.sidebarList li').click(function() {
		$('ul.sidebarList li').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		$.post("ajax/users_read.php", {id:$(this).data('id')}, function(response, status){
			var userData = JSON.parse(response);
			$(".customerIDDisp").text(userData[0]["customerID"]);
			$(".nameDisp").text(userData[0]["name"]);
			$(".passwordDisp").text(userData[0]["password"]);
			$(".customerCategoryDisp").text(userData[0]["customerCategory"]);
			$(".mailAdressToDisp").text(userData[0]["mailAdressTo"]);
			$(".mailAdressReceiveDisp").text(userData[0]["mailAdressReceive"]);
			$(".telephone1Disp").text(userData[0]["telephone1"]);
			$(".telephone2Disp").text(userData[0]["telephone2"]);
			$(".faxDisp").text(userData[0]["fax"]);
			$(".storeAdressDisp").text(userData[0]["storeAdress"]);
			$(".whereToPutOrderDisp").text(userData[0]["whereToPutOrder"]);
		});
	});
	
	
	
	$('.createUserButton').click(function(){
		//$.post('server.php', $('#theForm').serialize());
		$("#createUser").modal("show");
	});
	
	$('.updateUserButton').click(function(){
		var item = $("li.active.sidelist");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var itemID = item.data('id');
			$.ajax({
				type: 'POST',
				url: 'ajax/users_read.php',
				data: {
					id:itemID
				}
			}).done(function(response){
				var userData = JSON.parse(response);
				//set values of form
				$('#customeridUp').val(userData[0]["customerID"]);
				$('#nameUp').val(userData[0]["name"]);
				$('#passwordUp').val(userData[0]["password"]);
				$('#customerCategoryUp').val(userData[0]["customerCategory"]);
				$('#mailAdressToUp').val(userData[0]["mailAdressTo"]);
				$('#mailAdressReceiveUp').val(userData[0]["mailAdressReceive"]);
				$('#telephone1Up').val(userData[0]["telephone1"]);
				$('#telephone2Up').val(userData[0]["telephone2"]);
				$('#faxUp').val(userData[0]["fax"]);
				$('#storeAdress').val(userData[0]["storeAdress"]);
				$('#whereToPutOrderUp').val(userData[0]["whereToPutOrder"]);
				$('#idUp').val(userData[0]["id"]);
				
				//show modal
				$("#updateUser").modal("show");
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Benutzer konnte nicht geändert werden.');
				}
			});
		}
		else{
			alert("Kein Benutzer ausgewählt");
		}
	});
	
	$('.deleteUserButton').click(function(){
		var messages = $('#messages');
		
		var item = $("li.active.sidelist");
		var itemID = item.data('id');
		if (item.length){
			$.ajax({
				type: 'POST',
				url: 'ajax/users_delete.php',
				data: {
					id:itemID
				}
			}).done(function(response){
				$(".messages").text("Benutzer erfolgreich gel&ouml;scht!");
				//reload page to show new article
				location.reload(); 
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Benutzer konnte nicht gel&ouml;scht werden.');
				}
			});
		}
		else{
			alert("Kein Benutzer ausgewählt");
		}
	});
	
	


}

$(document).ready(main);

