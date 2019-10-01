<?php
include('../admin/db_crud.php');

$db = new db_connection();
$data = $db->getData("orders", array('orderDate'), "idCustomer=?1",$_POST["userID"],true);

$jsonData = json_encode($data);
echo $jsonData;
?>

