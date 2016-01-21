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

//create category form submit
$(function() {
    // Get the form.
    var form = $('#createProductCatForm');

    // Get the messages div.
    var messages = $('#messages');
		
	//clear formfields after modal close (event)
	$('#createProductCat').on('hidden.bs.modal', function () {
		$('#productCatName').val('');
		$('#catId').val('');
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
			$("#createProductCat").modal("hide");
			//show changes
			//showOrders();
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kategorie konnte nicht erstellt werden.');
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
	$('#updateProductCat').on('hidden.bs.modal', function () {
		$('#productCatNameUp').val('');
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
			$("#updateProductCat").modal("hide");
			//display changes
			//showOrders();
		}).fail(function(data) {

			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kategorie konnte nicht geändert werden.');
			}
		});
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
	
	$('.createProductCatButton').click(function(){
		$("#createProductCat").modal("show");
	});
	
	$('.updateProductCatButton').click(function(){
		var item = $("li.activeOrder");
		if (true){//item.length
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var selectedCategory = 3;//$('li.active.sidelist').data('id');
		
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_product_single_read.php',
				data: {
					catId:selectedCategory
				}
			}).done(function(response){
				var productData = JSON.parse(response);
				//set values of form
				$('#productCatNameUp').val(productData[name]);
				
				//set hidden formfields
				$('#catIdUp').val(selectedCategory);
				
				//show modal
				$("#updateProductCat").modal("show");
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Produktkategorie konnte nicht geändert werden.');
				}
			});
			
		}
		else{
			alert("Keine Kategorie ausgewählt");
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

