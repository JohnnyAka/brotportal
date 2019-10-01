<?php
include('../db_crud.php');


$db = new db_connection();
$result = $db->deleteData("orders", "idCustomer=?1",$_POST["id"]);

echo $result;
?>