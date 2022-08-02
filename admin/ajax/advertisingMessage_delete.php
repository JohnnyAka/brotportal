<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->deleteData("advertisingMessages", "id=?1",$_POST["id"]);
echo $data;
?>


