<?php
include('../db_crud.php');

$strDate = $_POST["date"];


$day = strtok($strDate, ".");
$month = strtok(".");
$year = strtok(".");
$orderDate = $year."-".$month."-".$day;

$db = new db_connection();
$data = $db->getData("orders", array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), "idCustomer=?1 AND orderDate=?2", array($_POST["id"],$orderDate));

$jsonData = json_encode($data);
echo $jsonData;
?>

