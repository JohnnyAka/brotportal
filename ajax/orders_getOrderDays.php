<?php
include('../admin/db_crud.php');

$db = new db_connection();
$data = $db->getData("orders", array('orderDate'), "idCustomer=".$_POST["userID"],true);

$jsonData = json_encode($data);
echo $jsonData;
?>

