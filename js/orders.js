/*This file contains event handlers for click events and form-submit events*/

//main function for click event handlers
var main = function(){

	$('.showMultipleArticles').click(function() {
		$('ul.sidebarList').find('*').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		//$.post("ajax/products_read.php", {id:$(this).data('id')}, function(response, status){
		//	var productData = JSON.parse(response);
			
		//});
	});
	
	$('.showSingleArticle').click(function() {
		$('ul.sidebarList').find('*').removeClass("active");
		$(this).addClass("active");
		
		//ajax call for product data
		//$.post("ajax/products_read.php", {id:$(this).data('id')}, function(response, status){
		//	var productData = JSON.parse(response);
			
		//});
	});
	
}

$(document).ready(main);

