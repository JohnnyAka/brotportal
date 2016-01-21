<?php
include('../db_crud.php');

$name = strip_tags(trim($_POST["productCatName"]));

$db = new db_connection();
$result = $db->createData("productCategories",array('name'), array($name));

echo $result;
?>