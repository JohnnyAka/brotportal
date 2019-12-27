/*This file contains event handlers for click events and form-submit events*/


//create user form submit
$(function() {
    // Get the form.
    var form = $('#loginForm');
		


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
			let responseObject = JSON.parse(response);
			if(responseObject['pwCorrect'] == false){
				displayMessage("Nachricht","Der Benutzername und das Passwort stimmen nicht überein.");
			}
			else{
				if (responseObject['agbsRead'] != 0){
					window.location.href = 'orders.php';
				}
				else{
					$.ajax({
						async:false,
						url: '../external/AGBs.txt',
						dataType: 'text',
						success: function(data) 
						{
							$('#agbMessageText').append(data);
						}
					});
					$("#agbModal").modal("show");
				}
			}
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				logMessage('Fehler', data.responseText);
				displayMessage('Fehler', 'Eingabe konnte nicht verarbeitet werden. Fehlermeldung: '+data.responseText);
			} else {
				displayMessage('Fehler', 'Eingabe konnte nicht verarbeitet werden.');
			}
		});
	});
});

			
//main function for click event handlers
var main = function(){
	$('.agbModalCheck').change(function(){
		let btn = $('.agbModalButton');
		if($('.checkAGB')[0].checked == true && $('.checkCookies')[0].checked == true){
			btn.removeClass('disabled');
			btn[0].disabled = false;
		}
		else{
			btn.addClass('disabled');
			btn[0].disabled = true;
		}
	});
	
	$('.agbModalButton').click(function(){
		$.ajax({
				type: 'POST',
				url: 'ajax/login_agreeAGBandCookies.php'
			}).done(function(response) {
				window.location.href = 'orders.php';
			}).fail(function(data) {
				displayMessage('Fehler', 'Es ist ein Fehler beim Speichern der Zustimmung aufgetreten.');
				if (data.responseText !== '') {
					logMessage(data.responseText);
				} else {
					logMessage('Fehler', 'AGB und Cookies Fehler.');
				}
			});
	});
	
}

$(document).ready(main);

