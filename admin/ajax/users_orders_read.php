<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("orders", array('idCustomer'), "idCustomer=".$_POST["id"]);

$jsonData = json_encode($data);
echo $jsonData;
?>

