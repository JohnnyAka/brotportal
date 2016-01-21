<?php
include('../db_crud.php');

$strDate = $_POST["date"];
$day = strtok($strDate, ".");
$month = strtok(".");
$year = strtok(".");
$orderDate = $year."-".$month."-".$day;

$db = new db_connection();
$result = $db->deleteData("orders", 
"idProduct=".$_POST["productId"]." AND hook=".$_POST["orderHook"]." AND idCustomer=".$_POST["customer"]." AND orderDate='".$orderDate."'");

echo $result;
?>