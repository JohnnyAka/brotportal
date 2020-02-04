/*This file contains event handlers for click events and form-submit events*/


//datepicker setup including onclose ajax orderlist load function
$(function() {
	$( "#ordersDatepicker" ).datepicker({
	dateFormat:"dd.mm.yy",
	minDate:"-380",
	onClose:function(selectedDate, picker){
		showOrders();
	},
	beforeShowDay:function(date){
			day  = ('0' + date.getDate()).slice(-2);
			month = ('0' + (date.getMonth() + 1)).slice(-2);
			year =  date.getFullYear();
			var formatedDate = year + '-' + month + '-' + day;
			if ($.inArray(formatedDate, orderDates) !== -1) {
				return [true, 'ui-state-orderDays', 'Bestellung vorhanden.'];
			}
			return [true, 'ui-state-noOrderDays', 'Keine Bestellung vorhanden.'];
		}
}).attr('readonly','readonly');
$( "#ordersDatepicker" ).datepicker( "setDate", "+1" );

	/*$( "#ordersDatepicker" ).datepicker($.datepicker.regional[ "de" ]);
	$( "#ordersDatepicker" ).datepicker( "option", "dateFormat", "dd.mm.yy" );
	$( "#ordersDatepicker" ).datepicker( "setDate", "+1" );
	$( "#ordersDatepicker" ).datepicker( "option", "minDate", "-380" );
	$( "#ordersDatepicker" ).datepicker( "option", "onClose", function(selectedDate, picker){
		showOrders();
	});*/
	updateOrderDays();
	//datepicker for take orders from selected date to current date
	$( "#takeDatepicker" ).datepicker({
		dateFormat:"dd.mm.yy",
		minDate:"-380",
		beforeShowDay:function(date){
			day  = ('0' + date.getDate()).slice(-2);
			month = ('0' + (date.getMonth() + 1)).slice(-2);
			year =  date.getFullYear();
			var formatedDate = year + '-' + month + '-' + day;
			if ($.inArray(formatedDate, orderDates) !== -1) {
				return [true, 'ui-state-orderDays', 'Bestellung vorhanden.'];
			}
			return [false, 'ui-state-noOrderDays', 'Keine Bestellung vorhanden.'];
		}
	}).attr('readonly','readonly');
});

var orderDates = [];
var updateOrderDays = function(){
	var customerID = $('#userID').data("value");
	$.ajax({
		type: 'POST',
		url: 'ajax/orders_getOrderDays.php',
		data: {
			userID:customerID
		}
	}).done(function(response) {
		var ordersData = JSON.parse(response);
		orderDates = [];
		for(var x=0; x < ordersData.length; x++){
			orderDates.push(ordersData[x].orderDate);
		}
	}).fail(function(data) {
		displayMessage('Fehler', 'Tage an denen bestellt wurde konnten nicht geladen werden.');
		if (data.responseText !== '') {
			logMessage(data.responseText);
		} else {
			logMessage('Fehler', 'Tage an denen bestellt wurde konnten nicht geladen werden.');
		}
	});
}

//create product list(dictionary) for name retrieval via id, productPriorityDict, product-category list for category retrieval via id
//, category list for category name retrieval via id and categoryPriorityDict --- then show orders
//productsNameDict, productsCategoryDict, categoriesNameDict
$(function() {
	// Submit the form using AJAX.
	$.ajax({
		type: 'POST',
		url: 'ajax/orders_read_products.php'
	}).done(function(response) {
		productsData = JSON.parse(response);
		//set Item List
		productsNameDict = {};
		productsCategoryDict = {};
		productsOrderPriorityDict = {};
		for(var x=0; x < productsData.length; x++){
			productsCategoryDict[productsData[x].id] = productsData[x].productCategory;
			productsOrderPriorityDict[productsData[x].id] = productsData[x].orderPriority;
			productsNameDict[productsData[x].id] = productsData[x].name;
		}
		//create product category list(dictionary) for name retrieval
		$.ajax({
			type: 'POST',
			url: 'ajax/orders_read_productCategories.php'
		}).done(function(response) {
			categoriesData = JSON.parse(response);
			//set Item List
			categoriesNameDict = {};
			categoriesPriorityDict = {};
			for(var x=0; x < categoriesData.length; x++){
				categoriesNameDict[categoriesData[x].id] = categoriesData[x].name;
				categoriesPriorityDict[categoriesData[x].id] = categoriesData[x].orderPriority;
			}
			showOrders();
			createCategoryTree(3, ".productList");
		}).fail(function(data) {
			displayMessage('Fehler', 'Kategorienamensliste konnte nicht erstellt werden.');
			if (data.responseText !== '') {
				logMessage(data.responseText);
			} else {
				logMessage('Fehler', 'Kategorienamensliste konnte nicht erstellt werden.');
			}
		});
	}).fail(function(data) {
		displayMessage('Fehler', 'Artikelnamensliste konnte nicht erstellt werden.');
		if (data.responseText !== '') {
			logMessage(data.responseText);
		} else {
			logMessage('Fehler', 'Artikelnamensliste konnte nicht erstellt werden.');
		}
	});
});

