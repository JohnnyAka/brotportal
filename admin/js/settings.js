/*This file contains event handlers for click events and form-submit events*/

//update settings form submit
$(function() {
	// Get the form.
	var form = $('#updateSettingsForm');

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
			//display changes
			displaySettings();
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

//displays settings in text-fields
var displaySettings = function(){

	// Get the messages div.
	var messages = $('#messages');

	$.ajax({
		type: 'POST',
		url: 'ajax/settings_read.php'
	}).done(function(response){
		var settingsData = JSON.parse(response);
		//set values of form
		$('#adminName').val(settingsData[0]["adminName"]);
		$('#adminPassword').val(settingsData[0]["adminPassword"]);
		$('#deleteOrdersInDays').val(settingsData[0]["deleteOrdersInDays"]);
		$('#imagesPath').val(settingsData[0]["imagesPath"]);
		$('#endOfOrderTime').val(settingsData[0]["endOfOrderTime"]);
		$('#exportOrdersTo').val(settingsData[0]["exportOrdersTo"]);
		$('#saveDatabaseTo').val(settingsData[0]["saveDatabaseTo"]);
		
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Einstellungen konnten nicht angezeigt werden.');
		}
	});
};

			
//main function for click event handlers
var main = function(){
	
	displaySettings();
	
}

$(document).ready(main);

	
