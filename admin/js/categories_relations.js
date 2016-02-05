/*This file contains event handlers for click events and form-submit events*/

//create productCategory dictionary (id to name)
$(function(){
		$.ajax({
			type: 'POST',
			url: 'ajax/categories_product_read.php'
		}).done(function(response){
			productCatNameDict = new Object();
			var categoriesData = JSON.parse(response);
			//set product options of select
			for (var x=0;x<categoriesData.length;x++) {
				productCatNameDict[categoriesData[x].id] = categoriesData[x].name;
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

//displays User categories
var displayUserCategories = function(){
	$('ul.sidebarList').empty();
	$.ajax({
		type: 'POST',
		url: 'ajax/categories_user_read.php'
	}).done(function(response){
		var categoryData = JSON.parse(response);
		//set Item List
		for(var x=0; x < categoryData.length; x++){
			$('ul.sidebarList').append("<li class='userCatListItem' data-idCategory='"+categoryData[x].id+"'>"+categoryData[x].name+"</li>");
		}
		// click-event to select category
		$('ul.sidebarList li').click(function() {
			$('ul.sidebarList li').removeClass("active");
			$(this).addClass("active");
			displayVisibleProductCats();
		});
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Kategorien konnten nicht angezeigt werden.');
		}
	});
};

//displays Product categories
var displayProductCategories = function(){
	$('ul.rightSidebarList').empty();
	$.ajax({
		type: 'POST',
		url: 'ajax/categories_product_read.php'
	}).done(function(response){
	
		var categoryData = JSON.parse(response);
		//set Item List
		for(var x=0; x < categoryData.length; x++){
			$('ul.rightSidebarList').append("<li class='productCatListItem' data-idCategory='"+categoryData[x].id+"'>"+categoryData[x].name+"</li>");
		}
		// click-event to select category
		$('ul.rightSidebarList li').click(function() {
			$('ul.rightSidebarList li').removeClass("active");
			$(this).addClass("active");
		});
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Kategorien konnten nicht angezeigt werden.');
		}
	});
};

//display productCategories visible by selected userCategory
var displayVisibleProductCats = function (){
	$('ul.visibleProductCatList').empty();

	//get values of item from db
	var userCat = $(".userCatListItem.active");
	var userID = userCat.data('idcategory');
	$.ajax({
		type: 'POST',
		url: 'ajax/categories_relations_single_read.php',
		data: {
			userId:userID
		}
	}).done(function(response){
		$(".messages").text("Kategorien erfolgreich verbunden!");
		var productCatData = JSON.parse(response);
		//set Item List
		for(var x=0; x < productCatData.length; x++){
			$('ul.visibleProductCatList').append("<li class='visibleProductCatItem' data-idproduct='"+productCatData[x].idProductCat+"'>"+productCatNameDict[productCatData[x].idProductCat]+"</li>");
		}
		// click-event to select category
		$('ul.visibleProductCatList li').click(function() {
			$('ul.visibleProductCatList li').removeClass("active");
			$(this).addClass("active");
		});
		
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Kategorie konnte nicht verbunden werden.');
		}
	});
}
			
//main function for click event handlers
var main = function(){
	
	displayUserCategories();
	displayProductCategories();
	
	$('.addProductCatButton').click(function(){
		var productCat = $(".productCatListItem.active");
		var userCat = $(".userCatListItem.active");
		if (productCat.length && userCat.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var userID = userCat.data('idcategory');
			var productID = productCat.data('idcategory');
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_relations_create.php',
				data: {
					userId:userID,
					productId:productID
				}
			}).done(function(response){
				$(".messages").text("Kategorien erfolgreich verbunden!");
				displayVisibleProductCats();
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Kategorie konnte nicht verbunden werden.');
				}
			});
		}
		else{
			alert("Nicht beide Kategorien ausgewählt");
		}
	});
	
	$('.removeProductCatButton').click(function(){
		var productCat = $(".visibleProductCatItem.active");
		var userCat = $(".userCatListItem.active");
		if (productCat.length && userCat.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var productID = productCat.data('idproduct');
			var userID = userCat.data('idcategory');
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_relations_delete.php',
				data: {
					userId:userID,
					whatever:productID
				}
			}).done(function(response){
				$(".messages").text("Produktkategorie erfolgreich entfernt!");
				displayVisibleProductCats();
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Kategorie konnte nicht entfernt werden.');
				}
			});
		}
		else{
			alert("Bitte eine Kundenkategorie und eine darin sichtbare Produktkategorie ausgewählen");
		}
	});
	
}

$(document).ready(main);

	
