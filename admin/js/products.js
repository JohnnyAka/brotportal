/*This file contains event handlers for click events and form-submit events*/

//create category dictionary (id to name)
$(function(){
		$.ajax({
			type: 'POST',
			url: 'ajax/categories_product_read.php'
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

//create calendar dictionary (id to name)
$(function(){
		$.ajax({
			type: 'POST',
			url: 'ajax/calendars_read.php'
		}).done(function(response){
			calendarsNameDict = new Object();
			var calendarsData = JSON.parse(response);
			//set product options of select
			for (var x=0;x<calendarsData.length;x++) {
				calendarsNameDict[calendarsData[x].id] = calendarsData[x].name;
			}
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kalender konnten nicht aus Datenbank gelesen werden.');
				return;
			}
		});
});
		
//create product form submit
$(function() {
    // Get the form.
    var form = $('#createProductForm');

    // Get the messages div.
    var messages = $('#messages');
		
		//clear formfields after modal close (event)
		$('#createProduct').on('hidden.bs.modal', function () {
			form[0].reset();
			//clear selects separately
			$('#productCategory').empty();
			$('#idCalendar').empty();
		})

	// Set up an event listener for the createProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		//check input of form
		var formArray = $(form).serializeArray();
		var preBakeTmp=0;
		for(var x=0;x<formArray.length;x++){
			if(formArray[x].name=='preBakeExp'){
				preBakeTmp=formArray[x].value;
			}
			if(formArray[x].name=='preBakeMax'){
				if(Number(formArray[x].value) < Number(preBakeTmp)){
					alert("Der Wert für 'Tage vorher backen' muss kleiner oder gleich sein, als der Wert für 'Maximal Tage vorher backen'.");
					return;
				}
			}
		}
		
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
			$("#createProduct").modal("hide");
			//reload page to show new article
			//location.reload(); 
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

//update product form submit
$(function() {
    // Get the form.
    var form = $('#updateProductForm');

    // Get the messages div.
    var messages = $('#messages');
		
			//clear formfields after modal close (event)
		$('#updateProduct').on('hidden.bs.modal', function () {
			$(this).find('form')[0].reset();
			//clear selects separately
			$('#productCategoryUp').empty();
			$('#idCalendarUp').empty();
		})

	// Set up an event listener for the updateProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		
		//check input of form
		var formArray = $(form).serializeArray();
		var preBakeTmp=0;
		for(var x=0;x<formArray.length;x++){
			if(formArray[x].name=='preBakeExp'){
				preBakeTmp=formArray[x].value;
			}
			if(formArray[x].name=='preBakeMax'){
				if(Number(formArray[x].value) < Number(preBakeTmp)){
					alert("Der Wert für 'Tage vorher backen' muss kleiner oder gleich sein, als der Wert für 'Maximal Tage vorher backen'.");
					return;
				}
			}
		}

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
			$("#updateProduct").modal("hide");
			//reload page to show new article
			//location.reload(); 
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

//deletes a product and orders if parameter is true
var deleteProductAndOrders = function(itemID, deleteOrders = false){
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
	if(deleteOrders){
		$.ajax({
			type: 'POST',
			url: 'ajax/products_orders_delete.php',
			data: {
				id:itemID
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



$(function() {
    // Get the form.
    var form = $('#importProductDataForm');

    // Get the messages div.
    var messages = $('#messages');
		

	// Set up an event listener for the createProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		//check input of form
		var formArray = $(form).serializeArray();

		let csvFile = event.target[5].files[0];
		var priceType = formArray[0].value;

		var reader = new FileReader();
		reader.onload = function(e) {
			var lines = e.target.result.split('\r\n');
		    var properties;
		    var productObjects = [];
		    for (i = 0; i < lines.length; ++i){
		    	properties = lines[i].split('|');
		    	productObjects.push({
		    		'artikelNummer':properties[0],
		    		'name':properties[1],
		    		'preis':properties[5],
		    		'gewicht':properties[26],
		    		'zutaten':properties[29],
		    		'kj':properties[71],
		    		'kcal':properties[72],
		    		'eiweiss':properties[73],
		    		'fett':properties[74],
		    		'kohlenhydrate':properties[75],
		    		'gesFettS':properties[76],
		    		'zucker':properties[77],
		    		'ballaststoffe':properties[78],
		    		'kochsalz':properties[79],
		    		'broteinheiten':properties[80],
		    		'grammBE':properties[81],
		    		'allergene':properties[82]
		    	});
		    }
			
			$.ajax({
				type: 'POST',
				url: $(form).attr('action'),
				data:{
					productObj: JSON.stringify(productObjects),
					priceT: priceType
				}
			}).done(function(response) {

				// Set the message text.
				$(messages).text(response);
				
				//close modal
				$("#importProductData").modal("hide");
			}).fail(function(data) {
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Daten konnten nicht importiert werden.');
				}
			});
		};
		reader.readAsText(csvFile);
	});
});
			
//main function for click event handlers
var main = function(){

	// click-event to retrieve data-id and alert
	$('ul.subSidebarList li').click(function() {
		$('ul.sidebarList li').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		$.post("ajax/products_read.php", {id:$(this).data('id')}, function(response, status){
			var productData = JSON.parse(response);
			
			$(".displayProductID").text(productData[0]["productID"]);
			$(".displayName").text(productData[0]["name"]);
			$(".displayDescription").text(productData[0]["description"]);
			var visableFUText = "Nein";
			if(productData[0]["visibleForUser"]!=0) visableFUText = "Ja";
			$(".displayVisibleForUser").text(visableFUText);
			$(".displayProductCategory").text(categoriesNameDict[productData[0]["productCategory"]]);
			$(".displayOrderPriority").text(productData[0]["orderPriority"]);
			$(".displayImagePath").text(productData[0]["imagePath"]);
			$(".displayIngredients").text(productData[0]["ingredients"]);
			$(".displayAllergens").text(productData[0]["allergens"]);
			$(".displayWeight").text(productData[0]["weight"]);
			$(".displayPreBakeExp").text(productData[0]["preBakeExp"]);
			$(".displayPreBakeMax").text(productData[0]["preBakeMax"]);
			$(".displayFeatureExp").text(productData[0]["featureExp"]);
			$(".displayPrice1").text(productData[0]["price1"]);
			$(".displayPrice2").text(productData[0]["price2"]);
			$(".displayPrice3").text(productData[0]["price3"]);
			$(".displayPrice4").text(productData[0]["price4"]);
			$(".displayPrice5").text(productData[0]["price5"]);
			$(".displayIdCalendar").text(calendarsNameDict[productData[0]["idCalendar"]]);
		});
	});

    //toggle product-list icon
    $('.icon-list-collapse').click(function() {
        $(this).parent().next("ul").toggle();
        $(this).toggleClass("glyphicon-collapse-down glyphicon-collapse-up");
    });
	
	$('.createProductButton').click(function(){
		//set product options of select
		for (var key in categoriesNameDict) {
				if (key === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
					continue;
				}
				$('#productCategory').append($('<option>', {
					value: key,
					text: categoriesNameDict[key]
				}));
		}
		//set calendar options of select
		for (var key in calendarsNameDict) {
				if (key === 'length' || !calendarsNameDict.hasOwnProperty(key)){ 
					continue;
				}
				$('#idCalendar').append($('<option>', {
					value: key,
					text: calendarsNameDict[key]
				}));
		}
		
		$("#createProduct").modal("show");
	});
	
	$('.updateProductButton').click(function(){
		var item = $("li.active.subSidebarElement");
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
				
				//set productCategory options of select
				for (var key in categoriesNameDict) {
					if (key === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
						continue;
					}
					$('#productCategoryUp').append($('<option>', {
						value: key,
						text: categoriesNameDict[key]
					}));
				}
				//set calendar options of select
				for (var key in calendarsNameDict) {
						if (key === 'length' || !calendarsNameDict.hasOwnProperty(key)){ 
							continue;
						}
						$('#idCalendarUp').append($('<option>', {
							value: key,
							text: calendarsNameDict[key]
						}));
				}
				
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
				$('#orderPriorityUp').val(productData[0]["orderPriority"]);
				$('#imagePathUp').val(productData[0]["imagePath"]);
				$('#ingredientsUp').val(productData[0]["ingredients"]);
				$('#allergensUp').val(productData[0]["allergens"]);
				$('#weightUp').val(productData[0]["weight"]);
				$('#preBakeExpUp').val(productData[0]["preBakeExp"]);
				$('#preBakeMaxUp').val(productData[0]["preBakeMax"]);
				$('#featureExpUp').val(productData[0]["featureExp"]);
				$("#price1Up").val(productData[0]["price1"]);
				$("#price2Up").val(productData[0]["price2"]);
				$("#price3Up").val(productData[0]["price3"]);
				$("#price4Up").val(productData[0]["price4"]);
				$("#price5Up").val(productData[0]["price5"]);
				$("#idCalendarUp").val(productData[0]["idCalendar"]);
				
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
		
		var item = $("li.active.subSidebarElement");
		var itemID = item.data('id');
		if (item.length){
			//delete only if not present in any order
			$.ajax({
				type: 'POST',
				url: 'ajax/products_orders_read.php',
				data: {
					id:itemID
				}
			}).done(function(response){
				productOrders = JSON.parse(response);
				if(productOrders !== 'undefined' && productOrders.length > 0){
					$('#deleteProductChoice').modal("show");
				}
				else{
					deleteProductAndOrders(itemID);
				}
				
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Artikel konnte nicht gelesen werden.');
				}
			});
		}
		else{
			alert("Kein Artikel ausgewählt");
		}
	});
	
	$('.deleteProductAndOrdersButton').click(function(){
		var itemID = $("li.active.subSidebarElement").data('id');
		deleteProductAndOrders(itemID, true);
	});

	$('.imageUploadButton').click(function(){
		$('#imageUpload').modal("show");
	});

	$('.importProductDataButton').click(function(){
		$('#importProductData').modal("show");
	});
}

$(document).ready(main);

