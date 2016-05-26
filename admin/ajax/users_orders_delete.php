<?php
include('../db_crud.php');


$db = new db_connection();
$result = $db->deleteData("orders", "idCustomer=".$_POST["id"]);

echo $result;
?>