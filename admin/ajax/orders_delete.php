<?php
include('../db_crud.php');

$strDate = $_POST["date"];
$day = strtok($strDate, ".");
$month = strtok(".");
$year = strtok(".");
$orderDate = $year."-".$month."-".$day;

$db = new db_connection();

$locked = $db->getData("orders",array('locked'),
    "idProduct=".$_POST["productId"]." AND hook=".$_POST["orderHook"]." AND idCustomer=".$_POST["customer"]." AND orderDate='".$orderDate."'")[0]['locked'];

if(!$locked) {
    $result = $db->deleteData("orders",
        "idProduct=" . $_POST["productId"] . " AND hook=" . $_POST["orderHook"] . " AND idCustomer=" . $_POST["customer"] . " AND orderDate='" . $orderDate . "'");
}
else {
    $result = "Der Artikel kann nicht gelöscht werden, er wurde schon exportiert.";
}
$output = json_encode($result);
return $output;
?>