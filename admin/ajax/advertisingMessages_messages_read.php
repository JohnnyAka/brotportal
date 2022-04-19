<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("advertisingMessages", array('id','name','orderPriority'));


$jsonData = json_encode($data);
echo $jsonData;
?>

