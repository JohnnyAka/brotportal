/*This file contains event handlers for click events and form-submit events*/


//create user form submit
$(function() {
    // Get the form.
    var form = $('#loginForm');

    // Get the messages div.
    var messages = $('#messages');
		


	// Set up an event listener for the createUser form.
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

			if(response != false){
				window.location.href = 'orders.php';
			}
			else{
				alert("Der Benutzername und das Passwort stimmen nicht überein.");
			}
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Eingabe konnte nicht verarbeitet werden.');
			}
		});
	});
});

			
//main function for click event handlers
var main = function(){

	
}

$(document).ready(main);

