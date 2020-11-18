<?php
include('../db_crud.php');
include('orders_helpers.php');

$strDate = $_POST["date"];
$idCustomer = $_POST["id"];
$standardSlot = $_POST["standardSlot"];
$normalOrderMode = $_POST["normalOrderMode"];


if($normalOrderMode == "false"){
	if($standardSlot == 0){
		return;
	}
	$orderDate = getStandardOrderDate($standardSlot);
}else{
	$day = strtok($strDate, ".");
	$month = strtok(".");
	$year = strtok(".");
	$orderDate = $year."-".$month."-".$day;
}

$db = new db_connection();
$data = $db->getData("orders", array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), "idCustomer=?1 AND orderDate=?2",array($idCustomer,$orderDate));

$jsonData = json_encode($data);
echo $jsonData;




?>