//create productlist
function createCategoryTree(treeDepth, startNode){
	$.ajax({
		type: 'POST',
		url: 'ajax/orders_read_productCategories.php'
	}).done(function(response) {
		categoriesData = JSON.parse(response);
		var listlvl = [];
		for(var x=0; x<treeDepth; x++){
			if(x==0){upperlvl = false}//first level
			else{upperlvl = listlvl[x-1]};
			listlvl.push(createListLevel(x, upperlvl, categoriesData));
		}
		productTree = compileTree(listlvl, treeDepth);//needs to be global -> see resize()
		buildVisualProductList(productTree, startNode);
	}).fail(function(data) {
		displayMessage('Fehler', 'Produktliste konnte nicht erstellt werden.');
		if (data.responseText !== '') {
			logMessage(data.responseText);
		} else {
			logMessage('Fehler', 'Produktliste konnte nicht erstellt werden.');
		}
	});
};

function createListLevel(x, upperlvl, categoriesData){
	var levelList = [];
	
	if(x != 0){	
		for(currentUpperCat of upperlvl){
			//check if any category has this upperlvl
			for(var y=0; y<categoriesData.length; y++){
				if(categoriesData[y].upperCategoryID == currentUpperCat.id){
					levelList.push(categoriesData[y]);
				}
			}
		}
	}
	else{
		//check if any category has this upperlvl
		for(var y=0; y<categoriesData.length; y++){
			if(categoriesData[y].upperCategoryID == 0){
				levelList.push(categoriesData[y]);
			}
		}
	}
	return levelList;
}
//filter functions
function findProducts(catID) {
  return function(product) {
    if(product.productCategory == catID){
			return product.id;
		};
  }
}

function findSubcategories(catID) {
  return function(categoryNow) {
    if(categoryNow.upperCategoryID == catID){
			return categoryNow.id;
		}
  }
}

//tree building
function compileTree(listlvl, treeDepth){
	let growingTree = [];
	growingTree = listlvl[0].sort(compareListItems);
	
	var lvlCounter = 0;
	for(category of growingTree){
		linkSubcategories(category, lvlCounter, treeDepth, listlvl);
	}
	return growingTree;
}

function linkSubcategories(category, lvlCounter, treeDepth, listlvl){
	lvlCounter += 1;
	if(lvlCounter < treeDepth){
		let resultsSubcategories = listlvl[lvlCounter].filter(findSubcategories(category.id));
		category["subcategories"] = resultsSubcategories;
		for(cat of category["subcategories"]){
			linkSubcategories(cat, lvlCounter, treeDepth, listlvl);
		}
	}
	let resultsProducts = productsData.filter(findProducts(category.id));
	resultsProducts.sort(compareListItems);
	category["products"] = resultsProducts;
	lvlCounter -= 1;
}

function compareListItems(a, b){
	let comparison = 0;
	if (a.orderPriority < b.orderPriority){
		comparison = -1;
	}
	else if (a.orderPriority > b.orderPriority){
		comparison = 1;
	}
	else{
		let nameA = a.name.toUpperCase();
		let nameB = b.name.toUpperCase();
		if (nameA < nameB){
			comparison = -1;
		}
		else if (nameA > nameB){
			comparison = 1;
		}
	}
	return comparison;
}

function buildVisualProductList(tree, startnode){
	//walk the tree and build the dom
	let listBox = $(startnode).addClass('sidebarList listsHeight');
	walkItemTree(tree, listBox, true);
}

