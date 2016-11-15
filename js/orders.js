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
		$.post("ajax/orders_readProduct.php", {id:$(this).data('id')}, function(response, status){
			var productData = JSON.parse(response)[0];
			
			$('.productContent').empty();
			$('.productContent').append('<h3>'+productData["name"]+'</h3>');
			$('.productContent').append('<hr>');
			var imagePath = productData["imagePath"];
			if(imagePath){
				$('.productContent').append('<img id="productImgSingle" src="images/'+imagePath+'">');
			}
			$('.productContent').append('<p>Gewicht: '+productData["weight"]+'</p>');
			$('.productContent').append('<p>Artikelnummer: '+productData["productID"]+'</p>');
			var prebake = productData["preBakeExp"];
			if(prebake!=0){
				var dayOrDays = " Tage ";
				if(prebake==1){dayOrDays = " Tag "}
				$('.productContent').append('<p>Bitte '+productData["preBakeExp"]+dayOrDays+'im Voraus bestellen.</p>');
			}
			$('.productContent').append('<p>Zutaten <br />'+productData["ingredients"]+'</p>');
			$('.productContent').append('<p>Allergene <br />'+productData["allergens"]+'</p>');
			$('.productContent').append('<p>Beschreibung <br />'+productData["description"]+'</p>');
			
			console.log(productData["id"]);
		});
	});
	
}

$(document).ready(main);

