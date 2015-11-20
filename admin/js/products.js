

var main = function(){

// click-event to retrieve data-id and alert
$('ul.productList li').click(function() {
$('ul.productList li').removeClass("active");
$(this).addClass("active");

$(".displayProductID").text($(this).data('productid'));
$(".displayName").text($(this).data('name'));
$(".displayDescription").text($(this).data('description'));
});


}

$(document).ready(main);