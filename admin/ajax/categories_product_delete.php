<?php
include('../db_crud.php');

$catId = strip_tags(trim($_POST["catId"]));

$db = new db_connection();
$result = $db->deleteData("productCategories", "id=?1",$catId);

echo $result;
?>