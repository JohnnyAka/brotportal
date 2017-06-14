<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("settings", 'deleteOrdersInDays');

$today = new DateTime();
$deleteInDays = $data[0]['deleteOrdersInDays'];
$today->modify('-'.$deleteInDays.' day');
$today = $today->format('Y-m-d');

$dbreturn = $db->deleteData("orders","orderDate < '".$today."'");

$jsonData = json_encode($dbreturn);
echo $jsonData;
?>

