<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("orders", array('idCustomer'), "idCustomer=?1",$_POST["id"]);

$jsonData = json_encode($data);
echo $jsonData;
?>

