<?php
session_start();
include('../admin/db_crud.php');

$db = new db_connection();
$data = $db->getData("users", array('customerID','name','mailAdressTo','mailAdressReceive'), "id=".$_SESSION["userid"]);


$jsonData = json_encode($data);
echo $jsonData;
?>

