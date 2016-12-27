<?php
	include('../db_crud.php');

	$idCalendar = strip_tags(trim($_POST["idCalendar"]));
	$day = strip_tags(trim($_POST["day"]));
	$month = strip_tags(trim($_POST["month"]));
	$year = strip_tags(trim($_POST["year"]));
	$date = $year.'-'.$month.'-'.$day;

	$db = new db_connection();

	$exists = $db->getData(
		"calendarsDaysRelations", 
		array('idCalendar','date'),
		'date="'.$date.'"'
	);
	
	if($exists){
		$result = $db->deleteData("calendarsDaysRelations", 
		"idCalendar=".$idCalendar." AND date='".$date."'");
	}
	else{
		$result = $db->createData(
			"calendarsDaysRelations", 
			array('idCalendar','date'), 
			array($idCalendar,$date)
		);
	}


	echo $result;
?>