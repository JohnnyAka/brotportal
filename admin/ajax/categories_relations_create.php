<?php
include('../db_crud.php');

$productCatId = $_POST["productId"];
$userCatId = $_POST["userId"];

$db = new db_connection();
$result = $db->createData("categoryRelations",array('idUserCat','idProductCat'), array($userCatId,$productCatId));

echo $result;
?>