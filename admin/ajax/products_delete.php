<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->deleteData("products", "id=?1",$_POST["id"]);
echo $data;
?>


