<?php
include('../db_crud.php');

$catId = strip_tags(trim($_POST["catId"]));
$name = strip_tags(trim($_POST["productCatName"]));
$orderPriority = strip_tags(trim($_POST["orderPriority"]));

$db = new db_connection();
$result = $db->updateData("productCategories", array('name', 'orderPriority'), array($name, $orderPriority), "id=?1",$catId);
echo $result;
?>