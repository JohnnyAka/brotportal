<?php
include('../db_crud.php');

$strDate = $_POST["date"];
$day = strtok($strDate, ".");
$month = strtok(".");
$year = strtok(".");
$orderDate = $year."-".$month."-".$day;

$db = new db_connection();
$data = $db->getData("orders", array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), 
"idProduct=?1 AND hook=?2 AND idCustomer=?3 AND orderDate=?4", 
array($_POST["productId"],$_POST["orderHook"],$_POST["customer"],$orderDate));

$jsonData = json_encode($data);
echo $jsonData;
?>

