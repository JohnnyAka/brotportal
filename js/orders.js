/*This file contains event handlers for click events and form-submit events*/

//create product list(dictionary) for name retrieval via id, product-category list for category retrieval via id
//and category list for category name retrieval via id --- then show orders
//productsNameDict, productsCategoryDict, categoriesNameDict
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
		productsNameDict = new Object();
		productsCategoryDict = new Object();
		for(var x=0; x < productsData.length; x++){
			productsCategoryDict[productsData[x].id] = productsData[x].productCategory;
			productsNameDict[productsData[x].id] = productsData[x].name;
		}
		//create product category list(dictionary) for name retrieval
		$.ajax({
			type: 'POST',
			url: 'ajax/orders_read_productCategories.php'
		}).done(function(response) {
			categoriesData = JSON.parse(response);
			//set Item List
			categoriesNameDict = new Object();
			for(var x=0; x < categoriesData.length; x++){
				categoriesNameDict[categoriesData[x].id] = categoriesData[x].name;
			}
			showOrders();
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kategorienamensliste konnte nicht erstellt werden.');
			}
		});
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
			//update orderSentSign
			showOrderSentIcon();
			
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
			showOrderSentIcon();
			var ordersData = JSON.parse(response);
			//set Item List/Form
			var productList = Object();
			for(var x=0; x < ordersData.length; x++){
				//create ordered Product-List with category headings
				singleOrder = ordersData[x];
				productCategory = productsCategoryDict[singleOrder.idProduct];
				
				if(!productList.hasOwnProperty(productCategory)){
					productList[productCategory] = [];
				}
				productList[productCategory].push([singleOrder.idProduct, singleOrder.number]);
			}
			//sort categories
			var keys = [];
			for(k in productList){
				if(productList.hasOwnProperty(k)){
					keys.push(k);
				}
			}
			keys.sort(function(a,b){
				return categoriesNameDict[a].localeCompare(categoriesNameDict[b]);
			});
			console.log(productList);
			console.log(keys);
			//sort products and build form
			for (var y=0; y < keys.length; y++){
				var category = keys[y];
				console.log(category);
				if (productList.hasOwnProperty(category)) {
					var currentList = productList[category];
					currentList.sort(function(a,b){
						var one = productsNameDict[a[0].toUpperCase()]; 
						var two = productsNameDict[b[0].toUpperCase()];
						if(one < two) return -1;
						if(one > two) return 1;
						return 0;
					});
					$('#sendOrderForm').append('<p>'+categoriesNameDict[category]+'</p>');
					for(var x = 0; x < currentList.length; x++){
						appendToProductList($('#sendOrderForm'), currentList[x][0], currentList[x][1], true);
					}
					$('#sendOrderForm').append('<hr>');
				}
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
var appendToProductList = function(formObj,idProduct, number, init){
	var productName = productsNameDict[idProduct];
	var orderLabel, maxSize = 30;
	//add class to make textsize smaller if name longer than maxSize
	if(productName.length > maxSize){
		orderLabel = " class='orderNameSize' ";
	}
	else{
		orderLabel = " ";
	} 
	formObj.append('<div class="field clearfix"><label'+orderLabel+'for='+idProduct+'>'+productName+'&nbsp;</label><input type="number" id="'+idProduct+'" value="'+number+'" min="0" name="'+idProduct+'"></div>');
	if(!init){
		showOrderNotYetSentIcon();
	}
	document.getElementById(idProduct).addEventListener("input", showOrderNotYetSentIcon, false);
}
var showOrderNotYetSentIcon = function(){
	var orderSent = $('#orderSentSign');
	orderSent.removeClass('glyphicon-check');
	orderSent.addClass('glyphicon-share');
}
var showOrderSentIcon = function(){
	var orderSent = $('#orderSentSign');
	orderSent.removeClass('glyphicon-share');
	orderSent.addClass('glyphicon-check');
}


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
			appendToProductList($('#sendOrderForm'),idProduct, 1, false);
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
$(document).ready(main);

