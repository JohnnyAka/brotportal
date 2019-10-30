/*This file contains helpers for all pages*/
	
	function displayMessage(title, message){
		$("#alertMessageTitle").text(title);
		$("#alertMessageText").text(message);
		$("#alertModal").modal("show");
	}
	
	function logMessage(type, message){
		var customerID = $('#userID').data("value");
		//var nowDateTime = new Date().toLocaleDateString('de-DE', {day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' });
		$.ajax({
				type: 'POST',
				url: 'ajax/orders_logMessage.php',
				data: {
					//logDateTime:nowDateTime,
					logType:type,
					logMessage:message
				}
			}).done(function(response) {				
			}).fail(function(data) {
			//falls der log auf dem Server nicht funktioniert, kann hier die Fehlernachricht im Client ausgegeben werden:
			//displayMessage("Fehler", data.responseText);
		});
	}
	

			
//main function for click event handlers
var main = function(){

	
}

$(document).ready(main);

