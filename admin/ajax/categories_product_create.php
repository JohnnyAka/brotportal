<?php
include('../db_crud.php');

$name = strip_tags(trim($_POST["productCatName"]));
$orderPriority = strip_tags(trim($_POST["orderPriority"]));
$upperCategoryID = strip_tags(trim($_POST["upperCategory"]));


$db = new db_connection();
$result = $db->createData("productCategories",array('name', 'orderPriority','upperCategoryID'), array($name, $orderPriority, $upperCategoryID));

echo $result;
?>