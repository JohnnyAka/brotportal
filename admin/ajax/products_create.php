<?php
include('../db_crud.php');

$productid = strip_tags(trim($_POST["productid"]));
$productid = str_replace("\'", "", $productid);
$name = strip_tags(trim($_POST["name"]));
$name = str_replace("\'", "", $name);
$description = strip_tags(trim($_POST["description"]));
$description = str_replace("\'", "", $description);

$db = new db_connection();
$result = $db->createData("products", array('productID','name','description'), array($productid,$name,$description));



echo $result;
?>