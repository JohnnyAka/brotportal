<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("advertisingMessages", array('id', 'name', 'messageImage','messageHeader','messageText','popupStartDate','popupEndDate','messageboxStartDate','messageboxEndDate','linkedProductId','orderPriority'), "id=?1",$_POST["id"]);


$jsonData = json_encode($data);
echo $jsonData;
?>