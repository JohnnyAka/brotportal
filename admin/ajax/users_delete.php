<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->deleteData("users", "id=".$_POST["id"]);
echo $data;
?>


