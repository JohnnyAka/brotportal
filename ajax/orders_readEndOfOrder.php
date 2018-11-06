<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("settings", 'endOfOrderTime');

$jsonData = json_encode($data);
echo $jsonData;
?>

