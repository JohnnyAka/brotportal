<?php
include('../db_crud.php');

$name = strip_tags(trim($_POST["productCatName"]));
$orderPriority = strip_tags(trim($_POST["orderPriority"]));

$db = new db_connection();
$result = $db->createData("productCategories",array('name', 'orderPriority'), array($name, $orderPriority));

echo $result;
?>