function walkItemTree(currentTwig, currentDomElement, firstCall){
	let category;
	for( category of currentTwig ){
	let currentElement = $('<div>');
	let categoryHeader = $('<div>');
	
		currentDomElement.append(currentElement
			.addClass('product-list-toggle')
			.attr('data-id',category.id)
		);
		currentElement.append(categoryHeader
			.addClass('category sidebarElement')
			.attr('data-id',category.id)
			.text(category.name)
			.append($('<span>')
				.addClass('icon-list-collapse glyphicon glyphicon-collapse-down')
				.attr('aria-hidden','true')
			)
			.append($('<span>')
				.addClass('searchCategoryIcon glyphicon glyphicon-search')
				.attr('aria-hidden','true')
			)
		);
		if(!firstCall){
			currentElement.addClass('hidden');
			currentElement.addClass('innerListItems');//padding: 0px evtl noch eigene divs geplant
		}
		else{
			
		}
		if(category.hasOwnProperty('subcategories')){
			walkItemTree(category.subcategories, currentElement, false);
		}
		if(category.products.length > 0){
			for( product of category.products){
				let currentListItem = $('<div>');
				currentElement.append(currentListItem
					.addClass('product hidden showSingleArticle subSidebarElement')
					.addClass('innerListItems')//padding: 0px evtl noch eigene divs geplant
					.attr({'data-id':product.id, 'name':product.name,'productCategory':product.productCategory})
					.text(product.name)
				);
				
				//create buttongroup for adding products
				let productButtonDiv = $('<div>').addClass('listProductAddButtonContainer btn-group').attr('role','group');
				let buttonSize;
				if( window.innerWidth <= 992){
					buttonSize = 'btn-sm'
				}else{
					buttonSize = 'btn-xs'
				}
				makeAddProductButtonGroup(productButtonDiv, product.id, buttonSize);
				currentListItem.append(productButtonDiv);
				
			}
		}
	}
}

//product list done



function getKeyByValue(object, value) {
	return Object.keys(object).find(key => object[key] === value);
}
//search done

$('#sendOrderForm').submit(function(event) {
	// Stop the browser from submitting the form.
	event.preventDefault();
	
	var selectedDate = $( "#ordersDatepicker" ).datepicker().val();
	var regExp = /\d\d.\d\d.\d\d\d\d/;
	if(regExp.test(selectedDate)){
		var form = $('#sendOrderForm');
		var customerID = $('#userID').data("value");
		form.append('<input type="hidden" value="'+selectedDate+'" name="orderDate">');
		form.append('<input type="hidden" value="'+customerID+'" name="userID">');

		// Serialize the form data.
		var formData = form.serialize();
		
		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: form.attr('action'),
			data: formData
		}).done(function(response) {
			//alert(response);
			resData = JSON.parse(response);
			//update orderSentSign
			showOrderSentIcon();
			showOrders();
			updateOrderDays();		
			if(!resData.success){
				displayMessage("Nachricht", resData.displayMessage);
				logMessage("Fehler", resData.logMessage);
			}
		}).fail(function(data) {
			displayMessage('Fehler', 'Artikel konnte nicht erstellt werden.');
			if (data.responseText !== '') {
				logMessage(data.responseText);
			} else {
				logMessage('Fehler', 'Artikel konnte nicht erstellt werden.');
			}
		});
	}
	else{
		displayMessage("Nachricht","Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
	}
});

/*$('.sendOrderButton').click(function() {
	//check dateinput and send ajax request
	var selectedDate = $( "#ordersDatepicker" ).datepicker().val();
	var regExp = /\d\d.\d\d.\d\d\d\d/;
	if(regExp.test(selectedDate)){
		var customerID = $('#userID').data("value");
		$('#sendOrderForm').append('<input type="hidden" value="'+selectedDate+'" name="orderDate">');
		$('#sendOrderForm').append('<input type="hidden" value="'+customerID+'" name="userID">');
		$('#sendOrderForm').submit();
	}
	else{
		displayMessage("Nachricht","Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
	}
});*/

