/*This file contains event handlers for click events and form-submit events*/

//create category dictionary (id to name)
$(function(){
		$.ajax({
			type: 'POST',
			url: 'ajax/categories_product_read.php'
		}).done(function(response){
			categoriesNameDict = new Object();
			var categoriesData = JSON.parse(response);
			//set product options of select
			for (var x=0;x<categoriesData.length;x++) {
				categoriesNameDict[categoriesData[x].id] = categoriesData[x].name;
			}
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kategorien konnten nicht aus Datenbank gelesen werden.');
				return;
			}
		});
});

//create calendar dictionary (id to name)
$(function(){
		$.ajax({
			type: 'POST',
			url: 'ajax/calendars_read.php'
		}).done(function(response){
			calendarsNameDict = new Object();
			var calendarsData = JSON.parse(response);
			//set product options of select
			for (var x=0;x<calendarsData.length;x++) {
				calendarsNameDict[calendarsData[x].id] = calendarsData[x].name;
			}
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Kalender konnten nicht aus Datenbank gelesen werden.');
				return;
			}
		});
});
		
//create product form submit
$(function() {
    // Get the form.
    var form = $('#createProductForm');

    // Get the messages div.
    var messages = $('#messages');
		
		//clear formfields after modal close (event)
		$('#createProduct').on('hidden.bs.modal', function () {
			form[0].reset();
			//clear selects separately
			$('#productCategory').empty();
			$('#idCalendar').empty();
			$('#imageDirectory').empty();
		})

	// Set up an event listener for the createProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		//check input of form
		var formArray = $(form).serializeArray();
		var preBakeTmp=0;
		for(var x=0;x<formArray.length;x++){
			if(formArray[x].name=='preBakeExp'){
				preBakeTmp=formArray[x].value;
			}
			if(formArray[x].name=='preBakeMax'){
				if(Number(formArray[x].value) < Number(preBakeTmp)){
					alert("Der Wert für 'Tage vorher backen' muss kleiner oder gleich sein, als der Wert für 'Maximal Tage vorher backen'.");
					return;
				}
			}
		}
		
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
			$("#createProduct").modal("hide");
			//reload page to show new article
			//location.reload(); 
		}).fail(function(data) {
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Artikel konnte nicht erstellt werden.');
			}
		});
	});
});

//update product form submit
$(function() {
    // Get the form.
    var form = $('#updateProductForm');

    // Get the messages div.
    var messages = $('#messages');
		
		//clear formfields after modal close (event)
		$('#updateProduct').on('hidden.bs.modal', function () {
			$(this).find('form')[0].reset();
			//clear selects separately
			$('#productCategoryUp').empty();
			$('#idCalendarUp').empty();
			$('#imageDirectoryUp').empty();
		})

	// Set up an event listener for the updateProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		
		//check input of form
		var formArray = $(form).serializeArray();
		var preBakeTmp=0;
		for(var x=0;x<formArray.length;x++){
			if(formArray[x].name=='preBakeExp'){
				preBakeTmp=formArray[x].value;
			}
			if(formArray[x].name=='preBakeMax'){
				if(Number(formArray[x].value) < Number(preBakeTmp)){
					alert("Der Wert für 'Tage vorher backen' muss kleiner oder gleich sein, als der Wert für 'Maximal Tage vorher backen'.");
					return;
				}
			}
		}

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
			$("#updateProduct").modal("hide");
			//reload page to show new article
			//location.reload(); 
		}).fail(function(data) {

			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Artikel konnte nicht geändert werden.');
			}
		});
	});
});

//deletes a product and orders if parameter is true
var deleteProductAndOrders = function(itemID, deleteOrders = false){
	$.ajax({
		type: 'POST',
		url: 'ajax/products_delete.php',
		data: {
			id:itemID
		}
		}).done(function(response){
		$(".messages").text("Artikel erfolgreich gel&ouml;scht!");
		//reload page to show new article
		location.reload(); 
		}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Artikel konnte nicht gel&ouml;scht werden.');
		}
	});
	if(deleteOrders){
		$.ajax({
			type: 'POST',
			url: 'ajax/products_orders_delete.php',
			data: {
				id:itemID
			}
		}).done(function(response){
			$(".messages").text("Bestellungen erfolgreich gel&ouml;scht!");
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Bestellungen konnten nicht gel&ouml;scht werden.');
			}
		});
	}
	$('#deleteProductChoice').modal("hide");
}


