/*This file contains event handlers for click events and form-submit events*/

//update usercat form submit
$(function() {
	// Get the form.
	var form = $('#updatePrizeCatForm');

	// Get the messages div.
	var messages = $('#messages');
	
	//clear formfields after modal close (event)
	$('#updatePrizeCat').on('hidden.bs.modal', function () {
		$(this).find(form)[0].reset();
	})

	// Set up an event listener for the updateUser form.
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
			$("#updatePrizeCat").modal("hide");
			//display changes
			//displayCategories();
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

//displays prizeDetails belonging to active category
var displayPrize = function(category){
	//reset list
	$('ul.prizeDetails').empty();
	var categoryID = category.data('idcategory');
	$.ajax({
		type: 'POST',
		url: 'ajax/categories_prizes_read.php',
		data: {
			id:categoryID
		}
	}).done(function(response){
		var prizeData = JSON.parse(response);
		$('ul.prizeDetails').append("<li>Preisinfo: "+prizeData[0].infoText+"</li>");
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
	
	// click-event to select category
		$('ul.sidebarList li').click(function() {
			$('ul.sidebarList li').removeClass("active");
			$(this).addClass("active");
			displayPrize($(this));
		});
	
	$('.updatePrizeCatButton').click(function(){
		var item = $(".categoryListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var selectedCategory = item.data('idcategory');
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_prizes_read.php',
				data: {
					id:selectedCategory
				}
			}).done(function(response){
				var prizeData = JSON.parse(response);
				//set values of form
				$('#prizeCatInfoUp').val(prizeData[0]['infoText']);
				
				//set hidden formfields
				$('#catIdUp').val(selectedCategory);
				
				//show modal
				$("#updatePrizeCat").modal("show");
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
	
	
}

$(document).ready(main);

	
