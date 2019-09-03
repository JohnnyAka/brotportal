/*This file contains event handlers for click events and form-submit events*/

//update user form submit
$(function() {
    // Get the form.
    var form = $('#updateUserPasswordForm');
		
			//clear formfields after modal close (event)
		$('#updateUserPassword').on('hidden.bs.modal', function () {
			$(this).find(form)[0].reset();
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
			resData = JSON.parse(response);
			if(!resData.success){
				displayMessage("Nachricht", resData.displayMessage);
				if(resData.logMessage != null){
					logMessage("Fehler", resData.logMessage);
				}
			}
			//close modal
			$("#updateUserPassword").modal("hide");
			//show changes
            showUserSettings();
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				logMessage('Fehler', data.responseText);
				displayMessage('Fehler', 'Passwort konnte nicht geändert werden. Fehlermeldung: '+data.responseText);
			} else {
				displayMessage('Fehler', 'Passwort konnte nicht geändert werden.');
			}
		});
	});
});

//update user form submit
$(function() {
    // Get the form.
    var form = $('#updateUserMailForm');

    //clear formfields after modal close (event)
    $('#updateUserMail').on('hidden.bs.modal', function () {
        $(this).find(form)[0].reset();
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
            resData = JSON.parse(response);
						if(!resData.success){
							displayMessage("Nachricht", resData.displayMessage);
							if(resData.logMessage != null){
								logMessage("Fehler", resData.logMessage);
							}
						}
            //close modal
            $("#updateUserMail").modal("hide");
            //show changes
            showUserSettings();
        }).fail(function(data) {

            // Set the message text.
            if (data.responseText !== '') {
              logMessage('Fehler', data.responseText);
							displayMessage('Fehler', 'E-Mail Adressen konnten nicht geändert werden. Fehlermeldung: '+data.responseText);
            } else {
              displayMessage('Fehler', 'E-Mail Adressen konnten nicht geändert werden.');
            }
        });
    });
});

function showUserSettings(){
    //ajax call for user data
    $.post("ajax/settings_readUserSettings.php", function(response, status){
        var userData = JSON.parse(response);
        $(".customerIDDisp").text(userData[0]["customerID"]);
        $(".nameDisp").text(userData[0]["name"]);
        $(".mailAdressToDisp").text(userData[0]["mailAdressTo"]);
        $(".mailAdressReceiveDisp").text(userData[0]["mailAdressReceive"]);
    });
}
showUserSettings();
			
//main function for click event handlers
var main = function(){

	$('.updatePasswordButton').click(function(){

		//show modal
		$("#updateUserPassword").modal("show");
	});


    $('.updateEMailButton').click(function(){
        $.ajax({
            type: 'POST',
            url: 'ajax/settings_readUserSettings.php'
        }).done(function(response){
            var userData = JSON.parse(response);

            //set values of form
            $('#EMail').val(userData[0]["mailAdressTo"]);
            $('#MailingList').val(userData[0]["mailAdressReceive"]);

            //show modal
            $("#updateUserMail").modal("show");
        }).fail(function(data){
            // Set the message text.
            if (data.responseText !== '') {
              displayMessage('Fehler', 'Mailadressen konnten nicht geändert werden. Fehlermeldung: '+data.responseText);
            } else {
              displayMessage('Fehler', 'Mailadressen konnten nicht geändert werden.');
            }
        });
    });
}

$(document).ready(main);

