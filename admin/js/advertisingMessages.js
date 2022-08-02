/*This file contains event handlers for click events and form-submit events*/
//create category dictionary (id to name)
function buildProductDict(){
		$.ajax({
			type: 'POST',
			url: 'ajax/advertisingMessages_products_read.php'
		}).done(function(response){
			productsNameDict = new Object();
			var productsData = JSON.parse(response);
			//set product options of select
			for (var x=0;x<productsData.length;x++) {
				productsNameDict[productsData[x].id] = productsData[x].productID + ' ' + productsData[x].name;
			}
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Produkte konnten nicht aus Datenbank gelesen werden.');
				return;
			}
		});
};
buildProductDict();

//create message form submit
$(function() {
    // Get the form.
    var form = $('#createAdvertisingMessageForm');

    // Get the messages div.
    var messages = $('#messages');
		
	//clear formfields after modal close (event)
	$('#createAdvertisingMessage').on('hidden.bs.modal', function () {
		$(this).find(form)[0].reset();
		//clear selects separately
		$('#messageImage').empty();
		$('#linkedProductId').empty();
	})

	// Set up an event listener for the createMessage form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();

		// Serialize the form data.
		var formData = $(form).serialize();
		
		//check whether start and end dates are in order 
		if($('#popupStartDate').datepicker('getDate') > $('#popupEndDate').datepicker('getDate') || $('#messageboxStartDate').datepicker('getDate') > $('#messageboxEndDate').datepicker('getDate')) {
			$(messages).text("Das Popup oder das Messagebox Enddatum ist vor dem Startdatum.");
			return;
		}

		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData
		}).done(function(response) {

			// Set the message text.
			$(messages).text(response);
			//buildAdvertisingMessagesDict();
			//close modal
			$("#createAdvertisingMessage").modal("hide");
			//show changes
			displayMessages();
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Nachricht konnte nicht erstellt werden.');
			}
		});
	});
});

//update advertisingMessages form submit
$(function() {
	// Get the form.
	var form = $('#updateAdvertisingMessageForm');

	// Get the messages div.
	var messages = $('#messages');
	
	//clear formfields after modal close (event)
	$('#updateAdvertisingMessage').on('hidden.bs.modal', function () {
		$('#nameUp').val('');
		$('#messageHeaderUp').val('');
		$('#messageTextUp').val('');
		$('#popupStartDateUp').val('');
		$('#popupEndDateUp').val('');
		$('#messageboxStartDateUp').val('');
		$('#messageboxEndDateUp').val('');
		$('#orderPriorityUp').val('');
		$('#messageImageUp').empty();
		$('#linkedProductIdUp').empty();
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
			// Set the message text.
			$(messages).text(response);
			//close modal
			$("#updateAdvertisingMessage").modal("hide");
			//display changes
			displayMessages();
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Nachricht konnte nicht geändert werden.');
			}
		});
	});
});

//displays messages in left sidebar
var displayMessages = function(){
	$('ul.sidebarList').empty();
	$.ajax({
		type: 'POST',
		url: 'ajax/advertisingMessages_messages_read.php'
	}).done(function(response){
		var messageData = JSON.parse(response);
		//set Item List
		for(var x=0; x < messageData.length; x++){
			$('ul.sidebarList').append("<li class='messageListItem' data-idmessage='"+messageData[x].id+"'>"+messageData[x].name+"</li>");
		}
		// click-event to select category
		$('ul.sidebarList li').click(function() {
			$('ul.sidebarList li').removeClass("active");
			$(this).addClass("active");
			displayActiveMessage($(this));
		});
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Nachrichten konnten nicht angezeigt werden.');
		}
	});
};

//displays active message
var displayActiveMessage = function(message){

	var MessageID = message.data('idmessage');
	$.ajax({
		type: 'POST',
		url: 'ajax/advertisingMessages_message_read.php',
		data: {
			id:MessageID
		}
	}).done(function(response){
		var messageData = JSON.parse(response);
		
		$(".displayName").text(messageData[0].name);
		$(".displayImage").text(messageData[0].messageImage);
		$(".displayCaption").text(messageData[0].messageHeader);
		$(".displayText").text(messageData[0].messageText);
		var productName;
		if(messageData[0].linkedProductId == 0)
			{productName = 'Kein Produkt verlinkt';}
		else
			{productName = productsNameDict[messageData[0].linkedProductId];}
		$(".displayLinkedProduct").text(productName);
		$(".displayPriority").text(messageData[0].orderPriority);
		$(".displayPopupStart").text(formatDateFromMysql(messageData[0].popupStartDate));
		$(".displayPopupEnd").text(formatDateFromMysql(messageData[0].popupEndDate));
		$(".displayMessageboxStart").text(formatDateFromMysql(messageData[0].messageboxStartDate));
		$(".displayMessageboxEnd").text(formatDateFromMysql(messageData[0].messageboxEndDate));

	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Nachricht konnte nicht geladen werden.');
		}
	});
};

function formatDateFromMysql(dateToFormat){
	const [year, month, day] = [...dateToFormat.split("-")];
	const popupStartDateFormated = day+'.'+month+'.'+year;
	return popupStartDateFormated;
}

