/*This file contains event handlers for click events and form-submit events*/

//create category form submit
$(function() {
    // Get the form.
    var form = $('#createCalendarForm');

    // Get the messages div.
    var messages = $('#messages');
		
	//clear formfields after modal close (event)
	$('#createCalendar').on('hidden.bs.modal', function () {
		$('#calendarName').val('');
		$('#calendarId').val('');
	})

	// Set up an event listener for the calendar form.
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
			$("#createUserCat").modal("hide");
			//show changes
			displayCategories();
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kalender konnte nicht erstellt werden.');
			}
		});
	});
});

//update usercat form submit
$(function() {
	// Get the form.
	var form = $('#updateCalendarForm');

	// Get the messages div.
	var messages = $('#messages');
	
	//clear formfields after modal close (event)
	$('#updateCalendar').on('hidden.bs.modal', function () {
		$('#calendarNameUp').val('');
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
			$("#updateCalendar").modal("hide");
			//display changes
			displayCategories();
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kalender konnte nicht geändert werden.');
			}
		});
	});
});

//displays categories
var displayCategories = function(){
	$('ul.sidebarList').empty();
	$.ajax({
		type: 'POST',
		url: 'ajax/calendars_read.php'
	}).done(function(response){
		var calendarData = JSON.parse(response);
		//set Item List
		for(var x=0; x < calendarData.length; x++){
			$('ul.sidebarList').append("<li class='calendarListItem' data-idCalendar='"+calendarData[x].id+"'>"+calendarData[x].name+"</li>");
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
			$(messages).text('Fehler, Kalender konnten nicht angezeigt werden.');
		}
	});
};

//displays users belonging to active category
var displayProducts = function(calendar){
	//reset list
	$('ul.productList').empty();
	var calendarID = calendar.data('idcalendar');
	$.ajax({
		type: 'POST',
		url: 'ajax/calendars_products_read.php',
		data: {
			id:calendarID
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
			$(messages).text('Fehler, Produkte konnten nicht geladen werden.');
		}
	});
};
			
//main function for click event handlers
var main = function(){
	
	displayCategories();
	
	$('.createCalendarButton').click(function(){
		$("#createCalendar").modal("show");
	});
	
	$('.updateCalendarButton').click(function(){
		var item = $(".calendarListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var selectedCalendar = item.data('idcalendar');
			$.ajax({
				type: 'POST',
				url: 'ajax/calendars_single_read.php',
				data: {
					calendarId:selectedCalendar
				}
			}).done(function(response){
				var userData = JSON.parse(response);
				//set values of form
				$('#calendarNameUp').val(userData[0]['name']);
				
				//set hidden formfields
				$('#calendarIdUp').val(selectedCalendar);
				
				//show modal
				$("#updateCalendar").modal("show");
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Kalender konnte nicht geändert werden.');
				}
			});
			
		}
		else{
			alert("Keinen Kalender ausgewählt");
		}
	});
	
	$('.deleteCalendarButton').click(function(){
		var item = $(".calendarListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var calendarID = item.data('idcalendar');
			//check for dependent products
			$.ajax({
				type: 'POST',
				url: 'ajax/calendars_products_read.php',
				data: {
					id:calendarID
				}
			}).done(function(response){
				users = JSON.parse(response);
				if(users !== 'undefined' && users.length > 0){
					alert("Es gibt noch Produkte mit diesem Kalender. Bevor der Kalender gelöscht werden kann, bitte die Produkte löschen oder die Kalender dieser Produkte ändern.");
				}
				else{
					//delete calendar
					$.ajax({
						type: 'POST',
						url: 'ajax/calendars_delete.php',
						data: {
							calendarId:calendarID
						}
					}).done(function(response){
						$(".messages").text("Kalender erfolgreich gel&ouml;scht!");
						displayCategories();
					}).fail(function(data){
						// Set the message text.
						if (data.responseText !== '') {
							$(messages).text(data.responseText);
						} else {
							$(messages).text('Fehler, Kalender konnte nicht gelöscht werden.');
						}
					});
				}
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Produkte konnten nicht gelesen werden.');
				}
			});
		}
		else{
			alert("Kein Kalender ausgewählt");
		}
	});
	
}

$(document).ready(main);

	
