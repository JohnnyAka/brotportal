<?php
include('../db_crud.php');
//is used in admin/export.js
$db = new db_connection();
$data = $db->getData("settings", 'deleteOrdersInDays');
$deleteInDays = $data[0]['deleteOrdersInDays'];

$calendars = $db->getData("calendars", array('id'));

$today = new DateTime();
$today->modify('-'.$deleteInDays.' day');
$deleteBoundaryDate = $today->format('Y-m-d');

$dbreturn = $db->deleteData("calendarsDaysRelations","date < ?1", $deleteBoundaryDate);

$jsonData = json_encode($dbreturn);
echo $jsonData;
?>

