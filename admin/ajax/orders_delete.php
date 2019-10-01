<?php
include('../db_crud.php');

$strDate = $_POST["date"];
$day = strtok($strDate, ".");
$month = strtok(".");
$year = strtok(".");
$orderDate = $year."-".$month."-".$day;

$db = new db_connection();

$locked = $db->getData("orders",array('locked'),
    "idProduct=?1 AND hook=?2 AND idCustomer=?3 AND orderDate=?4", array($_POST["productId"],$_POST["orderHook"],$_POST["customer"],$orderDate))[0]['locked'];

if(!$locked) {
    $result = $db->deleteData("orders",
        "idProduct=?1 AND hook=?2 AND idCustomer=?3 AND orderDate=?4", 
				array($_POST["productId"],$_POST["orderHook"],$_POST["customer"],$orderDate));
}
else {
    $result = "Der Artikel kann nicht gelöscht werden, er wurde schon exportiert.";
}
$output = json_encode($result);
return $output;
?>