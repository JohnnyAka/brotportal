<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("products", array("id", "name", "productid", "description"), "id=".$_POST["id"]);

//echo "we made it this time ". $data[0]["id"];

$jsonData = json_encode($data);
echo $jsonData;
?>