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
			//productsIdDict = new Object();
			productsNameDict = new Object();
			for(var x=0; x < productsData.length; x++){
				//productsIdDict[productsData[x].id] = productsData[x].productID;
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

$(function() {
	var form = $('#sendOrderForm');
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
			
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Artikel konnte nicht erstellt werden.');
			}
		});
	});
});


//datepicker setup including onclose ajax orderlist load function
$(function() {
	$( "#ordersDatepicker" ).datepicker($.datepicker.regional[ "de" ]);
	$( "#ordersDatepicker" ).datepicker( "option", "dateFormat", "dd.mm.yy" );
	$( "#ordersDatepicker" ).datepicker( "setDate", "+1" );
	$( "#ordersDatepicker" ).datepicker( "option", "minDate", "-380" );
	$( "#ordersDatepicker" ).datepicker( "option", "onClose", function(selectedDate, picker){
		showOrders();
	});
});

//displays orders of selected date and customer
var showOrders = function(){
	var selectedDate = $( "#ordersDatepicker" ).datepicker().val();
	//check dateinput and send ajax request
	var regExp = /\d\d.\d\d.\d\d\d\d/;
	if(regExp.test(selectedDate)){
		var customerID = $('#userID').data("value");
		$.ajax({
			type: 'POST',
			url: 'ajax/orders_readOrders.php',
			data: {
				id:customerID,
				date:selectedDate
			}
		}).done(function(response){
			//reset list
			$('#sendOrderForm').empty();
			var ordersData = JSON.parse(response);
			//set Item List/Form
			for(var x=0; x < ordersData.length; x++){
				$('#sendOrderForm').append('<div class="field"><label for='+ordersData[x].idProduct+'>'+productsNameDict[ordersData[x].idProduct]+'&nbsp;</label><input type="number" id="'+ordersData[x].idProduct+'" value="'+ordersData[x].number+'" min="0" name="'+ordersData[x].idProduct+'"></div>');
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

	$('.showMultipleArticles').click(function() {
		$('ul.sidebarList').find('*').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		//$.post("ajax/products_read.php", {id:$(this).data('id')}, function(response, status){
		//	var productData = JSON.parse(response);
			
		//});
	});
	//show single article in product content on click on left sidebar
	$('.showSingleArticle').click(function() {
		$('ul.sidebarList').find('*').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		$.post("ajax/orders_readProduct.php", {id:$(this).data('id')}, function(response, status){
			var productData = JSON.parse(response)[0];
			
			$('.productContent').empty();
			$('.productContent').append('<h3>'+productData["name"]+'</h3>');
			$('.productContent').append('<hr>');
			var imagePath = productData["imagePath"];
			if(imagePath){
				$('.productContent').append('<img id="productImgSingle" src="images/'+imagePath+'">');
			}
			$('.productContent').append('<p>Gewicht: '+productData["weight"]+'</p>');
			$('.productContent').append('<p>Artikelnummer: '+productData["productID"]+'</p>');
			var prebake = productData["preBakeExp"];
			if(prebake!=0){
				var dayOrDays = " Tage ";
				if(prebake==1){dayOrDays = " Tag "}
				$('.productContent').append('<p>Bitte '+productData["preBakeExp"]+dayOrDays+'im Voraus bestellen.</p>');
			}
			$('.productContent').append('<p>Zutaten <br />'+productData["ingredients"]+'</p>');
			$('.productContent').append('<p>Allergene <br />'+productData["allergens"]+'</p>');
			$('.productContent').append('<p>Beschreibung <br />'+productData["description"]+'</p>');
			
			console.log(productData["id"]);
		});
	});
	//show and hide addProduct button
	$(".subSidebarElement").mouseenter(function() { 
		$(this).find(".buttonAddProduct").css('visibility','visible'); 
	}).mouseleave(function() {
    $(this).find(".buttonAddProduct").css('visibility','hidden'); 
  });
	
	$('.buttonAddProduct').click(function(event) {
		event.stopPropagation();
		var idProduct = $(this).parent().data('id');
		if( $('#sendOrderForm').find('#'+idProduct).length < 1){
			$('#sendOrderForm').append('<div class="field"><label for='+idProduct+'>'+productsNameDict[idProduct]+'&nbsp;</label><input type="number" id="'+idProduct+'" value="'+1+'" min="0" name="'+idProduct+'"></div>');
		}
		$('input#'+idProduct).focus().select();
	});
	
	$('.sendOrderButton').click(function() {
		//check dateinput and send ajax request
		var selectedDate = $( "#ordersDatepicker" ).datepicker().val();
		var regExp = /\d\d.\d\d.\d\d\d\d/;
		if(regExp.test(selectedDate)){
			var customerID = $('#userID').data("value");
			$('#sendOrderForm').append('<input type="hidden" value="'+selectedDate+'" name="orderDate">');
			$('#sendOrderForm').append('<input type="hidden" value="'+customerID+'" name="userID">');
			$('#sendOrderForm').submit();
			setTimeout(function(){showOrders(); }, 50);
		}
		else{
			alert("Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
		}
	});
}
$(document).ready(function(){showOrders()});
$(document).ready(main);

