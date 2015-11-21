$(function() {
    // Get the form.
    var form = $('#createProductForm');

    // Get the messages div.
    var messages = $('#messages');

	// Set up an event listener for the contact form.
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
			// Make sure that the formMessages div has the 'success' class.
			$(messages).removeClass('error');
			$(messages).addClass('success');

			// Set the message text.
			$(messages).text(response);

			// Clear the form.
			$('#productid').val('');
			$('#name').val('');
			$('#description').val('');
			
			//close modal
			$("#createProduct").modal("hide");
			//reload page to show new article
			location.reload(); 
		}).fail(function(data) {
			// Make sure that the formMessages div has the 'error' class.
			$(messages).removeClass('success');
			$(messages).addClass('error');

			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Artikel konnte nicht gespeichert werden.');
			}
		});
	});
	
});

//main function for event handlers
var main = function(){

	// click-event to retrieve data-id and alert
	$('ul.productList li').click(function() {
		$('ul.productList li').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		$.post("ajax/products_post.php", {id:$(this).data('id')}, function(response, status){
			var productData = JSON.parse(response);
			
			$(".displayProductID").text(productData[0]["productid"]);
			$(".displayName").text(productData[0]["name"]);
			$(".displayDescription").text(productData[0]["description"]);
		});

	});
	
	
	
	$('.createProduct').click(function(){
		//$.post('server.php', $('#theForm').serialize());
	});
	
	$('.updateProduct').click(function(){
		
	});
	
	$('.deleteProduct').click(function(){
		
	});

}

$(document).ready(main);

