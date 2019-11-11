/*This file contains event handlers for click events and form-submit events*/
//create category dictionary (id to name)
function buildCategoryDict(){
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
};
buildCategoryDict();

//create category form submit
$(function() {
    // Get the form.
    var form = $('#createProductCatForm');

    // Get the messages div.
    var messages = $('#messages');
		
	//clear formfields after modal close (event)
	$('#createProductCat').on('hidden.bs.modal', function () {
		$(this).find(form)[0].reset();
		//clear selects separately
		$('#upperCategory').empty();
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
			buildCategoryDict();
			//close modal
			$("#createProductCat").modal("hide");
			//show changes
			displayCategories();
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

//update productcat form submit
$(function() {
	// Get the form.
	var form = $('#updateProductCatForm');

	// Get the messages div.
	var messages = $('#messages');
	
	//clear formfields after modal close (event)
	$('#updateProductCat').on('hidden.bs.modal', function () {
		$('#productCatNameUp').val('');
		$('#upperCategoryUp').empty();
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
			buildCategoryDict()
			//close modal
			$("#updateProductCat").modal("hide");
			//display changes
			displayCategories();
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

//displays categories
var displayCategories = function(){
	$('ul.sidebarList').empty();
	$.ajax({
		type: 'POST',
		url: 'ajax/categories_product_read.php'
	}).done(function(response){
		var categoryData = JSON.parse(response);
		//set Item List
		for(var x=0; x < categoryData.length; x++){
			$('ul.sidebarList').append("<li class='categoryListItem' data-idCategory='"+categoryData[x].id+"'>"+categoryData[x].name+"</li>");
		}
		// click-event to select category
		$('ul.sidebarList li').click(function() {
			$('ul.sidebarList li').removeClass("active");
			$(this).addClass("active");
			displayProducts($(this));
		});
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Kategorien konnten nicht angezeigt werden.');
		}
	});
};

//displays products belonging to active category
var displayProducts = function(category){
	//reset list
	$('ul.productList').empty();
	var categoryID = category.data('idcategory');
	$.ajax({
		type: 'POST',
		url: 'ajax/categories_product_products_read.php',
		data: {
			id:categoryID
		}
	}).done(function(response){
		var productData = JSON.parse(response);
		//set Item List
		for(var x=0; x < productData.length; x++){
			$('ul.productList').append("<li>"+productData[x].name+"</li>");
		}
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Bestellung konnte nicht geladen werden.');
		}
	});
};
			
//main function for click event handlers
var main = function(){
	
	displayCategories();
	
	$('.createProductCatButton').click(function(){
		//set product options of select
		$('#upperCategory').append($('<option>', {
					value: 0,
					text: 'Keine'
				}));
		for (var key in categoriesNameDict) {
				if (key === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
					continue;
				}
				$('#upperCategory').append($('<option>', {
					value: key,
					text: categoriesNameDict[key]
				}));
		}
		$("#createProductCat").modal("show");
	});
	
	$('.updateProductCatButton').click(function(){
		var item = $(".categoryListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var selectedCategory = item.data('idcategory');
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_product_single_read.php',
				data: {
					catId:selectedCategory
				}
			}).done(function(response){
				var productData = JSON.parse(response);
				
				//set product options of select
				$('#upperCategoryUp').append($('<option>', {
					value: 0,
					text: 'Keine'
				}));
				for (var key in categoriesNameDict) {
					if (key === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
						continue;
					}
					$('#upperCategoryUp').append($('<option>', {
						value: key,
						text: categoriesNameDict[key]
					}));
				}
				
				//set values of form
				$('#productCatNameUp').val(productData[0]['name']);
				$('#orderPriorityUp').val(productData[0]['orderPriority']);
				if(productData[0]["upperCategoryID"] != null){
					$('#upperCategoryUp').val(productData[0]["upperCategoryID"]);
				}
				
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
	
	$('.deleteProductCatButton').click(function(){
		var item = $(".categoryListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var categoryID = item.data('idcategory');
			//check for dependent customers
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_product_products_read.php',
				data: {
					id:categoryID
				}
			}).done(function(response){
				products = JSON.parse(response);
				if(products !== 'undefined' && products.length > 0){
					alert("Es gibt noch Artikel dieser Kategorie. Bevor die Kategorie gelöscht werden kann, bitte die Artikel löschen oder die Kategorie dieser Artikel ändern.");
				}
				else{
					$.ajax({
						type: 'POST',
						url: 'ajax/categories_product_delete.php',
						data: {
							catId:categoryID
						}
					}).done(function(response){
						$(".messages").text("Kategorie erfolgreich gel&ouml;scht!");
						displayCategories();
					}).fail(function(data){
						// Set the message text.
						if (data.responseText !== '') {
							$(messages).text(data.responseText);
						} else {
							$(messages).text('Fehler, Kategorie konnte nicht gelöscht werden.');
						}
					});
				}
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Artikel konnten nicht gelesen werden.');
				}
			});
		}
		else{
			alert("Keine Kategorie ausgewählt");
		}
	});
	
}

$(document).ready(main);

	
