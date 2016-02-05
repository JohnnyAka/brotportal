<?php
include('../db_crud.php');

$productCatId = $_POST["whatever"];

$db = new db_connection();
$result = $db->deleteData("categoryRelations", "idUserCat=".$_POST["userId"]." AND idProductCat='".$productCatId."'");

echo $result;
?>