<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("userCategories", array('id', 'name'), "id=?1",$_POST["catId"]);

$jsonData = json_encode($data);
echo $jsonData;
?>

