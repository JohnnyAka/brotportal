<?php
	include('../db_crud.php');

	$idCalendar = strip_tags(trim($_POST["idCalendar"]));
	$db = new db_connection();

	$result = $db->getData("calendarsDaysRelations",	array('date'), 'idCalendar=?1', $idCalendar);
	
	$jsonData = json_encode($result);

	echo $jsonData;
?>