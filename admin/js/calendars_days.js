/*This file contains event handlers for click events and form-submit events*/

//displays categories
var displayCalendars = function(){
	$('ul.sidebarList').empty();
	$.ajax({
		type: 'POST',
		url: 'ajax/calendars_read.php'
	}).done(function(response){
		var calendarData = JSON.parse(response);
		//set Item List
		for(var x=0; x < calendarData.length; x++){
			$('ul.sidebarList').append("<li class='calendarListItem' data-idCalendar='"+calendarData[x].id+"'>"+calendarData[x].name+"</li>");
		}
		// click-event to select category
		$('ul.sidebarList li').click(function() {
			$('ul.sidebarList li').removeClass("active");
			$(this).addClass("active");
			
			var selectedCalendar = $(this).data('idcalendar');
			$.ajax({
				type: 'POST',
				url: 'ajax/calendars_days_single_read.php',
				data: {
					idCalendar:selectedCalendar
				}
			}).done(function(response){
				response = JSON.parse(response);
				var daysOfCalendar= [], dayTemp;
				for(var x=0; x<response.length;x++){
					dayTemp = response[x].date.split('-');
					dayTemp = dayTemp[0]+'-'+Number(dayTemp[1]).toString()+'-'+Number(dayTemp[2]).toString();
					daysOfCalendar.push(dayTemp);
				}
				//load Calendar
				$('.calendar').unbind("clickDay");
				$('.calendar').calendar({
					clickDay: function(element){
						clickDayOnCalendar(element);
					},
					customDayRenderer: function(element, date){
						var currentDay = date.getFullYear()+'-'+(Number(date.getMonth())+1)+'-'+date.getDate();
						
						if(daysOfCalendar.indexOf(currentDay) != -1){
							element.addClass("activeDay");
						}
					}
				});
			}).fail(function(data){
				// Set the message text.
				if (data.responseText !== '') {
					$(messages).text(data.responseText);
				} else {
					$(messages).text('Fehler, Kalendertage konnten nicht gelesen werden.');
				}
			});
		});
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Kalender konnte nicht angezeigt werden.');
		}
	});
};

var clickDayOnCalendar = function(element){
	var selectedCalendar = $('li.active.calendarListItem').data('idcalendar');
	if(selectedCalendar){
	$.ajax({
		type: 'POST',
		url: 'ajax/calendars_days_toggleDay.php',
		data: {
			idCalendar:selectedCalendar,
			day:element.date.getDate(),
			month:element.date.getMonth()+1,
			year:element.date.getFullYear()
		}
	}).done(function(response){
		response = JSON.parse(response);
		dayId = 'dayID'+(element.date.getMonth()).toString()+(element.date.getDate()).toString();
		if(response != -1){
			$('.'+dayId).addClass("activeDay");
		}
		else{
			$('.'+dayId).removeClass("activeDay");
		}
		
	}).fail(function(data){
		// Set the message text.
		if (data.responseText !== '') {
			$(messages).text(data.responseText);
		} else {
			$(messages).text('Fehler, Kalender konnte nicht verändert werden.');
		}
	});
	}
	else{
		alert("Es muss ein Kalender ausgewählt sein, um ihn zu verändern.");
	}
};
			
//main function for click event handlers
var main = function(){
	
	displayCalendars();
	
	
}

$(document).ready(main);

	
