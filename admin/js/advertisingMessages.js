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
				$(messages).text('Fehler, Kategorie konnte nicht erstellt werden.');
			}
		});
	});
});

//update productcat form submit
$(function() {
	// Get the form.
	var form = $('#updateProductCatForm');

	// Get the messages div.
	var messages = $('#messages');
	
	//clear formfields after modal close (event)
	$('#updateProductCat').on('hidden.bs.modal', function () {
		$('#productCatNameUp').val('');
		$('#upperCategoryUp').empty();
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
			buildCategoryDict()
			//close modal
			$("#updateProductCat").modal("hide");
			//display changes
			displayMessages();
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
		$(".displayLinkedProduct").text(messageData[0].linkedProductId);
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
function resetDatepickers() {
	var showtimeDatepickers = ["#popupStartDate", "#popupEndDate", "#messageboxStartDate", "#messageboxEndDate"];
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
		resetDatepickers();

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
	
	
	$('.updateProductCatButton').click(function(){
		var item = $(".messageListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var selectedCategory = item.data('idcategory');
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_product_single_read.php',
				data: {
					catId:selectedCategory
				}
			}).done(function(response){
				var productData = JSON.parse(response);
				
				//set product options of select
				$('#upperCategoryUp').append($('<option>', {
					value: 0,
					text: 'Keine'
				}));
				for (var key in productsNameDict) {
					if (key === 'length' || !productsNameDict.hasOwnProperty(key)){ 
						continue;
					}
					$('#upperCategoryUp').append($('<option>', {
						value: key,
						text: productsNameDict[key]
					}));
				}
				
				//set values of form
				$('#productCatNameUp').val(productData[0]['name']);
				$('#orderPriorityUp').val(productData[0]['orderPriority']);
				if(productData[0]["upperCategoryID"] != null){
					$('#upperCategoryUp').val(productData[0]["upperCategoryID"]);
				}
				
				//set hidden formfields
				$('#catIdUp').val(selectedCategory);
				
				//show modal
				$("#updateProductCat").modal("show");
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
	
	$('.deleteProductCatButton').click(function(){
		var item = $(".messageListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var categoryID = item.data('idcategory');
			//check for dependent customers
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_product_products_read.php',
				data: {
					id:categoryID
				}
			}).done(function(response){
				products = JSON.parse(response);
				if(products !== 'undefined' && products.length > 0){
					alert("Es gibt noch Artikel dieser Kategorie. Bevor die Kategorie gelöscht werden kann, bitte die Artikel löschen oder die Kategorie dieser Artikel ändern.");
				}
				else{
					$.ajax({
						type: 'POST',
						url: 'ajax/categories_product_category_read.php',
						data: {
							catId:categoryID
						}
					}).done(function(response){
						categories = JSON.parse(response);
						if(categories !== 'undefined' && categories.length > 0){
							alert("Es gibt noch Kategorien, die dieser Kategorie untergeordnet sind. Bevor die Kategorie gelöscht werden kann, bitte die übergeordnete Kategorie dieser Kategorien ändern.");
						}
						else{
							$.ajax({
								type: 'POST',
								url: 'ajax/categories_product_delete.php',
								data: {
									catId:categoryID
								}
							}).done(function(response){
								$(".messages").text("Kategorie erfolgreich gel&ouml;scht!");
								buildCategoryDict();
								displayMessages();
							}).fail(function(data){
								// Set the message text.
								if (data.responseText !== '') {
									$(messages).text(data.responseText);
								} else {
									$(messages).text('Fehler, Kategorie konnte nicht gelöscht werden.');
								}
							});
						}
					}).fail(function(data){
						// Set the message text.
						if (data.responseText !== '') {
							$(messages).text(data.responseText);
						} else {
							$(messages).text('Fehler, Kategorie konnte nicht gelöscht werden.');
						}
					});
				}
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Artikel konnten nicht gelesen werden.');
				}
			});
		}
		else{
			alert("Keine Kategorie ausgewählt");
		}
	});
	
}

$(document).ready(main);

	
