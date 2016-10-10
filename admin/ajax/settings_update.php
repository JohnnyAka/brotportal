<?php
include('../db_crud.php');

$adminName = strip_tags(trim($_POST["adminName"]));
$adminPassword = strip_tags(trim($_POST["adminPassword"]));
$deleteOrdersInDays = strip_tags(trim($_POST["deleteOrdersInDays"]));
$imagesPath = strip_tags(trim($_POST["imagesPath"]));
$autoExportOrdersTime = strip_tags(trim($_POST["autoExportOrdersTime"]));
$exportOrdersTo = strip_tags(trim($_POST["exportOrdersTo"]));
$saveDatabaseTo = strip_tags(trim($_POST["saveDatabaseTo"]));

$db = new db_connection();
$result = $db->updateData("settings", 
array('adminName','adminPassword','deleteOrdersInDays','imagesPath','autoExportOrdersTime','exportOrdersTo','saveDatabaseTo'), 
array($adminName,$adminPassword,$deleteOrdersInDays,$imagesPath,$autoExportOrdersTime,$exportOrdersTo,$saveDatabaseTo));

echo $result;
?>