//displays orders of selected date and customer
var showOrders = function(){
	//check if data is still being created
		$.ajax({
			type: 'POST',
			url: 'ajax/orders_checkDataBlockedForDisplay.php'
		}).done(function(response) {
			var selectedDate = $( "#ordersDatepicker" ).datepicker().val();
			//check dateinput and send ajax request
			var regExp = /\d\d.\d\d.\d\d\d\d/;
			if(regExp.test(selectedDate)){
				var customerID = $('#userID').data("value");
				$.ajax({
					type: 'POST',
					url: 'ajax/orders_readOrders.php',
					data: {
						id:customerID,
						date:selectedDate
					}
				}).done(function(response){
					//reset list
					$('#sendOrderForm').empty();
					//reset orderCounter in ButtonGroups
					$('.productCountIcon').text(0);
					showOrderSentIcon();
					var ordersData = JSON.parse(response);
					//set Item List/Form
					var productList = Object();
					for(var x=0; x < ordersData.length; x++){
						//create ordered Product-List with category headings
						singleOrder = ordersData[x];
						productCategory = productsCategoryDict[singleOrder.idProduct];
						
						if(!productList.hasOwnProperty(productCategory)){
							productList[productCategory] = [];
						}
						productList[productCategory].push([singleOrder.idProduct, singleOrder.number]);
					}
					//sort categories
					var keys = [];
					for(k in productList){
						if(productList.hasOwnProperty(k)){
							keys.push(k);
						}
					}
					keys.sort(function(a,b){
						let result = 0;
						if (categoriesPriorityDict[a] < categoriesPriorityDict[b]){
							result = -1;
						}else if (categoriesPriorityDict[a] > categoriesPriorityDict[b]){
							result = 1;
						}
						else {
							result = categoriesNameDict[a].localeCompare(categoriesNameDict[b]);
						}
						return result;
					});
					//sort products and build form
					for (var y=0; y < keys.length; y++){
						var category = keys[y];
						if (productList.hasOwnProperty(category)) {
							var currentList = productList[category];
							currentList.sort(function(a,b){
								let result = 0;
								let aId = a[0], bId = b[0];
								let aPrio = productsOrderPriorityDict[aId];
								let bPrio = productsOrderPriorityDict[bId];
								if (aPrio < bPrio){
									result = -1;
								}else if (aPrio > bPrio){
									result = 1;
								}
								else {
									result = productsNameDict[aId].localeCompare(productsNameDict[bId]);
								}
								return result;
							});
							$('#sendOrderForm').append('<p class="orderListHeading">'+categoriesNameDict[category]+'</p>');
							for(var x = 0; x < currentList.length; x++){
								appendToProductList($('#sendOrderForm'), currentList[x][0], currentList[x][1], true);
							}
							$('#sendOrderForm').append('<hr class="orderListDivider">');
						}
					}
					
				}).fail(function(data){
					displayMessage('Fehler', 'Bestellung konnte nicht geladen werden.');
					if (data.responseText !== '') {
						logMessage(data.responseText);
					} else {
						logMessage('Fehler', 'Bestellung konnte nicht geladen werden.');
					}
				});
			}
			else{
				displayMessage("Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
			}
			
		}).fail(function(data) {
			displayMessage('Fehler', 'Verbindung zum Server ist unterbrochen. (Stichwort: dataBlockedForDisplay)');
			if (data.responseText !== '') {
				logMessage(data.responseText);
			} else {
				logMessage('Fehler', 'Verbindung zum Server ist unterbrochen. (Stichwort: dataBlockedForDisplay)');
			}
		});
	
		

};
var appendToProductList = function(formObj,idProduct, number, init){
	var productName = productsNameDict[idProduct];
	var orderLabel, maxSize = 30;
	//add class to make textsize smaller if name longer than maxSize
	if(productName.length > maxSize){
		orderLabel = " class='orderListItem orderNameSize' ";
	}
	else{
		orderLabel = " class='orderListItem' ";
	} 
	formObj.append('<div class="field clearfix"><label'+orderLabel+'for='+idProduct+'>'+productName+'&nbsp;</label><input type="number" id="'+idProduct+'" class="orderProductInput" value="'+number+'" min="0" name="'+idProduct+'"></div>');
	if(!init){
		showOrderNotYetSentIcon();
	}
	let inputField = document.getElementById(idProduct);
	inputField.addEventListener("input", showOrderNotYetSentIcon, false);
	triggerChangeOfOrderCount(idProduct);
}
var showOrderNotYetSentIcon = function(){
	var orderSent = $('#orderSentSign');
	orderSent.removeClass('glyphicon-check');
	orderSent.addClass('glyphicon-share');
	window.onbeforeunload = function() {
    return 'Die aktuelle Bestellung ist noch nicht abgeschickt.';
	}
}
var showOrderSentIcon = function(){
	var orderSent = $('#orderSentSign');
	orderSent.removeClass('glyphicon-share');
	orderSent.addClass('glyphicon-check');
	window.onbeforeunload = null;
}
var triggerChangeOfOrderCount = function (idProduct){
	$('input#'+idProduct).trigger("input");
}

function showMultipleArticles(productList, categoryId = null){
	$('div.sidebarList').find('*').removeClass("active");
	$('.productContent').empty();
	let productListJson = JSON.stringify(productList);
	$('.productContent').attr("data-backButtonContext",productListJson);
	$('.productContent').attr("data-categoryID",categoryId);
	//alert(productList[0]['id']);
	
	if(categoryId != null){
		$('.productContent').append('<h3>'+categoriesNameDict[categoryId]+'</h3><hr />');
	}
	
	productList.sort((a, b) => a['name'].localeCompare(b['name']));
	
	for ( let product of productList ){		
	
		//set grid classes of product
		let imgPathExists = product['imagePath'] != '';
		$('.productContent').append($('<div>')
			.addClass('multiProductContainer')
			.attr("id",'frame'+product['id'])
			.attr("data-id",product['id'])
		);
		let productFrame = $('#frame'+product['id']);
		if(imgPathExists){
			productFrame.append($('<img>')
				.addClass('multiProductImage image'+product['id'])
				.attr('src',product['imagePath'])
			)
		}
		
		productFrame.append($('<div>')
			.addClass('productTextFrameContainer')
			.attr('id','body'+product['id'])
		);
		let productBody = $('#body'+product['id']);
		productBody.append($('<div>')
			.addClass('multiviewProductNameContainer')
			.append($('<h4>').text(product['name'])
				.addClass('multiviewBoxProductName')
			)
			.append($('<span>')
				.text(categoriesNameDict[product['productCategory']])
				.addClass('multiviewBoxCategory')
			)
		);
		let productTextWrapper = $('<div>').addClass('multiviewProductTextContainerWrapper');
		let productText = $('<div>').addClass('multiviewProductTextContainer');
			productBody.append(productTextWrapper);
			productTextWrapper.append(productText);
		if(product['weight'] != ''){
			productText.append($('<div>').text('Gewicht: '+product['weight']));
		}
		productText.append($('<div>').text('Art.Nr: '+product['productID']));
		if(typeof product['price'] !== 'undefined'){
			productText.append($('<div>').text('Preis: '+product['price']+' '+product['priceInfoText']));
		}
		if(product['preBakeExp'] !== 0){
			let plural = 'Werktage';
			if(product['preBakeExp']==1){
				plural = 'Werktag';
			}
			productText.append($('<div>').text('Bitte mindestens '+product['preBakeExp']+' '+plural+' im Voraus bestellen'));
		}
		
		//create buttongroup for adding products
		let productButtonDiv = $('<div>').addClass('multiProductAddButtonContainer btn-group').attr('role','group');
		makeAddProductButtonGroup(productButtonDiv, product['id'], 'btn-md');
		productTextWrapper.append(productButtonDiv);
		
		productFrame.after($('<hr />'));
	}
}

function makeAddProductButtonGroup (productButtonDiv, productID, addedButtonClasses){
	let plusButton = $('<button>');
	productButtonDiv.append(plusButton
		.addClass('btn btn-default buttonPlusProductSingleView '+addedButtonClasses)
		.attr({'type':'button','data-id':productID})
	);
	plusButton.append($('<span>')
		.addClass('glyphicon glyphicon-plus iconAddProduct')
		.attr('aria-hidden','true')
	);
	let countButton = $('<button disabled>');
	productButtonDiv.append(countButton
		.addClass('btn btn-default buttonCountProductSingleView '+addedButtonClasses)
		.attr({'type':'button','data-id':productID})
	);
	let productCount = $('input#'+productID).val();
	if(productCount == undefined){
		productCount = 0;
	}
	countButton.append($('<span>')
		.addClass('productCountIcon glyphicon iconAddProduct productCounter'+productID)
		.text(productCount)
		.attr({'aria-hidden':'true'})
		//.attr({'aria-hidden':'true','id':productID})
	);
	let minusButton = $('<button>');
	productButtonDiv.append(minusButton
		.addClass('btn btn-default buttonMinusProductSingleView '+addedButtonClasses)
		.attr({'type':'button','data-id':productID})
	);
	minusButton.append($('<span>')
		.addClass('glyphicon glyphicon-minus iconAddProduct')
		.attr('aria-hidden','true')
	);
	let addButton = $('<button>');
	productButtonDiv.append(addButton
		.addClass('btn btn-default buttonAddProductSingleView '+addedButtonClasses)
		.attr({'type':'button','data-id':productID})
	);
	addButton.append($('<span>')
		.addClass('glyphicon glyphicon-triangle-right iconAddProduct')
		.attr('aria-hidden','true')
	);
}

function showSingleProduct(id, showBackButton = false){
	//ajax call for product data
	$.post("ajax/orders_readProduct.php", {id:id}, function(response, status){
		var productData = JSON.parse(response)[0];
		
		$('.productContent').empty();
		
		if(showBackButton){
			let backButton = $('<button>')
			.addClass('backButton btn btn-default btn-md')
			.append('<span class="glyphicon glyphicon-arrow-left"></span>')
			.click(function (){
				let productList = JSON.parse($('.productContent').attr('data-backButtonContext'));
				let productCategory = $('.productContent').attr('data-categoryID');
				showMultipleArticles(productList, productCategory);
				document.documentElement.scrollTop = $('.productContent').attr("data-previousPosition");
			});
			$('.productContent').append(backButton);
		}
		$('.productContent').append('<h3>'+productData["name"]+'</h3>');
		$('.productContent').append('<hr>');
		var imagePath = productData["imagePath"];
		if(imagePath){
			$('.productContent').append('<img id="productImgSingle" src="'+imagePath+'">');
		}
		if(productData["weight"] != ''){
			$('.productContent').append('<p>Gewicht: '+productData["weight"]+'</p>');
		}
		$('.productContent').append('<p>Artikelnummer: '+productData["productID"]+'</p>');
		var prebake = productData["preBakeExp"];
		if(prebake!=0){
			var dayOrDays = " Werktage ";
			if(prebake==1){dayOrDays = " Werktag "}
			$('.productContent').append('<p>Bitte mindestens '+productData["preBakeExp"]+dayOrDays+'im Voraus bestellen.</p>');
		}
		if(productData["ingredients"] != ''){
			$('.productContent').append('<p>Zutaten <br />'+productData["ingredients"]+'</p>');
		}
		if(productData["allergens"] != ''){
			$('.productContent').append('<p>Allergene <br />'+productData["allergens"]+'</p>');
		}
		if(productData["description"] != ''){
			$('.productContent').append('<p>Beschreibung <br />'+productData["description"]+'</p>');
		}
		if(typeof productData["price"] !== 'undefined'){
			$('.productContent').append('<p>Preis <br />'+productData["price"]+productData["priceInfoText"]+'</p>');
		}
		//create buttongroup for adding products
		//let productButtonDiv = $('<div>').addClass('singleProductAddButtonContainer btn-group').attr('role','group');
		
		//create buttongroup for adding products
		let productButtonDiv = $('<div>').addClass('singleProductAddButtonContainer btn-group').attr('role','group');
		makeAddProductButtonGroup(productButtonDiv, productData['id'], 'btn-md');
		$('.productContent').append(productButtonDiv);
	});
}

//search box form
$(function() {
	var form = $('#searchBoxForm');
	$(form).submit(function(event) {
		event.preventDefault();
		$('div.sidebarList').find('*').removeClass("active");
		
		let searchText = $('.productSearchTextInput').val();
		
		//validate for special characters
		let regEx = /\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\"|\;|\:|\§/g;
		if(regEx.test(searchText)){
			displayMessage('Eingabefehler','Spezielle Zeichen sind in der Suche nicht erlaubt');
			return;
		}
		
		$.ajax({
			type: 'POST',
			url: 'ajax/orders_searchProducts.php',
			data: {
				productSearchText: searchText
			}
		}).done(function(response) {
			//alert(response);
			resData = JSON.parse(response);
			showMultipleArticles(resData);
		}).fail(function(data) {
			displayMessage('Fehler', 'Die Suche ist fehlgeschlagen.');
			if (data.responseText !== '') {
				logMessage(data.responseText);
			} else {
				logMessage('Fehler', 'Die Suche ist fehlgeschlagen.');
			}
		});
	});
});

//main function for click event handlers
var main = function(){



	$(document).on('click','.searchCategoryIcon', function(event) {
		event.stopPropagation();
		$('div.sidebarList').find('*').removeClass("active");
		//$(this).addClass("active");
		
		//remove addToOrder Button
    $(".subSidebarElement").find(".listProductAddButtonContainer").css('visibility','hidden');
		
		//ajax call for product data
		let categoryId = $(this).parent().data('id');
		$.ajax({
			type: 'POST',
			url: 'ajax/orders_readProductsOfCategory.php',
			data: {
				categoryID: categoryId
			}
		}).done(function(response) {
			//alert(response);
			resData = JSON.parse(response);
			showMultipleArticles(resData, categoryId);
		}).fail(function(data) {
			displayMessage('Fehler', 'Artikel konnten nicht gefunden werden.');
			if (data.responseText !== '') {
				logMessage(data.responseText);
			} else {
				logMessage('Fehler', 'Artikel konnten nicht gefunden werden.');
			}
		});
	});
	
	//show single article on click in multiview
	$(document).on('click', ".multiProductContainer", function(event){
		let showBackButton = true;
		$('.productContent').attr("data-previousPosition",document.documentElement.scrollTop);
		showSingleProduct($(this).attr('data-id'), showBackButton, $(this).attr('data-categoryID'));
	});
	
	$(document).on('click', ".buttonAddProductMultiView", function(event){
		event.stopPropagation();
		let orderForm = $('#sendOrderForm');
		let idProduct = $(this).attr('data-id');
		if( orderForm.find('#'+idProduct).length < 1){
			appendToProductList(orderForm,idProduct, 1, false);
		}
		$('input#'+idProduct).focus().select();
	
		/*event.stopPropagation();
		var idProduct = $(this).parent().data('id');
		if( $('#sendOrderForm').find('#'+idProduct).length < 1){
			appendToProductList($('#sendOrderForm'),idProduct, 1, false);
		}
		$('input#'+idProduct).focus().select();*/
		
	});
	
	//show single article in product content on click on left sidebar
	$(document).on('click', '.showSingleArticle', function(event) {
		event.stopPropagation();
		$('div.sidebarList').find('*').removeClass("active");
		$(this).addClass("active");
		let id = $(this).data('id');
		showSingleProduct(id);
	});

	$(document).on('click','.product-list-toggle' , function(event) {
		event.stopPropagation();
		let childElements = $(this).children("div");
		childElements.not(':first-child').toggleClass("visible hidden");
		childElements.children(".icon-list-collapse").toggleClass("glyphicon-collapse-down glyphicon-collapse-up");
	});
	//add ProductButtonGroup
	$(document).on('click', ".buttonAddProductSingleView", function(event){
		event.stopPropagation();
		let orderForm = $('#sendOrderForm');
		let idProduct = $(this).attr('data-id');
		if( orderForm.find('#'+idProduct).length < 1){
			appendToProductList(orderForm,idProduct, 1, false);
		}
		$('input#'+idProduct).focus().select();
	});
	
	$(document).on('click', ".buttonPlusProductSingleView", function(event){
		event.stopPropagation();
		let orderForm = $('#sendOrderForm');
		let idProduct = $(this).attr('data-id');
		
		if( orderForm.find('#'+idProduct).length < 1){
			appendToProductList(orderForm,idProduct, 1, false);
		}else{
			let numberInput = $('input#'+idProduct);
			let productCount = numberInput.val();
			if(productCount == ''){
				productCount = 0;
			}
			numberInput.val(parseInt(productCount) + 1);
		}
		showOrderNotYetSentIcon();
		triggerChangeOfOrderCount(idProduct);
	});
	
	$(document).on('click', ".buttonMinusProductSingleView", function(event){
		event.stopPropagation();
		let orderForm = $('#sendOrderForm');
		let idProduct = $(this).attr('data-id');
		
		if( orderForm.find('#'+idProduct).length < 1){
			//appendToProductList(orderForm,idProduct, 0, false);
		}else{
			let numberInput = $('input#'+idProduct);
			let numberInputCount = numberInput.val();
			if(numberInputCount > 0){
				numberInput.val(parseInt(numberInputCount) - 1);
			}
			showOrderNotYetSentIcon();
			triggerChangeOfOrderCount(idProduct); 
		}
	});
	//refresh buttongroupProductCounter
	$(document).on('input','.orderProductInput',function (event){
		let productCount = $('input#'+event.target.id).val();
		if(productCount == ''){
			productCount = 0;
		}
		$('.productCounter'+event.target.id).text(productCount);
	});

	//show and hide addProduct buttonGroup in left menu (productlist)
	$(document).on('mouseenter', ".subSidebarElement", function(event) { 
		$(this).find(".listProductAddButtonContainer").css('visibility','visible'); 
	});
	$(document).on('mouseleave', ".subSidebarElement", function(event) {
		if(!$(this).hasClass("active")){
            $(this).find(".listProductAddButtonContainer").css('visibility','hidden');
		}
	}).on('click', ".subSidebarElement", function(event){
			$(".subSidebarElement").find(".listProductAddButtonContainer").css('visibility','hidden');
			$(this).find(".listProductAddButtonContainer").css('visibility','visible');
	});
	
	
	/*//add product functionality for counterButton in left productlist buttongroup
	$(document).on('click', '.listProductAddButtonContainer', function(event) {
		event.stopPropagation();
		var idProduct = $(this).parent().data('id');
		if( $('#sendOrderForm').find('#'+idProduct).length < 1){
			appendToProductList($('#sendOrderForm'),idProduct, 1, false);
		}
		$('input#'+idProduct).focus().select();
	});*/
	
	
	
	//show and hide category search icon
	/*$(document).on('mouseenter', ".sidebarElement", function(event) { 
		//event.stopPropagation();
		$(this).find(".searchCategoryIcon").css('visibility','visible'); 
	});
	
	$(document).on('mouseleave', ".sidebarElement", function(event) {
		if(!$(this).hasClass("active")){
      $(this).find(".searchCategoryIcon").css('visibility','hidden');
		}
	}).on('click', ".sidebarElement", function(event){
		$(".sidebarElement").find(".searchCategoryIcon").css('visibility','hidden');
		$(this).find(".searchCategoryIcon").css('visibility','visible');
	});	*/
	
	$(window).resize(function(){
		$(".productList").children().remove();
		buildVisualProductList(productTree, ".productList");
	});
	
	$('.deleteOrderButton').click(function() {
		//check dateinput and send ajax request
		var selectedDate = $( "#ordersDatepicker" ).datepicker().val();
		var regExp = /\d\d.\d\d.\d\d\d\d/;
		if(regExp.test(selectedDate)){
			var customerID = $('#userID').data("value");
			$.ajax({
				type: 'POST',
				url: 'ajax/orders_deleteOrdersOfDay.php',
				data: {
					orderDate:selectedDate,
					userID:customerID
				}
			}).done(function(response) {
				showOrderSentIcon();
				updateOrderDays();	
				var responseObject = JSON.parse(response);
				if(!responseObject.success){
					if(responseObject.logMessage != null){
						logMessage('Fehler', responseObject.logMessage);
					}
					displayMessage('Nachricht', responseObject.displayMessage);
				}
			}).fail(function(data) {
				displayMessage('Fehler', 'Bestellungen konnten nicht gelöscht werden.');
				if (data.responseText !== '') {
					logMessage(data.responseText);
				} else {
					logMessage('Fehler', 'Bestellungen konnten nicht gelöscht werden.');
				}
			});
			setTimeout(function(){showOrders(); }, 50);
		}
		else{
			displayMessage("Nachricht","Das Datum entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
		}
	});
	
	$('.takeOrdersFromButton').click(function() {
		//check dateinput and send ajax request
		var selectedDate = $( "#ordersDatepicker" ).datepicker().val();
		var takeFromDateSelected = $( "#takeDatepicker" ).datepicker().val();
		var regExp = /\d\d.\d\d.\d\d\d\d/;
		if(regExp.test(selectedDate) && regExp.test(takeFromDateSelected)){
			//only take over orders, if no order exists on this date
			if(!$('#sendOrderForm').children('.field').length){
				var customerID = $('#userID').data("value");
				$.ajax({
					type: 'POST',
					url: 'ajax/orders_takeOverOrdersFrom.php',
					data: {
						takeFromDate:takeFromDateSelected,
						orderDate:selectedDate,
						userID:customerID
					}
				}).done(function(response) {
					$('#pickDateModal').modal('hide');
					var responseObject = JSON.parse(response);
					if(!responseObject.success){
						if(responseObject.logMessage != null){
							logMessage('Fehler', responseObject.logMessage);
						}
						if(responseObject.displayMessage != null){
							displayMessage('Nachricht', responseObject.displayMessage);
						}
					}
					showOrderSentIcon();
					updateOrderDays();
				}).fail(function(data) {
					$('#pickDateModal').modal('hide');
					if (data.responseText !== '') {
						logMessage("Fehler",data.responseText);
					} else {
						logMessage("Fehler", 'Fehler, Bestellungen konnten nicht übernommen werden.');
					}
					displayMessage("Fehler", 'Fehler, Bestellungen konnten nicht übernommen werden.');
				});
				setTimeout(function(){showOrders(); }, 100);
			}
			else{
				displayMessage("Nachricht","Es sind noch Bestellungen an diesem Tag vorhanden. Bitte löschen Sie diese, bevor Sie eine Bestellung von einem anderen Tag übernehmen.");
			}
		}
		else{
			displayMessage("Nachricht"," Mindestens eines der Daten entspricht nicht dem vorgegebenen Format ( dd.mm.yyyy )");
		}
	});
	
}
$(document).ready(main);