//import product data from file to database table "products"
$(function() {
    // Get the form.
    var form = $('#importProductDataForm');

    // Get the messages div.
    var messages = $('#messages');
		

	// Set up an event listener for the createProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		//check input of form
		var formArray = $(form).serializeArray();

		let csvFile = event.target[5].files[0];
		var priceType = formArray[0].value;

		var reader = new FileReader();
		reader.onload = function(e) {
			var lines = e.target.result.split('\r\n');
		    var properties;
		    var productObjects = [];
		    for (i = 0; i < lines.length; ++i){
		    	properties = lines[i].split('|');
		    	productObjects.push({
		    		'artikelNummer':properties[0],
		    		'name':properties[1],
		    		'preis':properties[5],
		    		'gewicht':properties[26],
		    		'zutaten':properties[29],
		    		'kj':properties[71],
		    		'kcal':properties[72],
		    		'eiweiss':properties[73],
		    		'fett':properties[74],
		    		'kohlenhydrate':properties[75],
		    		'gesFettS':properties[76],
		    		'zucker':properties[77],
		    		'ballaststoffe':properties[78],
		    		'kochsalz':properties[79],
		    		'broteinheiten':properties[80],
		    		'grammBE':properties[81],
		    		'allergene':properties[82]
		    	});
		    }
			
			$.ajax({
				type: 'POST',
				url: $(form).attr('action'),
				data:{
					productObj: JSON.stringify(productObjects),
					priceT: priceType
				}
			}).done(function(response) {

				// Set the message text.
				$(messages).text(response);
				
				//close modal
				$("#importProductData").modal("hide");
			}).fail(function(data) {
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Daten konnten nicht importiert werden.');
				}
			});
		};
		reader.readAsText(csvFile);
	});
});

//upload product image
$(function() {
    // Get the form.
    var form = $('#uploadImagesForm');

    // Get the messages div.
    var messages = $('#messages');

	//clear formfields after modal close (event)
	$('#imageUpload').on('hidden.bs.modal', function () {
		$(this).find('form')[0].reset();
		//clear selects separately
		$('#directoryInput').empty();
	})
		

	// Set up an event listener for the createProduct form.
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		//check input of form
		var formArray = $(form).serializeArray();

		//let csvFile = event.target[5].files[0];
		var dir = formArray[0].value;
		var imageSize = formArray[1].value;


		for(var imageFile of event.target[4].files){
			readFile(imageFile);
		}
		//close modal
		$("#imageUpload").modal("hide");

		function readFile(imgFile){
			var reader = new FileReader();
			reader.onload = function(e) {
				var fileDataString = e.target.result;
				$.ajax({
					type: 'POST',
					url: $(form).attr('action'),
					data:{
						image: JSON.stringify(fileDataString),
						directory: dir,
						name: fileName,
						imgSize: imageSize
					}
				}).done(function(response) {
					// Set the message text.
					$(messages).text(response);
				}).fail(function(data) {
					// Set the message text.
					if (data.responseText !== '') {
						$(messages).text(data.responseText);
					} else {
						$(messages).text('Fehler, Bild konnte nicht hochgeladen werden.');
					}
				});
			}

			var fileName = imgFile.name;
			reader.readAsDataURL(imgFile);
		}
	});
});

