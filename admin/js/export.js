

//datepicker setup including onclose ajax orderlist load function
$(function() {
	$( "#datepicker" ).datepicker($.datepicker.regional[ "de" ]);
	$( "#datepicker" ).datepicker( "option", "dateFormat", "dd.mm.yy" );
	$( "#datepicker" ).datepicker( "setDate", "+1" );
});

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

//update the list of Orderexportfiles available for download
async function updateExportList(){
		await sleep(50);//wait for the file to be able to be spotted
    $.ajax({
        type: 'POST',
        url: 'ajax/export_updateExportList.php'
    }).done(function(response){
        //alert(response);
		var files = JSON.parse(response);
		var listnode = $("#listExportFiles");
		listnode.empty();
		listnode.html("<h3>Export-Dateien</h3>")
		for(i = files.length-1; i>=0;i--){
			var filename = files[i];
			if(!(filename=="." || filename=="..")) {
                listnode.append("<a href='exports/"+filename+"' download><li>" + filename + "</li></a>");
            }
		}
        $(messages).text("Erfolgreiches Update!");
    }).fail(function(data){
        // Set the message text.
        if (data.responseText !== '') {
            $(messages).text(data.responseText);
        } else {
            $(messages).text('Fehler, Update der Exportfiles konnte nicht durchgeführt werden.');
        }
    });
}
updateExportList();

//is triggered on button-click of export orders button
function deleteOldOrders(){
		$.ajax({
			type: 'POST',
			url: 'ajax/export_deleteOldOrders.php'
		}).done(function(response){
			$(messages).text("Bestellungen erfolgreich gelöscht!");
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Bestellungen konnten nicht gelöscht werden.');
			}
		});
}

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
		updateExportList();
		//delete old orders in database, according to the number of days in settings
		deleteOldOrders();
	});
	
	$('.deleteOldOrdersButton').click(function(){
		alert("not working at the moment...")
	});
	
	
}

$(document).ready(main);

	