<?php
include('../db_crud.php');

$catId = strip_tags(trim($_POST["catId"]));
$name = strip_tags(trim($_POST["userCatName"]));

$db = new db_connection();
$result = $db->updateData("userCategories", array('name'), array($name), "id=?1",$catId);
echo $result;
?>