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
		
	//clear formfields after modal close (event)
	$('#createOrder').on('hidden.bs.modal', function () {
		$('#idProduct').empty();
		$('#number').val('');
		$('#hook').val('');
		$('#noteDelivery').val('');
		$('#noteBaking').val('');
		$('#idCustomer').val('');
		$('#orderDate').val('');
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
			$("#createOrder").modal("hide");
			//show changes
			showOrders();
		}).fail(function(data) {
			$('#idProduct').empty();
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Artikel konnte nicht erstellt werden.');
			}
		});
	});
});

//update order form submit
$(function() {
	// Get the form.
	var form = $('#updateOrderForm');

	// Get the messages div.
	var messages = $('#messages');
	
	//clear formfields after modal close (event)
	$('#updateOrder').on('hidden.bs.modal', function () {
		$('#idProductUp').empty();
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
			$("#updateOrder").modal("hide");
			//display changes
			showOrders();
			//reload datepicker
			//$('#datepicker').onClose();
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
		showOrders();
	});
});

//displays orders of selected date and customer
var showOrders = function populateOrders(){
	var selectedDate = $( "#datepicker" ).datepicker().val();
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
				$('ul.orderList').append("<li class='orderListItem' data-idproduct='"+ordersData[x].idProduct+"' data-orderhook='"+ordersData[x].hook+"'>Artikelnummer: "+productsIdDict[ordersData[x].idProduct]+" | Name: "+productsNameDict[ordersData[x].idProduct]+" | Anzahl: "+ordersData[x].number+" | Lieferung: "+ordersData[x].hook+"</li>");
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
};
			
//main function for click event handlers
var main = function(){

	
	// click-event to retrieve data-id
	$('ul.sidebarList li').click(function() {
		$('ul.sidebarList li').removeClass("active");
		$(this).addClass("active");
		
		showOrders();
	});

	// click-event to pick order row
	$('ul.orderList').on('click', 'li.orderListItem', function() {
		$('.orderListItem').removeClass("activeOrder");
		$(this).addClass("activeOrder");
	});
	
	$('.createOrderButton').click(function(){
	//get customerID
		var customerID = $("li.active.sidelist").data('id');
		if(customerID == null){
			alert("Es ist kein Kunde ausgewählt.");
			return;
		}
		//set hidden formfields
		$('#idCustomer').val(customerID);
		var dateSelected = $("#datepicker").datepicker("getDate");
		$('#orderDate').val(dateSelected.getFullYear()+"-"+(dateSelected.getMonth()+1)+"-"+dateSelected.getDate());
		
		//set product options of select
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
		var item = $("li.activeOrder");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var productID = item.data('idproduct');
			var itemHook = item.data('orderhook');
			var selectedCustomer = $('li.active.sidelist').data('id');
			var selectedDate = $( "#datepicker" ).datepicker().val();
			//check dateinput and send ajax request
			var regExp = /\d\d.\d\d.\d\d\d\d/;
			if(regExp.test(selectedDate)){
				$.ajax({
					type: 'POST',
					url: 'ajax/orders_single_read.php',
					data: {
						productId:productID,
						orderHook:itemHook,
						customer:selectedCustomer,
						date:selectedDate
					}
				}).done(function(response){
					var productData = JSON.parse(response);
					//set values of form
					$('#nameProductUp').val(productsNameDict[productID]);
					$('#numberUp').val(productData[0]["number"]);
					$('#deliveryUp').val(productData[0]["hook"]);
					//Boolean() doesnt seem to work
					var important = productData[0]["important"];
					if (important != 0){important = true}
					else{important = false}
					$('#importantUp').prop('checked', important);
					$('#noteDeliveryUp').val(productData[0]["noteDelivery"]);
					$('#noteBakingUp').val(productData[0]["noteBaking"]);
					$('#idProductUp').val(productID);
					$('#hookUp').val(productData[0]["hook"]);
					
					//set hidden formfields
					$('#idCustomerUp').val(selectedCustomer);
					var dateSelected = $("#datepicker").datepicker("getDate");
					$('#orderDateUp').val(dateSelected.getFullYear()+"-"+(dateSelected.getMonth()+1)+"-"+dateSelected.getDate());
					
					
				
					//show modal
					$("#updateOrder").modal("show");
				}).fail(function(data){
					// Set the message text.
					if (data.responseText !== '') {
						$(messages).text(data.responseText);
					} else {
						$(messages).text('Fehler, Bestellung konnte nicht geändert werden.');
					}
				});
			}
			else{
				alert("Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
			}
				
		}
		else{
			alert("Keine Bestellung ausgewählt");
		}
	});
	
	$('.deleteOrderButton').click(function(){
		var item = $("li.activeOrder");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var productID = item.data('idproduct');
			var itemHook = item.data('orderhook');
			var selectedCustomer = $('li.active.sidelist').data('id');
			var selectedDate = $( "#datepicker" ).datepicker().val();
			//check dateinput and send ajax request
			var regExp = /\d\d.\d\d.\d\d\d\d/;
			if(regExp.test(selectedDate)){
				$.ajax({
					type: 'POST',
					url: 'ajax/orders_delete.php',
					data: {
						productId:productID,
						orderHook:itemHook,
						customer:selectedCustomer,
						date:selectedDate
					}
				}).done(function(response){
					$(".messages").text("Bestellung erfolgreich gel&ouml;scht!");
					showOrders();
				}).fail(function(data){
					// Set the message text.
					if (data.responseText !== '') {
						$(messages).text(data.responseText);
					} else {
						$(messages).text('Fehler, Bestellung konnte nicht geändert werden.');
					}
				});
			}
			else{
				alert("Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
			}
		}
		else{
			alert("Keine Bestellung ausgewählt");
		}
	});
	
	


}

$(document).ready(main);

