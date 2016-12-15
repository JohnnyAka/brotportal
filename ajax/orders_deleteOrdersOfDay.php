<?php
include('../db_crud.php');


$important = '';
$noteDelivery = '';
$noteBaking = '';
$hook = 1;

$strDate = $_POST['orderDate'];
$idCustomer = $_POST['userID'];
unset($_POST['orderDate']);
unset($_POST['userID']);
//format Date
$day = strtok($strDate, ".");
$month = strtok(".");
$year = strtok(".");
$orderDate = $year."-".$month."-".$day;


$db = new db_connection();

$result = $db->deleteData("orders",
"idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook='1'");

echo $result;

?>