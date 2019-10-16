<?php
include('../db_crud.php');
//the admin doesnt have privileges to change these settings
//$adminName = strip_tags(trim($_POST["adminName"]));
//$adminPassword = strip_tags(trim($_POST["adminPassword"]));
$deleteOrdersInDays = strip_tags(trim($_POST["deleteOrdersInDays"]));
$imagesPath = '/';//strip_tags(trim($_POST["imagesPath"]));
$endOfOrderTime = strip_tags(trim($_POST["endOfOrderTime"]));
$exportOrdersTo = strip_tags(trim($_POST["exportOrdersTo"]));
$saveDatabaseTo = strip_tags(trim($_POST["saveDatabaseTo"]));

$db = new db_connection();
$result = $db->updateData("settings", 
array('deleteOrdersInDays','imagesPath','endOfOrderTime','exportOrdersTo','saveDatabaseTo'), 
array($deleteOrdersInDays,$imagesPath,$endOfOrderTime,$exportOrdersTo,$saveDatabaseTo));

echo $result;
?>