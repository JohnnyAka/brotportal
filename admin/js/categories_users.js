/*This file contains event handlers for click events and form-submit events*/

//create category form submit
$(function() {
    // Get the form.
    var form = $('#createUserCatForm');

    // Get the messages div.
    var messages = $('#messages');
		
	//clear formfields after modal close (event)
	$('#createUserCat').on('hidden.bs.modal', function () {
		$(this).find(form)[0].reset();
	})

	// Set up an event listener for the createUser form.
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
			$("#createUserCat").modal("hide");
			//show changes
			displayCategories();
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

//update usercat form submit
$(function() {
	// Get the form.
	var form = $('#updateUserCatForm');

	// Get the messages div.
	var messages = $('#messages');
	
	//clear formfields after modal close (event)
	$('#updateUserCat').on('hidden.bs.modal', function () {
		$(this).find(form)[0].reset();
	})

	// Set up an event listener for the updateUser form.
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
			$("#updateUserCat").modal("hide");
			//display changes
			displayCategories();
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

//displays categories
var displayCategories = function(){
	$('ul.sidebarList').empty();
	$.ajax({
		type: 'POST',
		url: 'ajax/categories_user_read.php'
	}).done(function(response){
		var categoryData = JSON.parse(response);
		//set Item List
		for(var x=0; x < categoryData.length; x++){
			$('ul.sidebarList').append("<li class='categoryListItem' data-idCategory='"+categoryData[x].id+"'>"+categoryData[x].name+"</li>");
		}
		// click-event to select category
		$('ul.sidebarList li').click(function() {
			$('ul.sidebarList li').removeClass("active");
			$(this).addClass("active");
			displayUsers($(this));
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

//displays users belonging to active category
var displayUsers = function(category){
	//reset list
	$('ul.userList').empty();
	var categoryID = category.data('idcategory');
	$.ajax({
		type: 'POST',
		url: 'ajax/categories_user_users_read.php',
		data: {
			id:categoryID
		}
	}).done(function(response){
		var userData = JSON.parse(response);
		//set Item List
		for(var x=0; x < userData.length; x++){
			$('ul.userList').append("<li>"+userData[x].name+"</li>");
		}
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Bestellung konnte nicht geladen werden.');
		}
	});
};
			
//main function for click event handlers
var main = function(){
	
	displayCategories();
	
	$('.createUserCatButton').click(function(){
		$("#createUserCat").modal("show");
	});
	
	$('.updateUserCatButton').click(function(){
		var item = $(".categoryListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var selectedCategory = item.data('idcategory');
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_user_single_read.php',
				data: {
					catId:selectedCategory
				}
			}).done(function(response){
				var userData = JSON.parse(response);
				//set values of form
				$('#userCatNameUp').val(userData[0]['name']);
				
				//set hidden formfields
				$('#catIdUp').val(selectedCategory);
				
				//show modal
				$("#updateUserCat").modal("show");
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
	
	$('.deleteUserCatButton').click(function(){
		var item = $(".categoryListItem.active");
		if (item.length){
			// Get the messages div.
			var messages = $('#messages');
			
			//get values of item from db
			var categoryID = item.data('idcategory');
			//check for dependent customers
			$.ajax({
				type: 'POST',
				url: 'ajax/categories_user_users_read.php',
				data: {
					id:categoryID
				}
			}).done(function(response){
				users = JSON.parse(response);
				if(users !== 'undefined' && users.length > 0){
					alert("Es gibt noch Kunden dieser Kategorie. Bevor die Kategorie gelöscht werden kann, bitte die Kunden löschen oder die Kategorie dieser Kunden ändern.");
				}
				else{
					//delete user
					$.ajax({
						type: 'POST',
						url: 'ajax/categories_user_delete.php',
						data: {
							catId:categoryID
						}
					}).done(function(response){
						$(".messages").text("Kategorie erfolgreich gel&ouml;scht!");
						displayCategories();
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
					$(messages).text('Fehler, Kunden konnten nicht gelesen werden.');
				}
			});
		}
		else{
			alert("Keine Kategorie ausgewählt");
		}
	});
	
}

$(document).ready(main);

	