function setImgsReloadInCreateProductForm(size, selectObjectHandle, changingObjectHandle){
	changingObjectHandle.addEventListener('change', function(event){
	selectObjectHandle.empty();

	$.ajax({
		type: 'POST',
		url: 'ajax/products_imagesOfDirectory_read.php',
		data: {
			'directory': this.value,
			'size': size
		}
	}).done(function(response){
		var imageList = JSON.parse(response);
		
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
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Bilder konnten nicht geladen werden.');
		}
	});
	}, false);
}

			
//main function for click event handlers
var main = function(){
	//events for loading image selects in create product form
	let imageDirectoryHandle = document.getElementById('imageDirectory');
	setImgsReloadInCreateProductForm('medium', $('#imagePath'), imageDirectoryHandle);
	setImgsReloadInCreateProductForm('small', $('#imagePathSmall'), imageDirectoryHandle);
	setImgsReloadInCreateProductForm('big', $('#imagePathBig'), imageDirectoryHandle);
	//events for loading image selects in update product form
	let imageDirectoryUpHandle = document.getElementById('imageDirectoryUp');
	setImgsReloadInCreateProductForm('medium', $('#imagePathUp'), imageDirectoryUpHandle);
	setImgsReloadInCreateProductForm('small', $('#imagePathSmallUp'), imageDirectoryUpHandle);
	setImgsReloadInCreateProductForm('big', $('#imagePathBigUp'), imageDirectoryUpHandle);


	// click-event to retrieve data-id and alert
	$('ul.subSidebarList li').click(function() {
		$('ul.sidebarList li').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		$.post("ajax/products_read.php", {id:$(this).data('id')}, function(response, status){
			var productData = JSON.parse(response);
			
			$(".displayProductID").text(productData[0]["productID"]);
			$(".displayName").text(productData[0]["name"]);
			$(".displayDescription").text(productData[0]["description"]);
			var visableFUText = "Nein";
			if(productData[0]["visibleForUser"]!=0) visableFUText = "Ja";
			$(".displayVisibleForUser").text(visableFUText);
			$(".displayProductCategory").text(categoriesNameDict[productData[0]["productCategory"]]);
			$(".displayOrderPriority").text(productData[0]["orderPriority"]);
			$(".displayImagePath").text(productData[0]["imagePath"]);
			$(".displayImagePathSmall").text(productData[0]["imagePathSmall"]);
			$(".displayImagePathBig").text(productData[0]["imagePathBig"]);
			$(".displayIngredients").text(productData[0]["ingredients"]);
			$(".displayAllergens").text(productData[0]["allergens"]);
			$(".displayWeight").text(productData[0]["weight"]);
			$(".displayPreBakeExp").text(productData[0]["preBakeExp"]);
			$(".displayPreBakeMax").text(productData[0]["preBakeMax"]);
			$(".displayFeatureExp").text(productData[0]["featureExp"]);
			$(".displayPrice1").text(productData[0]["price1"]);
			$(".displayPrice2").text(productData[0]["price2"]);
			$(".displayPrice3").text(productData[0]["price3"]);
			$(".displayPrice4").text(productData[0]["price4"]);
			$(".displayPrice5").text(productData[0]["price5"]);
			$(".displayIdCalendar").text(calendarsNameDict[productData[0]["idCalendar"]]);
		});
	});

    //toggle product-list icon
    $('.icon-list-collapse').click(function() {
        $(this).parent().next("ul").toggle();
        $(this).toggleClass("glyphicon-collapse-down glyphicon-collapse-up");
    });
	
	$('.createProductButton').click(function(){
		//set product options of select
		for (var key in categoriesNameDict) {
				if (key === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
					continue;
				}
				$('#productCategory').append($('<option>', {
					value: key,
					text: categoriesNameDict[key]
				}));
		}
		//set calendar options of select
		for (var key in calendarsNameDict) {
				if (key === 'length' || !calendarsNameDict.hasOwnProperty(key)){ 
					continue;
				}
				$('#idCalendar').append($('<option>', {
					value: key,
					text: calendarsNameDict[key]
				}));
		}

		
		//get subdirectories of images directory
		$.ajax({
			type: 'POST',
			url: 'ajax/products_imageDirectories_read.php'
		}).done(function(response){
			var directoryList = JSON.parse(response);
			$('#imageDirectory').append($('<option>', {
					value: '',
					text: ''
				}));
			//set options of directory select
			for (var dirName of directoryList) {
				//if (dirName === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
				//	continue;
				//}
				$('#imageDirectory').append($('<option>', {
					value: dirName,
					text: dirName
				}));
			}
			//show modal
			$("#createProduct").modal("show");
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Ordner konnten nicht geladen werden.');
			}
		});
	});
	
	$('.updateProductButton').click(function(){
		var item = $("li.active.subSidebarElement");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var itemID = item.data('id');
			$.ajax({
				type: 'POST',
				url: 'ajax/products_read.php',
				data: {
					id:itemID
				}
			}).done(function(response){
				var productData = JSON.parse(response);
				
				//set productCategory options of select
				for (var key in categoriesNameDict) {
					if (key === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
						continue;
					}
					$('#productCategoryUp').append($('<option>', {
						value: key,
						text: categoriesNameDict[key]
					}));
				}
				//set calendar options of select
				for (var key in calendarsNameDict) {
						if (key === 'length' || !calendarsNameDict.hasOwnProperty(key)){ 
							continue;
						}
						$('#idCalendarUp').append($('<option>', {
							value: key,
							text: calendarsNameDict[key]
						}));
				}
				//get subdirectories of images directory
				$.ajax({
					type: 'POST',
					url: 'ajax/products_imageDirectories_read.php'
				}).done(function(response){
					var directoryList = JSON.parse(response);
					$('#imageDirectoryUp').append($('<option>', {
							value: '',
							text: ''
						}));
					//set options of directory select
					for (var dirName of directoryList) {
						//if (dirName === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
						//	continue;
						//}
						$('#imageDirectoryUp').append($('<option>', {
							value: dirName,
							text: dirName
						}));
					}

					//set values of form
					$('#productidUp').val(productData[0]["productID"]);
					$('#nameUp').val(productData[0]["name"]);
					$('#descriptionUp').val(productData[0]["description"]);
					$('#idUp').val(productData[0]["id"]);
					//Boolean() doesnt seem to work
					var visForU = productData[0]["visibleForUser"];
					if (visForU != 0){visForU = true}
					else{visForU = false}
					$('#visibleForUserUp').prop('checked', visForU);
					$('#productCategoryUp').val(productData[0]["productCategory"]);
					$('#orderPriorityUp').val(productData[0]["orderPriority"]);

					//cut image Path Strings and display in form
					var productImageObjects = [];
					if(productData[0]["imagePath"] != '' && productData[0]["imagePath"] != null){
						let imageString = productData[0]["imagePath"].split('/');
						productImageObjects.push({
							'imageNames' : imageString[1],
							'imageSelectHandles' : $('#imagePathUp'),
							'imagePathsDir' : imageString[0],
							'size' : 'medium'
						});
					}
					if(productData[0]["imagePathSmall"] != '' && productData[0]["imagePathSmall"] != null){
						let imageStringSmall = productData[0]["imagePathSmall"].split('/');
						productImageObjects.push({
							'imageNames' : imageStringSmall[1],
							'imageSelectHandles' : $('#imagePathSmallUp'),
							'imagePathsDir' : imageStringSmall[0],
							'size' : 'small'
						});
					}
					if(productData[0]["imagePathBig"] != '' && productData[0]["imagePathBig"] != null){
						let imageStringBig = productData[0]["imagePathBig"].split('/');
						productImageObjects.push({
							'imageNames' : imageStringBig[1],
							'imageSelectHandles' : $('#imagePathBigUp'),
							'imagePathsDir' : imageStringBig[0],
							'size' : 'big'
						});
					}
					for(let imagePathObject of productImageObjects){
						if(imagePathObject['imagePathsDir'] != ''){
							$('#imageDirectoryUp').val(imagePathObject['imagePathsDir']);
							var imagePathDirectory = imagePathObject['imagePathsDir'];
						}
					}
					/*//trigger onchange event on imageDirectory select to load images to image selects
					if ("createEvent" in document) {
					    var evt = document.createEvent("HTMLEvents");
					    evt.initEvent("change", false, true);
					    document.getElementById('imageDirectoryUp').dispatchEvent(evt);
					}
					else{
					    document.getElementById('imageDirectoryUp').fireEvent("onchange");
					}*/
					if(imagePathDirectory != ''){
						for(let imagePathObject of productImageObjects){
							let selectObjectHandle = imagePathObject['imageSelectHandles'];
							$.ajax({
								type: 'POST',
								url: 'ajax/products_imagesOfDirectory_read.php',
								data: {
									'directory': imagePathDirectory,
									'size': imagePathObject['size']
								}
							}).done(function(response){
								var imageList = JSON.parse(response);
								//set empty option
								selectObjectHandle.append($('<option>', {
									value: '',
									text: ''
								}));
								//set options of directory select
								for (var imgName of imageList) {
									selectObjectHandle.append($('<option>', {
										value: imgName,
										text: imgName
									}));
								}
								//set image selects 
									if(imagePathObject['imageNames'] != ''){
										selectObjectHandle.val(imagePathObject['imageNames']);
									
								}
							}).fail(function(data){
								// Set the message text.
								if (data.responseText !== '') {
									$(messages).text(data.responseText);
								} else {
									$(messages).text('Fehler, Pfade konnten nicht geladen werden.');
								}
							});
						}
					}


					
					
					$('#ingredientsUp').val(productData[0]["ingredients"]);
					$('#allergensUp').val(productData[0]["allergens"]);
					$('#weightUp').val(productData[0]["weight"]);
					$('#preBakeExpUp').val(productData[0]["preBakeExp"]);
					$('#preBakeMaxUp').val(productData[0]["preBakeMax"]);
					$('#featureExpUp').val(productData[0]["featureExp"]);
					$("#price1Up").val(productData[0]["price1"]);
					$("#price2Up").val(productData[0]["price2"]);
					$("#price3Up").val(productData[0]["price3"]);
					$("#price4Up").val(productData[0]["price4"]);
					$("#price5Up").val(productData[0]["price5"]);
					$("#idCalendarUp").val(productData[0]["idCalendar"]);


					//show modal
					$("#updateProduct").modal("show");
				}).fail(function(data){
					// Set the message text.
					if (data.responseText !== '') {
						$(messages).text(data.responseText);
					} else {
						$(messages).text('Fehler, Ordner konnten nicht geladen werden.');
					}
				});
				
				

				
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Artikel konnte nicht geändert werden.');
				}
			});
		}
		else{
			alert("Kein Artikel ausgewählt");
		}
	});
	$('.deleteProductButton').click(function(){
		var messages = $('#messages');
		
		var item = $("li.active.subSidebarElement");
		var itemID = item.data('id');
		if (item.length){
			//delete only if not present in any order
			$.ajax({
				type: 'POST',
				url: 'ajax/products_orders_read.php',
				data: {
					id:itemID
				}
			}).done(function(response){
				productOrders = JSON.parse(response);
				if(productOrders !== 'undefined' && productOrders.length > 0){
					$('#deleteProductChoice').modal("show");
				}
				else{
					deleteProductAndOrders(itemID);
				}
				
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Artikel konnte nicht gelesen werden.');
				}
			});
		}
		else{
			alert("Kein Artikel ausgewählt");
		}
	});
	
	$('.deleteProductAndOrdersButton').click(function(){
		var itemID = $("li.active.subSidebarElement").data('id');
		deleteProductAndOrders(itemID, true);
	});

	$('.imageUploadButton').click(function(){

		//get subdirectories of images directory
		$.ajax({
			type: 'POST',
			url: 'ajax/products_imageDirectories_read.php'
		}).done(function(response){
			var directoryList = JSON.parse(response);
			
			//set options of directory select
			for (var dirName of directoryList) {
				//if (dirName === 'length' || !categoriesNameDict.hasOwnProperty(key)){ 
				//	continue;
				//}
				$('#directoryInput').append($('<option>', {
					value: dirName,
					text: dirName
				}));
			}
			//show modal
			$('#imageUpload').modal("show");
		}).fail(function(data){
			// Set the message text.
			if (data.responseText !== '') {
				$(messages).text(data.responseText);
			} else {
				$(messages).text('Fehler, Ordner konnten nicht geladen werden.');
			}
		});
	});

	$('.importProductDataButton').click(function(){
		$('#importProductData').modal("show");
	});
}

$(document).ready(main);

