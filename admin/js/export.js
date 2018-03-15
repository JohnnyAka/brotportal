

//datepicker setup including onclose ajax orderlist load function
$(function() {
	$( "#datepicker" ).datepicker($.datepicker.regional[ "de" ]);
	$( "#datepicker" ).datepicker( "option", "dateFormat", "dd.mm.yy" );
	$( "#datepicker" ).datepicker( "setDate", "+1" );
});

//main function for click event handlers
var main = function(){
	var messages = $("#messages");
	
	$('.exportOrdersButton').click(function(){
		var selectedDate = $( "#datepicker" ).datepicker().val();
		//check dateinput and send ajax request
		var regExp = /\d\d.\d\d.\d\d\d\d/;
		if(regExp.test(selectedDate)){
			selectedDate = selectedDate.split('.');
			$.ajax({
				type: 'POST',
				url: 'ajax/export_saveDataToCSV.php',
				data: {
					day:selectedDate[0],
					month:selectedDate[1],
					year:selectedDate[2]
				}
			}).done(function(response){
				//alert(response);
				$(messages).text("Bestellungen erfolgreich exportiert!");
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Bestellungen konnten nicht exportiert werden.');
				}
			});
		}
		else{
			alert("Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
		}
	});
	
	$('.deleteOldOrdersButton').click(function(){
		$.ajax({
			type: 'POST',
			url: 'ajax/export_deleteOldOrders.php'
		}).done(function(response){
			alert(response);
			$(messages).text("Bestellungen erfolgreich gelöscht!");
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Bestellungen konnten nicht gelöscht werden.');
			}
		});
	});
	
	
}

$(document).ready(main);

	