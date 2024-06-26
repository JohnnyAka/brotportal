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
			$(this).find(form)[0].reset();
			//clear selects separately
			$('#customerCategory').empty();
		})

	// Set up an event listener for the createUser form.
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
			//reload page to show new user
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

//update user form submit
$(function() {
    // Get the form.
    var form = $('#updateUserForm');

    // Get the messages div.
    var messages = $('#messages');
		
			//clear formfields after modal close (event)
		$('#updateUser').on('hidden.bs.modal', function () {
			$(this).find(form)[0].reset();
			//clear selects separately
			$('#customerCategoryUp').empty();
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

//deletes a user and orders if parameter is true
var deleteUserAndOrders = function(userID, deleteOrders = false){
	$.ajax({
		type: 'POST',
		url: 'ajax/users_delete.php',
		data: {
			id:userID
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
	if(deleteOrders){
		$.ajax({
			type: 'POST',
			url: 'ajax/users_orders_delete.php',
			data: {
				id:userID
			}
		}).done(function(response){
			$(".messages").text("Bestellungen erfolgreich gel&ouml;scht!");
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Bestellungen konnten nicht gel&ouml;scht werden.');
			}
		});
	}
	$('#deleteProductChoice').modal("hide");
}

			
//main function for click event handlers
var main = function(){

	// click-event to show userdata on screen
	$('ul.subSidebarList li').click(function() {
		$('ul.sidebarList li').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for user data
		$.post("ajax/users_read.php", {id:$(this).data('id')}, function(response, status){
			var userData = JSON.parse(response);
			$(".customerIDDisp").text(userData[0]["customerID"]);
			$(".nameDisp").text(userData[0]["name"]);
			$(".customerCategoryDisp").text(categoriesNameDict[userData[0]["customerCategory"]]);
			$(".discountRelativeDisp").text(userData[0]["discountRelative"]);
			$(".warningThresholdDisp").text(userData[0]["warningThreshold"]+" €");
			var autoSendText = "Nein";
			if(userData[0]["autoSendOrders"]!=0) autoSendText = "Ja";
			$(".autoSendOrdersDisp").text(autoSendText);
			$(".mailAdressToDisp").text(userData[0]["mailAdressTo"]);
			$(".mailAdressReceiveDisp").text(userData[0]["mailAdressReceive"]);
			$(".telephone1Disp").text(userData[0]["telephone1"]);
			$(".telephone2Disp").text(userData[0]["telephone2"]);
			$(".faxDisp").text(userData[0]["fax"]);
			$(".storeAdressDisp").text(userData[0]["storeAdress"]);
			$(".whereToPutOrderDisp").text(userData[0]["whereToPutOrder"]);
			var pCat = userData[0]["priceCategory"];
			if(pCat == 0) pCat = " 0 (Preise werden nicht angezeigt)";
			$(".priceCategoryDisp").text(pCat);
			$(".preOrderCustomerIdDisp").text(userData[0]["preOrderCustomerId"]);
		});
	});

    //toggle product-list icon
    $('.icon-list-collapse').click(function() {
        $(this).parent().next("ul").toggle();
        $(this).toggleClass("glyphicon-collapse-down glyphicon-collapse-up");
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
		var item = $("li.active.subSidebarElement");
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
				//$('#passwordUp').val(userData[0]["password"]);
				$('#customerCategoryUp').val(userData[0]["customerCategory"]);
				$('#mailAdressToUp').val(userData[0]["mailAdressTo"]);
				$('#mailAdressReceiveUp').val(userData[0]["mailAdressReceive"]);
				$("#discountRelativeUp").val(userData[0]["discountRelative"]);
				$("#warningThresholdUp").val(userData[0]["warningThreshold"]);
				//Boolean() doesnt seem to work
				var autoSend = userData[0]["autoSendOrders"];
				if (autoSend != 0){autoSend = true}
				else{autoSend = false}
				$("#autoSendOrdersUp").prop('checked', autoSend);
				$('#telephone1Up').val(userData[0]["telephone1"]);
				$('#telephone2Up').val(userData[0]["telephone2"]);
				$('#faxUp').val(userData[0]["fax"]);
				$('#storeAdress').val(userData[0]["storeAdress"]);
				$('#whereToPutOrderUp').val(userData[0]["whereToPutOrder"]);
				$('#idUp').val(userData[0]["id"]);
				$("#priceCategoryUp").val(userData[0]["priceCategory"]);
				$("#preOrderCustomerIdUp").val(userData[0]["preOrderCustomerId"]);
				
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
		
		var item = $("li.active.subSidebarElement");
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
					$('#deleteUserChoice').modal("show");
				}
				else{
					deleteUserAndOrders(itemID);
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
	
	$('.deleteUserAndOrdersButton').click(function(){
		var userID = $("li.active.sidelist").data('id');
		deleteUserAndOrders(userID, true);
	});
	
}

$(document).ready(main);