//datepicker setup including onclose ajax orderlist load function
function resetDatepickers(showtimeDatepickers) {
	showtimeDatepickers.forEach(function(picker){
		$( picker ).datepicker($.datepicker.regional[ "de" ])
		.datepicker( "option", "dateFormat", "dd.mm.yy" )
		.datepicker( "setDate", "-1" )
		.datepicker( "option", "onClose", function(selectedDate, picker){})
		.attr('readonly','readonly');
	});
};

	
//main function for click event handlers
var main = function(){



	displayMessages();
	
	$('.createAdvertisingMessageButton').click(function(){
		//reset datepickers of form
		resetDatepickers(["#popupStartDate", "#popupEndDate", "#messageboxStartDate", "#messageboxEndDate"]);

		//set product options of select
		$('#linkedProductId').append($('<option>', {
					value: 0,
					text: 'Kein Produkt'
				}));
		for (var key in productsNameDict) {
				if (key === 'length' || !productsNameDict.hasOwnProperty(key)){ 
					continue;
				}
				$('#linkedProductId').append($('<option>', {
					value: key,
					text: productsNameDict[key]
				}));
		}

		//set image options of select
		$.ajax({
			type: 'POST',
			url: 'ajax/advertisingMessages_imagesOfDirectory_read.php'
		}).done(function(response){
			var imageList = JSON.parse(response);
			selectObjectHandle = $('#messageImage');

			selectObjectHandle.append($('<option>', {
				value: '',
				text: ''
			}));
			//set options of directory select
			for (var imgName of imageList) {
				//if (dirName === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
				//	continue;
				//}
				selectObjectHandle.append($('<option>', {
					value: imgName,
					text: imgName
				}));
			}
			$("#createAdvertisingMessage").modal("show");
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Bilder konnten nicht geladen werden.');
			}
		});
	});
	
	
	$('.updateAdvertisingMessageButton').click(function(){
		var item = $(".messageListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');


			//get values of item from db
			var selectedMessage = item.data('idmessage');
			$.ajax({
				type: 'POST',
				url: 'ajax/advertisingMessages_message_read.php',
				data: {
					id:selectedMessage
				}
			}).done(function(response){
				var messageData = JSON.parse(response);
				
				//set values of form
				$('#nameUp').val(messageData[0]['name']);
				$('#messageHeaderUp').val(messageData[0]['messageHeader']);
				$('#messageTextUp').val(messageData[0]['messageText']);
				$('#orderPriorityUp').val(messageData[0]['orderPriority']);
				
				$('#idUp').val(messageData[0]["id"]);
				

				//reset datepickers of form
				resetDatepickers(["#popupStartDateUp", "#popupEndDateUp", "#messageboxStartDateUp", "#messageboxEndDateUp"]);
				var showtimeDatepickers = [ "#popupEndDateUp", "#messageboxStartDateUp", "#messageboxEndDateUp"];
				$("#popupStartDateUp").datepicker( "setDate", formatDateFromMysql(messageData[0]['popupStartDate']));
				$("#popupEndDateUp").datepicker( "setDate", formatDateFromMysql(messageData[0]['popupEndDate']));
				$("#messageboxStartDateUp").datepicker( "setDate", formatDateFromMysql(messageData[0]['messageboxStartDate']));
				$("#messageboxEndDateUp").datepicker( "setDate", formatDateFromMysql(messageData[0]['messageboxEndDate']));

				//set product options of select
				$('#linkedProductIdUp').append($('<option>', {
							value: 0,
							text: 'Kein Produkt'
						}));
				for (var key in productsNameDict) {
						if (key === 'length' || !productsNameDict.hasOwnProperty(key)){ 
							continue;
						}
						$('#linkedProductIdUp').append($('<option>', {
							value: key,
							text: productsNameDict[key]
						}));
				}
				$('#linkedProductIdUp').val(messageData[0]["linkedProductId"]);

				//set image options of select
				$.ajax({
					type: 'POST',
					url: 'ajax/advertisingMessages_imagesOfDirectory_read.php'
				}).done(function(response){
					var imageList = JSON.parse(response);
					selectObjectHandle = $('#messageImageUp');

					selectObjectHandle.append($('<option>', {
						value: '',
						text: 'Kein Bild'
					}));
					//set options of directory select
					for (var imgName of imageList) {
						//if (dirName === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
						//	continue;
						//}
						selectObjectHandle.append($('<option>', {
							value: imgName,
							text: imgName
						}));
					}
					$('#messageImageUp').val(messageData[0]['messageImage']);

					$("#updateAdvertisingMessage").modal("show");
				}).fail(function(data){
					// Set the message text.
					if (data.responseText !== '') {
						$(messages).text(data.responseText);
					} else {
						$(messages).text('Fehler, Bilder konnten nicht geladen werden.');
					}
				});
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Nachricht konnte nicht geändert werden.');
				}
			});
			
		}
		else{
			alert("Keine Nachricht ausgewählt");
		}
	});
	
	$('.deleteAdvertisingMessageButton').click(function(){
		var item = $(".messageListItem.active");
		// Get the messages div.
		var messages = $('#messages');
		
		//get values of item from db
		var messageID = item.data('idmessage');
		if (item.length){
			$.ajax({
				type: 'POST',
				url: 'ajax/advertisingMessage_delete.php',
				data: {
					id:messageID
				}
			}).done(function(response){
				$(".messages").text("Nachricht erfolgreich gel&ouml;scht!");
				//reload page to show new message
				location.reload(); 
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Nachricht konnte nicht gel&ouml;scht werden.');
				}
			});
		}
		else{
			alert("Keine Nachricht ausgewählt");
		}
	});
	
}

$(document).ready(main);

	
