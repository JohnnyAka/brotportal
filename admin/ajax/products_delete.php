<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->deleteData("products", "id=".$_POST["id"]);
echo $data;
?>


