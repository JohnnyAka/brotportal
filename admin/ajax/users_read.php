<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("users", array('id','customerID','name','password','customerCategory','mailAdressTo','mailAdressReceive','telephone1','telephone2','fax','storeAdress','whereToPutOrder','priceCategory','preOrderCustomerId'), "id=".$_POST["id"]);


$jsonData = json_encode($data);
echo $jsonData;
?>

