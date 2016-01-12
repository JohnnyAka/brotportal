/*This file contains event handlers for click events and form-submit events*/

//create product list(dictionary) for name and backcontrol id retrieval
$(function() {
	// Get the messages div.
	var messages = $('#messages');

	// Submit the form using AJAX.
	$.ajax({
		type: 'POST',
		url: 'ajax/orders_read_products.php'
	}).done(function(response) {
		productsData = JSON.parse(response);
			//set Item List
			productsIdDict = new Object();
			productsNameDict = new Object();
			for(var x=0; x < productsData.length; x++){
				productsIdDict[productsData[x].id] = productsData[x].productID;
				productsNameDict[productsData[x].id] = productsData[x].name;
			}
	}).fail(function(data) {
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Artikelnamensliste konnte nicht erstellt werden.');
		}
	});
});

//create order form submit
$(function() {
    // Get the form.
    var form = $('#createOrderForm');

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

			//close modal
			$("#createOrder").modal("hide");
			//reload page to show new article
			//location.reload(); 
		}).fail(function(data) {
			$('#idProduct').empty();
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Artikel konnte nicht erstellt werden.');
			}
		}).always(function(data){
			// Clear the form.
			$('#idProduct').empty();
			$('#number').val('');
			$('#hook').val('');
			$('#noteDelivery').val('');
			$('#noteBaking').val('');
			$('#idCustomer').val('');
			$('#orderDate').val('');
		});
	});
});

//update product form submit
$(function() {
    // Get the form.
    var form = $('#updateProductForm');

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
			$('#productidUp').val('');
			$('#nameUp').val('');
			$('#productCategoryUp').val('');
			$('#visibleForUserUp').val('');
			$('#descriptionUp').val('');
			$('#imagePathUp').val('');
			$('#ingredientsUp').val('');
			$('#allergensUp').val('');
			$('#weightUp').val('');
			$('#preBakeExpUp').val('');
			$('#featureExpUp').val('');
			
			//close modal
			$("#updateProduct").modal("hide");
			
			//reload datepicker
			$('#datepicker').onClose();
		}).fail(function(data) {

			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Artikel konnte nicht geändert werden.');
			}
		});
	});
});

//datepicker setup including onclose ajax orderlist load function
$(function() {
	$( "#datepicker" ).datepicker($.datepicker.regional[ "de" ]);
	$( "#datepicker" ).datepicker( "option", "dateFormat", "dd.mm.yy" );
	$( "#datepicker" ).datepicker( "setDate", "+1" );
	$( "#datepicker" ).datepicker( "option", "minDate", "-1" );
	$( "#datepicker" ).datepicker( "option", "onClose", function(selectedDate, picker){
		//reset list
		$('ul.orderList').empty();
		//check dateinput and send ajax request
		var regExp = /\d\d.\d\d.\d\d\d\d/;
		if(regExp.test(selectedDate)){
			var customer = $("li.active.sidelist");
			var customerID = customer.data('id');
			if(customerID == null){
				alert("Es ist kein Kunde ausgewählt.");
				return;
			}
			$.ajax({
				type: 'POST',
				url: 'ajax/orders_read.php',
				data: {
					id:customerID,
					date:selectedDate
				}
			}).done(function(response){
				var ordersData = JSON.parse(response);
				//set Item List
				for(var x=0; x < ordersData.length; x++){
					$('ul.orderList').append("<li class='orderListItem'>Artikelnummer: "+productsIdDict[ordersData[x].idProduct]+" | Name: "+productsNameDict[ordersData[x].idProduct]+" | Anzahl: "+ordersData[x].number+"</li>");
				}
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Bestellung konnte nicht geladen werden.');
				}
			});
		}
		else{
			alert("Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
		}
	});
});
//baustelle!!!!
function populateOrders
			
//main function for click event handlers
var main = function(){

	
	// click-event to retrieve data-id and alert
	$('ul.sidebarList li').click(function() {
		$('ul.sidebarList li').removeClass("active");
		$(this).addClass("active");
	});
	
	
	
	$('.createOrderButton').click(function(){
	//get customerID
		var customer = $("li.active.sidelist");
		var customerID = customer.data('id');
		if(customerID == null){
			alert("Es ist kein Kunde ausgewählt.");
			return;
		}
		//set hidden formfields
		$('#idCustomer').val(customerID);
		var dateSelected = $("#datepicker").datepicker("getDate");
		$('#orderDate').val(dateSelected.getFullYear()+"-"+(dateSelected.getMonth()+1)+"-"+dateSelected.getDate());
		
		//set product options of select
		var idProductSelect = $('#idProduct');
		
		
		
		for (var key in productsNameDict) {
				if (key === 'length' || !productsIdDict.hasOwnProperty(key)){ 
					continue;
				}
				$('#idProduct').append($('<option>', {
					value: key,
					text: productsNameDict[key]
				}));
				
		}
		
		$("#createOrder").modal("show");
	});
	
	$('.updateOrderButton').click(function(){
		var item = $("li.active.sidelist");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var itemID = item.data('id');
			$.ajax({
				type: 'POST',
				url: 'ajax/products_read.php',
				data: {
					id:itemID
				}
			}).done(function(response){
				var productData = JSON.parse(response);
				//set values of form
				$('#productidUp').val(productData[0]["productID"]);
				$('#nameUp').val(productData[0]["name"]);
				$('#descriptionUp').val(productData[0]["description"]);
				$('#idUp').val(productData[0]["id"]);
				//Boolean() doesnt seem to work
				var visForU = productData[0]["visibleForUser"];
				if (visForU != 0){visForU = true}
				else{visForU = false}
				$('#visibleForUserUp').prop('checked', visForU);
				$('#productCategoryUp').val(productData[0]["productCategory"]);
				$('#imagePathUp').val(productData[0]["imagePath"]);
				$('#ingredientsUp').val(productData[0]["ingredients"]);
				$('#allergensUp').val(productData[0]["allergens"]);
				$('#weightUp').val(productData[0]["weight"]);
				$('#preBakeExpUp').val(productData[0]["preBakeExp"]);
				$('#featureExpUp').val(productData[0]["featureExp"]);
				
				//show modal
				$("#updateProduct").modal("show");
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Artikel konnte nicht geändert werden.');
				}
			});
		}
		else{
			alert("Kein Artikel ausgewählt");
		}
	});
	
	$('.deleteProductButton').click(function(){
		var messages = $('#messages');
		
		var item = $("li.active.sidelist");
		var itemID = item.data('id');
		if (item.length){
			$.ajax({
				type: 'POST',
				url: 'ajax/products_delete.php',
				data: {
					id:itemID
				}
			}).done(function(response){
				$(".messages").text("Artikel erfolgreich gel&ouml;scht!");
				//reload page to show new article
				location.reload(); 
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Artikel konnte nicht gel&ouml;scht werden.');
				}
			});
		}
		else{
			alert("Kein Artikel ausgewählt");
		}
	});
	
	


}

$(document).ready(main);

