<?php
include('../db_crud.php');

$productCatId = $_POST["whatever"];

$db = new db_connection();
$result = $db->deleteData("categoryRelations", "idUserCat=?1 AND idProductCat=?2", array($_POST["userId"],$productCatId));

echo $result;
?>