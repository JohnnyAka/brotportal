<?php
include('../db_crud.php');

$name = strip_tags(trim($_POST["userCatName"]));

$db = new db_connection();
$result = $db->createData("userCategories",array('name'), array($name));

echo $result;
?>