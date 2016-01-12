<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("products", array('id','productID','name'));


$jsonData = json_encode($data);
echo $jsonData;
?>