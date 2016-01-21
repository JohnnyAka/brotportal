<?php
include('../db_crud.php');

$catId = strip_tags(trim($_POST["catId"]));
$name = strip_tags(trim($_POST["productCatName"]));

$db = new db_connection();
$result = $db->updateData("productCategories", array('name'), array($name), "id=".$catId);
echo $result;
?>