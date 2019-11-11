<?php
include('../db_crud.php');

$catId = strip_tags(trim($_POST["catId"]));
$name = strip_tags(trim($_POST["productCatName"]));
$orderPriority = strip_tags(trim($_POST["orderPriority"]));
$upperCategoryID = strip_tags(trim($_POST["upperCategory"]));

$db = new db_connection();
$result = $db->updateData("productCategories", array('name', 'orderPriority','upperCategoryID'), array($name, $orderPriority,$upperCategoryID), "id=?1",$catId);
echo $result;
?>