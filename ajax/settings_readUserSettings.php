<?php
session_start();
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("users", array('customerID','name','warningThreshold','autoSendOrders','mailAdressTo','mailAdressReceive'), "id=?1",$_SESSION["userid"]);


$jsonData = json_encode($data);
echo $jsonData;
?>

