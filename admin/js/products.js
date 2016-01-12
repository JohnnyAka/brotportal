/*This file contains event handlers for click events and form-submit events*/

//create product form submit
$(function() {
    // Get the form.
    var form = $('#createProductForm');

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
			$('#productid').val('');
			$('#name').val('');
			$('#productCategory').val('');
			$('#visibleForUser').val('');
			$('#description').val('');
			$('#imagePath').val('');
			$('#ingredients').val('');
			$('#allergens').val('');
			$('#weight').val('');
			$('#preBakeExp').val('');
			$('#featureExp').val('');
			
			//close modal
			$("#createProduct").modal("hide");
			//reload page to show new article
			location.reload(); 
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
			//reload page to show new article
			location.reload(); 
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

			
//main function for click event handlers
var main = function(){

	// click-event to retrieve data-id and alert
	$('ul.sidebarList li').click(function() {
		$('ul.sidebarList li').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		$.post("ajax/products_read.php", {id:$(this).data('id')}, function(response, status){
			var productData = JSON.parse(response);
			
			$(".displayProductID").text(productData[0]["productID"]);
			$(".displayName").text(productData[0]["name"]);
			$(".displayDescription").text(productData[0]["description"]);
			$(".displayVisibleForUser").text(productData[0]["visibleForUser"]);
			$(".displayProductCategory").text(productData[0]["productCategory"]);
			$(".displayImagePath").text(productData[0]["imagePath"]);
			$(".displayIngredients").text(productData[0]["ingredients"]);
			$(".displayAllergens").text(productData[0]["allergens"]);
			$(".displayWeight").text(productData[0]["weight"]);
			$(".displayPreBakeExp").text(productData[0]["preBakeExp"]);
			$(".displayFeatureExp").text(productData[0]["featureExp"]);
		});

	});
	
	
	
	$('.createProductButton').click(function(){
		//$.post('server.php', $('#theForm').serialize());
		$("#createProduct").modal("show");
	});
	
	$('.updateProductButton').click(function(){
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

