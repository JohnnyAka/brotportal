<?php
	include('../db_crud.php');

	$idCalendar = strip_tags(trim($_POST["idCalendar"]));
	$db = new db_connection();

	$result = $db->getData(
		"calendarsDaysRelations", 
		array('date'),
		'idCalendar="'.$idCalendar.'"'
	);
	
	$jsonData = json_encode($result);

	echo $jsonData;
?>