/*This file contains event handlers for click events and form-submit events*/

//create category dictionary (id to name)
$(function(){
		$.ajax({
			type: 'POST',
			url: 'ajax/categories_user_read.php'
		}).done(function(response){
			categoriesNameDict = new Object();
			var categoriesData = JSON.parse(response);
			//set product options of select
			for (var x=0;x<categoriesData.length;x++) {
				categoriesNameDict[categoriesData[x].id] = categoriesData[x].name;
			}
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kategorien konnten nicht aus Datenbank gelesen werden.');
				return;
			}
		});
});

//create user form submit
$(function() {
    // Get the form.
    var form = $('#createUserForm');

    // Get the messages div.
    var messages = $('#messages');
		
		//clear formfields after modal close (event)
		$('#createUser').on('hidden.bs.modal', function () {
			$('#customerid').val('');
			$('#name').val('');
			$('#password').val('');
			$('#customerCategory').empty();
			$('#mailAdressTo').val('');
			$('#mailAdressReceive').val('');
			$('#telephone1').val('');
			$('#telephone2').val('');
			$('#fax').val('');
			$('#storeAdress').val('');
			$('#whereToPutOrder').val('');
		})

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
		
			//clear formfields after modal close (event)
		$('#updateUser').on('hidden.bs.modal', function () {
			$('#customeridUp').val('');
			$('#nameUp').val('');
			$('#passwordUp').val('');
			$('#customerCategoryUp').empty();
			$('#mailAdressToUp').val('');
			$('#mailAdressReceiveUp').val('');
			$('#telephone1Up').val('');
			$('#telephone2Up').val('');
			$('#faxUp').val('');
			$('#storeAdressUp').val('');
			$('#whereToPutOrderUp').val('');
		})

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
		
		//ajax call for user data
		$.post("ajax/users_read.php", {id:$(this).data('id')}, function(response, status){
			var userData = JSON.parse(response);
			$(".customerIDDisp").text(userData[0]["customerID"]);
			$(".nameDisp").text(userData[0]["name"]);
			$(".passwordDisp").text(userData[0]["password"]);
			$(".customerCategoryDisp").text(categoriesNameDict[userData[0]["customerCategory"]]);
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
		//set user options of select in modal
		for (var key in categoriesNameDict) {
				if (key === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
					continue;
				}
				$('#customerCategory').append($('<option>', {
					value: key,
					text: categoriesNameDict[key]
				}));
		}
		
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
				
				//set userCategory options of select
				for (var key in categoriesNameDict) {
					if (key === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
						continue;
					}
					$('#customerCategoryUp').append($('<option>', {
						value: key,
						text: categoriesNameDict[key]
					}));
				}
				
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
				url: 'ajax/users_orders_read.php',
				data: {
					id:itemID
				}
			}).done(function(response){
				customerOrders = JSON.parse(response);
				
				if(customerOrders !== 'undefined' && customerOrders.length > 0){
					alert("Kunde hat noch eingetragene Bestellungen");
				}
				else{
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
				
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Benutzer konnte nicht gelesen werden.');
				}
			});
		}
		else{
			alert("Kein Benutzer ausgewählt");
		}
	});
	
}

$(document).ready(main);

