<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("settings", array('adminName','adminPassword','deleteOrdersInDays','imagesPath','autoExportOrdersTime','exportOrdersTo','saveDatabaseTo'));

$jsonData = json_encode($data);
echo $jsonData;
?>

