<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("settings", array('deleteOrdersInDays','imagesPath','endOfOrderTime','exportOrdersTo','saveDatabaseTo'));

$jsonData = json_encode($data);
echo $jsonData;
?>

